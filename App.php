<?php

use DTO\UserInfo;
use Lib\Core\Router;
use Lib\Core\Request;
use Lib\Core\Response;
use Lib\Controller\Home;
use Lib\Model\Post;
use Service\UserService;
use Model\UserInfoDto;
require_once('./Model/User.php');

class App {
  public static function run() {
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    http_response_code(200);
    Post::load();
    
    Router::get('/', function () {
      (new Home())->indexAction();
    });

    Router::get('/post', function (Request $req, Response $res) {
      $res->toJSON(Post::all());
    });

    Router::post('/post', function (Request $req, Response $res) {
      $post = Post::add($req->getJSON());
      $res->status(201)->toJSON($post);
    });

    Router::get('/post/([0-9]*)', function (Request $req, Response $res) {
      $post = Post::findById($req->params[0]);
      if ($post) {
        $res->toJSON($post);
      } else {
        $res->status(404)->toJSON(['error' => "Not Found"]);
      }
    });

    Router::get('/users', function (Request $req, Response $res) {
      $users = UserService::findAll();
      http_response_code(200);
      echo $users;
    });

    Router::get('/users/([0-9]*)', function (Request $req, Response $res) {
      $id = $req->params[0];
      $users = UserService::findOne($id);
      http_response_code(200);
      echo $users;
    });

    Router::post('/users', function (Request $req, Response $res) {
      $userData = json_decode(file_get_contents("php://input"));
      $userInfo = new UserInfoDto($userData->username, $userData->email, $userData->dateOfBirth, $userData->address, $userData->phone);
      $result = UserService::create($userInfo);
      http_response_code(201);
      $res->status(201)->toJSON(["result" => json_encode($result)]);
    });
  }
}