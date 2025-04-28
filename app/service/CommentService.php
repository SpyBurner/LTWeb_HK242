<?php
namespace service;

use core\Database;
use Exception;
use model\CommentModel;
use function core\handleException;

class CommentService
{
    public static function createInstance($blogid, $userid, $content)
    {
        return new CommentModel($blogid, $userid, $content);
    }

    public static function save($model)
    {
        if (!$model instanceof CommentModel) {
            return ['success' => false, 'message' => 'Invalid model type'];
        }

        try {
            $pdo = Database::getInstance()->getConnection();

            $stmt = $pdo->prepare("
                INSERT INTO blogcomment (blogid, userid, content) 
                VALUES (:blogid, :userid, :content)
            ");
            $params = [
                ':content' => $model->getContent(),
                ':blogid' => $model->getBlogid(),
                ':userid' => $model->getUserid()
            ];

            $stmt->execute($params);

            return ['success' => true, 'message' => 'Comment created successfully'];
        } catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function findByBlogId($id) {
        try {
            $stmt = Database::getInstance()->getConnection()->prepare("SELECT b.*, u.username FROM blogcomment b inner join user u on u.userid = b.userid WHERE blogid = :id");
            $stmt->execute([':id' => $id]);
            $data = $stmt->fetchAll();

            return ['success' => true, 'data' => array_map(function ($item) {
                return CommentModel::toObject($item);
            }, $data)];
        } catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function findAll()
    {
        try {
            $stmt = Database::getInstance()->getConnection()->prepare("SELECT * FROM Comment");
            $stmt->execute();
            $data = $stmt->fetchAll();

            return ['success' => true, 'data' => array_map(function ($item) {
                return CommentModel::toObject($item);
            }, $data)];
        } catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function deleteComment($blogid, $userid, $commentdate)
    {
        try {
            $stmt = Database::getInstance()->getConnection()->prepare("DELETE FROM blogcomment WHERE blogid = :blogid AND userid = :userid AND commentdate = :commentdate");
            $stmt->execute([':blogid' => $blogid, ':userid' => $userid, ':commentdate' => $commentdate]);

            return ['success' => true, 'message' => 'Comment deleted successfully'];
        } catch (Exception $e) {
            return handleException($e);
        }
    }
}