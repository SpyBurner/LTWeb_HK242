<?php
namespace model;

use core\IModel;

class MessageModel implements IModel{
    private $msgid;
    private $senddate;
    private $content;
    private $qnaid;
    private $userid;

    public function __construct($msgid, $senddate, $content, $qnaid, $userid) {
        $this->msgid = $msgid;
        $this->senddate = $senddate;
        $this->content = $content;
        $this->qnaid = $qnaid;
        $this->userid = $userid;
    }

    /**
     * @return mixed
     */
    public function getMsgid()
    {
        return $this->msgid;
    }

    /**
     * @param mixed $msgid
     */
    public function setMsgid($msgid): void
    {
        $this->msgid = $msgid;
    }

    /**
     * @return mixed
     */
    public function getSenddate()
    {
        return $this->senddate;
    }

    /**
     * @param mixed $senddate
     */
    public function setSenddate($senddate): void
    {
        $this->senddate = $senddate;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content): void
    {
        $this->content = $content;
    }

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

    /**
     * @return mixed
     */
    public function getUserid()
    {
        return $this->userid;
    }

    /**
     * @param mixed $userid
     */
    public function setUserid($userid): void
    {
        $this->userid = $userid;
    }

    public function __toString() {
        return "MessageModel{" .
            "msgid=" . $this->msgid .
            ", senddate='" . $this->senddate . '\'' .
            ", content='" . $this->content . '\'' .
            "qnaid=" . $this->qnaid .
            ", userid='" . $this->userid . '\'' .
            '}';
    }
    public static function toObject($row){
        return new MessageModel(
            $row['msgid'],
            $row['senddate'],
            $row['content'],
            $row['qnaid'],
            $row['userid']
        );
    }

    public static function toArray($obj){
        return [
            'msgid' => $obj->getMsgid(),
            'senddate' => $obj->getSenddate(),
            'content' => $obj->getContent(),
            'qnaid' => $obj->getQnaid(),
            'userid' => $obj->getUserid(),
        ];
    }
}