<?php
class IdentityMap
{
  /**
   * @var ArrayObject
   */
  protected $idToObject;

  /**
   * @var SplObjectStorage
   */
  protected $objectToId;

  public function __construct()
  {
    $this->objectToId = new SplObjectStorage();
    $this->idToObject = new ArrayObject();
  }

  /**
   * @param integer $id
   * @param object $object
   */
  public function set($id, $object)
  {
    $this->idToObject[$id]     = $object;
    $this->objectToId[$object] = $id;
  }

  /**
   * @param object $object
   * @throws OutOfBoundsException
   * @return integer
   */
  public function getId($object)
  {
    if (false === $this->hasObject($object)) {
      throw new OutOfBoundsException();
    }

    return $this->objectToId[$object];
  }

  /**
   * @param integer $id
   * @return boolean
   */
  public function hasId($id)
  {
    return isset($this->idToObject[$id]);
  }

  /**
   * @param object $object
   * @return boolean
   */
  public function hasObject($object)
  {
    return isset($this->objectToId[$object]);
  }

  /**
   * @param integer $id
   * @throws OutOfBoundsException
   * @return object
   */
  public function getObject($id)
  {
    if (false === $this->hasId($id)) {
      throw new OutOfBoundsException();
    }

    return $this->idToObject[$id];
  }
}
