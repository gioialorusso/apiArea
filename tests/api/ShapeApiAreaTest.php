<?php

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Exception\ClientException;

class ShapeApiAreaTest extends TestCase{
    
    public function testCircleMethod(){
        
        $url = "/area/circle/";
        $radius = "3";
        
        $path = $url.$radius;
        $response = HttpClient::request($path, HttpClient::HTTP_METHOD_GET);
        $status_code = $response->getStatusCode();
        
        $this->assertEquals(200, $status_code);
        
        $decoded_body = json_decode($response->getBody(), true);
        $this->assertEquals(ApiResponse::OK_RESPONSE, $decoded_body["result"]);
        $this->assertEquals(round(pow($radius,2)*pi(), 3), $decoded_body["output"]);
        $this->assertNull($decoded_body["debug_info"]["error_msg"]);
        $this->assertEquals("circle", $decoded_body["debug_info"]["shape"]);
        $this->assertEquals($radius, $decoded_body["debug_info"]["radius"]);
        
        
        //Now some malformed input
        
        //Negative number
        $radius = "-3";
        
        $path = $url.$radius;
        try{
            $response = HttpClient::request($path, HttpClient::HTTP_METHOD_GET);
        }catch(ClientException $e){
            $response = $e->getResponse();
        }
        $status_code = $response->getStatusCode();
        
        $this->assertEquals(400, $status_code);
        
        $decoded_body = json_decode($response->getBody(), true);
        $this->assertEquals(ApiResponse::KO_RESPONSE, $decoded_body["result"]);
        $this->assertNull($decoded_body["output"]);
        $this->assertEquals("[radius] This value should be greater than or equal to 0.\n", $decoded_body["debug_info"]["error_msg"]);
        $this->assertEquals("circle", $decoded_body["debug_info"]["shape"]);
        $this->assertEquals($radius, $decoded_body["debug_info"]["radius"]);
        
        
        //Not a number
        $radius = "abc";
        
        $path = $url.$radius;
        try{
            $response = HttpClient::request($path, HttpClient::HTTP_METHOD_GET);
        }catch(ClientException $e){
            $response = $e->getResponse();
        }
        $status_code = $response->getStatusCode();
        
        $this->assertEquals(400, $status_code);
        
        $decoded_body = json_decode($response->getBody(), true);
        $this->assertEquals(ApiResponse::KO_RESPONSE, $decoded_body["result"]);
        $this->assertNull($decoded_body["output"]);
        $this->assertEquals("[radius] This value should be of type numeric.\n", $decoded_body["debug_info"]["error_msg"]);
        $this->assertEquals("circle", $decoded_body["debug_info"]["shape"]);
        $this->assertEquals($radius, $decoded_body["debug_info"]["radius"]);
        
        
        //Wrong url
        $path = $url;
        try{
            $response = HttpClient::request($path, HttpClient::HTTP_METHOD_GET);
        }catch(ClientException $e){
            $response = $e->getResponse();
        }
        $status_code = $response->getStatusCode();
        
        $this->assertEquals(404, $status_code);
        $decoded_body = json_decode($response->getBody(), true);

        $this->assertEquals(ApiResponse::KO_RESPONSE, $decoded_body["result"]);
        $this->assertNull($decoded_body["output"]);
        $this->assertEquals("Route not implemented!", $decoded_body["debug_info"]["error_msg"]);
        $this->assertEmpty($decoded_body["debug_info"]["shape"]);
        $this->assertEmpty($decoded_body["debug_info"]["radius"]);
        
        
    }
    
    
    
