<?php


require_once __DIR__ . '/../Services/ShowsService.php';
require_once __DIR__ . '/../Repositories/ShowsRepository.php';

class ShowsController
{
    private ShowsService $service;

    public function __construct(){
        $this->service = new ShowsService(
            new ShowsRepository(db())
        );
    }

    // GET /shows
    public function list(): array
    {
        $shows = $this->service->getShows();

        return [[
            'success' => true,
            'data' => $shows,
            'message' => 'List of shows.'
        ], 200];
    }

    // GET /shows/{id}
    public function detail(int $id): array
    {
        $show = $this->service->getShow($id);

     if (!$show) {
        return [[
            'success' => false,
            'error' => 'Show not found.',
            'message' => 'The requested show does not exist.'
    ], 404];

        }

        return [[
            'success' => true,
            'data' => $show,
            'message' => 'Show details.'
        ], 200];
    }


/// sanitize string
private function sanitizeString(string $value): string
{
    return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
}

// numeric validation
private function validateInt($value): ?int
{
    $int = filter_var($value, FILTER_VALIDATE_INT);
    return $int !== false ? $int : null;
}

// date validation (YYYY-MM-DD)
private function validateDate(string $value, string $format = 'Y-m-d'): ?string
{
    $date = DateTime::createFromFormat($format, $value);
    return ($date && $date->format($format) === $value) ? $value : null;
}

// time validation (HH:MM)
private function validateTime(string $value): ?string
{
    return preg_match('/^\d{2}:\d{2}$/', $value) ? $value : null;
}



    // POST /shows
//     public function create(): array
// {
//     print_r($_POST);
//     return [[
//         'success' => false,
//         'debug' => [

//             '_POST' => $_POST,
//             '_REQUEST' => $_REQUEST,
//             'body' => file_get_contents('php://input')
//         ]
//     ], 200];
// }


