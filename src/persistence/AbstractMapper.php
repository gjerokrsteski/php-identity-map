<?php
/**
 * @method insert($entity)
 * @method update($entity)
 * @method delete($entity)
 * @method find($id)
 */
abstract class AbstractMapper
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

  public function __destruct()
  {
    unset($this->identityMap, $this->db);
  }
}