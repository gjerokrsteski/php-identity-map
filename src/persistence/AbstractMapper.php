<?php
abstract class AbstractMapper implements InterfaceMapper
{
  /**
   * @var PDO The database resource.
   */
  protected $db;

  /**
   * @var IdentityMap
   */
  protected $identityMap;

  /**
   * @param PDO $db
   */
  public function __construct(PDO $db)
  {
    $this->db          = $db;
    $this->identityMap = new IdentityMap();
  }

  /**
   * @abstract
   * @param integer $id
   */
  abstract public function find($id);

  public function __destruct()
  {
    unset($this->identityMap, $this->db);
  }
}