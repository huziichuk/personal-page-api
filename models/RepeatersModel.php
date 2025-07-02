<?php

class RepeatersModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getPageRepeaters($page_id): array
    {
        $stmt = $this->pdo->prepare("SELECT id, repeater_key, sort_order FROM repeaters WHERE page_id = :page_id ORDER BY sort_order, id");
        $stmt->execute(['page_id' => $page_id]);
        return $stmt->fetchAll();
    }

    public function create(array $data): void
    {
        $sql = "INSERT INTO repeaters (page_id, repeater_key) VALUES (:page_id, :repeater_key)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'page_id' => $data['page_id'],
            'repeater_key' => $data['repeater_key']
        ]);
    }

    public function update(array $data): void
    {
        $sql = "UPDATE repeaters SET repeater_key = :repeater_key WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id' => $data['id'],
            'repeater_key' => $data['repeater_key']
        ]);
    }

    public function getAll(): array
    {
        $sql = "SELECT * FROM repeaters";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById(int $id): array
    {
        $sql = "SELECT * FROM repeaters WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function delete(int $id): void
    {
        $sql = "DELETE FROM repeaters WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id' => $id
        ]);
    }
}