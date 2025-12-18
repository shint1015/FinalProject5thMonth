<?php
include_once __DIR__ . '/../Repositories/CategoryRepository.php';

class CategoryService {
    private CategoryRepository $repo;

    public function __construct(CategoryRepository $repo)
    {
        $this->repo = $repo;
    }

    public function list(): array {
        return $this->repo->list();
    }

    public function find(int $id): ?array {
        return $this->repo->find($id);
    }

    public function create(string $name, int $sort = 0): ?array {
        $name = trim($name);
        if ($name === '') return null;
        return $this->repo->create(['category_name' => $name, 'sort' => $sort]);
    }

    public function update(int $id, array $fields): ?array {
        return $this->repo->update($id, $fields);
    }

    public function delete(int $id): bool {
        return $this->repo->delete($id);
    }
}
