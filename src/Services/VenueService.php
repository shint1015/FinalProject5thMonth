<?php
include_once __DIR__ . '/../Repositories/VenueRepository.php';

class VenueService {
    private VenueRepository $repo;

    public function __construct(VenueRepository $repo)
    {
        $this->repo = $repo;
    }

    public function list(): array {
        return $this->repo->list();
    }

    public function find(int $id): ?array {
        return $this->repo->find($id);
    }

    public function create(array $payload): ?array {
        $name = trim((string)($payload['name'] ?? ''));
        $capacity = (int)($payload['capacity'] ?? 0);
        if ($name === '' || $capacity <= 0) {
            return null;
        }
        return $this->repo->create([
            'name' => $name,
            'capacity' => $capacity,
            'seat_id_format' => $payload['seat_id_format'] ?? null,
            'notes' => $payload['notes'] ?? null,
            'layout' => $payload['layout'] ?? null,
        ]);
    }

    public function update(int $id, array $fields): ?array {
        return $this->repo->update($id, $fields);
    }

    public function delete(int $id): bool {
        return $this->repo->delete($id);
    }
}
