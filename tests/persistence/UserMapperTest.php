<?php
class UserMapperTest extends PHPUnit_Extensions_Database_TestCase
{
    protected $db;
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
          file_get_contents(dirname(dirname(dirname(__FILE__))) . '/database/tbl_user.sql')
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
    * @covers UserMapper::getAllIds
    */
    public function testListOfUserAccountIdsCanBeRetrieved()
    {
        $this->assertEquals(array(1, 2), $this->mapper->getAllIds());
    }

    public function testInsertingNewUserAndCompareObjectsThanDelete()
    {
      $user = new User();
      $user->setNickname('billy')->setPassword('gatter');

      $insertId = $this->mapper->insert($user);

      $user2 = $this->mapper->findById($insertId);

      $this->assertTrue($user === $user2);
      $this->assertTrue($this->mapper->delete($user2));
    }

    /**
    * @covers UserMapper::findById
    */
    public function testUserCanBeFoundById()
    {
        $user = $this->mapper->findById(1);

        $this->assertEquals('joe123', $user->getNickname());
    }

    /**
    * @expectedException OutOfBoundsException
    */
    public function testUserCanNotBeFoundById()
    {
        $user = $this->mapper->findById(123);
    }

   /**
    * @covers UserMapper::insert
    */
    public function testUserCanBeInserted()
    {
        $newUser = new User();
        $newUser
            ->setNickname('maxf')
            ->setPassword('love123');

        $lastinsertId = $this->mapper->insert($newUser);

        $this->assertEquals(3, $lastinsertId);

        $user = $this->mapper->findById($lastinsertId);

        $this->assertEquals('maxf', $user->getNickname());
    }

    public function testIdentityMapInteractionAndConsistency()
    {
      $user1 = $this->mapper->findById(1);
      $user2 = $this->mapper->findById(1);

      // expects same nickname in each object.
      $this->assertEquals($user2->getNickname(), $user1->getNickname());

      // update the nickname on user1.
      $user2->setNickname('tucker');

      // expects same nickname in each object.
      $this->assertEquals($user2->getNickname(), $user1->getNickname());

      // than update into the database.
      $this->mapper->update($user2);
    }
}
