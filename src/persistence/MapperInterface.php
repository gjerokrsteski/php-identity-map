<?php
interface MapperInterface
{
    /**
     * @param PDO $db
     */
    public function __construct(PDO $db);

    /**
     * @param integer $id
     * @return array
     */
    public function find($id);
}