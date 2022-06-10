<?php
require_once "BaseGameTwigController.php";

class SearchController extends BaseGameTwigController {
    public $template = "search.twig";

    public function getContext():array{
        $context = parent::getContext();

        $type = isset($_GET['type']) ? $_GET['type'] : '';
        $title = isset($_GET['title']) ? $_GET['title'] : '';
        $description = isset($_GET['description']) ? $_GET['description'] : '';


        if($type == "All"){
            $sql = <<<EOL
            SELECT id, title, description
            FROM games_objects
            EOL;            
        }elseif(isset($description) ){
            $sql = <<<EOL
            SELECT id, title, description
            FROM games_objects
            WHERE(:description = '' OR description like CONCAT('%', :description, '%'))
                AND (type = :type)
            EOL;            
        }else{
            // $sql = <<<EOL
            // SELECT id, title, description
            // FROM games_objects
            // WHERE(:title = '' OR title like CONCAT('%', :title, '%'))
            //     AND (type = :type)
            // EOL;            
        }


        $query = $this->pdo->prepare($sql);

        $query->bindValue("title",$title);
        $query->bindValue("type",$type);
        $query->bindValue("description",$description);
        $query->execute();

        $context['objects'] = $query->fetchAll();
        return $context;
    }

} 