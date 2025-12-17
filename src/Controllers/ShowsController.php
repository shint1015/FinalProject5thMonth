<?php

require_once __DIR__ . '/../Services/ShowsService.php';
require_once __DIR__ . '/../Repositories/ShowsRepository.php';
require_once __DIR__ . '/../config/database.php';

class ShowsController
{
    private ShowsService $service;

    public function __construct()
    {
        // Controller only for assembling Service
        $this->service = new ShowsService(
            new ShowsRepository(db())
        );
    }

    // GET /shows
    
    public function list(): array
    {
        $shows = $this->service->getShows();
        return [['data' => $shows], 200];
    }
    //method detail
    //GET /shows/id
    public function detail(int $id): array
    {
        $show = $this->service->getShow($id);

        if (!$show) {
            return [['error' => 'Show not found'], 404];
        }

        return [['data' => $show], 200];
    }

    // POST/shows
    
    public function create(): array
    {
        // get JSON body
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);

        if (!$data || !is_array($data)) {
            return [['error' => 'Invalid JSON body'], 400];
        }

        $newId = $this->service->createShow($data);

        if ($newId <= 0) {
            return [['error' => 'Show not created'], 400];
        }

        return [['data' => ['show_id' => $newId]], 201];
    }
}
?>