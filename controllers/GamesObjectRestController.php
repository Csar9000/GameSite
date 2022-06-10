<?php

require_once "../controllers/BaseRestController.php";

class GamesObjectRestController extends BaseRestController {
    function list(){
        $query = $this-> pdo ->query("SELECT * FROM games_objects");
        $query->execute();
        $data = $query->fetchAll();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    function retrieve(){
        $query = $this->pdo->prepare("SELECT * FROM games_objects WHERE id=:my_id");
        $query->bindValue("my_id",$this->params['id']);
        $query->execute();

        $data = $query->fetch();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    function create(){
        echo $_SERVER['REQUEST_METHOD'];
        

        $data = file_get_contents("php://input");
        $data = json_decode($data, true);
        $context['message'] = 'Вы успешно создали объект'; // добавили сообщение
        $title = $data['title'];
        $description = $data['description'];
        $type = $data['type'];
        $info = $data['info'];
        $image_url = $data['image_url'];

    //    // вытащил значения из $_FILES
    //     $tmp_name = $_FILES['image']['tmp_name'];
    //     $name =  $_FILES['image']['name'];
        
    //     // используем функцию которая проверяет
    //     // что файл действительно был загружен через POST запрос
    //     // и если это так, то переносит его в указанное во втором аргументе место
    //     move_uploaded_file($tmp_name, "../public/media/$name");

    //     $tmp_name = $_FILES['image']['tmp_name'];
    //     $name =  $_FILES['image']['name'];
    //     move_uploaded_file($tmp_name, "../public/media/$name");
    //     $image_url = "/media/$name"; // формируем ссылку без адреса сервера

        $sql = <<<EOL
INSERT INTO games_objects(title, description, type, info, image)
VALUES(:title, :description, :type, :info, :image_url) -- передаем переменную в запрос
EOL;

        $query = $this->pdo->prepare($sql);
        $query->bindValue("title", $title);
        $query->bindValue("description", $description);
        $query->bindValue("type", $type);
        $query->bindValue("info", $info);
        $query->bindValue("image_url", $image_url); // подвязываем значение ссылки к переменной  image_url
        $query->execute();
        
        // а дальше как обычно
        $context['message'] = 'Вы успешно создали объект';
        $context['id'] = $this->pdo->lastInsertId();


        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    function update(){
        $id = $this->params['id'];
        $data = file_get_contents("php://input");
        
        $data = json_decode($data, true);
        
    
        $title = $data['title'];
        $description = $data['description'];
        $type = $data['type'];
        $info = $data['info'];
        $image_url = $data['image_url'];


    //    // вытащил значения из $_FILES
    //     $tmp_name = $_FILES['image']['tmp_name'];
    //     $name =  $_FILES['image']['name'];
        
    //     // используем функцию которая проверяет
    //     // что файл действительно был загружен через POST запрос
    //     // и если это так, то переносит его в указанное во втором аргументе место
    //     move_uploaded_file($tmp_name, "../public/media/$name");

    //     $tmp_name = $_FILES['image']['tmp_name'];
    //     $name =  $_FILES['image']['name'];
    //     move_uploaded_file($tmp_name, "../public/media/$name");
    //     $image_url = "/media/$name"; // формируем ссылку без адреса сервера




                    $sql = <<<EOL
UPDATE games_objects SET title = :title, description = :description, type = :type, info = :info, image = :image_url
WHERE id=:id
EOL;
      

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
  
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    function delete(){
        $id = $this->params['id'];

        $sql =<<<EOL
DELETE FROM games_objects WHERE id = :id
EOL; // сформировали запрос
        
        // выполнили
        $query = $this->pdo->prepare($sql);
        $query->bindValue(":id", $id);
        $query->execute();


        // устанавливаем заголовок Location, на новый путь, я хочу перейти на главную страницу поэтому пишу /
        header("Location: /");
        exit; // после  header("Location: ...") надо писать exit
    }

}