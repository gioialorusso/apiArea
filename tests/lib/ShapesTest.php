<?php

use PHPUnit\Framework\TestCase;

class ShapesTest extends TestCase{
    
    
    //NO_NEGATIVE_PARAMS EXCEPTION
    
    
    public function negativeValues(): array
    {
        return [
            [-1, BaseShape::CIRCLE],
            [-5, BaseShape::SQUARE],
            [-10, BaseShape::RECTANGLE],
            [-15, BaseShape::CIRCLE],
            [-20, BaseShape::SQUARE],
            [-25, BaseShape::RECTANGLE],
            
        ];
    }
    
    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @expectedExceptionMessage BaseShape::NO_NEGATIVE_PARAMS
     * @dataProvider negativeValues
     */
    public function testNegativeParamNotAdmitted($negativeValue, $shape){        
        new Circle($negativeValue);
    }
    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @expectedExceptionMessage BaseShape::NO_NEGATIVE_PARAMS
     * @dataProvider negativeValues
     */
    public function testNegativeParamNotAdmittedSquare($negativeValue){
        new Square($negativeValue);
    }
    
    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @expectedExceptionMessage BaseShape::NO_NEGATIVE_PARAMS
     * @dataProvider negativeValues
     */
    public function testNegativeParamNotAdmittedRectangleBaseParam($negativeValue){
        new Rectangle($negativeValue, 3);
    }
    
    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @expectedExceptionMessage BaseShape::NO_NEGATIVE_PARAMS
     * @dataProvider negativeValues
     */
    public function testNegativeParamNotAdmittedRectangleHeightParam($negativeValue){
        new Rectangle(3, $negativeValue);
    }
    
    //SPECIFY_INPUT_PARAMS EXCEPTION
    
    
    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @expectedExceptionMessage BaseShape::SPECIFY_INPUT_PARAMS
     */
    public function testSpecifyInputParamCircle(){
        $null_param = null;
        new Circle($null_param);
    }
    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @expectedExceptionMessage BaseShape::SPECIFY_INPUT_PARAMS
     */
    public function testSpecifyInputParamSquare(){
        $null_param = null;
        new Square($null_param);
    }
    
    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @expectedExceptionMessage BaseShape::SPECIFY_INPUT_PARAMS
     */
    public function testSpecifyInputParamRectangleBaseParam(){
        $null_param = null;
        new Rectangle($null_param, 3);
    }
    
    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @expectedExceptionMessage BaseShape::SPECIFY_INPUT_PARAMS
     */
    public function testSpecifyInputParamRectangleHeightParam(){
        $null_param = null;
        new Rectangle(3, $null_param);
    }
    
    //NO_NONNUMERIC_PARAMS EXCEPTION
    public function nonNumericValues(): array
    {
        return [
            ["abc"],
            ["onetwothree"],
            ["asd"],
            ["5-g*"],
            ["a-b"],
        ];
    }
    
    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @expectedExceptionMessage BaseShape::NO_NONNUMERIC_PARAMS
     * @dataProvider nonNumericValues
     */
    public function testNonNumericParamNotAdmittedCircle($nonNumericValue){
        new Circle($nonNumericValue);
    }
    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @expectedExceptionMessage BaseShape::NO_NONNUMERIC_PARAMS
     * @dataProvider nonNumericValues
     */
    public function testNonNumericParamNotAdmittedSquare($nonNumericValue){
        new Square($nonNumericValue);
    }
    
    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @expectedExceptionMessage BaseShape::NO_NONNUMERIC_PARAMS
     * @dataProvider nonNumericValues
     */
    public function testNonNumericParamNotAdmittedRectangleBaseParam($nonNumericValue){
        new Rectangle($nonNumericValue, 3);
    }
    
    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @expectedExceptionMessage BaseShape::NO_NONNUMERIC_PARAMS
     * @dataProvider nonNumericValues
     */
    public function testNonNumericParamNotAdmittedRectangleHeightParam($nonNumericValue){
        new Rectangle(3, $nonNumericValue);
    }
    
    
    //WORKING FUNCTIONS
    
    public function radiusValues(): array
    {
        return [
            ["4"],
            [7.5],
            [100],
            ["73"],
            [42],
        ];
    }
    /**
     * 
     * @dataProvider radiusValues
     */
    public function testCircleArea($radiusValue){
        
        $circle = BaseShape::getShape([
            "shape" => BaseShape::CIRCLE,
            "radius" => $radiusValue
        ]);
        $this->assertEquals(round((pow($radiusValue, 2) * pi()), BaseShape::DECIMALS), $circle->calcArea());
        
    }
    
    
    public function sideValues(): array
    {
        return [
            ["4"],
            [7.5],
            [100],
            ["73"],
            [42],
        ];
    }
    /**
     *
     * @dataProvider sideValues
     */
    public function testSquareArea($sideValue){
        
        $square = BaseShape::getShape([
            "shape" => BaseShape::SQUARE,
            "side" => $sideValue
        ]);
        $this->assertEquals(round(pow($sideValue, 2), BaseShape::DECIMALS), $square->calcArea());
        
    }
    
    
    public function baseHeightValues(): array
    {
        return [
            ["4", "5"],
            [7.5, 9.3],
            [100, 200],
        ];
    }
    /**
     *
     * @dataProvider baseHeightValues
     */
    public function testRectangleArea($base, $height){
        
        $rectangle = BaseShape::getShape([
            "shape" => BaseShape::RECTANGLE,
            "base" => $base,
            "height" => $height
        ]);
        $this->assertEquals(round(($base*$height), BaseShape::DECIMALS), $rectangle->calcArea());
        
    }
    
}