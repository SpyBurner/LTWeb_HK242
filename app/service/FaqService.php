<?php
namespace service;

use core\Database;
use core\IService;
use core\Logger;
use Exception;
use model\FAQEntryModel;
use function core\handleException;

class FaqService implements IService
{
    public static function save($model)
    {
        if (!$model instanceof FAQEntryModel) {
            return ['success' => false, 'message' => 'Invalid model type'];
        }

        try {
            if ($model->getFaqid()){
                //Update existing
                $stmt = Database::getInstance()->getConnection()->prepare("
                    UPDATE FaqEntry 
                    SET question = :question, answer = :answer 
                    WHERE faqid = :faqid
                ");
                $params = [
                    ':question' => $model->getQuestion(),
                    ':answer' => $model->getAnswer(),
                    ':faqid' => $model->getFaqid() // Only used for updates
                ];
            }
            else {
                $stmt = Database::getInstance()->getConnection()->prepare("
                    INSERT INTO FaqEntry (question, answer)
                    VALUES (:question, :answer)
                ");

                $params = [
                    ':question' => $model->getQuestion(),
                    ':answer' => $model->getAnswer()
                ];
            }
            $stmt->execute($params);

            return ['success' => true, 'message' => 'FAQ entry saved successfully'];
        }
        catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function findById($id)
    {
        try {
            $stmt = Database::getInstance()->getConnection()->prepare("SELECT * FROM FaqEntry WHERE faqid = :faqid");
            $stmt->execute([':faqid' => $id]);

            Logger::log("Fetching FAQ entry with ID: $id");

            $result = $stmt->fetch();

            if ($result) {
                $model = FAQEntryModel::toObject($result);
                $ret = ['success' => true, 'data' => $model];
            }
            else{
                $ret = ['success' => false, 'message' => 'FAQ entry not found'];
            }

            return $ret;
        }
        catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function findAll()
    {
        try {
            $stmt = Database::getInstance()->getConnection()->prepare("SELECT * FROM FaqEntry");
            $stmt->execute();
            $data = array_map(fn($row) => FAQEntryModel::toObject($row), $stmt->fetchAll());

            return ['success' => true, 'data' => $data];
        } catch (Exception $e) {
            return handleException($e);
        }
    }

    public static function deleteById($id)
    {
        try {
            $stmt = Database::getInstance()->getConnection()->prepare("DELETE FROM FaqEntry WHERE faqid = :faqid");
            $stmt->execute([':faqid' => $id]);

            if ($stmt->rowCount() == 0) {
                Logger::log("No FaqEntry found with ID: $id");
                return ['success' => false, 'message' => 'FaqEntry not found'];
            }
            else {
                Logger::log("FaqEntry with ID: $id deleted successfully");
                return ['success' => true, 'message' => 'FaqEntry deleted successfully'];
            }
        } catch (Exception $e) {
            Logger::log("Failed to delete FaqEntry: " . $e->getMessage());
            return handleException($e);
        }
    }
}