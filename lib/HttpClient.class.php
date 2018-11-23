<?php
use GuzzleHttp\Client as Client;
class HttpClient {

    const HTTP_METHOD_GET = "GET";

    const HTTP_METHOD_POST = "POST";

    /**
     *
     * @param string $url
     * @param string $method
     * @param array $headers
     * @param array $query
     * @param array $json_body
     * @throws Exception
     * @return \Psr\Http\Message\ResponseInterface
     */
    public static function request( string $url, string $method, array $headers = array(), array $query = array(), array $json_body = array()): \Psr\Http\Message\ResponseInterface{

        $client = new Client( [
            "base_uri" => "http://" . $GLOBALS['base_url_address']
        ] );
        $options = [
            "headers" => $headers,
            "query" => $query,
            "json" => $json_body
        ];
        switch( $method ){
            case self::HTTP_METHOD_GET:
                return $client->get( $url, $options );
            case self::HTTP_METHOD_POST:
                return $client->post( $url, $options );
            default:
                throw new Exception( "Http Method Not Implemented" );
        }

    }
}