<?php

namespace controllers;

use core\Controller;
use models\RelacaoDb;

class HomeController extends Controller {
  public function index() {
    $info = [
      'pergunta' => "Você deseja consultar ou cadastrar?",
      'assets' => "assets"
    ];
    $this->renderTemplate("home", $info);
  }
}