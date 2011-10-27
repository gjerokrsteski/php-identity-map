<?php
class User
{
    protected $nickname;
    protected $password;

    /**
     * @return string The $nickname
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * @param $nickname the $nickname to set.
     * @return User
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;

        return $this;
    }

    /**
     * @return string The $password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param $password the $password to set.
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }
}