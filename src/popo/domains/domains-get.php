<?php

require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/vendor/autoload.php');

use iDimensionz\HttpClient\Guzzle\GuzzleHttpClient;
use iDimensionz\LinodeApiV4\Api\Domains\DomainsApi;
use iDimensionz\LinodeApiV4\Api\Domains\Filters\DomainFilter;
use iDimensionz\LinodeApiV4\Api\Filters\FilterFieldCondition\FilterFieldConditionString;

$token = getenv('LINODE_API_TOKEN');
if ($token !== false) {
    $httpClient = new GuzzleHttpClient([
        'headers' => [
            "Authorization" => "token {$token}"
        ]
    ]);
    $domainsApi = new DomainsApi($httpClient);
    $filter = new DomainFilter();
    // Change group value to a group value you use.
    $groupValue = 'Some group name';
    $filter->addCondition(new FilterFieldConditionString('group', $groupValue));
    $domainsApi->setFilter($filter);
    $domains = $domainsApi->getAllDomains();
    print_r($domains);
} else {
    echo 'Error: LINODE_API_TOKEN not set.' . PHP_EOL;
    echo 'Please set the environment variable LINODE_API_TOKEN to the value of your Linode API token.' . PHP_EOL;
}