    public function create(): array
{
    $data = json_decode(file_get_contents('php://input'), true);

    if (!is_array($data)) {
        return [[
            'success' => false,
            'error' => 'Invalid JSON body.'
        ], 400];
    }

    // validate & sanitize
    $title = $this->sanitizeString($data['title'] ?? '');
    $description = $this->sanitizeString($data['description'] ?? '');
    $category = $this->sanitizeString($data['category'] ?? '');
    $venue = $this->sanitizeString($data['venue'] ?? '');
    $city = $this->sanitizeString($data['city'] ?? '');
    $thumbnail = $this->sanitizeString($data['thumbnail'] ?? '');
    $status = $this->sanitizeString($data['status'] ?? '');

    $price = $this->validateInt($data['price'] ?? null);
    $capacity = $this->validateInt($data['capacity'] ?? null);
    $availableTickets = $this->validateInt($data['available_tickets'] ?? null);

    $date = $this->validateDate($data['date'] ?? '');
    $startTime = $this->validateTime($data['start_time'] ?? '');
    $endTime = $this->validateTime($data['end_time'] ?? '');

    // check
    if (
        empty($title) ||
        empty($date) ||
        empty($venue) ||
        $price === null
    ) {
        return [[
            'success' => false,
            'error' => 'Missing or invalid show data.'
        ], 400];
    }

    // Service call
    $cleanData = [
        'title' => $title,
        'description' => $description,
        'category' => $category,
        'date' => $date,
        'start_time' => $startTime,
        'end_time' => $endTime,
        'venue' => $venue,
        'city' => $city,
        'price' => $price,
        'thumbnail' => $thumbnail,
        'capacity' => $capacity,
        'available_tickets' => $availableTickets,
        'status' => $status
    ];

    $newId = $this->service->createShow($cleanData);

    if ($newId <= 0) {
        return [[
            'success' => false,
            'error' => 'Show not created.'
        ], 400];
    }

    return [[
        'success' => true,
        'data' => ['show_id' => $newId],
        'message' => 'Show created successfully.'
    ], 201];
}
// PUT /shows/{id}
public function update(int $id): array
{
    // id validation
    $id = $this->validateInt($id);
    if ($id === null) {
        return [[
            'success' => false,
            'error' => 'Invalid show ID'
        ], 400];
    }

    // 存在チェック
    if (!$this->service->getShow($id)) {
        return [[
            'success' => false,
            'error' => 'Show not found'
        ], 404];
    }

    // raw JSON
    $data = json_decode(file_get_contents('php://input'), true);
    if (!is_array($data)) {
        return [[
            'success' => false,
            'error' => 'Invalid JSON body'
        ], 400];
    }

    // validate & sanitize（id 以外すべて対象）
    $cleanData = [];

    if (isset($data['title'])) {
        $cleanData['title'] = $this->sanitizeString($data['title']);
    }
    if (isset($data['description'])) {
        $cleanData['description'] = $this->sanitizeString($data['description']);
    }
    if (isset($data['category'])) {
        $cleanData['category'] = $this->sanitizeString($data['category']);
    }
    if (isset($data['date'])) {
        $date = $this->validateDate($data['date']);
        if ($date === null) {
            return [[ 'success'=>false, 'error'=>'Invalid date' ], 400];
        }
        $cleanData['date'] = $date;
    }
    if (isset($data['start_time'])) {
        $time = $this->validateTime($data['start_time']);
        if ($time === null) {
            return [[ 'success'=>false, 'error'=>'Invalid start_time' ], 400];
        }
        $cleanData['start_time'] = $time;
    }
    if (isset($data['end_time'])) {
        $time = $this->validateTime($data['end_time']);
        if ($time === null) {
            return [[ 'success'=>false, 'error'=>'Invalid end_time' ], 400];
        }
        $cleanData['end_time'] = $time;
    }
    if (isset($data['venue'])) {
        $cleanData['venue'] = $this->sanitizeString($data['venue']);
    }
    if (isset($data['city'])) {
        $cleanData['city'] = $this->sanitizeString($data['city']);
    }
    if (isset($data['price'])) {
        $price = $this->validateInt($data['price']);
        if ($price === null) {
            return [[ 'success'=>false, 'error'=>'Invalid price' ], 400];
        }
        $cleanData['price'] = $price;
    }
    if (isset($data['thumbnail'])) {
        $cleanData['thumbnail'] = $this->sanitizeString($data['thumbnail']);
    }
    if (isset($data['capacity'])) {
        $cap = $this->validateInt($data['capacity']);
        if ($cap === null) {
            return [[ 'success'=>false, 'error'=>'Invalid capacity' ], 400];
        }
        $cleanData['capacity'] = $cap;
    }
    if (isset($data['available_tickets'])) {
        $avail = $this->validateInt($data['available_tickets']);
        if ($avail === null) {
            return [[ 'success'=>false, 'error'=>'Invalid available_tickets' ], 400];
        }
        $cleanData['available_tickets'] = $avail;
    }
    if (isset($data['status'])) {
        $cleanData['status'] = $this->sanitizeString($data['status']);
    }

    if (empty($cleanData)) {
        return [[
            'success' => false,
            'error' => 'No valid fields to update'
        ], 400];
    }

    // Service
    $updated = $this->service->updateShow($id, $cleanData);

    if (!$updated) {
        return [[
            'success' => false,
            'error' => 'Update failed'
        ], 400];
    }

    return [[
        'success' => true,
        'message' => 'Show updated successfully'
    ], 200];
}

      public function delete(int $id): array
    {
        if ($id <= 0) {
            return [['error' => 'Invalid ID'], 400];
        }

        $deleted = $this->service->deleteShow($id);

        if (!$deleted) {
            return [['error' => 'Show not found'], 404];
        }

        return [[
            'success' => true,
            'deleted_id' => $id
        ], 200];
    }
}


?>