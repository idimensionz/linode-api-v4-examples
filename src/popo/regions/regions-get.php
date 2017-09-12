<?php
/*
 * iDimensionz/{linode-api-v4-examples}
 * regions-get.php
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

use iDimensionz\LinodeApiV4\Api\Regions\RegionsApi;
use iDimensionz\LinodeApiV4\Api\Regions\RegionFilter;

/**
 * This file shows how to call the Regions GET endpoint to get a list of regions back from Linode.
 * It also shows how to use the filter to reduce the number of regions returned.
 */

require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/vendor/autoload.php');

use iDimensionz\HttpClient\Guzzle\GuzzleHttpClient;

// Setup the HTTP client. No API token in the header for this endpoint.
$httpClient = new GuzzleHttpClient();
$regionsApi = new RegionsApi($httpClient);

/* OPTIONAL: Setup a filter for the Regions API. */
// Create a RegionsApi specific filter object which has functions for setting filters on the fields which are
// filterable.

$filter = new RegionFilter();

// Check for options.
// -l for label, -c for country, -i for ID
$options = getopt('l:c:i:');

// @todo Figure out why the label filter AND country filter do not work.

// Get the label filter option and add a filter for it.
$labelOption = isset($options['l']) ? $options['l'] : null;
// The filter will ignore the label option if it is empty.
$filter->addLabelFilter($labelOption);

// Get the country filter option and add a filter for it.
$countryOption = isset($options['c']) ? $options['c'] : null;
// The filter will ignore the label option if it is empty.
$filter->addCountryFilter($countryOption);

// Set the filter in the Regions API.
$regionsApi->setFilter($filter);

/* END OPTIONAL filtering code. */


// Call the getById() endpoint if an ID was supplied.
if (isset($options['i'])) {
    $regionModels = $regionsApi->getById($options['i']);
} else {
    // Otherwise, call the function to get all the types.
    // The TypesApi class will utilize the filter object that was injected when calling the Linode endpoint.
    $regionModels = $regionsApi->getAll();
}

print_r($regionModels);
