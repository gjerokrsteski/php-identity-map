<?php
class UserTest extends PHPUnit_Framework_TestCase
{
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp ()
    {
        parent::setUp();
    }
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown ()
    {
        parent::tearDown();
    }

    /**
     * tests the status before the instance.
     */
    public function testPropertiesBeforeSettingValues()
    {
        $user = new User();

        $reflectedUser = new ReflectionClass(get_class($user));

        $this->assertTrue($reflectedUser->hasProperty('nickname'));
        $this->assertEquals(null, $user->getNickname());


        $this->assertTrue($reflectedUser->hasProperty('password'));
        $this->assertEquals(null, $user->getPassword());
    }


    public function testInstanceNoException()
    {
        $newUser = new User();
        $newUser
            ->setNickname('maxf')
            ->setPassword('love123');

        $this->assertEquals('love123', $newUser->getPassword());
    }
}

