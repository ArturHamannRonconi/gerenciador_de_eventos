<?php

namespace core;

use controllers\HomeController;
use controllers\ErrorController;
use controllers\CadastroController;
use controllers\ConsultaController;

class Core {
  public function run() {
    $path = filter_input(INPUT_GET, "request") ?? "Home/index";
    $path = explode("/", $path);

    [$classController, $method] = array_map(
      fn($primary, $default) => !empty($primary) ? $primary : $default,
      $path,
      ["Home", "index"]
    );
    $params = array_slice($path, 2);

    $classController = $this->formatController($classController);
    $controller = $this->CreateController($classController);
    $method = method_exists($controller, $method) ? $method : "index";

    call_user_func_array([$controller, $method], $params);
  }
  public function formatController(string $class): string {
    $class = ucfirst($class);
    return "controllers\\{$class}Controller";
  }
  public function CreateController(string $class) {
    return ($this->ControllerExists($class)) ?  new $class : new ErrorController; 
  }
  public function ControllerExists(string $class): bool {
    $file = str_replace("\\", "/", __DIR__."\\..\\{$class}.php");
    return (file_exists($file)) ? true : false;
  }
}