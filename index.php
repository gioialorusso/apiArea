<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/lib/Shapes.class.php';
require_once __DIR__ . '/lib/ApiResponse.class.php';
require_once __DIR__ . '/lib/ApiHelper.class.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;
use Symfony\Component\Validator\Constraints as Assert;

define( 'CONTENT_TYPE_APPLICATION_JSON', 'application/json' );
$headers = [
    "Content-Type" => CONTENT_TYPE_APPLICATION_JSON
];

$app = new Silex\Application();
$app->register( new Silex\Provider\ValidatorServiceProvider() );
$app['helpers'] = function ( $app ){
    return new ApiHelper( $app );
};

// BEFORE MIDDLEWARE
/**
 * The request body should only be parsed as JSON if the Content-Type header begins with
 * application/json.
 */
$app->before( function ( Request $request ){
    if ( 0 === strpos( $request->headers->get( 'Content-Type' ), CONTENT_TYPE_APPLICATION_JSON ) ){
        $data = json_decode( $request->getContent(), true );
        $request->request->replace( is_array( $data ) ? $data : [] );
    }
} );

// ROUTES DEFINITION
$app->get( '/area/square', function ( Application $app, Request $request ) use ($headers ){

    $side = $request->query->get( 'side' );

    $input_parameters = [
        "shape" => "square",
        "side" => $side
    ];

    $constraint = new Assert\Collection( [
        'side' => [
            new Assert\NotBlank(),
            new Assert\GreaterThanOrEqual( 0 ),
            new Assert\Type( [
                "type" => "numeric"
            ] )
        ],
        'shape' => new Assert\Optional()
    ] );

    $response = $app['helpers']->validateRequestAndCalcArea( $input_parameters, $constraint );

    return new Response( json_encode( $response["response"] ), $response["response_code"], $headers );
} );

$app->get( '/area/circle/{radius}', function ( Application $app, Request $request ) use ($headers ){

    $radius = $request->attributes->get( '_route_params' )["radius"];
    $input_parameters = [
        "shape" => "circle",
        "radius" => $radius
    ];

    // Not Blank constraint not needed: is implicit from the route.
    $constraint = new Assert\Collection( [
        'radius' => [
            new Assert\GreaterThanOrEqual( 0 ),
            new Assert\Type( [
                "type" => "numeric"
            ] )
        ],
        'shape' => new Assert\Optional()
    ] );
    $response = $app['helpers']->validateRequestAndCalcArea( $input_parameters, $constraint );

    return new Response( json_encode( $response["response"] ), $response["response_code"], $headers );
} );

$app->post( '/area/rectangle', function ( Application $app, Request $request ) use ($headers ){

    $base = $request->request->get( "base" );
    $height = $request->request->get( "height" );

    $input_parameters = [
        "shape" => "rectangle",
        "base" => $base,
        "height" => $height
    ];

    $constraint = new Assert\Collection( [
        'base' => [
            new Assert\NotBlank(),
            new Assert\GreaterThanOrEqual( 0 ),
            new Assert\Type( [
                "type" => "numeric"
            ] )
        ],
        'height' => [
            new Assert\NotBlank(),
            new Assert\GreaterThanOrEqual( 0 ),
            new Assert\Type( [
                "type" => "numeric"
            ] )
        ],
        'shape' => new Assert\Optional()
    ] );
    $response = $app['helpers']->validateRequestAndCalcArea( $input_parameters, $constraint );

    return new Response( json_encode( $response["response"] ), $response["response_code"], $headers );
} );

// HANDLES NON DEFINED ROUTES
$app->match( '/{url}', function ( Application $app, Request $request ) use ($app, $headers ){
    $input_parameters = array_merge( $request->request->all(), $request->query->all(), $request->attributes->get( '_route_params' ) );
    $response = GenericApiResponse::buildKOResponse( $input_parameters, "Route not implemented!" );

    return new Response( json_encode( $response ), Response::HTTP_NOT_FOUND, $headers );
} )->method( 'GET|POST' )->assert( 'url', '.+' )->value( 'url', '' );

// RUN IT!
$app->run();
    