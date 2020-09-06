<?php
namespace Model;
use Lib\Core\Entity;
class User extends Entity {
  public $id;
  public $username;
  public $email;
  public $date_of_birth;
  public $address;
  public $phone;
  public $created_at;
  public $updated_at;

  public function __construct() {
    parent::__construct();
    $this->tableName = 'users';
  }
}

class UserDto {
  public $userId;
  public $username;
  public $email;
  public $dateOfBirth;
  public $address;
  public $phone;
  public $createdAt;
  public $updateAt;

  public function __construct($userId, $username, $email, $dateOfBirth, $address, $phone, $createdAt, $updateAt) {
    $this->userId = $userId;
    $this->username = $username;
    $this->email = $email;
    $this->dateOfBirth = $dateOfBirth;
    $this->address = $address;
    $this->phone = $phone;
    $this->createdAt = $createdAt;
    $this->updateAt = $updateAt;
  }
}

class UserInfoDto {
  public $username;
  public $email;
  public $dateOfBirth;
  public $address;
  public $phone;

  public function __construct($username, $email, $dateOfBirth, $address, $phone) {
    $this->username = $username;
    $this->email = $email;
    $this->dateOfBirth = $dateOfBirth;
    $this->address = $address;
    $this->phone = $phone;
  }
}
