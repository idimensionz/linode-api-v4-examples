<?php
/*
 * iDimensionz/{linode-api-v4-examples}
 * kernels-get.php
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
use iDimensionz\LinodeApiV4\Api\Linode\Kernels\KernelsApi;
use iDimensionz\LinodeApiV4\Api\Linode\Kernels\KernelFilter;

// Setup the HTTP client. No API token in the header for this endpoint.
$httpClient = new GuzzleHttpClient();
$kernelsApi = new KernelsApi($httpClient);

/* OPTIONAL: Setup a filter for the Kernels API. */
// Create a KernelsApi specific filter object which has functions for setting filters on the fields which are
// filterable.

$filter = new KernelFilter();

// Check for options.
// -z for xen, -k for kvm, -l for label, -v for version, -x for x64, -c for current, -d for deprecated
$options = getopt('z:k:l:v:x:c:d:i:');

// Get the Xen filter option and add a filter for it.
if (isset($options['z'])) {
    $filter->addXenFilter($options['z']);
}

// Get the KVM filter option and add a filter for it.
if (isset($options['k'])) {
    $filter->addKvmFilter($options['k']);
}

// Get the label filter option and add a filter for it.
$labelOption = isset($options['l']) ? $options['l'] : null;
// The filter will ignore the label option if it is empty.
$filter->addLabelFilter($labelOption);

// Get the version filter option and add a filter for it.
$versionOption = isset($options['v']) ? $options['v'] : null;
// The filter will ignore the version option if it is empty.
$filter->addVersionFilter($versionOption);

// Get the x64 filter option and add a filter for it.
if (isset($options['x'])) {
    $filter->addX64Filter($options['x']);
}

// Get the current filter option and add a filter for it.
if (isset($options['c'])) {
    $filter->addCurrentFilter($options['c']);
}

// Get the deprecated filter option and add a filter for it.
if(isset($options['d'])) {
    // The filter will cast the deprecated value to a bool.
    $filter->addDeprecatedFilter((int) $options['d']);
}

// Set the filter in the Domain API.
$kernelsApi->setFilter($filter);
/* END OPTIONAL filtering code. */

// Call the getById() endpoint if an ID was supplied.
if (isset($options['i'])) {
    $kernelModels = $kernelsApi->getById($options['i']);
} else {
    // Otherwise, call the function to get all the kernels.
    // The KernelsApi class will utilize the filter object that was injected when calling the Linode endpoint.
    $kernelModels = $kernelsApi->getAll();
}

print_r($kernelModels);