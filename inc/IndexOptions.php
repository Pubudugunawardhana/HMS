<?php

class IndexOptions{
    private $conn;

    public function __construct(mysqli $db) {
        $this->conn = $db;
    }

    public function getLatestDineIn(): string {
        $result = select("SELECT * FROM dine_in_options ORDER BY id DESC LIMIT 3", [], '');
        $rows = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return json_encode($rows);
    }

    public function getLatestExperience(): string {
        $result = select("SELECT * FROM experience_options ORDER BY id DESC LIMIT 3", [], '');
        $rows = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return json_encode($rows);
    }

}