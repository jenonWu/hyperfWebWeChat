<?php
declare(strict_types=1);
namespace App\Common\Controller;


class ExceptionController extends BaseController
{
    public function notFound(){

        return $this->view('404');
    }

}