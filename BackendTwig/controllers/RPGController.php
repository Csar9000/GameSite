<?php
//require_once "TwigBaseConroller.php";

class RPGController extends TwigBaseController {
public $title = "RPG";
public $template = "__object.twig";

public function getContext(): array
{
    $context = parent::getContext();
    $context['title'] = "RPG";
    return $context;
}
}