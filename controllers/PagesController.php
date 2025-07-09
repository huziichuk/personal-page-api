<?php

class PagesController
{
    private pdo $pdo;

    public function __construct(pdo $pdo)
    {
        $this->pdo = $pdo;
    }

    public function index():void
    {
        $pagesModel = new PagesModel($this->pdo);
        $pages = $pagesModel->getAll();
        echo json_encode($pages);
    }

    public function indexWithFields():void
    {
        $pagesModel = new PagesModel($this->pdo);
        $pages = $pagesModel->getAllWithFields();
        echo json_encode($pages);
    }


    public function store(array $data):void
    {
        authMiddleware();
        validateRequiredFields($data, ["title", "slug"]);

        $pagesModel = new PagesModel($this->pdo);

        $existingPage = $pagesModel->get($data['slug']);
        if($existingPage) {
            http_response_code(400);
            echo json_encode(["message" => "Page already exists"]);
            return;
        }

        $pagesModel->create($data);
        http_response_code(20);
    }

    public function show(string $slug): void
    {
        $pagesModel = new PagesModel($this->pdo);
        $pages = $pagesModel->get($slug);
        if (empty($pages)) {
            http_response_code(404);
            exit();
        }
        echo json_encode($pages);
    }

    public function update(string $slug, array $data):void
    {

    }
}