<?php
namespace model;

use core\IModel;

class ProductModel implements IModel {
    private $productid;
    private $name;
    private $price;
    private $description;
    private $avgrating;
    private $bought;
    private $mfgid;
    private $stock;
    private $cateid;
    private $avatarurl;
    private $manufacturerName; // Added property
    private $categoryName;    // Added property

    public function __construct(
        $name,
        $price,
        $description,
        $mfgid,
        $stock,
        $cateid,
        $avatarurl,
        $manufacturerName = null, // Added parameter
        $categoryName = null,     // Added parameter
        $productid = null,
        $avgrating = 0.0,
        $bought = 0
    ) {
        $this->productid = $productid;
        $this->name = $name;
        $this->price = $price;
        $this->description = $description;
        $this->avgrating = $avgrating;
        $this->bought = $bought;
        $this->mfgid = $mfgid;
        $this->stock = $stock;
        $this->cateid = $cateid;
        $this->avatarurl = $avatarurl;
<<<<<<< HEAD
        $this->manufacturerName = $manufacturerName;
        $this->categoryName = $categoryName;
=======
        $this->manufacturer = $manufacturer;
>>>>>>> php/home
    }

    // Getters
    public function getProductid() {
        return $this->productid;
    }

    public function getName() {
        return $this->name;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getAvgrating() {
        return $this->avgrating;
    }

    public function getBought() {
        return $this->bought;
    }

    public function getMfgid() {
        return $this->mfgid;
    }

<<<<<<< HEAD
=======
    public function getManufacturer() {
        return $this->manufacturer;
    }

>>>>>>> php/home
    public function getStock() {
        return $this->stock;
    }

    public function getCateid() {
        return $this->cateid;
    }

    public function getAvatarurl() {
        return $this->avatarurl;
    }

<<<<<<< HEAD
    public function getManufacturerName() {
        return $this->manufacturerName;
    }

    public function getCategoryName() {
        return $this->categoryName;
    }

=======
>>>>>>> php/home
    // Setters
    public function setName($name) {
        $this->name = $name;
    }

    public function setPrice($price) {
        $this->price = $price;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setAvgrating($avgrating) {
        $this->avgrating = $avgrating;
    }

    public function setBought($bought) {
        $this->bought = $bought;
    }

    public function setMfgid($mfgid) {
        $this->mfgid = $mfgid;
    }

    public function setStock($stock) {
        $this->stock = $stock;
    }

    public function setCateid($cateid) {
        $this->cateid = $cateid;
    }

    public function setAvatarurl($avatarurl) {
        $this->avatarurl = $avatarurl;
    }

<<<<<<< HEAD
    public function setManufacturerName($manufacturerName) {
        $this->manufacturerName = $manufacturerName;
    }

    public function setCategoryName($categoryName) {
        $this->categoryName = $categoryName;
    }

=======
>>>>>>> php/home
    public function __toString() {
        return "Product ID: $this->productid, Name: $this->name, Price: $this->price, Category ID: $this->cateid";
    }

    public static function toObject($row) {
        return new ProductModel(
            $row['name'],
            $row['price'],
            $row['description'],
            $row['mfgid'],
            $row['stock'],
            $row['cateid'],
            $row['avatarurl'],
<<<<<<< HEAD
            $row['manufacturer_name'] ?? null, // Updated
            $row['category_name'] ?? null,     // Updated
            $row['productid'] ?? null,
            $row['avgrating'] ?? 0.0,
            $row['bought'] ?? 0
=======
            $row['productid'] ?? null,
            $row['avgrating'] ?? 0.0,
            $row['bought'] ?? 0,
            $row['manufacturer'] ?? null
>>>>>>> php/home
        );
    }

    public static function toArray($obj) {
        return [
            'productid' => $obj->getProductid(),
            'name' => $obj->getName(),
            'price' => $obj->getPrice(),
            'description' => $obj->getDescription(),
            'avgrating' => $obj->getAvgrating(),
            'bought' => $obj->getBought(),
            'mfgid' => $obj->getMfgid(),
            'stock' => $obj->getStock(),
            'cateid' => $obj->getCateid(),
<<<<<<< HEAD
            'avatarurl' => $obj->getAvatarurl(),
            'manufacturerName' => $obj->getManufacturerName(), // Added
            'categoryName' => $obj->getCategoryName()         // Added
=======
            'avatarurl' => $obj->getAvatarurl()
>>>>>>> php/home
        ];
    }
}