<?php

class RepeaterFieldsController
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function index(): void
    {
        $repeaterFieldsModel = new RepeaterFieldsModel($this->pdo);
        $repeaterFields = $repeaterFieldsModel->getAll();
        http_response_code(200);
        echo json_encode($repeaterFields);
    }

    public function show(int $id): void
    {
        $repeaterFieldsModel = new RepeaterFieldsModel($this->pdo);
        $repeaterFields = $repeaterFieldsModel->getById($id);
        http_response_code(200);
        echo json_encode($repeaterFields);
    }

    public function store(array $data): void
    {
        authMiddleware();
        validateRequiredFields($data, ["title","repeater_id", "field_key", "value", "type"]);
        $data["type"] = strtolower($data["type"]);
        typeValidation($data["type"]);
        $repeaterFieldsModel = new RepeaterFieldsModel($this->pdo);
        $id = $repeaterFieldsModel->create($data);
        http_response_code(200);
        echo json_encode(["message" => "Repeater field created.", "id" => $id]);
    }

    public function update(int $id,array $data): void
    {
        authMiddleware();
        validateRequiredFields($data, ["title", "field_key", "value", "type"]);
        $data["type"] = strtolower($data["type"]);
        typeValidation($data["type"]);
        $repeaterFieldsModel = new RepeaterFieldsModel($this->pdo);
        $existingRepeaterField = $repeaterFieldsModel->getById($id);
        if(!$existingRepeaterField) {
            http_response_code(404);
            echo json_encode(["message" => "Repeater field not found."]);
            exit();
        }
        $repeaterFieldsModel->update($id, $data);
        http_response_code(200);
        echo json_encode(["message" => "Repeater field updated successfully."]);
    }

    public function remove(int $id): void
    {
        authMiddleware();
        $repeaterFieldsModel = new RepeaterFieldsModel($this->pdo);
        $repeaterFields = $repeaterFieldsModel->getById($id);
        if(!$repeaterFields) {
            http_response_code(404);
            echo json_encode(["message" => "Repeater field not found."]);
            exit();
        }
        $repeaterFieldsModel->delete($id);
        http_response_code(200);
        echo json_encode(["message" => "Repeater field deleted successfully."]);
    }
}