<?php

include_once __DIR__ . '/../../config/database.php';
include_once __DIR__ . '/../Repositories/VenueRepository.php';
include_once __DIR__ . '/../Services/VenueService.php';

class VenueController {
    private VenueService $service;

    public function __construct()
    {
        $this->service = new VenueService(new VenueRepository(db()));
    }

    public function list(): array {
        $list = $this->service->list();
        if (!$list) {
            return [["success" => false, "error" => "No venues found"], 404];
        }
        return [["success" => true, "data" => $list, "message" => "list of venue"], 200];
    }

    public function show(int $id): array {
        $v = $this->service->find($id);
        if (!$v) return [["success" => false, "error" => "Venue not found"], 404];
        return [["success" => true, "data" => $v, "message" => "venue data"], 200];
    }

    public function create(): array {
        $json_data = json_decode(file_get_contents('php://input'), true);
        if (!is_array($json_data)) {
            return [[
                'success' => false,
                'error' => 'Invalid JSON'
            ], 400];
        }

        $data = [
            'name' => trim(htmlspecialchars((string)($json_data['name'] ?? ''), ENT_QUOTES, 'UTF-8')),
            'capacity' => (int)($json_data['capacity'] ?? 0),
            'seat_id_format' => isset($json_data['seat_id_format']) ? htmlspecialchars($json_data['seat_id_format'], ENT_QUOTES, 'UTF-8') : null,
            'notes' => isset($json_data['notes']) ? htmlspecialchars($json_data['notes'], ENT_QUOTES, 'UTF-8') : null,
            'layout' => $json_data['layout'] ?? null,
            'address' => isset($json_data['address']) ? htmlspecialchars($json_data['address'], ENT_QUOTES, "UTF-8") : null,
        ];
        if ($data['name'] === '' || $data['capacity'] <= 0) {
            return [["success" => false, "error" => "name and positive capacity are required"], 400];
        }
            $created = $this->service->create($data);
        if (!$created) return [["success" => false, "error" => "Venue not created"], 400];
        return [["success" => true, "message" => "Venue created successfully"], 201];
    }

    public function update(int $id): array {
        $json_data = json_decode(file_get_contents('php://input'), true);
        if (!is_array($json_data)) {
            return [[
                'success' => false,
                'error' => 'Invalid JSON'
            ], 400];
        }
        $fields = [];
        foreach (['name','capacity','seat_id_format','notes','layout', 'address'] as $k) {
            if (isset($json_data[$k])) {
                $fields[$k] = in_array($k, ['seat_id_format','notes','name'])
                    ? htmlspecialchars((string)$json_data[$k], ENT_QUOTES, 'UTF-8')
                    : $json_data[$k];
            }
        }
        if (empty($fields)) return [["success" => false, "error" => "no fields"], 400];
        $updated = $this->service->update($id, $fields);
        if (!$updated) return [["success" => false, "error" => "Venue not found or not updated"], 404];
        return [["success" => true, "message" => "Venue updated successfully"] , 200];
    }

    public function delete(int $id): array {
        $ok = $this->service->delete($id);
        if (!$ok) {
            return [["success" => false, "error" => "Venue not found or not deleted"], 404];
        }
        return [["success" => true, "message" => "Venue deleted successfully"], 200];
    }
}
