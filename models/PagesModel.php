<?php

class PagesModel {
    private pdo $pdo;

    public function __construct(pdo $pdo)
    {
        $this->pdo = $pdo;
    }


}