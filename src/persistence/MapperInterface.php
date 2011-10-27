<?php
interface MapperInterface
{
    /**
     * @param PDO $db
     */
    public function __construct(PDO $db);

    /**
     * @return array
     */
    public function getAllIds();

    /**
     * @param integer $id
     * @return array
     */
    public function findById($id);
}