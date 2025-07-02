<?php

class FieldsController
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function index(): void
    {
        $fieldsModel = new FieldsModel($this->pdo);
        $fields = $fieldsModel->getAll();
        if (empty($fields)) {
            http_response_code(404);
            echo json_encode(["message" => "No fields found."]);
            exit();
        }
        http_response_code(200);
        echo json_encode($fields);
    }

    public function show($id): void
    {
        $fieldsModel = new FieldsModel($this->pdo);
        $field = $fieldsModel->getFieldById($id);
        http_response_code(200);
        echo json_encode($field);
    }

    public function store($data): void
    {
        authMiddleware();
        validateRequiredFields($data, ["title", "page_id", "type", "field_key"]);

        $fieldsModel = new FieldsModel($this->pdo);
        $pagesModel = new PagesModel($this->pdo);

        $page = $pagesModel->getById($data['page_id']);
        if (empty($page)) {
            http_response_code(404);
            echo json_encode(["message" => "Page not found."]);
            exit();
        }

        $existField = $fieldsModel->getFieldByKey($data['field_key']);

        if(!empty($existField)) {
            http_response_code(400);
            echo json_encode(["message" => "Field already exist."]);
            exit();
        }

        $fieldsModel->createField($data);
        http_response_code(200);
        echo json_encode(["message" => "Field created."]);
    }


}