<?php

use \Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpFoundation\Response;

interface Shape{
    public function calcArea();
}

abstract class BaseShape implements Shape{
    
    const NO_NEGATIVE_PARAMS = "Negative parameters not admitted";
    const SPECIFY_INPUT_PARAMS = "Please specify input parameters";
    const NO_NONNUMERIC_PARAMS = "You cannot use non-numeric parameters";
    
    const CIRCLE = "circle";
    const SQUARE = "square";
    const RECTANGLE = "rectangle";
    
    const DECIMALS = 3;
    
    
    /**
     *
     * @param array $params => The params to validate
     * @throws BadRequestHttpException
     */
    public function validate(array $params)
    {
        foreach($params as $param){
            if(is_null($param)){
                throw new BadRequestHttpException(self::SPECIFY_INPUT_PARAMS);
            }
            if(!is_numeric($param)){
                throw new BadRequestHttpException(self::NO_NONNUMERIC_PARAMS);
            }
            if($param < 0){
                throw new BadRequestHttpException(self::NO_NEGATIVE_PARAMS);
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
        return round(pow($this->side, 2), BaseShape::DECIMALS);
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
        return round(pow($this->radius, 2)*pi(), BaseShape::DECIMALS);
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
        return round(($this->base * $this->height), BaseShape::DECIMALS);
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
