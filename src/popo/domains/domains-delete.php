<?php
/*
 * iDimensionz/{linode-api-v4-examples}
 * domains-delete.php
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
 * This file shows how to call the Domains DELETE endpoint to delete a domain from Linode.
 */

require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/vendor/autoload.php');

use iDimensionz\HttpClient\Guzzle\GuzzleHttpClient;
use iDimensionz\LinodeApiV4\Api\Domains\DomainsApi;

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

    // Check for filter options.
    // -i for domain ID
    $options = getopt('i:');

    if (isset($options['i'])) {
        $domainId = $options['i'];
        $response = $domainsApi->delete($domainId);
        echo 'Delete ' . ($response->isSuccess() ? 'was successful' : 'failed') . '.' . PHP_EOL;
    } else {
        echo 'Please supply the "-i" option with the ID of the domain to delete.' . PHP_EOL;
    }
} else {
    echo 'Error: LINODE_API_TOKEN not set.' . PHP_EOL;
    echo 'Please set the environment variable LINODE_API_TOKEN to the value of your Linode API token.' . PHP_EOL;
}
