<?php
/*
 * iDimensionz/{linode-api-v4-examples}
 * domains-get.php
 *
 * The MIT License (MIT)
 *
 * Copyright (c) 2015 iDimensionz
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
use iDimensionz\LinodeApiV4\Api\Domains\DomainsApi;
use iDimensionz\LinodeApiV4\Api\Domains\DomainFilter;

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

    /* OPTIONAL: Setup a filter for the Domains API. */
    // Create a DomainsApi specific filter object which has functions for setting filters on the fields which are
    // filterable.
    $filter = new DomainFilter();

    // Check for filter options.
    // -d for domain, -g for group, and -m for master-ips
    $options = getopt('d:g:m:');

    // Get the domain filter option (i.e. mydomainname.com) and add a filter for it.
    $domainOption = isset($options['d']) ? $options['d'] : null;
    // The filter will ignore the domain value if it is empty.
    $filter->addDomainFilter($domainOption);

    // Get the group filter option and add a filter for it.
    $groupOption = isset($options['g']) ? $options['g'] : null;
    // The filter will ignore the group value if it is empty.
    $filter->addGroupFilter($groupOption);

    // Note: master ips filter doesn't work yet.
    $masterIpsOption = isset($options['m']) ? $options['m'] : null;
    $filter->addMasterIpsFilter($masterIpsOption);

    // Set the filter in the Domain API.
    $domainsApi->setFilter($filter);
    /* END OPTIONAL filtering code. */

    // Call the function to get all the domains.
    // The DomainsApi class will utilize the filter object that was injected when calling the Linode endpoint.
    $domains = $domainsApi->getAll();
    // Output the domain data.
    print_r($domains);
} else {
    echo 'Error: LINODE_API_TOKEN not set.' . PHP_EOL;
    echo 'Please set the environment variable LINODE_API_TOKEN to the value of your Linode API token.' . PHP_EOL;
}