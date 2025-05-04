<?php
namespace service;

use core\Database;
use core\IService;
use core\Logger;
use Exception;
use model\FAQEntryModel;
use model\MessageModel;
use model\QnaEntryModel;
use function core\handleException;

class MessageService implements IService
{
    public static function save($model)
    {
        if (!$model instanceof MessageModel) {
            return ['success' => false, 'message' => 'Invalid model type'];
        }

        try {
            if ($model->getMsgid()){
                //Update existing
                $stmt = Database::getInstance()->getConnection()->prepare("
                    UPDATE Message 
                    SET content = :content, qnaid = :qnaid, userid = :userid, senddate = :senddate
                    WHERE msgid = :msgid
                ");
                $params = [
                    ':content' => $model->getContent(),
                    ':qnaid' => $model->getQnaid(),
                    ':userid' => $model->getUserid(),
                    ':senddate' => $model->getSenddate(),
                ];
            }
            else {
                $stmt = Database::getInstance()->getConnection()->prepare("
                    INSERT INTO FaqEntry (content, qnaid, userid)
                    VALUES (:content, :qnaid, :userid)
                ");

                $params = [
                    ':content' => $model->getContent(),
                    ':qnaid' => $model->getQnaid(),
                    ':userid' => $model->getUserid(),
                ];
            }
            $stmt->execute($params);

            return ['success' => true, 'message' => 'Message saved successfully'];
        }
        catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function findById($id)
    {
        try {
            $stmt = Database::getInstance()->getConnection()->prepare("SELECT * FROM Message WHERE msgid = :msgid");
            $stmt->execute([':msgid' => $id]);

            $result = $stmt->fetch();

            if ($result) {
                $model = MessageModel::toObject($result);
                $ret = ['success' => true, 'data' => $model];
            }
            else{
                $ret = ['success' => false, 'message' => 'Message not found'];
            }

            return $ret;
        }
        catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function findAllByQnaId($qnaid)
    {
        try {
            $stmt = Database::getInstance()->getConnection()->prepare("SELECT * FROM Message WHERE qnaid = :qnaid");
            $stmt->execute([':qnaid' => $qnaid]);
            $data = array_map(fn($row) => MessageModel::toObject($row), $stmt->fetchAll());

            return ['success' => true, 'data' => $data];
        } catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function findAll()
    {
        try {
            $stmt = Database::getInstance()->getConnection()->prepare("SELECT * FROM Message");
            $stmt->execute();
            $data = array_map(fn($row) => MessageModel::toObject($row), $stmt->fetchAll());

            return ['success' => true, 'data' => $data];
        } catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function deleteById($id)
    {
        try {
            $stmt = Database::getInstance()->getConnection()->prepare("DELETE FROM Message WHERE msgid = :msgid");
            $stmt->execute([':msgid' => $id]);

            if ($stmt->rowCount() == 0) {
                Logger::log("No Message found with ID: $id");
                return ['success' => false, 'message' => 'Message not found'];
            }
            else {
                Logger::log("Message with ID: $id deleted successfully");
                return ['success' => true, 'message' => 'Message deleted successfully'];
            }
        } catch (Exception $e) {
            Logger::log("Failed to delete Message: " . $e->getMessage());
            return handleException($e);
        }
    }
}