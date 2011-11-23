<?php
class User
{
    protected $id;
    protected $nickname;
    protected $password;

    /**
     * @var array
     */
    protected $articles;

    /**
     * @param string $nickname
     * @param string $password
     */
    public function __construct($nickname = null, $password = null)
    {
    	$this->nickname = $nickname;
    	$this->password = $password;
    }

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

    /**
     * @return integer The unique id.
     */
    public function getId()
    {
        return $this->id;
    }

    public function addArticle($title, $content)
    {
      $this->articles[] = new Article($title, $content, $this);
    }

    public function getArticles()
    {
      $this->articles;
    }

    /**
     * @return boolean
     */
    public function hasArticles()
    {
      return (true === is_array($this->articles) && false === empty($this->articles));
    }
}