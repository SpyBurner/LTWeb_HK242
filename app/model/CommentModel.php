<?php 
namespace model;

use core\IModel;

class CommentModel implements IModel {
    private $blogid;
    private $userid;
    private $content;
    private $commentdate;

    public function __construct($blogid, $userid, $content, $commentdate = null) {
        $this->blogid = $blogid;
        $this->userid = $userid;
        $this->content = $content;
        $this->commentdate = $commentdate ?? date('Y-m-d');
    }

    public function getBlogid() {
        return $this->blogid;
    }

    public function getUserid() {
        return $this->userid;
    }

    public function getContent() {
        return $this->content;
    }

    public function getCommentdate() {
        return $this->commentdate;
    }

    public function setBlogid($blogid) {
        $this->blogid = $blogid;
    }

    public function setUserid($userid) {
        $this->userid = $userid;
    }

    public function setContent($content) {
        $this->content = $content;
    }

    public function setCommentdate($commentdate) {
        $this->commentdate = $commentdate;
    }

    public function __toString()
    {
        return "Blog ID: $this->blogid, User ID: $this->userid, Content: $this->content, Comment Date: $this->commentdate";
    }

    public static function toObject($row)
    {
        return new CommentModel(
            $row['blogid'],
            $row['userid'],
            $row['content'],
            $row['commentdate']
        );
    }

    public static function toArray($object)
    {
        return [
            'blogid' => $object->getBlogid(),
            'userid' => $object->getUserid(),
            'content' => $object->getContent(),
            'commentdate' => $object->getCommentdate()
        ];
    }
}