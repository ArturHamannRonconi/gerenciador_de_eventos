<?php

namespace models;

require "Participante.php";

use core\Connection;
use PDO;

class ParticipanteDb implements ParticipanteDataBase {
  private $con;

  public function __construct() {
    $this->con = Connection::getConnection();
  }

  public function inserirParticipante(Participante $p): int {
    $sql = $this->con->prepare("INSERT INTO participantes (nome, sobrenome) VALUES (:nome, :sobrenome)");
    $sql->bindValue(":nome", $p->getNome());
    $sql->bindValue(":sobrenome", $p->getSobrenome());
    $sql->execute();

    return $this->con->lastInsertId();
  }
  public function procurarPeloId(int $id): ?Participante {
    $sql = $this->con->prepare("SELECT * FROM participantes WHERE id = :id");
    $sql->bindValue(":id", $id);
    $sql->execute();

    if($sql->rowCount() === 0)
      return null;

    $participanteData = $sql->fetch();
    $participante = new Participante($participanteData["nome"], $participanteData["sobrenome"], $id);
    return $participante;
  }
  public function procurarPeloNome(string $nome): ?Participante {
    $sql = $this->con->prepare("SELECT * FROM participantes WHERE nome = :nome LIMIT 1");
    $sql->bindValue(":nome", $nome);
    $sql->execute();

    if($sql->rowCount() === 0)
      return null;

    $participantesData = $sql->fetch();
    $participante = new Participante($nome, $participantesData["sobrenome"], $participantesData["id"]); 
  
    return $participante;
  }
  public function procurarPeloSobrenome(string $sobrenome): ?Participante {
    $sql = $this->con->prepare("SELECT * FROM participantes WHERE sobrenome = :sobrenome LIMIT 1");
    $sql->bindValue(":sobrenome", $sobrenome);
    $sql->execute();

    if($sql->rowCount() === 0)
      return null;

    $participantesData = $sql->fetch();
    $participante = new Participante($participantesData["nome"], $sobrenome, $participantesData["id"]); 

    return $participante;
  }
  public function chekcExists(string $nome, string $sobrenome): bool {
    $sql = $this->con->prepare("SELECT id FROM participantes WHERE nome = :nome AND sobrenome = :sobrenome");
    $sql->bindValue(":nome", $nome);
    $sql->bindValue(":sobrenome", $sobrenome);
    $sql->execute();

    if($sql->rowCount() > 0)
      return true;

    return false;
  }
  public function checkExistsParticipantes(): ?array {
    $participantes = [];
    $sql = $this->con->query("SELECT * FROM participantes");

    if($sql->rowCount() === 0)
      return null;

    $participantesData = $sql->fetchAll(PDO::FETCH_ASSOC);

    foreach ($participantesData as $data) {
      $participante = new Participante($data["nome"], $data["sobrenome"], $data["id"]);
      array_push($participantes, $participante);
    }

    return $participantes;
  }
  public function procurarSalasEmCadaEtapa(int $id): ?array {
    $sql = $this->con->prepare(
      "SELECT salas.nome, salas.capacidade, rel.etapa 
      FROM relacao_participante_sala_local rel
      LEFT JOIN salas_do_evento salas
      ON rel.fk_sala = salas.id 
      WHERE rel.fk_participante = :id"
    );
    $sql->bindValue(":id", $id);
    $sql->execute();

    if($sql->rowCount() === 0)
      return null;

    $relData = $sql->fetchAll(PDO::FETCH_ASSOC);
    return $relData;
  }
  public function procurarLocalDeIntervaloEmCadaEtapa(int $id): ?array {
    $sql = $this->con->prepare(
      "SELECT local.nome, local.capacidade, rel.etapa 
      FROM relacao_participante_sala_local rel
      LEFT JOIN locais_de_intervalo local
      ON rel.fk_local = local.id 
      WHERE rel.fk_participante = :id"
    );
    $sql->bindValue(":id", $id);
    $sql->execute();

    if($sql->rowCount() === 0)
      return null;

    $relData = $sql->fetchAll(PDO::FETCH_ASSOC);
    return $relData;
  }
}