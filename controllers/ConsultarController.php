<?php

namespace controllers;

use core\Controller;
use models\RelacaoDb;
use models\SalaDoEventoDb;
use models\SalaDoEvento;
use models\ParticipanteDb;
use models\Participante;
use models\LocalDeIntervaloDb;
use models\LocalDeIntervalo;

class ConsultarController extends Controller {
  public function index() {
    $info = [
      'pergunta' => "Qual categoria você deseja consultar?",
      'class' => "consultar",
      'nameButton' => ["Salas de evento", "Participantes", "Locais de intervalo"],
      'link' => ["salasDeEvento", "participantes", "locaisDeIntervalo"],
      'assets' => "assets"
    ];
    $this->renderTemplate("cadastroConsulta", $info);
  }

  public function salasDeEvento($warning = null, $idSala = null) {
    $salaDb = new SalaDoEventoDb();

    $todasAsSalas = null;
    $participantes = null;
    $sala = null;
    $warnings = [
      'NotFoundParticipantes' => "Não existem participantes cadastrados nesta sala.",
      'doesNotExist' => "Não existem salas cadastradas.",
      'doNotExist' => "A sala não existe."
    ];

    if($idSala) {
      $sala = $salaDb->procurarPeloId($idSala);
      $participantes = $salaDb->procurarParticipantesEmCadaEtapa($sala);
      if(!$participantes)
        $warning = "NotFoundParticipantes";
    }
    

    if(!$idSala) {
      if($salaDb->checkExistSalas())
        $todasAsSalas = $salaDb->checkExistSalas();
      else
        $warning = "doesNotExist";
    }

    
    $assets = (!$warning || $warning === "doesNotExist") ? "../assets" : "../../assets";
    $assets = ($idSala) ? "../../../assets" : $assets;
    $info = [
      'pergunta' => "Digite o nome da sala que você quer achar.",
      'action' => "consultar/salasDeEvento_action",
      'headerTable' => [
        'id' => "ID",
        'primario' => "Nome da Sala",
        'secundario' => "Capacidade"
      ],
      'relacao' => $participantes,
      'instancia' => $sala,
      'todosOsRegistros' => $todasAsSalas,
      'assets' => $assets,
      'warnings' => $warnings,
      'warning' => $warning
    ];
    
    $this->renderTemplate("consulta", $info);
  }
  public function salasDeEvento_action() {
    $salaDb = new SalaDoEventoDb();
    if(!$salaDb->checkExistSalas()) {
      header("Location: http://localhost/gerenciador_de_eventos/consultar/salasDeEvento");
      exit;
    }

    $search = filter_input(INPUT_POST, "pesquisa", FILTER_SANITIZE_SPECIAL_CHARS) ?? "";
    $sala =  $salaDb->procurarPeloNome($search);
    if($sala instanceof salaDoEvento) {
      header("Location: http://localhost/gerenciador_de_eventos/consultar/salasDeEvento/participantesNaSala/{$sala->getId()}");
      exit;
    }

    header("Location: http://localhost/gerenciador_de_eventos/consultar/salasDeEvento/doNotExist");
    exit;
  }

