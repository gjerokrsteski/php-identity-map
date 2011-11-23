<?php
interface MapperInterface
{
    /**
     * @param PDO $db
     */
    public function __construct(PDO $db);
}