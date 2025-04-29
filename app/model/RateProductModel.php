<?php

namespace model;

use core\IModel;

class RateProductModel implements IModel
{
    private int $orderid;
    private int $productid;
    private int $rating;
    private string $comment;
    private string $ratingdate;

    public function __construct($orderid, $productid, $rating, $comment, $ratingdate)
    {
        $this->orderid = $orderid;
        $this->productid = $productid;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->ratingdate = $ratingdate;
    }

    // ✅ Getter & Setter
    public function getOrderid(): int { return $this->orderid; }
    public function setOrderid(int $orderid): void { $this->orderid = $orderid; }

    public function getProductid(): int { return $this->productid; }
    public function setProductid(int $productid): void { $this->productid = $productid; }

    public function getRating(): int { return $this->rating; }
    public function setRating(int $rating): void { $this->rating = $rating; }

    public function getComment(): string { return $this->comment; }
    public function setComment(string $comment): void { $this->comment = $comment; }

    public function getRatingDate(): string { return $this->ratingdate; }
    public function setRatingDate(string $ratingdate): void { $this->ratingdate = $ratingdate; }

    // ✅ Implement IModel
    public static function toObject($row): RateProductModel
    {
        return new RateProductModel(
            $row['orderid'],
            $row['productid'],
            $row['rating'],
            $row['comment'],
            $row['ratingdate']
        );
    }

    public static function toArray($obj): array
    {
        return [
            'orderid' => $obj->getOrderid(),
            'productid' => $obj->getProductid(),
            'rating' => $obj->getRating(),
            'comment' => $obj->getComment(),
            'ratingdate' => $obj->getRatingDate()
        ];
    }

    public function __toString(): string
    {
        return "RateProductModel [orderid={$this->orderid}, productid={$this->productid}, rating={$this->rating}, comment={$this->comment}, ratingdate={$this->ratingdate}]";
    }
}
