<?php

$url = "http://stg-svcs.provinciamicroempresas.com/crmservices/apicrm/SiteVisitsController/getsitevisitsbyuser/DF26AC9";

$options = [
    "http" => [
        "method" => "GET",
        "header" => "Accept: application/json\r\n" .
         "Authorization: bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1bmlxdWVfbmFtZSI6Im1rcGx1cyIsImlzcyI6Imh0dHBzOi8vc3RnLXN2Y3MucHJvdmluY2lhbWljcm9lbXByZXNhcy5jb20iLCJhdWQiOiI0MTRlMTkyN2EzODg0ZjY4YWJjNzlmNzI4MzgzN2ZkMSIsImV4cCI6MTUwMDA0NjMwOSwibmJmIjoxNDk5OTU5OTA5fQ.tYzZ6NcaWUPPEWzSou83HIcCuQkfJ6r6iFvE8cjGR-E \r\n"
    ]
];

var_dump(file_get_contents($url,false,stream_context_create($options)));