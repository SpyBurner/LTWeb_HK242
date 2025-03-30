<?php

namespace core;
require_once __DIR__ . "/../core/Database.php";

interface IModel
{
    public static function toObject($row);

    /* Creates an array of objects of the class, for whatever reason
     *
     */
    public static function toArray($obj);

    /* @return string representation of the object, mostly for debugging
     *
     */
    public function __toString();

}