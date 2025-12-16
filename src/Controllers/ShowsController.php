<?php
class ShowsController {
    public function list(): array {
        return [['message' => 'list called'], 200];
    }
    public function detail(int $id): array {
        return [['message' => "detail {$id}"], 200];
    }
    public function create(): array {
        return [['message' => 'create called'], 201];
    }
}
