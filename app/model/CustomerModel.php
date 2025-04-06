<?php
namespace model;

use core\IModel;
use const config\DEFAULT_AVATAR_URL;

class CustomerModel implements IModel
{
    private $userid;
    private $avatarurl;

    public function __construct($id, $avatarurl = null)
    {
        $this->userid = $id;
        $this->avatarurl = $avatarurl ?? DEFAULT_AVATAR_URL;
    }

    /**
     * @return mixed
     */
    public function getUserid()
    {
        return $this->userid;
    }

    /**
     * @param mixed $userid
     */
    public function setUserid($userid): void
    {
        $this->userid = $userid;
    }

    /**
     * @return mixed
     */
    public function getAvatarurl()
    {
        return $this->avatarurl;
    }

    /**
     * @param mixed $avatarurl
     */
    public function setAvatarurl($avatarurl): void
    {
        $this->avatarurl = $avatarurl;
    }


    public static function toObject($row)
    {
        return new CustomerModel(
            $row['userid'],
            $row['avatarurl']
        );
    }

    public static function toArray($obj)
    {
        return [
            'userid' => $obj->getUserid(),
            'avatarurl' => $obj->getAvatarurl()
        ];
    }

    public function __toString()
    {
        return "User ID: $this->userid, Avatar URL: $this->avatarurl";
    }
}