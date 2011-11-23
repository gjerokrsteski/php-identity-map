<?php
class Article
{
  protected $title;
  protected $content;

  /**
   * User has many Articles.
   * @var User
   */
  protected $user;

  /**
   * @param string $title
   * @param string $content
   * @param User $user
   */
  public function __construct($title = null, $content = null, User $user = null)
  {
    $this->title   = $title;
    $this->content = $content;
    $this->user    = $user;
  }

	/**
	 * @return string
	 */
	public function getTitle()
	{
	    return $this->title;
	}

	/**
	 * @param string $title
	 * @return Article
	 */
	public function setTitle($title)
	{
	    $this->title = $title;

	    return $this;
	}

	/**
	 * @return string
	 */
	public function getContent()
	{
	    return $this->content;
	}

	/**
	 * @param string $content
	 * @return Article
	 */
	public function setContent($content)
	{
	    $this->content = $content;

	    return $this;
	}

	/**
	 * @return User
	 */
	public function getUser()
	{
	    return $this->user;
	}

	/**
	 * @param User $user
	 * @return Article
	 */
	public function setUser(User $user)
	{
	    $this->user = $user;

	    return $this;
	}
}