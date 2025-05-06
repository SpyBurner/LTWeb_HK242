<?php
namespace model;

use core\IModel;

class QnaEntryModel implements IModel{
    private $qnaid;
    //First message is the question
    private $message;

    /**
     * @return mixed
     */
    public function getQnaid()
    {
        return $this->qnaid;
    }

    /**
     * @param mixed $qnaid
     */
    public function setQnaid($qnaid): void
    {
        $this->qnaid = $qnaid;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message): void
    {
        $this->message = $message;
    }

    public function __construct($qnaid, $message = null) {
        $this->qnaid = $qnaid;
        $this->message = $message;
    }

    public function __toString() {
        return "QnaEntryModel{" .
            "qnaid=" . $this->qnaid .
            ", messages=" . json_encode($this->message) .
            '}';
    }
    public static function toObject($row){
        return new QnaEntryModel(
            $row['qnaid'],
        );
    }

    public static function toArray($obj){
        return [
            'qnaid' => $obj->getQnaid(),
        ];
    }
}