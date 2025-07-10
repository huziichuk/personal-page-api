<?php

class RepeatersController
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function index(): void
    {
        $repeatersModel = new RepeatersModel($this->pdo);
        $repeaters = $repeatersModel->getAll();
        http_response_code(200);
        echo json_encode($repeaters);
    }

    public function store(array $data): void
    {
        authMiddleware();
        validateRequiredFields($data, ["page_id", "repeater_key", "title"]);
        $repeatersModel = new RepeatersModel($this->pdo);
        $insertedId = $repeatersModel->create($data);
        http_response_code(200);
        echo json_encode([
            "message"=> "Repeater created successfully.",
            "id"=> $insertedId
        ]);
    }
    public function show(int $id): void
    {
        $repeatersModel = new RepeatersModel($this->pdo);
        $repeater = $repeatersModel->getById($id);
        if (empty($repeater)) {
            http_response_code(404);
            echo json_encode(["message" => "Repeater not found."]);
            exit();
        }
        http_response_code(200);
        echo json_encode($repeater);
    }

    public function update(int $id, array $data): void
    {
        authMiddleware();
        validateRequiredFields($data, ["repeater_key"]);
        $repeatersModel = new RepeatersModel($this->pdo);
        $repeater = $repeatersModel->getById($id);
        if (empty($repeater)) {
            http_response_code(404);
            echo json_encode(["message" => "Repeater not found."]);
            exit();
        }

        $repeatersModel->update($data);
        http_response_code(200);
    }

    public function delete(int $id): void
    {
        authMiddleware();

    }
}