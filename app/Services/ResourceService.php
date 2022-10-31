<?php


namespace App\Services;


use GuzzleHttp\Client;

class ResourceService
{
    public function __construct(protected $coreUrl = 'http://62.209.129.41:8000/')
    {
    }

    public function getTranslate($key)
    {
        try {
            $client = new Client();
            $response = $client->get($this->coreUrl.'api/v1/translate/'.$key);
            if($response->getStatusCode() == 200){
                return json_decode($response->getBody(), true);
            }
            return [];

        } catch (\Exception $exception){
            return [];
        }
    }

    public function getLanguage()
    {
        try {
            $client = new Client();
            $response = $client->get($this->coreUrl.'api/v1/language');
            if($response->getStatusCode() == 200){
                return json_decode($response->getBody(), true);
            }
            return [];

        } catch (\Exception $exception){
            return [];
        }
    }

    public function getLanguageDefault()
    {
        try {
            $client = new Client();
            $response = $client->get($this->coreUrl.'api/v1/language/default');
            if($response->getStatusCode() == 200){
                return json_decode($response->getBody(), true);
            }
            return [];

        } catch (\Exception $exception){
            return [];
        }
    }
}