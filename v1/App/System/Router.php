<?php

// Controller Registration
// ===============================================================================================
$controllers = array ( 
    "Auth", "General",
);

// **
// *
// *    Default Router
// *
// **
$api->map(['GET', 'POST'], '/', function( $request, $response, $args ) {

    return $response->withJSON (
        array (
            "name"          => "Welcome in JOKE API",
            "version"       => "1.0",
            "description"   => "Authentication is needed to access the API."
        )
    );
});

// **
// *
// *    Dynamic Router
// *
// **
function requiringRouter ( $i = 0 ) {

    global $controllers, $api;

    $_SESSION['controllerName'] = $controllers[$i];
    
    $api->group( '/' . strtolower ( $_SESSION['controllerName'] ), function()
    {
        if ( file_exists ( CONTROLLER . ucfirst( $_SESSION['controllerName'] ) . PHP ) ) {
            require_once ( CONTROLLER . ucfirst( $_SESSION['controllerName'] ) . PHP );
        }    
    });

    if ( $i < sizeof ( $controllers ) - 1 ) requiringRouter ( $i + 1 );
}

requiringRouter ();


?>
