<?php

declare(strict_types=1);

namespace Tierperso\SyliusAmazonPayPlugin\Amazon;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;

class AmazonClient extends Client
{

   public function sendRequest(): array
   {
       $client = new Client([
           'merchant_id'   => 'A5445N2KWSM0Z',
        'access_key'   => 'YOUR_ACCESS_KEY',
        'secret_key'   => '5ca9edd539ff4e17989385f8cb62d5133621274473b86499279c87e5127e6edc',
        'client_id'   => 'amzn1.application-oa2-client.46d27733e6044b08860955d63511ad19',
        'region'   => 'uk',
        'sandbox'   => true
       ]);

       $response = $client->post('https://api.sandbox.amazon.co.uk');

       return json_decode($response->getBody()->getContents(), true);
   }

   public function authencicate($token)
   {

   }

   public function getAuthToken()
   {

   }

    public function setToken($token)
    {
        $this->token = $token;
    }
}
