<?php
namespace App\Controllers;
require_once __DIR__ . '/../Helpers/response.php';

class HomeController
{
    public function get()
    {
        return json_response(['message' => 'Welcome to the Home Page']);
    }

    public function create() {
        // Implementation for create action
        return json_response(['message'=> 'create']);

    }

    public function update() {
        return json_response(['message'=> 'update']);
    }


    public function delete() {
        return json_response(['message'=> 'delete']);
    }
}