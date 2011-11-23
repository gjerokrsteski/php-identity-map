<?php
class UserTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function PropertiesBeforeSettingValues()
    {
        $user = new User();

        $reflectedUser = new ReflectionClass(get_class($user));

        $this->assertTrue($reflectedUser->hasProperty('nickname'));
        $this->assertEquals(null, $user->getNickname());

        $this->assertTrue($reflectedUser->hasProperty('password'));
        $this->assertEquals(null, $user->getPassword());
    }

    /**
     * @test
     */
    public function InstanceNoException()
    {
        $newUser = new User('maxf', 'love123');

        $this->assertEquals('love123', $newUser->getPassword());
    }

    /**
     * @test
     */
    public function AddSomeArticles()
    {
      $newUser = new User('Conan', 'He rocks!');

      $this->assertFalse($newUser->hasArticles());

      $newUser->addArticle('Conan I', 'Some content about conan')
              ->addArticle('Conan I', 'Some content about conan');

      $this->assertTrue($newUser->hasArticles());
      $this->assertInstanceOf('Article', current($newUser->getArticles()));
    }
}

