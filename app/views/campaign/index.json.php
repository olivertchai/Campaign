<?php

$campaignsToJson = [];

foreach ($campaigns as $campaign) {
    $campaignsToJson[] = ['id' => $campaign->id, 'title' => $campaign->title];
}

header('Content-Type: application/json');

$json['campaigns'] = $campaignsToJson;
$json['pagination'] = [
    'page'                       => $paginator->getPage(),
    'per_page'                   => $paginator->perPage(),
    'total_of_pages'             => $paginator->totalOfPages(),
    'total_of_registers'         => $paginator->totalOfRegisters(),
    'total_of_registers_of_page' => $paginator->totalOfRegistersOfPage(),
];
echo json_encode($json);