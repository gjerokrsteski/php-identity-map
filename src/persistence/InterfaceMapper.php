<?php
interface InterfaceMapper
{
    /**
     * @param PDO $db
     */
    public function __construct(PDO $db);
}