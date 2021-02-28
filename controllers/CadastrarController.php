<?php

namespace controllers;

use core\Controller;
use models\RelacaoDb;
use models\SalaDoEventoDb;
use models\SalaDoEvento;
use models\ParticipanteDb;
use models\Participante;
use models\localDeIntervaloDb;
use models\localDeIntervalo;

class CadastrarController extends Controller {
  public function index() {
    $info = [
      'pergunta' => "O que você deseja cadastrar?",
      'class' => "cadastrar",
      'nameButton' => ["Sala de Evento", "Participante", "Local de intervalo"],
      'link' => ["salaDeEvento", "participante", "localDeIntervalo"],
      'assets' => "assets"
    ];
    $this->renderTemplate("cadastroConsulta", $info);
  }

  public function salaDeEvento($warning = null) {
    $assets = ($warning === null) ? "../assets" : "../../assets"; 

    $warnings = [
      'invalid' => "preencha todos os campos corretamente.",
      'exists' => "O cadastro já existe",
      'nonexistent' => "Não existem salas ou locais suficientes.",
      'capacidadeMax' => "A capacidade máxima de todas as salas ou de todos os lugares já foi atingida."
    ];

    $info = [
      'pergunta' => "Digite o nome e a capacidade da sala.",
      'action' => "cadastrar/salaDeEvento_action",
      'assets' => $assets,
      'type' => "number",
      'name' => "capacidade",
      'warnings' => $warnings,
      'warning' => $warning
    ];
    $this->renderTemplate("cadastro", $info);
  }
  public function salaDeEvento_action() {
    $nome = filter_input(INPUT_POST, "nome", FILTER_SANITIZE_SPECIAL_CHARS) ?? null;
    $capacidade = filter_input(INPUT_POST, "capacidade", FILTER_VALIDATE_INT) ?? null;

    if(!$nome || !$capacidade) {
      header("Location: http://localhost/gerenciador_de_eventos/cadastrar/salaDeEvento/invalid");
      exit;
    }

    $salaDb = new SalaDoEventoDb();
    $sala = new SalaDoEvento($nome, $capacidade);

    if($salaDb->procurarPeloNome($nome) !== null) {
      header("Location: http://localhost/gerenciador_de_eventos/cadastrar/salaDeEvento/exists");
      exit;
    }

    $salaId = $salaDb->inserirSala($sala);
    header("Location: http://localhost/gerenciador_de_eventos/consultar/salasDeEvento/participantesNaSala/{$salaId}");
    exit;
  }

  public function participante($warning = null) {
    $relacaoDb = new RelacaoDb();

    $warnings = [
      'invalid' => "preencha todos os campos corretamente.",
      'exists' => "O cadastro já existe",
      'nonexistent' => "Não existem salas ou locais suficientes.",
      'capacidadeMax' => "A capacidade máxima de todas as salas ou de todos os lugares já foi atingida."
    ];

    if(!$relacaoDb->checkExistsSalasAndLocal())
      $warning = "nonexistent";

    $assets = ($warning === null || $warning === "nonexistent") ? "../assets" : "../../assets"; 
    $info = [
      'pergunta' => "Digite o nome e o sobrenome do participante.",
      'action' => "cadastrar/participante_action",
      'assets' => $assets,
      'type' => "text",
      'name' => "sobrenome",
      'warnings' => $warnings,
      'warning' => $warning
    ];
    $this->renderTemplate("cadastro", $info);
  }
  public function participante_action() {
    $relacaoDb = new RelacaoDb();
    if(!$relacaoDb->checkExistsSalasAndLocal()) {
      header("Location: http://localhost/gerenciador_de_eventos/cadastrar/participante");
      exit;
    }

    if(!$relacaoDb->checkCapacidades()) {
      header("Location: http://localhost/gerenciador_de_eventos/cadastrar/participante/capacidadeMax");
      exit;
    }

    $nome = filter_input(INPUT_POST, "nome", FILTER_SANITIZE_SPECIAL_CHARS) ?? null;
    $sobrenome = filter_input(INPUT_POST, "sobrenome", FILTER_SANITIZE_SPECIAL_CHARS) ?? null;

    if(!$nome || !$sobrenome) {
      header("Location: http://localhost/gerenciador_de_eventos/cadastrar/participante/invalid");
      exit;
    }

    $participanteDb = new ParticipanteDb();
    $participante = new Participante($nome, $sobrenome);

    if($participanteDb->chekcExists($nome, $sobrenome)) {
      header("Location: http://localhost/gerenciador_de_eventos/cadastrar/participante/exists");
      exit;
    }
    
    $idParticipante = $participanteDb->inserirParticipante($participante);
    $relacaoDb->relacionarParticipante($idParticipante);
    header("Location: http://localhost/gerenciador_de_eventos/consultar/participantes/salasElocaisDoParticipante/{$idParticipante}");
    exit;
  }

  public function localDeIntervalo($warning = null) {
    $warnings = [
      'invalid' => "preencha todos os campos corretamente.",
      'exists' => "O cadastro já existe",
      'nonexistent' => "Não existem salas ou locais suficientes.",
      'capacidadeMax' => "A capacidade máxima de todas as salas ou de todos os lugares já foi atingida."
    ];

    $assets = ($warning === null) ? "../assets" : "../../assets"; 
    $info = [
      'pergunta' => "Digite o nome e a capacidade do local.",
      'action' => "cadastrar/localDeIntervalo_action",
      'assets' => $assets,
      'type' => "number",
      'name' => "capacidade",
      'warnings' => $warnings,
      'warning' => $warning
    ];
    $this->renderTemplate("cadastro", $info);
  }
  public function localDeIntervalo_action() {
    $nome = filter_input(INPUT_POST, "nome", FILTER_SANITIZE_SPECIAL_CHARS) ?? null;
    $capacidade = filter_input(INPUT_POST, "capacidade", FILTER_VALIDATE_INT) ?? null;

    if(!$nome || !$capacidade) {
      header("Location: http://localhost/gerenciador_de_eventos/cadastrar/localDeIntervalo/invalid");
      exit;
    }

    $localDb = new localDeIntervaloDb();
    $local = new localDeIntervalo($nome, $capacidade);

    if($localDb->procurarPeloNome($nome) !== null) {
      header("Location: http://localhost/gerenciador_de_eventos/cadastrar/localDeIntervalo/exists");
      exit;
    }
    
    $localId = $localDb->inserirLocal($local);
    header("Location: http://localhost/gerenciador_de_eventos/consultar/locaisDeIntervalo/participantesNoLocal/{$localId}");
    exit;
  }
}