<?php

require_once __DIR__ . "/../core/Database.php";
interface IModel
{
//    CREATE & UPDATE
/* @return number of rows updated/added
 *
 */
    public function save();
//    READ
/* @return object of the class
 *
 */
    public static function findById($id);
/* @return array of objects of the class
 *
 */
    public static function findAll();
//    Delete
/* @return number of rows deleted
 *
 */
    public function delete();
/* @return number of rows deleted
 *
 */
    public static function deleteById($id);
/* Creates an object of the class from a row in the database (result of a find())
 * @param $row - a row from the database
 * @return object of the class
 */
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