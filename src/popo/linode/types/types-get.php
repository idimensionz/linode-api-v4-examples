<?php
/*
 * iDimensionz/{linode-api-v4-examples}
 * types-get.php
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
 * This file shows how to call the Kernels GET endpoint to get a list of distributions back from Linode.
 */

require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/vendor/autoload.php');

use iDimensionz\HttpClient\Guzzle\GuzzleHttpClient;
use iDimensionz\LinodeApiV4\Api\Linode\Types\TypesApi;
use iDimensionz\LinodeApiV4\Api\Linode\Types\Filters\TypeFilter;

// Setup the HTTP client. No API token in the header for this endpoint.
$httpClient = new GuzzleHttpClient();
$typesApi = new TypesApi($httpClient);

/* OPTIONAL: Setup a filter for the Types API. */
// Create a TypesApi specific filter object which has functions for setting filters on the fields which are
// filterable.

$filter = new TypeFilter();

// Check for options.
// -s for storage, -h for hourly price, -l for label, -b for bandwidth, -m for monthly price, -r for ram, -t for transfer, -c for cpus, -i for ID
$options = getopt('s:h:l:b:m:r:t:c:i:');

// Get the storage filter option and add a filter for it.
// @todo Figure out why the storage filter does not seem to work.
if (isset($options['s'])) {
    $filter->addStorageFilter($options['s']);
}

// Get the hourly price filter option and add a filter for it.
if (isset($options['h'])) {
    // @todo Create an hourly price filter.
    //    $filter->addHourlyPriceFilter($options['h']);
}

// Get the label filter option and add a filter for it.
$labelOption = isset($options['l']) ? $options['l'] : null;
// The filter will ignore the label option if it is empty.
$filter->addLabelFilter($labelOption);

// Get the outbound bandwidth filter option and add a filter for it.
if (isset($options['b'])) {
    $filter->addOutboundBandwidthFilter($options['b']);
}

// Get the monthly price filter option and add a filter for it.
if (isset($options['m'])) {
    $filter->addMonthlyPriceFilter($options['m']);
}

// Get the RAM filter option and add a filter for it.
if (isset($options['r'])) {
    $filter->addRamFilter($options['r']);
}

// Get the outbound transfer filter option and add a filter for it.
if(isset($options['t'])) {
    // The filter will cast the deprecated value to a bool.
    $filter->addOutboundTransferFilter($options['t']);
}

// Get the outbound CPUs filter option and add a filter for it.
if(isset($options['c'])) {
    $filter->addCpuCoreCountFilter($options['c']);
}

// Set the filter in the Domain API.
$typesApi->setFilter($filter);

/* END OPTIONAL filtering code. */

// Call the getById() endpoint if an ID was supplied.
if (isset($options['i'])) {
    $typeModels = $typesApi->getById($options['i']);
} else {
    // Otherwise, call the function to get all the types.
    // The TypesApi class will utilize the filter object that was injected when calling the Linode endpoint.
    $typeModels = $typesApi->getAll();
}

print_r($typeModels);