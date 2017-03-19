<?php
/**
 * Created by PhpStorm.
 * User: maxi
 * Date: 3/18/17
 * Time: 6:52 PM
 */

namespace Framework\Base;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Framework\Controllers\ErrorController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Router
{

    const CLASS_NOT_FOUND = 0;
    const METHOD_NOT_FOUND = 1;

    public function __construct(Request $request)
    {
        /*
         * Routes
         */
        $dispatcher = \FastRoute\simpleDispatcher(function (RouteCollector $r) {
            $routes = Config::get('routes');
            foreach ($routes as $route) {
                $r->addRoute($route[0], $route[1], $route[2]);
            }
        });

        $this->dispatch($request, $dispatcher);
    }


    private function dispatch(Request $request, Dispatcher $dispatcher)
    {
        /*
         * Dispatch
         */

        if($url = $request->query->get('url')) {
            $request = Request::create(
                $url,
                $request->getMethod(),
                []
            );
        }

        $routeInfo = $dispatcher->dispatch($request->getMethod(), $request->getPathInfo());
        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                // No matching route was found.
                $response = Response::create("404 Not Found", Response::HTTP_NOT_FOUND)
                    ->prepare($request);

                $request->attributes->add(['controllerId' => 'error']);
                $errorController = new ErrorController($request, $response);

                $errorController->error404();

                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                // A matching route was found, but the wrong HTTP method was used.
                $response = Response::create("405 Method Not Allowed", Response::HTTP_METHOD_NOT_ALLOWED)
                    ->prepare($request);

                $request->attributes->add(['controllerId' => 'error']);
                $errorController = new ErrorController($request, $response);

                $errorController->error405();
                break;
            case Dispatcher::FOUND:
                // Fully qualified class name of the controller
                $className = $routeInfo[1][0];
                // Controller method responsible for handling the request
                $routeMethod = $routeInfo[1][1];
                // Route parameters (ex. /products/{category}/{id})
                $routeParams = $routeInfo[2];

                if(class_exists($className)) {

                    $method = isset($routeParams['action']) ? $routeParams['action'] : $routeMethod;

                    $response = new Response();
                    //$response->setContent('Hello world!');
                    $response->setStatusCode(Response::HTTP_OK);
                    $response->headers->set('Content-Type', 'text/html');
                    $class = new $className($request, $response);
                    $reflection = new \ReflectionClass($className);
                    $shortName = strtolower($reflection->getShortName());
                    $controllerId = str_replace('controller', '', $shortName);
                    $request->attributes->add(['controllerId' => $controllerId]);
                    if(method_exists($class, $method)) {
                        $class->$method($routeParams);
                    } else {
                        $response = Response::create("500 Method Not Found", Response::HTTP_INTERNAL_SERVER_ERROR)
                            ->prepare($request);
                        $request->attributes->add(['controllerId' => 'error']);
                        $request->attributes->add(['reason' => sprintf('Method %s not found in controller %s', $method, $className)]);
                        $errorController = new ErrorController($request, $response);
                        $errorController->error500();
                    }
                } else {
                    $response = Response::create("500 Class Not Found", Response::HTTP_INTERNAL_SERVER_ERROR)
                        ->prepare($request);
                    $request->attributes->add(['controllerId' => 'error']);
                    $request->attributes->add(['reason' => sprintf('Class %s not found', $className)]);
                    $errorController = new ErrorController($request, $response);
                    $errorController->error500();
                }
                break;
            default:
                // According to the dispatch(..) method's documentation this shouldn't happen.
                // But it's here anyways just to cover all of our bases.
                Response::create('Received unexpected response from dispatcher.', Response::HTTP_INTERNAL_SERVER_ERROR)
                    ->prepare($request)
                    ->send();
                return;
        }
    }

}