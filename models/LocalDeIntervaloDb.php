<?php

namespace models;

require "LocalDeIntervalo.php";

use core\Connection;
use PDO;

class LocalDeIntervaloDb implements LocalDeIntervaloDataBase {
  private $con;

  public function __construct() {
    $this->con = Connection::getConnection();
  }

  public function inserirLocal(LocalDeIntervalo $l): int {
    $sql = $this->con->prepare(
      "INSERT INTO locais_de_intervalo 
      (nome, capacidade) 
      VALUES (:nome, :capacidade)"
    );
    $sql->bindValue(":nome", $l->getNome());
    $sql->bindValue(":capacidade", $l->getCapacidade());
    $sql->execute();

    return $this->con->lastInsertId();
  }
  public function procurarPeloId(int $id): ?LocalDeIntervalo {
    $sql = $this->con->prepare("SELECT * FROM locais_de_intervalo WHERE id = :id");
    $sql->bindValue(":id", $id);
    $sql->execute();

    if($sql->rowCount() === 0)
      return null;

    $localData = $sql->fetch();
    $local = new LocalDeIntervalo($localData["nome"], $localData["capacidade"], $id);

    return $local;
  }
  public function procurarPeloNome(string $nome): ?LocalDeIntervalo {
    $sql = $this->con->prepare("SELECT * FROM locais_de_intervalo WHERE nome = :nome LIMIT 1");
    $sql->bindValue(":nome", $nome);
    $sql->execute();

    if($sql->rowCount() === 0)
      return null;

    $localData = $sql->fetch();
    $local = new LocalDeIntervalo($nome, $localData["capacidade"], $localData["id"]);
    return $local;
  }
  public function procurarParticipantesEmCadaEtapa(LocalDeIntervalo $l): ?array {
    $sql = $this->con->prepare(
      "SELECT participante.nome as primario, participante.sobrenome as secundario, rel.etapa 
      FROM relacao_participante_sala_local rel
      LEFT JOIN participantes participante
      ON rel.fk_participante = participante.id 
      WHERE rel.fk_local = :local_id ORDER BY rel.etapa ASC"
    );
    $sql->bindValue(":local_id", $l->getId());
    $sql->execute();

    if($sql->rowCount() === 0)
      return null;

    $participantes = $sql->fetchAll(PDO::FETCH_ASSOC);
    return $participantes;
  }
  public function checkExistLocais(): ?array {
    $locais = [];
    $sql = $this->con->query("SELECT * FROM locais_de_intervalo");

    if($sql->rowCount() === 0)
      return null;

    $locaisData = $sql->fetchAll(PDO::FETCH_ASSOC);

    foreach ($locaisData as $data) {
      $local = new LocalDeIntervalo($data["nome"], $data["capacidade"], $data["id"]);
      array_push($locais, $local);
    }

    return $locais;
  }
}