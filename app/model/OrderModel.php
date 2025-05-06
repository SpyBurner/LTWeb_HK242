<?php
namespace model;

use core\IModel;

class OrderModel implements IModel {
    private $orderid;
    private $status;
    private $totalcost;
    private $orderdate;
    private $customerid;
    private $contactid;

    // Additional property for view purposes
    public $customerName;

    public function __construct($status, $totalcost, $customerid, $contactid, $orderid = null, $orderdate = null) {
        $this->orderid = $orderid;
        $this->status = $status;
        $this->totalcost = $totalcost;
        $this->customerid = $customerid;
        $this->contactid = $contactid;
        $this->orderdate = $orderdate ?? date('Y-m-d H:i:s');
    }

    // Getters
    public function getOrderid() {
        return $this->orderid;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getTotalcost() {
        return $this->totalcost;
    }

    public function getOrderdate() {
        return $this->orderdate;
    }

    public function getCustomerid() {
        return $this->customerid;
    }

    public function getContactid() {
        return $this->contactid;
    }

    // Setters
    public function setStatus($status) {
        $this->status = $status;
    }

    public function setTotalcost($totalcost) {
        $this->totalcost = $totalcost;
    }

    public function setCustomerid($customerid) {
        $this->customerid = $customerid;
    }

    public function setContactid($contactid) {
        $this->contactid = $contactid;
    }

    public function setOrderdate($orderdate) {
        $this->orderdate = $orderdate;
    }

    public function __toString() {
        return sprintf(
            "Order ID: %d, Status: %s, Total: %d, Date: %s, Customer ID: %d, Contact ID: %d",
            $this->orderid,
            $this->status,
            $this->totalcost,
            $this->orderdate,
            $this->customerid,
            $this->contactid
        );
    }

    public static function toObject($row) {
        return new OrderModel(
            $row['status'],
            $row['totalcost'],
            $row['customerid'],
            $row['contactid'],
            $row['orderid'],
            $row['orderdate']
        );
    }

    public static function toArray($obj) {
        return [
            'orderid' => $obj->getOrderid(),
            'status' => $obj->getStatus(),
            'totalcost' => $obj->getTotalcost(),
            'orderdate' => $obj->getOrderdate(),
            'customerid' => $obj->getCustomerid(),
            'contactid' => $obj->getContactid(),
            'customerName' => $obj->customerName ?? null,
            'products' => $obj->products ?? []
        ];
    }

    // Additional method to calculate total with currency formatting
    public function getFormattedTotal() {
        return number_format($this->totalcost) . 'VND';
    }

    // Additional method to get status with badge styling
    public function getStatusBadge() {
        $status = $this->status;
        $classMap = [
            'Preparing' => 'bg-warning',
            'Prepared' => 'bg-primary',
            'Delivering' => 'bg-info',
            'Delivered' => 'bg-success'
        ];

        $class = $classMap[$status] ?? 'bg-secondary';
        return '<span class="badge ' . $class . '">' . $status . '</span>';
    }
    public $products = [];
    
    // Thêm method để tính tổng số lượng sản phẩm
    public function getTotalItems() {
        return array_reduce($this->products, function($carry, $product) {
            return $carry + $product['amount'];
        }, 0);
    }
}