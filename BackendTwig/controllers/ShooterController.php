<?php
//require_once "TwigBaseConroller.php";

class ShooterController extends TwigBaseController {
  public $title = "Shooting";
  public $template = "__object.twig";

public function getContext(): array
{
    $context = parent::getContext();
    $context['title'] = "Shooting";
    return $context;
}
}