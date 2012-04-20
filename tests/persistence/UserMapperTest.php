<?php
class UserMapperTest extends PHPUnit_Extensions_Database_TestCase
{
  protected $db;

  /**
   * @var UserMapper
   */
  protected $mapper;

  protected function setUp()
  {
    $this->db = new PDO(
      $GLOBALS['DB_DSN'],
      $GLOBALS['DB_USER'],
      $GLOBALS['DB_PASSWD'],
      array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
    );

    $this->db->exec(
      file_get_contents(
        dirname(dirname(dirname(__FILE__))) . '/database/tbl_user.sql'
      )
    );

    $this->mapper = new UserMapper($this->db);

    parent::setUp();
  }

  public function getConnection()
  {
    return $this->createDefaultDBConnection($this->db, $GLOBALS['DB_DBNAME']);
  }

  public function getDataSet()
  {
    return $this->createFlatXMLDataSet(
      __DIR__ . '/fixture/user-seed.xml'
    );
  }

  /**
   * @test
   */
  public function UserCanBeFoundById()
  {
    $user = $this->mapper->find(1);

    $this->assertEquals('joe123', $user->getNickname());
  }

  /**
   * @test
   */
  public function InsertingNewUserAndCompareObjectsThanDelete()
  {
    $user = new User('billy', 'gatter');

    $insertId = $this->mapper->insert($user);

    $user2 = $this->mapper->find($insertId);

    $this->assertTrue($user === $user2);
    $this->assertTrue($this->mapper->delete($user2));
  }

  /**
   * @test
   * @expectedException OutOfBoundsException
   */
  public function UserCanNotBeFoundById()
  {
    $this->mapper->find(123);
  }

  /**
   * @test
   */
  public function UserCanBeInserted()
  {
    $newUser = new User('maxf', 'love123');

    $lastinsertId = $this->mapper->insert($newUser);

    $this->assertEquals(3, $lastinsertId);

    $user = $this->mapper->find($lastinsertId);

    $this->assertEquals('maxf', $user->getNickname());
  }

  /**
   * @test
   */
  public function IdentityMapInteractionAndConsistency()
  {
    $user1 = $this->mapper->find(1);
    $user2 = $this->mapper->find(1);

    // expects same nickname in each object.
    $this->assertEquals($user2->getNickname(), $user1->getNickname());

    // update the nickname on user1.
    $user2->setNickname('tucker');

    // expects same nickname in each object.
    $this->assertEquals($user2->getNickname(), $user1->getNickname());

    // than update into the database.
    $this->mapper->update($user2);
  }

  /**
   * @test
   */
  public function PersistUserWithSomeArticles()
  {
    $newUser = new User('Conan', 'He rocks!');
    $newUser->addArticle('Conan I', 'Some content about Conan')
            ->addArticle('Conan II', 'Some content about Conan')
            ->addArticle('Rambo III', 'Some content about Rambo');

    $lastUserId = $this->mapper->insert($newUser);

    // unset the user-mapper and the identity-map - force db connection.
    unset($this->mapper);

    // create new user-mapper with new identity-map.
    $this->mapper = new UserMapper($this->db);

    $user = $this->mapper->find($lastUserId);

    foreach ($user->getArticles() as $article) {
      $this->assertInstanceOf('Article', $article);
    }
  }
}
