<?php
/*
 * iDimensionz/{linode-api-v4-examples}
 * distributions-get.php
 *
 * The MIT License (MIT)
 *
 * Copyright (c) 2017 iDimensionz
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
 * This file shows how to call the Distributions GET endpoint to get a list of distributions back from Linode.
 */

require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/vendor/autoload.php');

use iDimensionz\HttpClient\Guzzle\GuzzleHttpClient;
use iDimensionz\LinodeApiV4\Api\Linode\Distributions\DistributionsApi;
use iDimensionz\LinodeApiV4\Api\Linode\Distributions\DistributionFilter;

// Setup the HTTP client. No API token in the header for this endpoint.
$httpClient = new GuzzleHttpClient();
// Instantiate a DistributionsApi injecting the HTTP client.
$distributionsApi = new DistributionsApi($httpClient);

/* OPTIONAL: Setup a filter for the Distributions API. */
// Create a DistributionsApi specific filter object which has functions for setting filters on the fields which are
// filterable.
$filter = new DistributionFilter();

// Check for filter options.
// -l for label, -m for minimum storage size, -d for deprecated, and -v for vendor
$options = getopt('i:l:m:d:v:');

// Get the label filter option and add a filter for it.
$labelOption = isset($options['l']) ? $options['l'] : null;
// The filter will ignore the label option if it is empty.
$filter->addLabelFilter($labelOption);

// Get the deprecated filter option and add a filter for it.
if(isset($options['d'])) {
    $deprecatedOption = $options['d'];
    // The filter will cast the deprecated value to a bool.
    $filter->addDeprecatedFilter($deprecatedOption);
}

if (isset($options['m'])) {
    $minimumStorageSizeOption = $options['m'];
    // The filter will cast the value to an int.
    $filter->addMinimumStorageSizeFilter($minimumStorageSizeOption);
}

// Get the vendor filter option and add a filter for it.
$vendorOption = isset($options['v']) ? $options['v'] : null;
// The filter will ignore the vendor option if it is empty;
$filter->addVendorFilter($vendorOption);

// Set the filter in the Domain API.
$distributionsApi->setFilter($filter);
/* END OPTIONAL filtering code. */

// Call the getById() endpoint if an ID was supplied.
if (isset($options['i'])) {
    $distributionModels = $distributionsApi->getById($options['i']);
} else {
    // Otherwise, call the function to get all the distributions.
    // The DistributionsApi class will utilize the filter object that was injected when calling the Linode endpoint.
    $distributionModels = $distributionsApi->getAll();
}
// Output the domain data.
print_r($distributionModels);
