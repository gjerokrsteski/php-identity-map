<?php
class Repository extends AbstractMapper
{
  /**
   * @param string $entity The name of the data-mapper class.
   * @return AbstractMapper
   */
  public function load($entity)
  {
    $entity = ucfirst($entity).'Mapper';

    if (true === $this->identityMap->hasId($entity)) {
      return $this->identityMap->getObject($entity);
    }

    $this->identityMap->set($entity, new $entity($this->db));

    return $this->identityMap->getObject($entity);
  }
}
