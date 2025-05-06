<?php
namespace model;

use core\IModel;

class ManufacturerModel implements IModel {
    private $mfgid;
    private $name;
    private $country;

    public function __construct($mfgid, $name, $country) {
        $this->mfgid = $mfgid;
        $this->name = $name;
        $this->country = $country;
    }

    // Getters
    public function getMfgid() {
        return $this->mfgid;
    }

    public function getName() {
        return $this->name;
    }

    public function getCountry() {
        return $this->country;
    }

    // Setters
    public function setName($name) {
        $this->name = $name;
    }

    public function setCountry($country) {
        $this->country = $country;
    }

    public function __toString() {
        return "Manufacturer ID: $this->mfgid, Name: $this->name, Country: $this->country";
    }

    public static function toObject($row) {
        return new ManufacturerModel($row['mfgid'], $row['name'], $row['country']);
    }

    public static function toArray($obj) {
        return [
            'mfgid' => $obj->getMfgid(),
            'name' => $obj->getName(),
            'country' => $obj->getCountry()
        ];
    }
}