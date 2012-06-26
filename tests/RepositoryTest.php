<?php
/**
 * @namespace   EntityRepositoryTest.php
 * @copyright   (c) 1999-2012 QuestBack AG http://www.questback.de/
 * @globalsfree
 * @strict
 */
class RepositoryTest extends PHPUnit_Framework_TestCase
{
  /**
   * @var PDO
   */
  protected $db;

  /**
   * @var Repository
   */
  protected $er;

  public function setUp()
  {
    parent::setUp();

    if ($this->db === null) {
      $this->db = new PDO(
        $GLOBALS['DB_DSN'],
        $GLOBALS['DB_USER'],
        $GLOBALS['DB_PASSWD'],
        array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
      );
    }
  }

  /**
   * @test
   */
  public function CreatingNewInstance()
  {
    new Repository($this->db);
  }

  /**
   * @test
   */
  public function LoadingEntity()
  {
    $repository = new Repository($this->db);

    $this->assertInstanceOf('UserMapper', $repository->load('user'));
  }

  /**
   * @test
   */
  public function LoadingEntityTwiceTimeExpectingTheSameObject()
  {
    $repository = new Repository($this->db);

    $this->assertSame($repository->load('user'), $repository->load('user'));
  }

  /**
   * @test
   */
  public function LoadingEntityAnFindOneEntry()
  {
    $repository = new Repository($this->db);
    $user = $repository->load('user');

    $this->assertInstanceOf('User', $user->find(1));
  }

  /**
   * @test
   */
  public function InsertingNewUserAndCompareObjectsThanDelete()
  {
    $user       = new User('billy', 'gatter');
    $repository = new Repository($this->db);
    $userMapper = $repository->load('User');
    $insertId   = $userMapper->insert($user);
    $user2      = $userMapper->find($insertId);

    $this->assertTrue($user === $user2);
    $this->assertTrue($userMapper->delete($user2));
  }

  /**
   * @test
   */
  public function CreateUserWithArticlesAndFindArticlesByUserIdThanDeleteUser()
  {
    $repository = new Repository($this->db);
    $userMapper = $repository->load('User');

    // create data.
    $newUser = new User('Conan', 'He rocks!');
    $newUser->addArticle('Conan I', 'Some content about Conan')
            ->addArticle('Conan II', 'Some content about Conan')
            ->addArticle('Rambo III', 'Some content about Rambo');

    // insert it.
    $lastInsertInd = $userMapper->insert($newUser);

    // retrieve the data from the articles.
    $articleMapper = $repository->load('Article');
    $articles1 = $articleMapper->findByUserId($lastInsertInd);
    $articles2 = $articleMapper->findByUserId($lastInsertInd);

    $this->assertNotEmpty($articles1);
    $this->assertNotEmpty($articles2);

    foreach ($articles1 as $article) {
      $this->assertInstanceOf('Article', $article);
    }

    foreach ($articles2 as $article) {
      $this->assertInstanceOf('Article', $article);
    }

    $this->assertTrue($userMapper->delete($newUser));
  }
}
