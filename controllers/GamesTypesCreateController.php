<?php
require_once "BaseGameTwigController.php";

class GamesTypesCreateController extends BaseGameTwigController {
    public $template = "game_type_create.twig";
    

    public function get(array $context) // добавили параметр
    {
        echo $_SERVER['REQUEST_METHOD'];
        
        parent::get($context); // пробросили параметр
    }

    public function post(array $context) { // добавили параметр
        $context['message'] = 'Вы успешно создали объект'; // добавили сообщение
        $title = $_POST['title'];
        //$description = $_POST['description'];
        //$type = $_POST['type'];
        //$info = $_POST['info'];

       // вытащил значения из $_FILES
        $tmp_name = $_FILES['image']['tmp_name'];
        $name =  $_FILES['image']['name'];
        
        // используем функцию которая проверяет
        // что файл действительно был загружен через POST запрос
        // и если это так, то переносит его в указанное во втором аргументе место
        move_uploaded_file($tmp_name, "../public/media/$name");

        $tmp_name = $_FILES['image']['tmp_name'];
        $name =  $_FILES['image']['name'];
        move_uploaded_file($tmp_name, "../public/media/$name");
        $image_url = "/media/$name"; // формируем ссылку без адреса сервера

        $sql = <<<EOL
INSERT INTO games_types(name, image)
VALUES(:title, :image_url)
EOL;

        $query = $this->pdo->prepare($sql);
        $query->bindValue("title", $title);
        //$query->bindValue("description", $description);
        //$query->bindValue("type", $type);
        //$query->bindValue("info", $info);
        $query->bindValue("image_url", $image_url); // подвязываем значение ссылки к переменной  image_url
        $query->execute();
        

        // а дальше как обычно
        $context['message'] = 'Вы успешно создали объект';
        $context['id'] = $this->pdo->lastInsertId();

        $this->get($context);
    }
}