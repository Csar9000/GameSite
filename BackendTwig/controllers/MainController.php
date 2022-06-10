<?php
require_once "BaseGameTwigController.php";

class MainController extends BaseGameTwigController {
    public $template = "main.twig";
    public $title = "Главная";


    // добавим метод getContext()
    public function getContext(): array
    {
        $context = parent::getContext();
        
        if(isset($_GET['type'])){
            $query = $this->pdo->prepare("SELECT * FROM games_objects WHERE type=:type");
            $query ->bindValue("type",$_GET['type']);
            $query ->execute();
        }else{
            $query = $this->pdo->query("SELECT * FROM games_objects");
        }

        $context['games_objects'] = $query->fetchAll();

        return $context;
    }
}