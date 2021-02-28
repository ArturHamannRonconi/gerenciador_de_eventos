<?php 

namespace core;

use core\Config;

class Controller {

  public function renderTemplate(string $viewName, array $modelData = []) {
    extract($modelData);
    $base = Config::BASE_DIR;
    require "views/template.php";
  }
  public function renderView(string $viewName, array $modelData = []) {
    extract($modelData);
    $base = Config::BASE_DIR;
    require "views/{$viewName}.php";
  }

}