<?php
namespace model;

use core\IModel;

class QnaEntryModel implements IModel{
    private $qnaid;

    private $messages;

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

    public function getMessages()
    {
        return $this->messages;
    }

    public function setMessages($messages): void
    {
        $this->messages = $messages;
    }

    public function __construct($qnaid) {
        $this->qnaid = $qnaid;
    }

    public function __toString() {
        return "QnaEntryModel{" .
            "qnaid=" . $this->qnaid .
            ", messages=" . json_encode($this->messages) .
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