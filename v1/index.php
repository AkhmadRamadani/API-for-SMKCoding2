<?php

date_default_timezone_set('Asia/Jakarta');

define ( "API_BASE", dirname ( __FILE__ ) . "/" );

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// Add the framework
// ===============================================================================================
require_once '../vendor/autoload.php';

// defining word
define ( "PHP", ".php", TRUE);
define ( "API", "App/System/API" . PHP, TRUE );
define ( "DATABASE", "App/System/Database" . PHP, TRUE );
define ( "CONFIG", "App/System/Config" . PHP, TRUE );
define ( "DEP", "App/System/Dependencies" . PHP, TRUE );
define ( "ROUTER", "App/System/Router" . PHP, TRUE );
define ( "MIDDLEWARE", "App/Middleware/Middleware" . PHP, TRUE );
define ( "CONTROLLER", "App/Controllers/", TRUE );

// Configure App
require_once ( CONFIG );

// Inisiate the Apps
// ===============================================================================================
$api = new \Slim\App( $config );

// set Dependencies
// require_once ( DEP );

// Requiring Database Engine
require_once ( DATABASE );

// Requiring API Engine
require_once ( API );

// set Middleware
require_once ( MIDDLEWARE );

// set Routers
require_once ( ROUTER );

// ===============================================================================================
// CORS Setup
// ===============================================================================================
$api->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response->withHeader('Access-Control-Allow-Origin', '*')
                    ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
                    ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});

// ===============================================================================================
// Run the App
// ===============================================================================================
$api->run();

?>