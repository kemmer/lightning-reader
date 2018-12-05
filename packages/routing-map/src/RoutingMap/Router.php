<?php

namespace RoutingMap;


class Router
{
    private $request;
    private $supportedHttpMethods = ["GET"];
    private $routeCallbacks;

    /**
     * Our $request object represents all information we need
     * to know about user's request
     */
    function __construct(Request $request)
    {
        $this->request = $request;

        // We might want to separate route callbacks by
        // their methods (GET, POST, etc)
        foreach($this->supportedHttpMethods as $method) {
            $this->routeCallbacks[$method] = [];
        }
    }

    /**
     * Allows route-callback associations
     * Every association is unique by method type (GET, POST, etc)
     */
    function __call($name, $args)
    {
        $method = strtoupper($name);

        if(!in_array($method, $this->supportedHttpMethods))
            $this->invalidMethodHandler();

        // Associating callback with its method and URI
        list($route, $callback) = $args;
        $this->routeCallbacks[$method][$this->stantdardizeURI($route)] = $callback;
    }

    /**
     * Standardizes URI in a predictable pattern
     */
    private function stantdardizeURI($route)
    {
        $result = rtrim($route, '/');
        if ($result === '')
        {
            return '/';
        }
        return $result;
    }

    /**
     * Responsible for calling our callbacks
     */
    function resolve()
    {
        // Getting request information from our $request object
        $requestMethod = $this->request->method;
        $route = $this->stantdardizeURI($this->request->uri);

        // Route is not defined, should return 404
        if(! array_key_exists($route, $this->routeCallbacks[$requestMethod])) {
            $this->notFoundRequestHandler();
            return;
        }

        // Execute the callback associated with the route
        // passing the $request as parameter
        $callback = $this->routeCallbacks[$requestMethod][$route];
        echo call_user_func_array($callback , [$this->request]);
    }

    /**
     * Following two methods are for handling invalid routes
     */
    private function invalidMethodHandler()
    {
        header("{$this->request->protocol} 405 Method Not Allowed");
    }

    private function notFoundRequestHandler()
    {
        header("{$this->request->protocol} 404 Not Found");
    }

    /**
     * The destructor will be activated after all routes were defined
     * and request was obviously been made, so it is safe to resolve
     * and call the desired method
     */
    function __destruct()
    {
        $this->resolve();
    }
}
