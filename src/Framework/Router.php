<?php

namespace Framework;

use \Framework\Session;

class Router
{
    private $routes;

    private $currentRoute;

    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    public function redirect($to)
    {
        header("Location: {$this->generateUrl($to)}");
        die;
    }

    public function generateUrl($name, array $parameters = [])
    {
        $routes = $this->routes;
        foreach ($routes as $key => $route)
        {
            if ($name == $key){
                $str=$route['pattern'];

                if($parameters) {
                    $pattern = '@{[a-zA-Z]+}@';
                    preg_match_all($pattern,$str,$matches);
                    $mas=array_combine($matches[0],$parameters);

                    foreach ($mas as $keyy => $item){
                        $pattern = $keyy;
                        $str = str_replace($pattern,$item,$str);

                    }
                    return $str;
                }
                return $str;
            }
        }
    }

    public function match(Request $request)
    {
        $uri = $request->getUri();
        ;
        $routes = $this->routes;

        foreach ($routes as $route) {
            $pattern = $route['pattern'];


            if (!empty($route['parameters'])) {
                foreach ($route['parameters'] as $name => $regex) {
                    $pattern = str_replace(
                        '{' . $name . '}',
                        '(' . $regex . ')',
                        $pattern
                    );
                }
            }

            $pattern = '@^' . $pattern . '$@';



            if (preg_match($pattern, $uri, $matches)) {

                array_shift($matches);


                if (!empty($route['parameters'])) {
                    $result = array_combine(
                        array_keys($route['parameters']),
                        $matches
                    );



                    $request->mergeGetWithArray($result);
                }
                $this->currentRoute = $route;


                    if (isset($route['access'])) {
                        $access=$route['access'];
                        if(Session::get("{$access}") != 'ok'){

                            throw new \Exception('В доступе отказано');}


                    }

                return;
            }
        }

        throw new \Exception('Page not found', 404);
    }

    public function getCurrentController()
    {
        return $this->getCurrentRouteAttribute('controller');
    }

    public function getCurrentAction()
    {
        return $this->getCurrentRouteAttribute('action');
    }

    private function getCurrentRouteAttribute($key)
    {
        if (!$this->currentRoute) {
            return null;
        }

        return $this->currentRoute[$key];
    }
}