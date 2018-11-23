<?php
interface ApiResponse {

    const OK_RESPONSE = "OK";

    const KO_RESPONSE = "KO";

    public static function buildResponse( string $result, array $input_parameters = [], $output = null): array;

    public static function buildOKResponse( array $input_parameters = [], $output = null): array;

    public static function buildKOResponse( array $input_parameters = [], string $error_msg = null): array;
}
class GenericApiResponse implements ApiResponse {

    /**
     *
     * @param string $result
     * @param array $input_parameters
     * @param
     *            $output
     * @return array
     */
    public static function buildResponse( string $result, array $input_parameters = [], $output = null): array{

        $response = [
            "result" => $result,
            "output" => $output,
            "debug_info" => [
                "error_msg" => null
            ]
        ];
        $response["debug_info"] = array_merge( $response["debug_info"], $input_parameters );

        return $response;

    }

    /**
     *
     * @param array $input_parameters
     * @param
     *            $output
     * @return array
     */
    public static function buildOKResponse( array $input_parameters = [], $output = null): array{

        return self::buildResponse( ApiResponse::OK_RESPONSE, $input_parameters, $output );

    }

    /**
     *
     * @param array $input_parameters
     * @param string $error_msg
     * @return array
     */
    public static function buildKOResponse( array $input_parameters = [], string $error_msg = null): array{

        $response = self::buildResponse( ApiResponse::KO_RESPONSE, $input_parameters, null );
        $response["debug_info"]["error_msg"] = $error_msg;

        return $response;

    }
}
class AreaApiResponse extends GenericApiResponse {

    /**
     *
     * @param array $input_parameters
     * @param float $area
     * @return array
     */
    public static function buildOKResponse( array $input_parameters = [], $area = null): array{

        $response = parent::buildOKResponse( $input_parameters, $area );
        return $response;

    }

    /**
     *
     * @param array $input_parameters
     * @param string $error_msg
     * @return array
     */
    public static function buildKOResponse( array $input_parameters = [], string $error_msg = null): array{

        $response = parent::buildKOResponse( $input_parameters, $error_msg );
        return $response;

    }
}