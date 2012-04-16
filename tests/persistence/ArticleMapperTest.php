<?php
class ArticleMapperTest extends PHPUnit_Extensions_Database_TestCase
{
  protected $db;

  /**
   * @var ArticleMapper
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
        dirname(dirname(dirname(__FILE__))) . '/database/tbl_article.sql'
      )
    );

    $this->mapper = new ArticleMapper($this->db);

    parent::setUp();
  }

  public function getConnection()
  {
    return $this->createDefaultDBConnection($this->db, $GLOBALS['DB_DBNAME']);
  }

  public function getDataSet()
  {
    return $this->createFlatXMLDataSet(
      __DIR__ . '/fixture/article-seed.xml'
    );
  }

  /**
   * @test
   */
  public function FindByUserId()
  {
    $articles1 = $this->mapper->findByUserId(1);
    $articles2 = $this->mapper->findByUserId(1);

    $this->assertNotEmpty($articles1);
    $this->assertNotEmpty($articles2);

    foreach ($articles1 as $article) {
      $this->assertInstanceOf('Article', $article);
    }

    foreach ($articles2 as $article) {
      $this->assertInstanceOf('Article', $article);
    }
  }

  /**
   * @test
   * @expectedException OutOfBoundsException
   */
  public function deleteUserExpectingDeletingAllArticlesFromUser()
  {
    // create data.
    $newUser = new User('Conan', 'He rocks!');
    $newUser->addArticle('Conan I', 'Some content about Conan')
            ->addArticle('Conan II', 'Some content about Conan')
            ->addArticle('Rambo III', 'Some content about Rambo');

    // use user-mapper to insert data.
    $userMapper = new UserMapper($this->db);

    $lastUserId = $userMapper->insert($newUser);

    // than delete user with all his articles.
    $userMapper->delete($newUser);

    // this one throws the expected OutOfBoundsException.
    $this->mapper->findByUserId($lastUserId);
  }

  /**
   * @test
   */
  public function UpdateArticle()
  {
    $articles1 = $this->mapper->find(2);

    $articles1->setTitle('conan')->setContent('the barbar');

    $res = $this->mapper->update($articles1);

    $this->assertTrue($res);
  }
}
