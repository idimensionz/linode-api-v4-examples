<?php
/*
 * iDimensionz/{linode-api-v4-examples}
 * domainrecords-get.php
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
 * This file shows how to call the Domains GET endpoint to get a list of domains back from Linode.
 * It also shows how to use the filter to reduce the number of domains returned.
 */

require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/vendor/autoload.php');

use iDimensionz\HttpClient\Guzzle\GuzzleHttpClient;
use iDimensionz\LinodeApiV4\Api\Domains\Records\DomainRecordsApi;

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
    $domainRecordApi = new DomainRecordsApi($httpClient);

    // Check for filter options.
    // -d for domain ID, r for record ID
    $options = getopt('d:r:');

    if (isset($options['r']) && isset($options['d'])) {
        // Call the function to get a single domain record.
        $domainRecords = $domainRecordApi->getById($options['d'], $options['r']);
    } elseif (isset($options['d'])) {
        // Call the function to get all the domain records.
        $domainRecords = $domainRecordApi->getAll($options['d']);
    } else {
        echo 'You must supply a domain ID with the -d option.' . PHP_EOL;
        $domainRecords = null;
    }

    // Output the domain data.
    print_r($domainRecords);
} else {
    echo 'Error: LINODE_API_TOKEN not set.' . PHP_EOL;
    echo 'Please set the environment variable LINODE_API_TOKEN to the value of your Linode API token.' . PHP_EOL;
}