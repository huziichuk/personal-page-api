<?php

class RepeatersModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo){
        $this->pdo = $pdo;
    }

    public function getPageRepeaters($page_id) : array
    {
        $stmt = $this->pdo->prepare("SELECT id, repeater_key, sort_order FROM repeaters WHERE page_id = :page_id ORDER BY sort_order, id");
        $stmt->execute(['page_id' => $page_id]);
        return $stmt->fetchAll();
    }
}