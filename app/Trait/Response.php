<?php

namespace App\Trait;

use Illuminate\Http\Exceptions\HttpResponseException;

trait Response
{
    public function api($result = [],$method = '',$message = "",$status = true,$code = 200)
    {

        $apiResponse = [
            "success" => $status,
            "code" => $code ?: 500,
            "message" => $message ?: ($method ? $this->message($method) : ''),
            "data" => $result
        ];

        return response()->json($apiResponse, ($code && $code >= 200 && $code <= 600)? $code : 500 );

    }

    private function message($method): string
    {
        $model = str_replace('Controller','',explode('\\',explode('::',$method)[0])[count(explode('\\',explode('::',$method)[0])) - 1]);
        return $model . ' ' . explode('::',$method)[1] . ' successfully';
    }

    /**
     * Summary of errorResponse
     * @param mixed $code
     * @param mixed $message
     */
    public function errorResponse($message,$code = 404)
    {
       $apiErrorResponse = [
        "success" =>  false,
        "code" => $code,
        "message" => $message,
        "data" => null
       ];

       return response()->json($apiErrorResponse,$code);


    }
}
