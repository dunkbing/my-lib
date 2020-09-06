<?php
namespace Service;
use PDO;
use Model\User;
use Model\UserDto;
use Model\UserInfoDto;

class UserService {
  static function findAll() {
    $user = new User();
    $stmt = $user->find('select * from users ');
    $num = $stmt->rowCount();
    $result = [];
    if ($num > 0) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $result[] = new UserDto($id, $username, $email, $date_of_birth, $address, $phone, $created_at, $updated_at);
      }
    }
    return json_encode(['result' => $result]);
  }
  
  static function create(UserInfoDto $userInfo) {
    $user = new User();
    $user->username = $userInfo->username;
    $user->email = $userInfo->email;
    $user->date_of_birth = $userInfo->dateOfBirth;
    $user->address = $userInfo->address;
    $user->phone = $userInfo->phone;
    return $user->save();
  }

  static function findOne(int $id) {
    $user = new User();
    $stmt = $user->find('select * from users ', [
      'id' => $id
    ], '0, 1');
    $num = $stmt->rowCount();
    $result = [];
    if ($num > 0) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $result[] = new UserDto($id, $username, $email, $date_of_birth, $address, $phone, $created_at, $updated_at);
      }
    }
    return json_encode(['result' => count($result) ? $result[0] : "not found"]);
  }

  static function delete(int $id) {}

  static function update(int $id) {}
}