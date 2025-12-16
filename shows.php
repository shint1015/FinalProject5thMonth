<?php
//shows API
$data = file_get_contents(__DIR__ , "/../data/shows.json");
$shows = jsondecode($data, true);
echo json_encode([
    "success" => true,
    "data" => $shows
])
?>