<?php

class BaseGameTwigController extends TwigBaseController{
    public function getContext():array
    {
        $context = parent::getContext();

        //$query = $this->pdo->query("SELECT DISTINCT type FROM games_objects ORDER BY 1");
        $query = $this->pdo->query("SELECT DISTINCT name FROM games_types ORDER BY 1");
        $types = $query->fetchAll();
        $context['types'] = $types;

        return $context;
    }
}