  public function participantes($warning = null, $idParticipante = null) {
    $participanteDb = new ParticipanteDb();

    $salasELocais = null;
    $todosOsParticipantes = null;
    $participante = null;
    $warnings = [
      'doesNotExist' => "Não existem participantes cadastrados.",
      'doNotExist' => "O Participante não existe."
    ];

    if($idParticipante) {
      $participante = $participanteDb->procurarPeloId($idParticipante);

      $salas = $participanteDb->procurarSalasEmCadaEtapa($participante->getId());
      $locais = $participanteDb->procurarLocalDeIntervaloEmCadaEtapa($participante->getId());

      $salasELocais = [
        ["primario" => $salas[0]["nome"], "secundario" => $locais[0]["nome"], "etapa" => 1],
        ["primario" => $salas[1]["nome"], "secundario" => $locais[1]["nome"], "etapa" => 2]
      ];
    }
    
    if(!$idParticipante) {
      if($participanteDb->checkExistsParticipantes())
        $todosOsParticipantes = $participanteDb->checkExistsParticipantes();
      else
        $warning = "doesNotExist";
    }

    $assets = (!$warning || $warning === "doesNotExist") ? "../assets" : "../../assets";
    $assets = ($idParticipante) ? "../../../assets" : $assets;
    $info = [
      'pergunta' => "Digite o nome ou sobrenome do participante que vc quer achar.",
      'action' => "consultar/participantes_action",
      'headerTable' => [
        'id' => "ID",
        'primario' => "Nome",
        'secundario' => "Sobrenome"
      ],
      'relacao' => $salasELocais,
      'instancia' => $participante,
      'todosOsRegistros' => $todosOsParticipantes, 
      'assets' => $assets,
      'warnings' => $warnings,
      'warning' => $warning
    ];

    $this->renderTemplate("consulta", $info);
  }
  public function participantes_action() {
    $participanteDb = new participanteDb();

    if(!$participanteDb->checkExistsParticipantes()) {
      header("Location: http://localhost/gerenciador_de_eventos/consultar/participantes");
      exit;
    }

    $search = filter_input(INPUT_POST, "pesquisa", FILTER_SANITIZE_SPECIAL_CHARS) ?? "";

    $participante =  $participanteDb->procurarPeloSobrenome($search) ?? $participanteDb->procurarPeloNome($search);
    if($participante instanceof Participante) {
      header("Location: http://localhost/gerenciador_de_eventos/consultar/participantes/salasElocaisDoParticipante/{$participante->getId()}");
      exit;
    }

    header("Location: http://localhost/gerenciador_de_eventos/consultar/participantes/doNotExist");
    exit;
  }

  public function locaisDeIntervalo($warning = null, $idLocal = null) {
    $localDb = new LocalDeIntervaloDb();
    
    $todasOsLocais = null;
    $participantes = null;
    $local = null;
    $warnings = [
      'NotFoundParticipantes' => "Não existem participantes cadastrados neste local ainda.",
      'doesNotExist' => "Não existem locais cadastrados.",
      'doNotExist' => "O local não existe."
    ];

    if($idLocal) {
      $local = $localDb->procurarPeloId($idLocal);
      $participantes = $localDb->procurarParticipantesEmCadaEtapa($local);
      if(!$participantes)
        $warning = "NotFoundParticipantes";
    }
    if(!$idLocal) {
      if($localDb->checkExistLocais())
        $todasOsLocais = $localDb->checkExistLocais();
      else
        $warning = "doesNotExist";
    }
    
    $assets = (!$warning || $warning === "doesNotExist") ? "../assets" : "../../assets";
    $assets = ($idLocal) ? "../../../assets" : $assets;
    $info = [
      'pergunta' => "Digite o nome do local que você quer achar.",
      'action' => "consultar/locaisDeIntervalo_action",
      'headerTable' => [
        'id' => "ID",
        'primario' => "Nome do Local",
        'secundario' => "Capacidade"
      ],
      'relacao' => $participantes,
      'instancia' => $local,
      'todosOsRegistros' => $todasOsLocais,
      'assets' => $assets,
      'warnings' => $warnings,
      'warning' => $warning
    ];
    $this->renderTemplate("consulta", $info);

  }
  public function locaisDeIntervalo_action() {
    $localDb = new LocalDeIntervaloDb();

    if(!$localDb->checkExistLocais()) {
      header("Location: http://localhost/gerenciador_de_eventos/consultar/locaisDeIntervalo");
      exit;
    }

    $search = filter_input(INPUT_POST, "pesquisa", FILTER_SANITIZE_SPECIAL_CHARS) ?? "";

    $local =  $localDb->procurarPeloNome($search);
    if($local instanceof LocalDeIntervalo) {
      header("Location: http://localhost/gerenciador_de_eventos/consultar/locaisDeIntervalo/participantesNoLocal/{$local->getId()}");
      exit;
    }

    header("Location: http://localhost/gerenciador_de_eventos/consultar/locaisDeIntervalo/doNotExist");
    exit;
  }
}