<?php

namespace Linkedout\App\controllers\api;

use Exception;
use PDO;

abstract class ApiController
{
    protected PDO $database;
    protected int $responseCode = 200;

    public function __construct(PDO $database)
    {
        $this->database = $database;
    }

    /**
     * Render the JSON string
     * @return string The JSON string
     */
    public function render(): string
    {
        try {
            $data = [
                'data' => $this->fetch(),
                'error' => null,
            ];
        } catch (Exception $e) {
            http_response_code($this->responseCode);
            $data = [
                'data' => null,
                'error' => $e->getMessage(),
            ];
        }

        header('Content-Type: application/json');
        return json_encode($data, JSON_PRETTY_PRINT);
    }

    /**
     * @return array The data to be returned
     * @throws Exception The error to be displayed
     */
    abstract protected function fetch(): array;
}
