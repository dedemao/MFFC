<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require '../vendor/autoload.php';
$router = new \Bramus\Router\Router();

// Custom 404 Handler
    $router->set404(function () use ($router){
        header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
        echo '404, route not found! route:'.$router->getCurrentUri();
    });
    // Before Router Middleware
    $router->before('GET', '/.*', function () {
        header('X-Powered-By: bramus/router');
    });
    // Static route: / (homepage)

	/*
    $router->get('/', function () {
        echo '<h1>bramus/router</h1><p>Try these routes:<p><ul><li>/hello/<em>name</em></li><li>/blog</li><li>/blog/<em>year</em></li><li>/blog/<em>year</em>/<em>month</em></li><li>/blog/<em>year</em>/<em>month</em>/<em>day</em></li><li>/movies</li><li>/movies/<em>id</em></li></ul>';
    });
	*/

	$router->get('', 'App\controllers\HomeController@home');

    // Static route: /hello
    $router->get('/hello', function () {
        echo '<h1>bramus/router</h1><p>Visit <code>/hello/<em>name</em></code> to get your Hello World mojo on!</p>';
    });
    // Dynamic route: /hello/name
    $router->get('/hello/(\w+)', function ($name) {
        echo 'Hello ' . htmlentities($name);
    });
    // Dynamic route: /ohai/name/in/parts
    $router->get('/ohai/(.*)', function ($url) {
        echo 'Ohai ' . htmlentities($url);
    });
    // Dynamic route with (successive) optional subpatterns: /blog(/year(/month(/day(/slug))))
    $router->get('/blog(/\d{4}(/\d{2}(/\d{2}(/[a-z0-9_-]+)?)?)?)?', function ($year = null, $month = null, $day = null, $slug = null) {
        if (!$year) {
            echo 'Blog overview';
            return;
        }
        if (!$month) {
            echo 'Blog year overview (' . $year . ')';
            return;
        }
        if (!$day) {
            echo 'Blog month overview (' . $year . '-' . $month . ')';
            return;
        }
        if (!$slug) {
            echo 'Blog day overview (' . $year . '-' . $month . '-' . $day . ')';
            return;
        }
        echo 'Blogpost ' . htmlentities($slug) . ' detail (' . $year . '-' . $month . '-' . $day . ')';
    });
    // Subrouting
    $router->mount('/movies', function () use ($router) {
        // will result in '/movies'
        $router->get('/', function () {
            echo 'movies overview';
        });
        // will result in '/movies'
        $router->post('/', function () {
            echo 'add movie';
        });
        // will result in '/movies/id'
        $router->get('/(\d+)', function ($id) {
            echo 'movie id ' . htmlentities($id);
        });
        // will result in '/movies/id'
        $router->put('/(\d+)', function ($id) {
            echo 'Update movie id ' . htmlentities($id);
        });
    });
;

$router->run();
