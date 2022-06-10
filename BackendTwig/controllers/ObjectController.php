<?php

require_once "BaseGameTwigController.php";

class ObjectController extends BaseGameTwigController {
    public $template = "__object.twig"; // указываем шаблон

    public function getContext(): array
    {
        $context = parent::getContext();
        

        // создам запрос, под параметр создаем переменную my_id в запросе
        $query = $this->pdo->prepare("SELECT title,description, id, image, info FROM games_objects WHERE id= :my_id");
        // подвязываем значение в my_id 
        $query->bindValue("my_id", $this->params['id']);
        $query->execute(); // выполняем запрос

        // тянем данные
        $data = $query->fetch();
        
        // передаем описание из БД в контекст
        $context['description'] = $data['description'];
        $context['image'] = $data['image'];
        $context['info'] = $data['info'];
        $context['title'] = $data['title'];
        $context['id'] = $data['id'];


        if(isset($_GET['show'])){
            $name =$_GET['show'];
            if($name == 'image'){
                $context['is_image'] = true;
                $context['is_info'] = false;
            }elseif($name == 'info' ){
                $context['is_image'] = false;
                $context['is_info'] = true;
            }
        
        }
        return $context;
    }
}