<?php
// класс абстрактный, чтобы нельзя было создать экземпляр
abstract class BaseController {
    public PDO $pdo;
    public array $params; // добавил поле
    
    // добавил сеттер
    public function setParams(array $params) {
        $this->params = $params;
    }

    public function setPDO(PDO $pdo) { // и сеттер для него
        $this->pdo = $pdo;
    }

    // так как все вертится вокруг данных, то заведем функцию,
    // которая будет возвращать контекст с данными
    public function getContext(): array {
        return []; // по умолчанию пустой контекст
    }
    
    // новая функция
    public function process_response() {
        $method = $_SERVER['REQUEST_METHOD'];
        $context = $this->getContext(); // вызываю context тут
        if ($method == 'GET') {
            $this->get($context); // а тут просто его пробрасываю внутрь
        } else if ($method == 'POST') {
            $this->post($context); // и здесь
        }
    }
    

    public function get(array $context) {} // ну и сюда добавил в качестве параметра 
    public function post(array $context) {} // и сюда


}