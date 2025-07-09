<?php

class RepeaterFieldsModel {
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `repeater_fields`");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById(int $id): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `repeater_fields` WHERE `id` = :id");
        $stmt->execute([
            'id' => $id
        ]);
        return $stmt->fetch();
    }

    public function create(array $data): int
    {
        $sql = "INSERT INTO repeater_fields (title,repeater_id, field_key, value, type) VALUES (:title,:repeater_id, :field_key, :value, :type)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'title' => $data['title'],
            'repeater_id' => $data['repeater_id'],
            'field_key' => $data['field_key'],
            'value' => $data['value'],
            'type' => $data['type'],
        ]);
        return $this->pdo->lastInsertId();
    }

    public function update(int $id,array $data): void
    {
        $sql = "UPDATE repeater_fields SET title=:title,field_key=:field_key, value=:value, type=:type, sort_order=:sort_order WHERE id=:id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'title' => $data['title'],
            'field_key' => $data['field_key'],
            'value' => $data['value'],
            'type' => $data['type'],
            'sort_order' => $data['sort_order'],
            'id' => $id
        ]);
    }
    public function delete(int $id): void
    {
        $sql = "DELETE FROM `repeater_fields` WHERE `id` = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }
}
