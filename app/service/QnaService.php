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
        if (!$model instanceof QnaEntryModel) {
            return ['success' => false, 'message' => 'Invalid model type'];
        }

        if ($model->getQnaid()){
            Logger::log("[IMPOSSIBLE]QnaController::addQuestion " . $model->getQnaid());
            //Update existing
            //SHOULDN'T HAPPEN
        }
        else{
            //Insert new
            try {
                if ($model->getMessage() == null){
                    throw new Exception('Set first message before saving');
                }

                Logger::log("Before inserting QnaEntry");
                $pdo = Database::getInstance()->getConnection();
                $stmt = $pdo->prepare("
                    INSERT INTO QnaEntry () VALUES ();
                ");
                $stmt->execute();
                Logger::log("After inserting QnaEntry");

                $last_id = $pdo->lastInsertId();

                $message = $model->getMessage();
                $message->setQnaid($last_id);

                $result = MessageService::save($message);

                if (!$result['success']) {
                    Logger::log("Error saving message for QnaEntry with id " . $last_id . ": " . $result['message']);
                    return ['success' => false, 'message' => 'Error saving message for QnaEntry'];
                }

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
        $result = MessageService::findFirstByQnaId($entry->getQnaid());
        if ($result['success']) {
            $entry->setMessage($result['data']);
            return ['success' => true];
        } else {
            Logger::log("Error fetching first message for QnaEntry with id " . $entry->getQnaid() . ": " . $result['message']);
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

    public static function findAll($limit = null, $page = null)
    {
        try {
            $stmtLimit = ($limit != null)? " LIMIT :limit" : "";
            $stmtOffset = ($page != null)? " OFFSET :offset" : "";

            $stmt = Database::getInstance()->getConnection()->prepare(
                "SELECT * FROM QnaEntry"
                . " ORDER BY qnaid DESC"
                . $stmtLimit
                . $stmtOffset
            );

            $offset = ($page - 1) * PAGINATION_SIZE;

            if ($limit) $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            if ($page) $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

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

    public static function getCount()
    {
        try {
            $stmt = Database::getInstance()->getConnection()->prepare("SELECT COUNT(*) FROM QnaEntry");
            $stmt->execute();

            $result = $stmt->fetchColumn();

            return $result;
        } catch (Exception $e) {
            Logger::log("Failed to get QnaEntry count: " . $e->getMessage());
            return handleException($e);
        }
    }
}