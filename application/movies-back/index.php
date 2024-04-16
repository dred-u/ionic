<?php

header("Access-Control-Allow-Origin: *");

// Permitir métodos HTTP específicos
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE, HEAD, TRACE, PATCH");

// Permitir encabezados personalizados
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

// Permitir credenciales
header("Access-Control-Allow-Credentials: true");

class Router {
    private $routes = array();

    public function addRoute($route, $file) {
        $this->routes[$route] = $file;
    }

    public function route($url) {
        $url_parts = explode('?', $url, 2);
        $route = $url_parts[0]; // Obtener la parte de la URL antes del signo de interrogación

        if (isset($this->routes[$route])) {
            require $this->routes[$route];
        } else {
            echo "404 Not Found";
        }
    }
}

$router = new Router();
$router->addRoute('/movies', './routes/movies-method.php');
$router->addRoute('/users', './routes/users-method.php');
$router->addRoute('/favorites', './routes/favorites-method.php');
$router->addRoute('/auth', './routes/auth-method.php');
$router->addRoute('/logout', './routes/close-method.php');

$url = str_replace('/movies-back', '', $_SERVER['REQUEST_URI']);
$router->route($url);
