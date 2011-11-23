<?php
abstract class AbstractMapper implements MapperInterface
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
     * @param integer $id
     * @return array
     */
    abstract public function find($id);
}