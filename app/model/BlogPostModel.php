<?php
namespace model;

use core\IModel;

class BlogPostModel implements IModel {
    private $blogid;
    private $adminid;
    private $postdate;
    private $content;
    private $title;

    public function __construct($blogid, $adminid, $title, $content, $postdate = null) {
        $this->blogid = $blogid;
        $this->adminid = $adminid;
        $this->title = $title;
        $this->content = $content;
        $this->postdate = $postdate ?? date('Y-m-d');
    }

    // Getters
    public function getBlogid() {
        return $this->blogid;
    }

    public function getAdminid() {
        return $this->adminid;
    }

    public function getPostdate() {
        return $this->postdate;
    }

    public function getContent() {
        return $this->content;
    }

    public function getTitle() {
        return $this->title;
    }

    // Setters (no setter for blogid as it's auto-incremented)
    public function setAdminid($adminid) {
        $this->adminid = $adminid;
    }

    public function setPostdate($postdate) {
        $this->postdate = $postdate;
    }

    public function setContent($content) {
        $this->content = $content;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function __toString() {
        return "Blog ID: $this->blogid, Admin ID: $this->adminid, Title: $this->title, Post Date: $this->postdate";
    }

    public static function toObject($row) {
        return new BlogPostModel($row['blogid'], $row['adminid'], $row['title'], $row['content'], $row['postdate']);
    }

    public static function toArray($obj) {
        return [
            'blogid' => $obj->getBlogid(),
            'adminid' => $obj->getAdminid(),
            'title' => $obj->getTitle(),
            'content' => $obj->getContent(),
            'postdate' => $obj->getPostdate()
        ];
    }
}