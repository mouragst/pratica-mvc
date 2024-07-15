<?php

namespace App\Http;

use \Closure;
use Exception;
use App\Http\Request;
use LDAP\Result;

class Router {

    private $url = '';
    private $prefix = '';
    private $routes = [];
    private $request;

    public function __construct($url) {
        $this->request = new Request();
        $this->url = $url;
        $this->setPrefix();
    }

    private function setPrefix() {
        $parseUrl = parse_url($this->url);

        $this->prefix = $parseUrl['path'] ?? '';
    }

    private function addRoute ($method, $route, $params = []) {

        foreach ($params as $key => $value) {
            if ($value instanceof Closure) {
                $params['controller'] = $value;
                unset($params[$key]);
                continue;
            }
        }

        $patternRoute = '/^'.str_replace('/', '\/', $route).'$/';

        $this->routes[$patternRoute][$method] = $params;
    }

    public function get($route, $params = []) {
        return $this->addRoute('GET', $route, $params);
    }

    public function post($route, $params = []) {
        return $this->addRoute('POST', $route, $params);
    }

    public function put($route, $params = []) {
        return $this->addRoute('PUT', $route, $params);
    }

    public function delete($route, $params = []) {
        return $this->addRoute('DELETE', $route, $params);
    }

    private function getUri() {
        $uri = $this->request->getUri();    
        $xUri = strlen($this->prefix) ? explode($this->prefix, $uri) : [$uri];
        return end($xUri);
    }

    private function getRoute() {   
        $uri = $this->getUri();
        $httpMethod = $this->request->getHttpMethod();

        foreach ($this->routes as $paternRoute => $method) {
            if(preg_match($paternRoute, $uri)) {
                if($method[$httpMethod]) {
                    return $method[$httpMethod];
                }
                throw new Exception("Método não permitido", 405);
            }
        }
        throw new Exception("URL não encontrada", 404);
    }

    public function run() {
        try {
            $route = $this->getRoute();
            return new Response(200, $route);

            if (!isset($route['controller'])) {
                throw new Exception("URL não pôde ser processado", 500);
            }
        } catch (Exception $e) {
            return new Response($e->getCode(), $e->getMessage());
        }
    }

}