<?php
declare(strict_types=1);

require_once __DIR__ . '/../Repositories/ShowsRepository.php';

class ShowsService
{
    private ShowsRepository $repo;

    public function __construct(ShowsRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getShows(): array
    {
        return $this->repo->findAll();
    }

    public function getShow(int $id): ?array
    {
        return $this->repo->findById($id);
    }

    public function createShow(array $data): int
    {
        if (empty($data['title']) || empty($data['date'])) {
            return 0;
        }

        return $this->repo->insert($data);
    }

    public function updateShow(int $id, array $data): bool
    {
        

        return $this->repo->update($id, $data);
    }

    public function deleteShow(int $id): bool
    {
    return $this->repo->deleteById($id);
    }

}
?>