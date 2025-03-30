<?php

namespace core;
use Exception;

// Log the exception message into a file and return the message
function handleException(Exception $e)
{
    Logger::log($e->getMessage(), 'error');
    return ['success' => false, 'message' => 'Server-side error, please contact admin (to check the logs).'];
}

interface IService
{
    /**
     * Saves the current object.
     * If the object has an ID, it updates; otherwise, it inserts a new record.
     * @param object $model Object to save.
     * @return int Number of rows affected (inserted or updated).
     */
    public static function save($model);

    /**
     * Finds a record by its ID and returns an object representation.
     *
     * @param int $id ID of the record to find.
     * @return mixed|null Object of the class if found, otherwise null.
     */
    public static function findById($id);

    /**
     * Finds all records and returns an array of objects.
     *
     * @return array Array of objects of the class.
     */
    public static function findAll();

    /**
     * Deletes a record by its ID.
     *
     * @param int $id ID of the record to delete.
     * @return int Number of rows deleted (1 if successful, 0 otherwise).
     */
    public static function deleteById($id);
}
