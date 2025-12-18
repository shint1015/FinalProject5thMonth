<?php

include_once __DIR__ . '/../../config/database.php';
include_once __DIR__ . '/../Repositories/CategoryRepository.php';
include_once __DIR__ . '/../Services/CategoryService.php';

class CategoryController {
    private CategoryService $service;

    public function __construct()
    {
        $this->service = new CategoryService(new CategoryRepository(db()));
    }

    public function list(): array {
        $list = $this->service->list();
        if (!$list) {
            return [["success" => false, "error" => "No categories found"], 404];
        }
        return [["success" => true, "data" => $list, "message" => "list of category"], 200];
    }

    public function show(int $id): array {
        $row = $this->service->find($id);
        if (!$row) return [["success" => false, "error" => "Category not found"], 404];
        return [["success" => true, "data" => $row, "message" => "category data"], 200];
    }

    public function create(): array {
        $json_data = json_decode(file_get_contents('php://input'), true);
        if (!is_array($json_data)) {
            return [[
                'success' => false,
                'error' => 'Invalid JSON'
            ], 400];
        }
        $name = trim(htmlspecialchars((string)($json_data['category_name'] ?? ''), ENT_QUOTES, 'UTF-8'));
        $sort = (int)($json_data['sort'] ?? 0);
        if ($name === '') return [["success" => false, "error" => "category_name required"], 400];
        $created = $this->service->create($name, $sort);
        if (!$created) return [["success" => false, "error" => "Category not created"], 400];
        return [["success" => true, "message" => "Category created successfully"], 201];
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
        if (isset($json_data['category_name'])) $fields['category_name'] = htmlspecialchars((string)$json_data['category_name'], ENT_QUOTES, 'UTF-8');
        if (isset($json_data['sort'])) $fields['sort'] = (int)$json_data['sort'] ?? "";
        if (empty($fields)) return [["success" => false, "error" => "no fields"], 400];
        $updated = $this->service->update($id, $fields);
        if (!$updated) return [["error" => "Category not found or not updated"], 404];
        return [["suceess" => true, "message" => "Category updated successfully"], 200];
    }

    public function delete(int $id): array {
        $ok = $this->service->delete($id);
        if (!$ok) {
            return [["success" => false, "error" => "Category not found or not deleted"], 404];
        }
        return [["success" => true, "message" => "Category deleted successfully"], 200];
    }
}
