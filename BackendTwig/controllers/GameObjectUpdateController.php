<?php
require_once "BaseGameTwigController.php";

class GameObjectUpdateController extends BaseGameTwigController {
    public $template = "game_object_update.twig";
    

    public function get(array $context) // добавили параметр
    {
        $id = $this->params['id'];

        $sql = <<<EOL
SELECT * FROM games_objects WHERE id = :id
EOL;
        $query = $this->pdo->prepare($sql);
        $query->bindValue("id",$id);
        $query->execute();

        $data = $query ->fetch();

        $context['object'] = $data;

        parent::get($context); // пробросили параметр
        
        
    }

    public function post(array $context) { // добавили параметр
        $id = $this->params['id'];
        $context['message'] = 'Вы успешно изменили объект'; // добавили сообщение
        $title = $_POST['title'];
        $description = $_POST['description'];
        $type = $_POST['type'];
        $info = $_POST['info'];


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



        if(isset($name) && $name!=''){
                    $sql = <<<EOL
UPDATE games_objects SET title = :title, description = :description, type = :type, info = :info, image = :image_url
WHERE id=:id
EOL;
        }

        


        $query = $this->pdo->prepare($sql);
        $query->bindValue("id", $id);
        $query->bindValue("title", $title);
        $query->bindValue("description", $description);
        $query->bindValue("type", $type);
        $query->bindValue("info", $info);
        $query->bindValue("image_url", $image_url); // подвязываем значение ссылки к переменной  image_url
        $query->execute();
        
        // а дальше как обычно
        $context['message'] = 'Вы успешно изменили объект';
        $context['id'] = $this->pdo->lastInsertId();

        $this->get($context);
    }
}