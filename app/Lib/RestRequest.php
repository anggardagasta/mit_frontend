<?php

namespace App\Lib;

class RestRequest {

    private $baseUrl = "http://localhost:8082";

    public function post($path, $body) {
        try {
            $client = new \GuzzleHttp\Client();
            $request = new \GuzzleHttp\Psr7\Request('POST', $this->baseUrl . $path, [], json_encode($body));
            $response = $client->send($request);
            return $response->getBody()->getContents();
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
}
