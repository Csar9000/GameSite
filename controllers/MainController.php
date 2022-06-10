<?php
require_once "BaseGameTwigController.php";

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

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

        $context["history"] =$_SESSION["history"];
        $context["history"] = array_splice($context["history"],-10);
        $context["my_session_message"] = isset($_GET) ? $_GET : "";



        return $context;
    }
    public function unset(&$context, $variable, $key = null) {
        echo $variable;
        unset($context[$variable]);
    }
}