    public function testSquareMethod(){
        
        $url = "/area/square";
        $side = "4";
        
        $path = $url;
        $query = ["side" => $side];
        $response = HttpClient::request($path, HttpClient::HTTP_METHOD_GET, array(), $query);
        $status_code = $response->getStatusCode();
        
        $this->assertEquals(200, $status_code);
        
        $decoded_body = json_decode($response->getBody(), true);
        $this->assertEquals(ApiResponse::OK_RESPONSE, $decoded_body["result"]);
        $this->assertEquals(pow($side,2), $decoded_body["output"]);
        $this->assertEmpty($decoded_body["debug_info"]["error_msg"]);
        $this->assertEquals("square", $decoded_body["debug_info"]["shape"]);
        $this->assertEquals($side, $decoded_body["debug_info"]["side"]);
        
        
        //Now some malformed input
        
        //no side parameter passed
        
        try{
            $response = HttpClient::request($path, HttpClient::HTTP_METHOD_GET);
        }catch(ClientException $e){
            $response = $e->getResponse();
        }
        
        $status_code = $response->getStatusCode();
        $this->assertEquals(400, $status_code);
        
        $decoded_body = json_decode($response->getBody(), true);
        $this->assertEquals(ApiResponse::KO_RESPONSE, $decoded_body["result"]);
        $this->assertNull($decoded_body["output"]);
        $this->assertEquals("[side] This value should not be blank.\n", $decoded_body["debug_info"]["error_msg"]);
        $this->assertEquals("square", $decoded_body["debug_info"]["shape"]);
        $this->assertNull($decoded_body["debug_info"]["side"]);
        
        //Negative number
        $side = "-3";
        $query = ["side" => $side];
        
        try{
            HttpClient::request($path, HttpClient::HTTP_METHOD_GET, array(), $query);
        }catch(ClientException $e){
            $response = $e->getResponse();
        }
        $status_code = $response->getStatusCode();
        
        $this->assertEquals(400, $status_code);
        
        $decoded_body = json_decode($response->getBody(), true);
        $this->assertEquals(ApiResponse::KO_RESPONSE, $decoded_body["result"]);
        $this->assertEmpty($decoded_body["output"]);
        $this->assertEquals("[side] This value should be greater than or equal to 0.\n", $decoded_body["debug_info"]["error_msg"]);
        $this->assertEquals("square", $decoded_body["debug_info"]["shape"]);
        $this->assertEquals($side, $decoded_body["debug_info"]["side"]);
        
        
        //Not a number
        $side = "abc";
        
        $query = ["side" => $side];
        
        try{
            HttpClient::request($path, HttpClient::HTTP_METHOD_GET, array(), $query);
        }catch(ClientException $e){
            $response = $e->getResponse();
        }
        $status_code = $response->getStatusCode();
        
        $this->assertEquals(400, $status_code);
        
        $decoded_body = json_decode($response->getBody(), true);
        $this->assertEquals(ApiResponse::KO_RESPONSE, $decoded_body["result"]);
        $this->assertEmpty($decoded_body["output"]);
        $this->assertEquals("[side] This value should be of type numeric.\n", $decoded_body["debug_info"]["error_msg"]);
        $this->assertEquals("square", $decoded_body["debug_info"]["shape"]);
        $this->assertEquals($side, $decoded_body["debug_info"]["side"]);
        
        //Wrong url
        $path = $url."/".$side;
        try{
            HttpClient::request($path, HttpClient::HTTP_METHOD_GET);
        }catch(ClientException $e){
            $response = $e->getResponse();
        }
        $status_code = $response->getStatusCode();
        
        $this->assertEquals(404, $status_code);
        $decoded_body = json_decode($response->getBody(), true);
        $this->assertEquals(ApiResponse::KO_RESPONSE, $decoded_body["result"]);
        $this->assertEmpty($decoded_body["output"]);
        $this->assertEquals("Route not implemented!", $decoded_body["debug_info"]["error_msg"]);
        
    }
    
    
    
