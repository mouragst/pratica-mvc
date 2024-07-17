<?php

namespace App\Http;

use \Closure;
use Exception;
use App\Http\Request;
use \ReflectionFunction;
use App\Http\Middleware\Queue as MiddlewareQueue;

class Router {

    private $url = '';
    private $prefix = '';
    private $routes = [];
    private $request;

    public function __construct($url) {
        $this->request = new Request($this);
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

        $params['middlewares'] = $params['middlewares'] ?? [];

        $params['variables'] = [];
        
        $patternVariable = '/{(.*?)}/';
        if (preg_match_all($patternVariable, $route, $matches)) {
            $route = preg_replace($patternVariable, '(.*?)', $route);
            $params['variables'] = $matches[1];
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
            if(preg_match($paternRoute, $uri, $matches)) {
                if($method[$httpMethod]) {
                    if(isset($method[$httpMethod])) {
                        unset($matches[0]);
                        $keys = $method[$httpMethod]['variables'];
                        $method[$httpMethod]['variables'] = array_combine($keys, $matches);
                        $method[$httpMethod]['variables']['request'] = $this->request;
                        return $method[$httpMethod];
                    }
                }
                throw new Exception("Método não permitido", 405);
            }
        }
        throw new Exception("URL não encontrada", 404);
    }

    public function run() {
        try {
            $route = $this->getRoute();

            if (!isset($route['controller'])) {
                throw new Exception("URL não pôde ser processado", 500);
            }

            $args = [];

            $reflection = new ReflectionFunction($route['controller']);
            foreach ($reflection->getParameters() as $parameter) {
                $name = $parameter->getName();
                $args[$name] = $route['variables'][$name] ?? '';
            }
            return (new MiddlewareQueue($route['middlewares'], $route['controller'], $args))->next($this->request);
        } catch (Exception $e) {
            return new Response($e->getCode(), $e->getMessage());
        }
    }

    public function getCurrentUrl() {
        return $this->url.$this->getUri();
    }
}