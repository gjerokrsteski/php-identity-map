<?php
class ArticleTest extends PHPUnit_Framework_TestCase
{
  /**
   * @test
   */
  public function CreatingNewInstance()
  {
    $article = new Article(null, null);

    $reflectedArticle = new ReflectionClass(get_class($article));

    $this->assertTrue($reflectedArticle->hasProperty('title'));
    $this->assertEquals(null, $article->getTitle());

    $this->assertTrue($reflectedArticle->hasProperty('content'));
    $this->assertEquals(null, $article->getContent());

    $this->assertTrue($reflectedArticle->hasProperty('user'));
    $this->assertEquals(null, $article->getUser());
  }

  /**
   * @test
   */
  public function InstanceNoException()
  {
    new Article('the title', 'the content');
  }
}

