<?php

use Symfony\Component\HttpFoundation\Response;

class ApiHelper{
    
    private $ctx;
    
    public function __construct($ctx){
        $this->ctx = $ctx;
    }
    
    public function validateRequestAndCalcArea(array $input_parameters, $constraint): array
    {
        $errors = $this->ctx['validator']->validate( $input_parameters, $constraint );
        if ( count( $errors ) ){
            $response = [];
            foreach( $errors as $error ){
                $error_msg .= $error->getPropertyPath() . ' ' . $error->getMessage() . "\n";
            }
            $response["response"] = AreaApiResponse::buildKOResponse( $input_parameters, $error_msg );
            $response["response_code"] = Response::HTTP_BAD_REQUEST;
        }else{
            $response = ShapeAreaCalculator::calcArea( $input_parameters );
        }
        return $response;
    }
        
}