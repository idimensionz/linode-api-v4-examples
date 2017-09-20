<?php
/*
 * iDimensionz/{linode-api-v4-examples}
 * domains-create.php
 *  
 * The MIT License (MIT)
 * 
 * Copyright (c) 2017 Dimensionz
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
*/

/**
 * This file shows how to call the Domains POST endpoint to create a domain on your Linode.
 */

require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/vendor/autoload.php');

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use iDimensionz\HttpClient\Guzzle\GuzzleHttpClient;
use iDimensionz\LinodeApiV4\Api\Domains\DomainsApi;
use iDimensionz\LinodeApiV4\Api\Domains\DomainModel;

echo 'This example will attempt to create an actual (master) domain on your Linode.' . PHP_EOL;
$continue = readline('Are you sure you wish to continue? (y/n) ');

if ('y' !== strtolower($continue)) {
    echo 'Aborted.' . PHP_EOL;
    exit(0);
}

$domainName = readline('Please enter a valid domain name to create: ');

// Get the API token from the LINODE_API_TOKEN environment variable.
// That environment variable needs to be set before running this script.
// Note: This script could be modified to accept the token as a command line option.
$token = getenv('LINODE_API_TOKEN');
if ($token !== false) {
    // Setup the HTTP client with the API token in the header.
    $httpClient = new GuzzleHttpClient([
        'headers' => [
            "Authorization" => "token {$token}"
        ]
    ]);
    // Instantiate a DomainsApi injecting the HTTP client.
    $domainsApi = new DomainsApi($httpClient);
    $domainModel = new DomainModel();
    // Domain, type and soa_email are all required fields for creating a domain.
    $domainModel->setDomainName($domainName);
    $domainModel->setType('master');
    $domainModel->setSoaContactEmail('test@example.com');
    try {
        $isSuccess = $domainsApi->create($domainModel);
    } catch (\GuzzleHttp\Exception\ServerException $se) {
        $isSuccess = false;
        echo $se->getTraceAsString();
        echo PHP_EOL . 'Exception - Raw request: ' . (string) $se->getRequest()->getBody();
    }
    echo 'Result of call to create domain: ' . ($isSuccess ? 'success' : 'failed') . PHP_EOL;
    if ($isSuccess) {
        echo 'Now login to your Linode, go to the DNS manager (https://manager.linode.com/dns) and see the new domain.' . PHP_EOL;
    }
} else {
    echo 'Error: LINODE_API_TOKEN not set.' . PHP_EOL;
    echo 'Please set the environment variable LINODE_API_TOKEN to the value of your Linode API token.' . PHP_EOL;
}