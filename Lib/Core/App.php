<?php
namespace Lib\Core;
use Lib\Core\Router;
use Lib\Core\Request;
use Lib\Core\Response;
use Lib\Controller\Home;
use Lib\Model\Post;
class App {
  public static function run() {
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
  }
}