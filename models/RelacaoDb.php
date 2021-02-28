<?php

namespace models;

use core\Connection;
use PDO;

class RelacaoDb {
  private $con;
  public int $salaAtual;
  private int $localAtual;

  public function __construct() {
    $this->con = Connection::getConnection();
  }

  public function relacionarParticipante(int $id) {
    $salas = $this->processarSala($id);
    $locais = $this->processarLocal($id);

    for($etapa = 1; $etapa <= 2; $etapa++) {
      $sql = $this->con->prepare(
        "INSERT INTO relacao_participante_sala_local 
        (fk_participante, fk_sala, fk_local, etapa) 
        VALUES (:id_participante, :id_sala, :id_local, :etapa)"
      );
      $sql->bindValue(":id_participante", $id);
      $sql->bindValue(":id_sala", $salas[$etapa]);
      $sql->bindValue(":id_local", $locais[$etapa]);
      $sql->bindValue(":etapa", $etapa);
      $sql->execute();
    }
  }
  public function checkExistsSalasAndLocal(): bool {
    $lugares = ["salas_do_evento", "locais_de_intervalo"];

    foreach($lugares as $lugar) {
      $sql = $this->con->query("SELECT COUNT(id) FROM $lugar");
      $quantidadeDeLugares = intval($sql->fetch()["COUNT(id)"]);
      if($quantidadeDeLugares === 0)
        return false;
    }
    return true;
  }
  public function checkCapacidades(): bool {

    $idSalas = $this->getIdTables("salas_do_evento");
    $totalParticipantesEmCadaSala = $this->getTotalDeParticipantesNaEtapaUmEmCadaSala();
    foreach($idSalas as $idSala) {
      $sql = $this->con->prepare("SELECT capacidade FROM salas_do_evento WHERE id = :sala_id");
      $sql->bindValue(":sala_id", $idSala);
      $sql->execute();
      $capacidadeMaxima = $sql->fetch(PDO::FETCH_ASSOC)["capacidade"]; 
      if($totalParticipantesEmCadaSala[$idSala] < $capacidadeMaxima)
        return true;
    }
    
    $idLocais = $this->getIdTables("locais_de_intervalo");
    $totalParticipantesEmCadaLocal = $this->getTotalDeParticipantesNaEtapaUmEmCadaLocal();
    foreach($idLocais as $idLocal) {
      $sql = $this->con->prepare("SELECT capacidade FROM salas_do_evento WHERE id = :sala_id");
      $sql->bindValue(":sala_id", $idLocal);
      $sql->execute();
      $capacidadeMaxima = $sql->fetch(PDO::FETCH_ASSOC)["capacidade"]; 
      if($totalParticipantesEmCadaLocal[$idLocal] < $capacidadeMaxima)
        return true;
    }
    return false;
  }

  private function processarSala(int $id) {
    $sala1 = $this->setSala($id);
    $sala2 = $this->setSala($id);

    if($id % 2 === 0) {
      $sala2 = $this->verificarIdDaSalaOuLocal($sala2, "salas_do_evento");
    }

    return [1 => $sala1, 2 => $sala2];
  }
  private function setSala(int $id) {   
    $totalDeParticipantes = $this->getTotalDeParticipantes();
    if($totalDeParticipantes > 0) {
      $numeroDeParticipantesEmCadaSala = $this->getTotalDeParticipantesNaEtapaUmEmCadaSala(); 
      $diferencaMinimaDePessoasEmCadaSala = 1;
      if(max($numeroDeParticipantesEmCadaSala) - min($numeroDeParticipantesEmCadaSala) > $diferencaMinimaDePessoasEmCadaSala){
        $this->salaAtual = array_search(min($numeroDeParticipantesEmCadaSala), $numeroDeParticipantesEmCadaSala);
        return $this->salaAtual;
      } else {
        $sql = $this->con->prepare("SELECT fk_sala FROM relacao_participante_sala_local WHERE id = :id");
        $sql->bindValue(":id", max($this->getIdTables("relacao_participante_sala_local")) - 1);
        $sql->execute();
        $this->salaAtual = $sql->fetch(PDO::FETCH_ASSOC)["fk_sala"];

        $this->salaAtual = $this->verificarIdDaSalaOuLocal($this->salaAtual, "salas_do_evento");
      }
    } else {
      $this->salaAtual = min($this->getIdTables("salas_do_evento"));
    }

    $this->verificarCapacidadeDaSala($this->salaAtual);

    return $this->salaAtual;
  }
  private function getTotalDeParticipantesNaEtapaUmEmCadaSala(): array {
    $totalParticipantesEmCadaSala = [];
    foreach($this->getIdTables("salas_do_evento") as $idSala) {
      $sql = $this->con->prepare(
        "SELECT COUNT(fk_participante) 
        FROM relacao_participante_sala_local 
        WHERE fk_sala = :sala_id 
        AND etapa = 1"
      );
      $sql->bindValue(":sala_id", $idSala);
      $sql->execute();
      $totalParticipantesEmCadaSala[$idSala] = $sql->fetch(PDO::FETCH_ASSOC)["COUNT(fk_participante)"];
    }
    return $totalParticipantesEmCadaSala;
  }
  private function verificarCapacidadeDaSala(int $idSala) {
    $sql = $this->con->prepare("SELECT capacidade FROM salas_do_evento WHERE id = :sala_id");
    $sql->bindValue(":sala_id", $idSala);
    $sql->execute();

    $capacidade = $sql->fetch(PDO::FETCH_ASSOC)["capacidade"];
    $numeroDeParticipantesEmCadaSala = $this->getTotalDeParticipantesNaEtapaUmEmCadaSala();
    
    if($numeroDeParticipantesEmCadaSala[$idSala] === $capacidade){
      $this->salaAtual++;
      $this->verificarCapacidadeDaSala($this->salaAtual);
    } 
    
    return $this->salaAtual;
  }
  

