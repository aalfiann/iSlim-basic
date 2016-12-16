<?php
/**
 * iSlim3 is based on Slim Framework (http://slimframework.com)
 *
 * @link      https://github.com/aalfiann/iSlim3
 * @copyright Copyright (c) 2016 M ABD AZIZ ALFIAN
 * @license   https://github.com/iSlim3/license.md (MIT License)
 */

// Load all class libraries
require '../vendor/autoload.php';
// Load config
require '../config.php';


// Create container
$app = new \Slim\App(["settings" => $config]);
$container = $app->getContainer();


// Register component Monolog
$container['logger'] = function($c) {
    $logger = new \Monolog\Logger('reSlim_logger');
    $file_handler = new \Monolog\Handler\StreamHandler("../logs/app.log");
    $logger->pushHandler($file_handler);
    return $logger;
};

// Register component database connection on container
$container['db'] = function ($c) {
    $db = $c['settings']['db'];
    $pdo = new PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'],
        $db['user'], $db['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};

// Override the default Not Found Handler
$container['notFoundHandler'] = function ($container) {
    return function ($request, $response) use ($container) {
        $custom = new classes\Custom();
        return $container['response']
            ->withStatus(404)
            ->withHeader('Content-type', 'application/json;charset=utf-8')
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->write($custom->prettyPrint('{ "status": "error", "code": "404", "message": "Bad request!" }'));
    };
};

// Override the default Not Allowed Handler
$container['notAllowedHandler'] = function ($container) {
    return function ($request, $response, $methods) use ($container) {
        $custom = new classes\Custom();
        return $container['response']
            ->withStatus(405)
            ->withHeader('Allow', implode(', ', $methods))
            ->withHeader('Content-type', 'application/json;charset=utf-8')
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->write($custom->prettyPrint('{ "status": "error", "code": "405", "message": "Method must be one of: ' . implode(', ', $methods).'" }'));
    };
};

// Override the slim error handler
$container['errorHandler'] = function ($container) {
    return function ($request, $response, $exception) use ($container) {
        // retrieve logger from $container here and log the error
        $container->logger->addInfo($exception->getMessage());
        $custom = new classes\Custom();
        $response->getBody()->rewind();
        return $response
            ->withStatus(500)
            ->withHeader('Content-type', 'application/json;charset=utf-8')
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->write($custom->prettyPrint('{ "status": "error", "code": "500", "message": "'.$exception->getMessage().'" }'));
    };
};

// Override PHP error handler
$container['phpErrorHandler'] = function ($container) {
    return $container['errorHandler'];
};

//PHP Error Handler
set_error_handler(function ($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) {
        return;
    }
    throw new \ErrorException($message, 0, $severity, $file, $line);
});

// Load all router files before run
$routers = glob('../routers/*.router.php');
foreach ($routers as $router) {
    require $router;
}

$app->run();

?>