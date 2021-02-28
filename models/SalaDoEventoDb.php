<?php

namespace models;

require "SalaDoEvento.php";

use core\Connection;
use PDO;

class SalaDoEventoDb implements SalaDoEventoDataBase {
  private $con;

  public function __construct() {
    $this->con = Connection::getConnection();
  }

  public function inserirSala(SalaDoEvento $s): int {
    $sql = $this->con->prepare(
      "INSERT INTO salas_do_evento 
      (nome, capacidade) 
      VALUES (:nome, :capacidade)"
    );
    $sql->bindValue(":nome", $s->getNome());
    $sql->bindValue(":capacidade", $s->getCapacidade());
    $sql->execute();

    return $this->con->lastInsertId();
  }
  public function procurarPeloId(int $id): ?SalaDoEvento {
    $sql = $this->con->prepare("SELECT * FROM salas_do_evento WHERE id = :id");
    $sql->bindValue(":id", $id);
    $sql->execute();

    if($sql->rowCount() === 0)
      return null;

    $salaData = $sql->fetch();
    $sala = new SalaDoEvento($salaData["nome"], $salaData["capacidade"], $id);

    return $sala;
  }
  public function procurarPeloNome(string $nome): ?SalaDoEvento {
    $sql = $this->con->prepare("SELECT * FROM salas_do_evento WHERE nome = :nome LIMIT 1");
    $sql->bindValue(":nome", $nome);
    $sql->execute();

    if($sql->rowCount() === 0)
      return null;
    
    $salaData = $sql->fetch(PDO::FETCH_ASSOC);
    $sala = new SalaDoEvento($nome, $salaData["capacidade"], $salaData["id"]);
    return $sala;
  }
  public function procurarParticipantesEmCadaEtapa(SalaDoEvento $s): ?array {
    $sql = $this->con->prepare(
      "SELECT participante.nome as primario, participante.sobrenome as secundario, rel.etapa
      FROM relacao_participante_sala_local rel
      LEFT JOIN participantes participante
      ON rel.fk_participante = participante.id 
      WHERE rel.fk_sala = :sala_id ORDER BY rel.etapa ASC"
    );
    $sql->bindValue(":sala_id", $s->getId());
    $sql->execute();

    if($sql->rowCount() === 0)
      return null;

    $participantes = $sql->fetchAll(PDO::FETCH_ASSOC);
    return $participantes;
  }
  public function checkExistSalas(): ?array {
    $salas = [];
    $sql = $this->con->query("SELECT * FROM salas_do_evento");

    if($sql->rowCount() === 0)
      return null;

    $salasData = $sql->fetchAll(PDO::FETCH_ASSOC);

    foreach ($salasData as $data) {
      $sala = new SalaDoEvento($data["nome"], $data["capacidade"], $data["id"]);
      array_push($salas, $sala);
    }

    return $salas;
  }
}