<?php

use \Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpFoundation\Response;

interface Shape{
    public function calcArea();
}

abstract class BaseShape implements Shape{
    
    /**
     *
     * @param array $params => The params to validate
     * @throws BadRequestHttpException
     */
    public function validate(array $params)
    {
        
        foreach($params as $param){
            if(is_null($param)){
                throw new BadRequestHttpException("Please specify input parameters");
            }
            if(!is_numeric($param)){
                throw new BadRequestHttpException("You cannot use non-numeric parameters");
            }
            if($param < 0){
                throw new BadRequestHttpException("Negative parameters not admitted");
            }
        }
        
    }
    
    /**
     * 
     * @param array $input_parameters
     * @throws BadRequestHttpException
     * @return BaseShape
     */
    public static function getShape(array $input_parameters): BaseShape
    {
        
        if(empty($input_parameters["shape"])){
            throw new BadRequestHttpException("Missing parameter shape");
        }
        switch($input_parameters["shape"]){
            case 'circle':
                return new Circle($input_parameters["radius"]);
            case 'square':
                return new Square($input_parameters["side"]);
            case 'rectangle':
                return new Rectangle($input_parameters["base"], $input_parameters["height"]);
            default:
                throw new BadRequestHttpException("Shape type not handled");
                
        }
    }
}


class Square extends BaseShape implements Shape{
    private $side;
    
    public function __construct($side)
    {
        $this->validate(func_get_args());
        $this->side = $side;
    }
    
    public function calcArea(): float
    {
        return pow($this->side, 2);
    }
}

class Circle extends BaseShape implements Shape{
    private $radius;
    
    public function __construct($radius)
    {
        $this->validate(func_get_args());
        $this->radius = $radius;
    }
    
    public function calcArea(): float
    {
        return round(pow($this->radius, 2)*pi(), 3);
    }
}

class Rectangle extends BaseShape implements Shape{
    private $base;
    private $height;
    
    public function __construct($base, $height)
    {
        $this->validate(func_get_args());
        $this->base = $base;
        $this->height = $height;
    }
    
    public function calcArea(): float
    {
        return round(($this->base * $this->height), 2);
    }
}

class ShapeAreaCalculator{
    
    public static function calcArea(array $input_parameters): array
    {
        
        try{
            $shape = BaseShape::getShape($input_parameters);
            $area = $shape->calcArea();
            $response = AreaApiResponse::buildOKResponse( $input_parameters, $area );
            $response_code = Response::HTTP_OK;
        }catch( BadRequestHttpException $e ){
            $response = AreaApiResponse::buildKOResponse( $input_parameters, $e->getMessage() );
            $response_code = $e->getStatusCode();
        }
        
        return [
            "response" => $response,
            "response_code" => $response_code
        ];
    }
}