  private function processarLocal(int $id) {
    $local1 = $this->setLocal($id);
    $local2 = $this->setLocal($id);

    if($id % 2 !== 0) {
      $local2 = $this->verificarIdDaSalaOuLocal($local2, "locais_de_intervalo");
    }

    return [1 => $local1, 2 => $local2];
  } 
  private function setLocal(int $id) {  
    $totalDeParticipantes = $this->getTotalDeParticipantes();
    if($totalDeParticipantes > 0) {
      $sql = $this->con->prepare("SELECT fk_local FROM relacao_participante_sala_local WHERE id = :id");
      $sql->bindValue(":id", max($this->getIdTables("relacao_participante_sala_local")) - 1);
      $sql->execute();

      $this->localAtual = $sql->fetch(PDO::FETCH_ASSOC)["fk_local"];

      $this->localAtual = $this->verificarIdDaSalaOuLocal($this->localAtual, "locais_de_intervalo"); 

    } else {
      $this->localAtual = min($this->getIdTables("locais_de_intervalo"));
    }

    $this->localAtual = $this->verificarCapacidadeDoLocal($this->localAtual);

    return $this->localAtual;
  }
  private function getTotalDeParticipantesNaEtapaUmEmCadaLocal(): array {
    $totalParticipantesEmCadaLocal = [];
    foreach($this->getIdTables("locais_de_intervalo") as $idLocal) {
      $sql = $this->con->prepare(
        "SELECT COUNT(fk_participante) 
        FROM relacao_participante_sala_local 
        WHERE fk_local = :local_id 
        AND etapa = 1"
      );
      
      $sql->bindValue(":local_id", $idLocal);
      $sql->execute();
      $totalParticipantesEmCadaLocal[$idLocal] = $sql->fetch(PDO::FETCH_ASSOC)["COUNT(fk_participante)"];
    }
    return $totalParticipantesEmCadaLocal;
  }
  private function verificarCapacidadeDoLocal(int $idLocal) {
    $sql = $this->con->prepare("SELECT capacidade FROM locais_de_intervalo WHERE id = :local_id");
    $sql->bindValue(":local_id", $idLocal);
    $sql->execute();

    $capacidade = $sql->fetch(PDO::FETCH_ASSOC)["capacidade"];
    $numeroDeParticipantesEmCadaLocal = $this->getTotalDeParticipantesNaEtapaUmEmCadaLocal();
    
    if($numeroDeParticipantesEmCadaLocal[$idLocal] === $capacidade){
      $this->localAtual++;
      $this->verificarCapacidadeDoLocal($this->localAtual);
    }
    
    return $this->localAtual;
  }

  private function getTotalDeParticipantes(): int {
    $sql = $this->con->query("SELECT COUNT(id) FROM relacao_participante_sala_local");
    $quantidadeDeParticipantes = intval($sql->fetch()["COUNT(id)"]) / 2;
    return $quantidadeDeParticipantes;
  }
  private function getIdTables(string $table): array {
    $idsTable = [];
    $sql = $this->con->query("SELECT id FROM $table");
    $arraysId = $sql->fetchAll(PDO::FETCH_ASSOC);
    
    foreach($arraysId as $arrayId) {
      array_push($idsTable, $arrayId["id"]);
    }
    return $idsTable;
  }
  private function verificarIdDaSalaOuLocal(int $id, string $table) {
    if($id === intval(max($this->getIdTables($table))))
      $id = intval(min($this->getIdTables($table)));
    else
      $id++;

    return $id;
  }
}