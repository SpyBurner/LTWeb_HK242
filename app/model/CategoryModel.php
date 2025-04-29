<?php
namespace model;

use core\IModel;

class CategoryModel implements IModel {
    private $cateid;
    private $name;

    public function __construct($cateid, $name) {
        $this->cateid = $cateid;
        $this->name = $name;
    }

    // Getters
    public function getCateid() {
        return $this->cateid;
    }

    public function getName() {
        return $this->name;
    }

    // Setters
    public function setName($name) {
        $this->name = $name;
    }

    public function __toString() {
        return "Category ID: $this->cateid, Name: $this->name";
    }

    public static function toObject($row) {
        return new CategoryModel($row['cateid'], $row['name']);
    }

    public static function toArray($obj) {
        return [
            'cateid' => $obj->getCateid(),
            'name' => $obj->getName()
        ];
    }
}