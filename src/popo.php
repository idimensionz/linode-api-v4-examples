<?php

require_once(dirname(dirname(__FILE__)) . '/vendor/autoload.php');

use iDimensionz\LinodeApiV4\Api\Domains\DomainsApi;
use iDimensionz\HttpClient\Guzzle\GuzzleHttpClient;

$token = getenv('LINODE_API_TOKEN');
$httpClient = new GuzzleHttpClient([
    'headers' => [
        "Authorization" => "token {$token}"
    ]
]);
$domainsApi = new DomainsApi($httpClient);
$domains = $domainsApi->getAllDomains();
print_r($domains);