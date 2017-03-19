<?php
/**
 * Created by PhpStorm.
 * User: maxi
 * Date: 3/19/17
 * Time: 9:20 AM
 */

namespace Framework\Controllers;

use Framework\Base\Controller as BaseController;

class ErrorController extends BaseController
{

    public function error404()
    {
        return $this->render('404');
    }

    public function error500()
    {
        return $this->render('500');

    }

    public function error405()
    {
        return $this->render('500');

    }


}