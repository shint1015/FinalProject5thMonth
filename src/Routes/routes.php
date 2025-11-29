<?php
return [
    ['GET', '/', 'HomeController@get'],
    ['POST', '/','HomeController@create'],
    ['PUT', '/','HomeController@update'],
    ['DELETE', '/','HomeController@delete'],
    ['GET', '/show/status/{id}', 'ShowStatusController@showStatus'],
    ['GET', '/show/statuses', 'ShowStatusController@listStatuses'],
    ['POST', '/show/status', 'ShowStatusController@createStatus'],
    ['PUT', '/show/status/{id}', 'ShowStatusController@updateStatus'],
    ['DELETE', '/show/status/{id}', 'ShowStatusController@deleteStatus'],
];
