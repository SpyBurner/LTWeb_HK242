<?php
namespace service;

use core\Database;
use core\IService;
use Exception;
use PDO;

class ContactMessageService implements IService {

    public static function save($model): array
    {
        try {
            $pdo = Database::getInstance()->getConnection();

            if ($model->getId()) {
                // Update an existing contact message
                $stmt = $pdo->prepare("
                    UPDATE contactmessage 
                    SET name = :name, email = :email, title = :title, message = :message
                    WHERE contactid = :id
                ");
                $params = [
                    ':name' => $model->getName(),
                    ':email' => $model->getEmail(),
                    ':title' => $model->getTitle(),
                    ':message' => $model->getMessage(),
                    // ':created_at' => $model->getCreatedAt(),
                    ':id' => $model->getId() // Only used for updates
                ];

            } else {
                // Insert a new contact message
                $stmt = $pdo->prepare("
                    INSERT INTO contactmessage (name, email, title, message) 
                    VALUES (:name, :email, :title, :message)
                ");
                $params = [
                    ':name' => $model->getName(),
                    ':email' => $model->getEmail(),
                    ':title' => $model->getTitle(),
                    ':message' => $model->getMessage(),
                    // ':created_at' => $model->getCreatedAt()
                ];
            }
            $stmt->execute($params);

            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Failed to save contact message: ' . $e->getMessage()];
        }
    }

    public static function findById($id): array
    {
        try {
            $pdo = Database::getInstance()->getConnection();
            $stmt = $pdo->prepare("SELECT * FROM contactmessage WHERE contactid = :id");
            $stmt->execute([':id' => $id]);
            return [
                'success' => true,
                'data' => $stmt->fetch(PDO::FETCH_ASSOC)
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to fetch contact message: ' . $e->getMessage()
            ];
        }
    }

    public static function findAll(): array
    {
        try {
            $pdo = Database::getInstance()->getConnection();
            $stmt = $pdo->query("SELECT * FROM contactmessage");
            return [
                'success' => true,
                'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to fetch contact messages: ' . $e->getMessage()
            ];
        }
    }

    public static function findFiltered(string $filter, int $limit, int $offset): array
    {
        if (!in_array($filter, ['All', 'Unread', 'Read', 'Replied'])) {
            return [
                'success' => false,
                'message' => 'Invalid status filter'
            ];
        }
        try {
            $pdo = Database::getInstance()->getConnection();

            if ($filter == 'All') {
                $filter = '%';
            }
            $stmt = $pdo->prepare("
                SELECT * FROM contactmessage 
                WHERE status LIKE :filter 
                ORDER BY created_at DESC 
                LIMIT :limit OFFSET :offset
            ");
            $stmt->execute([
                ':filter' => $filter,
                ':limit' => $limit,
                ':offset' => $offset
            ]);

            return [
                'success' => true,
                'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to fetch filtered contact messages: ' . $e->getMessage()
            ];
        }
    }

    public static function countByStatus(string $status): array
    {
        if ($status == 'All') {
            return self::countAll();
        }

        if (!in_array($status, ['Unread', 'Read', 'Replied'])) {
            return [
                'success' => false,
                'message' => 'Invalid status filter'
            ];
        }

        try {
            $pdo = Database::getInstance()->getConnection();
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM contactmessage WHERE status = :status");
            $stmt->execute([':status' => $status]);
            return [
                'success' => true,
                'data' => $stmt->fetchColumn()
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to count contact messages by status: ' . $e->getMessage()
            ];
        }
    }

    public static function countAll(): array
    {
        try {
            $pdo = Database::getInstance()->getConnection();
            $stmt = $pdo->query("SELECT COUNT(*) FROM contactmessage");
            return [
                'success' => true,
                'data' => $stmt->fetchColumn()
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to count all contact messages: ' . $e->getMessage()
            ];
        }
    }

    public static function deleteById($id): array
    {
        try {
            $pdo = Database::getInstance()->getConnection();
            $stmt = $pdo->prepare("DELETE FROM contactmessage WHERE contactid = :id");
            $stmt->execute([':id' => $id]);
            return ['success' => true];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to delete contact message: ' . $e->getMessage()
            ];
        }
    }

    public static function markAsRead($id): array
    {
        try {
            $pdo = Database::getInstance()->getConnection();
            $stmt = $pdo->prepare("UPDATE contactmessage SET status = 'Read' WHERE contactid = :id");
            $stmt->execute([':id' => $id]);
            return ['success' => true];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to mark message as read: ' . $e->getMessage()
            ];
        }
    }

    public static function markAsReplied($id): array
    {
        try {
            $pdo = Database::getInstance()->getConnection();
            $stmt = $pdo->prepare("UPDATE contactmessage SET status = 'Replied' WHERE contactid = :id");
            $stmt->execute([':id' => $id]);
            return ['success' => true];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to mark message as replied: ' . $e->getMessage()
            ];
        }
    }


}