<?php
class UserMapper extends AbstractMapper
{
  /**
   * @param integer $id
   * @return User
   * @throws OutOfBoundsException
   */
  public function find($id)
  {
    if (true === $this->identityMap->hasId($id)) {
      return $this->identityMap->getObject($id);
    }

    $sth = $this->db->prepare(
      'SELECT * FROM tbl_user WHERE id = :id'
    );

    $sth->bindValue(':id', $id, PDO::PARAM_INT);
    $sth->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'User', array('nickname', 'password'));
    $sth->execute();

    if ($sth->rowCount() == 0) {
      throw new OutOfBoundsException(
        sprintf('No user with id #%d exists.', $id)
      );
    }

    // let pdo fetch the User instance for you.
    $user = $sth->fetch();

    // set the protected id of user via reflection.
    $attribute = new ReflectionProperty($user, 'id');
    $attribute->setAccessible(true);
    $attribute->setValue($user, $id);

    // load all user's articles
    $articleMapper = new ArticleMapper($this->db);

    try {

      $user->setArticles($articleMapper->findByUserId($id));

    } catch (OutOfBoundsException $e) {
	// no articles at the database.
    }
    
    $this->identityMap->set($id, $user);

    return $user;
  }

  /**
   * @param User $user
   * @throws MapperException
   * @return integer A lastInsertId.
   */
  public function insert(User $user)
  {
    if (true === $this->identityMap->hasObject($user)) {
      throw new MapperException('Object has an ID, cannot insert.');
    }

    $sth = $this->db->prepare(
      "INSERT INTO tbl_user (nickname, `password`) " .
      "VALUES (:nick, :passwd)"
    );

    $sth->bindValue(':nick', $user->getNickname(), PDO::PARAM_STR);
    $sth->bindValue(':passwd', $user->getPassword(), PDO::PARAM_STR);
    $sth->execute();

    $id = (int)$this->db->lastInsertId();

    $attribute = new ReflectionProperty($user, 'id');
    $attribute->setAccessible(true);
    $attribute->setValue($user, $id);

    // if user has assosiated articles.
    if (true === $user->hasArticles()) {
      $articleMapper = new ArticleMapper($this->db);

      // than insert the articles too.
      foreach ($user->getArticles() as $article) {
	$article->setUser($user);
        $articleMapper->insert($article);
      }
    }

    $this->identityMap->set($id, $user);

    return $id;
  }

  /**
   * @param User $user
   * @throws MapperException
   * @return boolean
   */
  public function update(User $user)
  {
    if (false === $this->identityMap->hasObject($user)) {
      throw new MapperException('Object has no ID, cannot update.');
    }

    $sth = $this->db->prepare(
      "UPDATE tbl_user " .
      "SET nickname = :nick, `password` = :passwd WHERE id = :id"
    );

    $sth->bindValue(':nick', $user->getNickname(), PDO::PARAM_STR);
    $sth->bindValue(':passwd', $user->getPassword(), PDO::PARAM_STR);
    $sth->bindValue(':id', $this->identityMap->getId($user), PDO::PARAM_INT);

    $sth->execute();

    if ($sth->rowCount() == 1) {
      return true;
    }

    return false;
  }

  /**
   * @param User $user
   * @throws MapperException
   * @return boolean
   */
  public function delete(User $user)
  {
    if (false === $this->identityMap->hasObject($user)) {
      throw new MapperException('Object has no ID, cannot delete.');
    }

    $sth = $this->db->prepare(
      "DELETE FROM tbl_user WHERE id = :id;"
    );

    $sth->bindValue(':id', $this->identityMap->getId($user), PDO::PARAM_INT);
    $sth->execute();

    if ($sth->rowCount() == 0) {
      return false;
    }

    return true;
  }
}
