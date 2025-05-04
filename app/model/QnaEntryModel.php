<?php
namespace model;

use core\IModel;

class QnaEntryModel implements IModel{
    private $qnaid;

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

    public function __construct($qnaid) {
        $this->qnaid = $qnaid;
    }

    public function __toString() {
        return "QnaEntryModel{" .
            "qnaid=" . $this->qnaid .
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