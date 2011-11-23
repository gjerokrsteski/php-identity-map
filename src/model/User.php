<?php
class User
{
    protected $id;
    protected $nickname;
    protected $password;

    /**
     * One User has many Article instances.
     * @var array A list of Article instances.
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

    /**
     * @param string $title
     * @param string $content
     * @return User
     */
    public function addArticle($title, $content)
    {
      $this->articles[] = new Article($title, $content, $this);

      return $this;
    }

    /**
     * @param Article $article
     * @return User
     */
    public function pushArticle(Article $article)
    {
      $this->articles[] = $article;

      return $this;
    }

    /**
     * @return array A list of Article instances.
     */
    public function getArticles()
    {
      return $this->articles;
    }

    /**
     * @return boolean
     */
    public function hasArticles()
    {
      return (
        true === is_array($this->articles)
        &&
        false === empty($this->articles)
      );
    }
}