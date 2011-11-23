<?php
class UserMapper extends AbstractMapper
{
    /**
     * @param integer $id
     * @throws OutOfBoundsException
     * @return User
     */
    public function find($id)
    {
        if (true === $this->identityMap->hasId($id))
        {
            return $this->identityMap->getObject($id);
        }

        $sth = $this->db->prepare('SELECT * FROM tbl_user WHERE id = :id');
        $sth->bindValue(':id', $id, PDO::PARAM_INT);
        $sth->execute();

        if ($sth->rowCount() == 0)
        {
            throw new OutOfBoundsException(
              sprintf('No user with id #%d exists.', $id)
            );
        }

        $object = $sth->fetchObject();
        $user   = new User($object->nickname, $object->password);

        $attribute = new ReflectionProperty($user, 'id');
        $attribute->setAccessible(true);
        $attribute->setValue($user, $id);

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
        if (true === $this->identityMap->hasObject($user))
        {
            throw new MapperException('Object has an ID, cannot insert.');
        }

        $sth = $this->db->prepare(
        	"INSERT INTO tbl_user (nickname, `password`) VALUES (:nick, :passwd)"
        );

        $sth->bindValue(':nick', $user->getNickname(), PDO::PARAM_STR);
        $sth->bindValue(':passwd', $user->getPassword(), PDO::PARAM_STR);
        $sth->execute();

        $id = (int)$this->db->lastInsertId();

        $attribute = new ReflectionProperty($user, 'id');
        $attribute->setAccessible(true);
        $attribute->setValue($user, $id);

        if (true === $user->hasArticles())
        {
          $articleMapper = new ArticleMapper($this->db);

          foreach ($user->getArticles() as $article) {
            $articleMapper->insert($article);
          }
        }

        $this->identityMap->set($id, $user);

        return (int) $this->db->lastInsertId();
    }

    /**
     * @param User $user
     * @throws MapperException
     * @return boolean
     */
    public function update(User $user)
    {
        if (false === $this->identityMap->hasObject($user))
        {
            throw new MapperException('Object has no ID, cannot update.');
        }

        $sth = $this->db->prepare(
        	"UPDATE tbl_user SET nickname = :nick, `password` = :passwd WHERE id = :id"
        );

        $sth->bindValue(':nick', $user->getNickname(), PDO::PARAM_STR);
        $sth->bindValue(':passwd', $user->getPassword(), PDO::PARAM_STR);
        $sth->bindValue(':id', $this->identityMap->getId($user), PDO::PARAM_INT);

        return $sth->execute();
    }

    /**
     * @param User $user
     * @throws MapperException
     * @return boolean
     */
    public function delete(User $user)
    {
        if (false === $this->identityMap->hasObject($user))
        {
            throw new MapperException('Object has no ID, cannot delete.');
        }

        $sth = $this->db->prepare(
        	"DELETE FROM tbl_user WHERE id = :id;"
        );

        $sth->bindValue(':id', $this->identityMap->getId($user), PDO::PARAM_INT);

        return $sth->execute();
    }
}