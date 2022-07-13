<?php
namespace App\Admin\Controller;

use Hyperf\HttpServer\Annotation\AutoController;


/**
 * @AutoController(prefix="/admin/test")
 */
class TestController
{
    public function index(){
        return "TestController444";
    }
}