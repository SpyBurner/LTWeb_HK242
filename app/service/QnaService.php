<?php
namespace service;

use core\Database;
use core\IService;
use core\Logger;
use Exception;
use model\QnaEntryModel;
use PDO;
use function core\handleException;

class QnaService implements IService
{
    public static function save($model)
    {
        if (!$model instanceof QnaModel) {
            return ['success' => false, 'message' => 'Invalid model type'];
        }

        if ($model->getQnaid()){
            //Update existing
            //SHOULDN'T HAPPEN
        }
        else{
            //Insert new
            try {
                $pdo = Database::getInstance()->getConnection();
                $stmt = $pdo->prepare("
                    INSERT INTO QnaEntry (question, answer, created_at) 
                    VALUES (:question, :answer, :created_at)
                ");
                $params = [
                    ':question' => $model->getQuestion(),
                    ':answer' => $model->getAnswer(),
                    ':created_at' => $model->getCreatedAt()
                ];
                $stmt->execute($params);

                return [
                    'success' => true,
                    'message' => 'Qna entry created successfully',
                    'qnaid' => $pdo->lastInsertId()
                ];
            } catch (Exception $e) {
                return handleException($e);
            }
        }
    }

    private static function fetchMsg($entry)
    {
        $msg = MessageService::findAllByQnaId($entry->getQnaid());
        if ($msg['success']) {
            $entry->setMessages($msg['data']);
            return ['success' => true];
        } else {
            Logger::log("Error fetching messages for QnaEntry with id " . $entry->getQnaid());
            return ['success' => false];
        }
    }

    public static function findById($id)
    {
        try {
            $stmt = Database::getInstance()->getConnection()->prepare("SELECT * FROM QnaEntry WHERE qnaid = :qnaid");
            $stmt->execute([':qnaid' => $id]);

            $result = $stmt->fetch();

            if ($result) {
                $model = QnaEntryModel::toObject($result);

                // Fetch messages related to this QnaEntry
                $result = self::fetchMsg($model);
                if (!$result['success']) {
                    return ['success' => false, 'message' => 'Error fetching messages for QnaEntry id ' . $id];
                }

                $ret = ['success' => true, 'data' => $model];
            }
            else{
                $ret = ['success' => false, 'message' => 'Qna entry not found'];
            }

            return $ret;
        }
        catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function findAll($limit = null, $offset = null)
    {
        try {
            $stmtLimit = ($limit != null)? " LIMIT :limit" : "";
            $stmtOffset = ($offset != null)? " OFFSET :offset" : "";

            $stmt = Database::getInstance()->getConnection()->prepare(
                "SELECT * FROM QnaEntry"
                . $stmtLimit
                . $stmtOffset
            );
            if ($limit) $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            if ($offset) $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

            $stmt->execute();
            $qnaEntries = array_map(fn($row) => QnaEntryModel::toObject($row), $stmt->fetchAll());

            // Fetch messages for each QnaEntry
            foreach ($qnaEntries as $entry) {
                $result = self::fetchMsg($entry);
                if (!$result['success']) {
                    return ['success' => false, 'message' => 'Error fetching messages for QnaEntry id ' . $entry->getQnaid()];
                }
            }

            return ['success' => true, 'data' => $qnaEntries];
        } catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function deleteById($id)
    {
        try {
            $stmt = Database::getInstance()->getConnection()->prepare("DELETE FROM QnaEntry WHERE qnaid = :qnaid");
            $stmt->execute([':qnaid' => $id]);

            if ($stmt->rowCount() == 0) {
                Logger::log("No QnaEntry found with ID: $id");
                return ['success' => false, 'message' => 'QnaEntry not found'];
            }
            else {
                Logger::log("QnaEntry with ID: $id deleted successfully");
                return ['success' => true, 'message' => 'QnaEntry deleted successfully'];
            }
        } catch (Exception $e) {
            Logger::log("Failed to delete QnaEntry: " . $e->getMessage());
            return handleException($e);
        }
    }
}