<?php
class User
{
  /**
   * @var integer
   */
  protected $id;

  /**
   * @var null|string
   */
  protected $nickname;

  /**
   * @var null|string
   */
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
  public function __construct($nickname, $password)
  {
    $this->nickname = $nickname;
    $this->password = $password;
  }

  /**
   * @return string
   */
  public function getNickname()
  {
    return $this->nickname;
  }

  /**
   * @param string $nickname
   * @return User
   */
  public function setNickname($nickname)
  {
    $this->nickname = $nickname;

    return $this;
  }

  /**
   * @return string
   */
  public function getPassword()
  {
    return $this->password;
  }

  /**
   * @param string $password
   * @return User
   */
  public function setPassword($password)
  {
    $this->password = $password;

    return $this;
  }

  /**
   * @return integer
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
   * @param array $article List of Article objects.
   * @return User
   */
  public function setArticles(array $article)
  {
    $this->articles = $article;

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
    return (true === is_array($this->articles) && false === empty($this->articles));
  }
}
