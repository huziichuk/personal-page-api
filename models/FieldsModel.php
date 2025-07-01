<?php

class FieldsModel {
    private pdo $pdo;

    public function __construct(pdo $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getPageFields($page_id) : array
    {
        $sql = "SELECT * FROM `fields` WHERE `page_id` = :page_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':page_id', $page_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function createField(array $data): void
    {
        $sql = "INSERT INTO `fields` (page_id, field_key, value, type, title) VALUES (:page_id, :field_key, :value, :type, :title)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'page_id' => $data['page_id'],
            'field_key' => $data['field_key'],
            'value' => $data['value'],
            'type' => $data['type'],
            'title' => $data['title']
        ]);
    }

    public function updateField(array $data): void
    {
        $sql = "UPDATE `fields` SET `value` = :value, `type` = :type, `field_key` = :field_key WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'value' => $data['value'],
            'type' => $data['type'],
            'id' => $data['id'],
            'field_key' => $data['field_key'],
        ]);
    }
    public function deleteField($id): void
    {
        $sql = "DELETE FROM `fields` WHERE `id` = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
    }

    public function getFieldById($id) : array
    {
        $sql = "SELECT * FROM `fields` WHERE `id` = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }
}