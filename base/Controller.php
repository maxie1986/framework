<?php
/**
 * Created by PhpStorm.
 * User: maxi
 * Date: 3/17/17
 * Time: 8:41 PM
 */

namespace Framework\Base;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Controller
{

    /**
     * @var $response Response
     */
    protected $response;

    /**
     * @var $request Request
     */
    protected $request;

    protected $controllerId;

    public function __construct(Request $request, Response $response)
    {
        $this->response = $response;
        $this->request = $request;
    }

    /**
     * @param $viewName
     * @param array $params
     * @return Response
     */
    protected function render($viewName, array $params = [])
    {
        $this->controllerId = $this->request->attributes->get('controllerId');
        $view = new View($this->controllerId . DS . $viewName, $params);

        return $this->response->setContent($view->render())->send();
    }

}