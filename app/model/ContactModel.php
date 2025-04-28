<?php
namespace model;

use core\IModel;

class ContactModel implements IModel{

    private $contactid;
    private $name;
    private $phone;
    private $address;
    private $customerid;

    public function __construct($contactid, $name, $phone, $address, $customerid)
    {
        $this->contactid = $contactid;
        $this->name = $name;
        $this->phone = $phone;
        $this->address = $address;
        $this->customerid = $customerid;
    }

    /**
     * @return mixed
     */
    public function getContactid()
    {
        return $this->contactid;
    }

    /**
     * @param mixed $contactid
     */
    public function setContactid($contactid): void
    {
        $this->contactid = $contactid;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address): void
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getCustomerid()
    {
        return $this->customerid;
    }

    /**
     * @param mixed $customerid
     */
    public function setCustomerid($customerid): void
    {
        $this->customerid = $customerid;
    }


    public static function toObject($row)
    {
        $contact = new ContactModel(
            $row['contactid'],
            $row['name'],
            $row['phone'],
            $row['address'],
            $row['customerid']
        );
        return $contact;
    }

    public static function toArray($obj)
    {
        $result = [
            'contactid' => $obj->getContactid(),
            'name' => $obj->getName(),
            'phone' => $obj->getPhone(),
            'address' => $obj->getAddress(),
            'customerid' => $obj->getCustomerid()
        ];

        return $result;
    }

    public function __toString()
    {
        return "Contact ID: $this->contactid, Name: $this->name, Phone: $this->phone, Address: $this->address, Customer ID: $this->customerid";
    }
}