    public function testRectangleMethod(){
        
        $url = "/area/rectangle";
        $base = "4";
        $height = "3";
        
        $path = $url;
        $json_body = ["base" => $base, "height" => $height];
        $response = HttpClient::request($path, HttpClient::HTTP_METHOD_POST, array(), array(), $json_body);
        $status_code = $response->getStatusCode();
        
        $this->assertEquals(200, $status_code);
        
        $decoded_body = json_decode($response->getBody(), true);
        $this->assertEquals(ApiResponse::OK_RESPONSE, $decoded_body["result"]);
        $this->assertEquals(($base*$height), $decoded_body["output"]);
        $this->assertEmpty($decoded_body["debug_info"]["error_msg"]);
        $this->assertEquals("rectangle", $decoded_body["debug_info"]["shape"]);
        $this->assertEquals($base, $decoded_body["debug_info"]["base"]);
        $this->assertEquals($height, $decoded_body["debug_info"]["height"]);
        
        
        //Now some malformed input
        
        //no height parameter passed
        
        $json_body = ["base" => $base];
        
        
        try{
            $response = HttpClient::request($path, HttpClient::HTTP_METHOD_POST, array(), array(), $json_body);
        }catch(ClientException $e){
            $response = $e->getResponse();
        }
        
        $status_code = $response->getStatusCode();
        $this->assertEquals(400, $status_code);
        
        $decoded_body = json_decode($response->getBody(), true);
        $this->assertEquals(ApiResponse::KO_RESPONSE, $decoded_body["result"]);
        $this->assertNull($decoded_body["output"]);
        $this->assertEquals("[height] This value should not be blank.\n", $decoded_body["debug_info"]["error_msg"]);
        $this->assertEquals("rectangle", $decoded_body["debug_info"]["shape"]);
        $this->assertArrayHasKey("base", $decoded_body["debug_info"]);
        $this->assertArrayHasKey("height", $decoded_body["debug_info"]);
        $this->assertEquals($base, $decoded_body["debug_info"]["base"]);
        $this->assertNull($decoded_body["debug_info"]["height"]);
        
        
        //no base parameter passed
        
        $json_body = ["height" => $height];
        
        
        try{
            $response = HttpClient::request($path, HttpClient::HTTP_METHOD_POST, array(), array(), $json_body);
        }catch(ClientException $e){
            $response = $e->getResponse();
        }
        
        $status_code = $response->getStatusCode();
        $this->assertEquals(400, $status_code);
        
        $decoded_body = json_decode($response->getBody(), true);
        $this->assertEquals(ApiResponse::KO_RESPONSE, $decoded_body["result"]);
        $this->assertNull($decoded_body["output"]);
        $this->assertEquals("[base] This value should not be blank.\n", $decoded_body["debug_info"]["error_msg"]);
        $this->assertEquals("rectangle", $decoded_body["debug_info"]["shape"]);
        $this->assertArrayHasKey("base", $decoded_body["debug_info"]);
        $this->assertArrayHasKey("height", $decoded_body["debug_info"]);
        $this->assertNull($decoded_body["debug_info"]["base"]);
        $this->assertEquals($height, $decoded_body["debug_info"]["height"]);
        
        //Negative number
        $base = "-3";
        $height = "4";
        $json_body = ["base" => $base, "height" => $height];
        
        try{
            $response = HttpClient::request($path, HttpClient::HTTP_METHOD_POST, array(), array(), $json_body);
        }catch(ClientException $e){
            $response = $e->getResponse();
        }
        $status_code = $response->getStatusCode();
        
        $this->assertEquals(400, $status_code);
        
        $decoded_body = json_decode($response->getBody(), true);
        $this->assertEquals(ApiResponse::KO_RESPONSE, $decoded_body["result"]);
        $this->assertEmpty($decoded_body["output"]);
        $this->assertEquals("[base] This value should be greater than or equal to 0.\n", $decoded_body["debug_info"]["error_msg"]);
        $this->assertEquals("rectangle", $decoded_body["debug_info"]["shape"]);
        $this->assertEquals($base, $decoded_body["debug_info"]["base"]);
        $this->assertEquals($height, $decoded_body["debug_info"]["height"]);
        
        
        $base = "3";
        $height = "-4";
        $json_body = ["base" => $base, "height" => $height];
        
        try{
            $response = HttpClient::request($path, HttpClient::HTTP_METHOD_POST, array(), array(), $json_body);
        }catch(ClientException $e){
            $response = $e->getResponse();
        }
        $status_code = $response->getStatusCode();
        
        $this->assertEquals(400, $status_code);
        
        $decoded_body = json_decode($response->getBody(), true);
        $this->assertEquals(ApiResponse::KO_RESPONSE, $decoded_body["result"]);
        $this->assertEmpty($decoded_body["output"]);
        $this->assertEquals("[height] This value should be greater than or equal to 0.\n", $decoded_body["debug_info"]["error_msg"]);
        $this->assertEquals("rectangle", $decoded_body["debug_info"]["shape"]);
        $this->assertEquals($base, $decoded_body["debug_info"]["base"]);
        $this->assertEquals($height, $decoded_body["debug_info"]["height"]);
        
        
        //Not a number
        $base = "abc";
        $height = "4";
        
        $json_body = ["base" => $base, "height" => $height];
        
        try{
            $response = HttpClient::request($path, HttpClient::HTTP_METHOD_POST, array(), array(), $json_body);
        }catch(ClientException $e){
            $response = $e->getResponse();
        }
        $status_code = $response->getStatusCode();
        
        $this->assertEquals(400, $status_code);
        
        $decoded_body = json_decode($response->getBody(), true);
        $this->assertEquals(ApiResponse::KO_RESPONSE, $decoded_body["result"]);
        $this->assertEmpty($decoded_body["output"]);
        $this->assertEquals("[base] This value should be of type numeric.\n", $decoded_body["debug_info"]["error_msg"]);
        $this->assertEquals("rectangle", $decoded_body["debug_info"]["shape"]);
        $this->assertEquals($base, $decoded_body["debug_info"]["base"]);
        $this->assertEquals($height, $decoded_body["debug_info"]["height"]);
        
        
        
        $base = "4";
        $height = "abc";
        
        $json_body = ["base" => $base, "height" => $height];
        
        try{
            $response = HttpClient::request($path, HttpClient::HTTP_METHOD_POST, array(), array(), $json_body);
        }catch(ClientException $e){
            $response = $e->getResponse();
        }
        $status_code = $response->getStatusCode();
        
        $this->assertEquals(400, $status_code);
        
        $decoded_body = json_decode($response->getBody(), true);
        $this->assertEquals(ApiResponse::KO_RESPONSE, $decoded_body["result"]);
        $this->assertEmpty($decoded_body["output"]);
        $this->assertEquals("[height] This value should be of type numeric.\n", $decoded_body["debug_info"]["error_msg"]);
        $this->assertEquals("rectangle", $decoded_body["debug_info"]["shape"]);
        $this->assertEquals($base, $decoded_body["debug_info"]["base"]);
        $this->assertEquals($height, $decoded_body["debug_info"]["height"]);
        
        
        //Wrong method
        try{
            $response = HttpClient::request($path, HttpClient::HTTP_METHOD_GET, array(), array(), $json_body);
        }catch(ClientException $e){
            $response = $e->getResponse();
        }
        $status_code = $response->getStatusCode();
        
        $this->assertEquals(404, $status_code);
        $decoded_body = json_decode($response->getBody(), true);
        $this->assertEquals(ApiResponse::KO_RESPONSE, $decoded_body["result"]);
        $this->assertEmpty($decoded_body["output"]);
        $this->assertEquals("Route not implemented!", $decoded_body["debug_info"]["error_msg"]);
        
        
        //Wrong url
        $path = $url."/".$base;
        try{
            $response = HttpClient::request($path, HttpClient::HTTP_METHOD_POST, array(), array(), $json_body);
        }catch(ClientException $e){
            $response = $e->getResponse();
        }
        $status_code = $response->getStatusCode();
        
        $this->assertEquals(404, $status_code);
        $decoded_body = json_decode($response->getBody(), true);
        $this->assertEquals(ApiResponse::KO_RESPONSE, $decoded_body["result"]);
        $this->assertEmpty($decoded_body["output"]);
        $this->assertEquals("Route not implemented!", $decoded_body["debug_info"]["error_msg"]);
        
    }
    
}