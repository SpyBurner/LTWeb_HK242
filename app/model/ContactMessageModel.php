<?php
namespace model;

use core\IModel;

class ContactMessageModel implements IModel
{
    private $id;
    private $name;
    private $email;
    private $title;
    private $message;
    private $created_at;

    public function __construct($name, $email, $title, $message, $id = null, $created_at = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->title = $title;
        $this->message = $message;
        $this->created_at = $created_at ?? date('Y-m-d H:i:s');
    }

    // Getters
    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public static function toObject($row): ContactMessageModel
    {
        return new ContactMessageModel(
            $row['id'],
            $row['name'],
            $row['email'],
            $row['title'],
            $row['message'],
            $row['created_at']
        );
    }

    public static function toArray($obj): array
    {
        return [
            'id' => $obj->getId(),
            'name' => $obj->getName(),
            'email' => $obj->getEmail(),
            'title' => $obj->getTitle(),
            'message' => $obj->getMessage(),
            'created_at' => $obj->getCreatedAt()
        ];
    }

    public function __toString()
    {
        return "ContactMessageModel{" .
            "id=" . $this->id .
            ", name='" . $this->name . '\'' .
            ", email='" . $this->email . '\'' .
            ", title='" . $this->title . '\'' .
            ", message='" . $this->message . '\'' .
            ", created_at='" . $this->created_at . '\'' .
            '}';
    }
}