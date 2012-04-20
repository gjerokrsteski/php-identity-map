<?php
class ArticleMapper extends AbstractMapper
{
  /**
   * @param integer $id
   * @return Article|object
   * @throws OutOfBoundsException
   */
  public function find($id)
  {
    if (true === $this->identityMap->hasId($id)) {
      return $this->identityMap->getObject($id);
    }

    $sth = $this->db->prepare(
      'SELECT * FROM tbl_article WHERE id = :id'
    );

    $sth->bindValue(':id', $id, PDO::PARAM_INT);
    $sth->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Article', array('title', 'content'));
    $sth->execute();

    if ($sth->rowCount() == 0) {
      throw new OutOfBoundsException(
        sprintf('No article with id #%d exists.', $id)
      );
    }

    // let pdo fetch the Article instance for you.
    $article = $sth->fetch();

    $this->identityMap->set($id, $article);

    return $article;
  }

  /**
   * @param integer $id
   * @throws OutOfBoundsException
   * @return array A list of Article objects.
   */
  public function findByUserId($id)
  {
    $sth = $this->db->prepare(
      "SELECT * FROM tbl_article WHERE userId = :userId"
    );

    $sth->bindValue(':userId', $id, PDO::PARAM_INT);
    $sth->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Article', array('title', 'content'));
    $sth->execute();

    if ($sth->rowCount() == 0) {
      throw new OutOfBoundsException(
        sprintf('No article with userId #%d exists.', $id)
      );
    }

    $articles = $sth->fetchAll();

    return $articles;
  }

  /**
   * @param Article $article
   * @throws MapperException
   * @return integer A lastInsertId.
   */
  public function insert(Article $article)
  {
    if (true === $this->identityMap->hasObject($article)) {
      throw new MapperException('Object has an ID, cannot insert.');
    }

    $sth = $this->db->prepare(
      "INSERT INTO tbl_article (title, content, userId) " .
      "VALUES (:title, :content, :userId)"
    );

    $sth->bindValue(':title', $article->getTitle(), PDO::PARAM_STR);
    $sth->bindValue(':content', $article->getContent(), PDO::PARAM_STR);
    $sth->bindValue(':userId', $article->getUser()->getId(), PDO::PARAM_INT);
    $sth->execute();

    $this->identityMap->set((int)$this->db->lastInsertId(), $article);

    return (int)$this->db->lastInsertId();
  }

  /**
   * @param Article $article
   * @throws MapperException
   * @return boolean
   */
  public function update(Article $article)
  {
    if (false === $this->identityMap->hasObject($article)) {
      throw new MapperException('Object has no ID, cannot update.');
    }

    $sth = $this->db->prepare(
      "UPDATE tbl_article " .
      "SET title = :title, content = :content WHERE id = :id"
    );

    $sth->bindValue(':title', $article->getTitle(), PDO::PARAM_STR);
    $sth->bindValue(':content', $article->getContent(), PDO::PARAM_STR);
    $sth->bindValue(':id', $this->identityMap->getId($article), PDO::PARAM_INT);
    $sth->execute();

    if ($sth->rowCount() == 1) {
      return true;
    }

    return false;
  }

  /**
   * @param Article $article
   * @throws MapperException
   * @return boolean
   */
  public function delete(Article $article)
  {
    if (false === $this->identityMap->hasObject($article)) {
      throw new MapperException('Object has no ID, cannot delete.');
    }

    $sth = $this->db->prepare(
      "DELETE FROM tbl_article WHERE id = :id LIMIT 1"
    );

    $sth->bindValue(':id', $this->identityMap->getId($article), PDO::PARAM_INT);
    $sth->execute();

    if ($sth->rowCount() == 0) {
      return false;
    }

    return true;
  }
}
