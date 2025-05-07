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
    private string $avatarurl;
    private string $username;

    public function __construct($orderid, $productid, $rating, $comment, $ratingdate, $avatarurl = 'assets/img/avatar_male.png', $username = '')
    {
        $this->orderid = $orderid;
        $this->productid = $productid;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->ratingdate = $ratingdate;
        $this->avatarurl = $avatarurl;
        $this->username = $username;
    }

    // Getter & Setter
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

    public function getAvatarurl(): string { return $this->avatarurl; }
    public function setAvatarurl(string $avatarurl): void { $this->avatarurl = $avatarurl; }

    public function getUsername(): string { return $this->username; }
    public function setUsername(string $username): void { $this->username = $username; }

    // Implement IModel
    public static function toObject($row): RateProductModel
    {
        return new RateProductModel(
            $row['orderid'],
            $row['productid'],
            $row['rating'],
            $row['comment'],
            $row['ratingdate'],
            $row['avatarurl'] ?? 'assets/img/avatar_male.png',
            $row['username'] ?? ''
        );
    }

    public static function toArray($obj): array
    {
        return [
            'orderid' => $obj->getOrderid(),
            'productid' => $obj->getProductid(),
            'rating' => $obj->getRating(),
            'comment' => $obj->getComment(),
            'ratingdate' => $obj->getRatingDate(),
            'avatarurl' => $obj->getAvatarurl(),
            'username' => $obj->getUsername()
        ];
    }

    public function __toString(): string
    {
        return "RateProductModel [orderid={$this->orderid}, productid={$this->productid}, rating={$this->rating}, comment={$this->comment}, ratingdate={$this->ratingdate}, avatarurl={$this->avatarurl}, username={$this->username}]";
    }
}