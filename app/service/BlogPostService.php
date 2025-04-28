<?php
namespace service;

use core\Database;
use core\IService;
use Exception;
use model\BlogPostModel;
use function core\handleException;

class BlogPostService implements IService
{
    public static function save($model)
    {
        if (!$model instanceof BlogPostModel) {
            return ['success' => false, 'message' => 'Invalid model type'];
        }

        try {
            $pdo = Database::getInstance()->getConnection();

            if ($model->getBlogid()) {
                // Update existing news
                $stmt = $pdo->prepare("
                    UPDATE blogpost
                    SET title = :title, content = :content, adminid = :adminid
                    WHERE blogid = :blogid
                ");
                $params = [
                    ':title' => $model->getTitle(),
                    ':content' => $model->getContent(),
                    ':adminid' => $model->getAdminid(),
                    ':blogid' => $model->getBlogid() // Only used for updates
                ];
            } else {
                // Insert new news
                $stmt = $pdo->prepare("
                    INSERT INTO blogpost (adminid, content, title)
                    VALUES (:adminid, :content, :title)
                ");
                $params = [
                    ':title' => $model->getTitle(),
                    ':content' => $model->getContent(),
                    ':adminid' => $model->getAdminId()
                ];
            }

            $stmt->execute($params);

            return ['success' => true, 'message' => $model->getBlogid() ? 'Blog updated successfully' : 'Blog created successfully'];
        } catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function createInstance($blogid, $title, $content, $adminid)
    {
        if ($blogid) {
            return new BlogPostModel($blogid, $adminid, $title, $content);
        }
        return new BlogPostModel(null, $adminid, $title, $content);
    }

    public static function findById($id)
    {
        try {
            $stmt = Database::getInstance()->getConnection()->prepare("SELECT * FROM blogpost WHERE blogid = :id");
            $stmt->execute([':id' => $id]);
            $data = $stmt->fetch();

            return $data ? ['success' => true, 'data' => BlogPostModel::toObject($data)] : ['success' => false, 'message' => 'Blog not found'];
        } catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function allLikesByBlogId($id) {
        try {
            $stmt = Database::getInstance()->getConnection()->prepare("SELECT COUNT(*) as amount FROM `like` WHERE blogid = :id");
            $stmt->execute([':id' => $id]);
            $data = $stmt->fetchAll();

            return ['success' => true, 'data' => $data[0]['amount']];
        } catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function findAllRelatedPosts($id) {
        try {
            $stmt = Database::getInstance()->getConnection()->prepare("SELECT * FROM blogpost WHERE blogid != :id");
            $stmt->execute([':id' => $id]);
            $data = $stmt->fetchAll();

            return ['success' => true, 'data' => array_map(function ($item) {
                return BlogPostModel::toObject($item);
            }, $data)];
        } catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function findAuthorById($adminid)
    {
        try {
            $stmt = Database::getInstance()->getConnection()->prepare("SELECT username FROM user WHERE userid = :adminid");
            $stmt->execute([':adminid' => $adminid]);
            $data = $stmt->fetch();

            return ['success' => true, 'data' => $data ? $data['username'] : null];
        } catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function findAll()
    {
        try {
            $stmt = Database::getInstance()->getConnection()->prepare("SELECT * FROM blogpost");
            $stmt->execute();
            $data = $stmt->fetchAll();

            return ['success' => true, 'data' => array_map(function ($item) {
                return BlogPostModel::toObject($item);
            }, $data)];
        } catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function deleteById($id)
    {
        try {
            $stmt = Database::getInstance()->getConnection()->prepare("DELETE FROM blogpost WHERE blogid = :id");
            $stmt->execute([':id' => $id]);

            return ['success' => true, 'message' => 'News deleted successfully'];
        } catch (Exception $e) {
            return handleException($e);
        }
    }
}
?>