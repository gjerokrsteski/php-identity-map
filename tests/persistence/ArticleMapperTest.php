<?php
class ArticleMapperTest extends PHPUnit_Extensions_Database_TestCase
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
          file_get_contents(dirname(dirname(dirname(__FILE__))) . '/database/tbl_article.sql')
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

    public function testInsertingNewArticleWithExistingUser()
    {
       $article1 = new Article(
       	'About ability',
       	'Ability can take you to the top, but it takes character to keep you there.'
       );

       $article2 = new Article(
       	'About ability',
       	'An explanation of the Convention on the Rights of Persons with Disabilities.'
       );

       $userMapper = new UserMapper($this->db);

       $article1->setUser($userMapper->find(1));
       $article2->setUser($userMapper->find(1));

       $this->mapper->insert($article1);
       $this->mapper->insert($article2);
    }

}
