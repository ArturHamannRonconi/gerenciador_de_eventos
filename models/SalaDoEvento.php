<?php 

namespace models;

class SalaDoEvento {
  private string $nome;
  private int $capacidade;
  private ?int $id;

  public function __construct(string $nome, int $capacidade, ?int $id = null) {
    $this->setNome($nome);
    $this->setCapacidade($capacidade);
    $this->setId($id);
  }

  public function getNome(): string {
    return $this->nome;
  }
  private function setNome(string $nome) {
    $this->nome = ucwords(trim($nome));
  }

  public function getCapacidade(): int {
    return $this->capacidade;
  }
  private function setCapacidade(int $capacidade) {
    $this->capacidade = $capacidade;
  }

  public function getId(): int {
    return $this->id;
  } 
  private function setId(?int $id = null) {
    $this->id = $id;
  }
}

interface SalaDoEventoDataBase {
  public function inserirSala(SalaDoEvento $s): int;
  public function procurarPeloId(int $id): ?SalaDoEvento;
  public function procurarPeloNome(string $nome): ?SalaDoEvento;
  public function procurarParticipantesEmCadaEtapa(SalaDoEvento $s): ?array;
  public function checkExistSalas(): ?array;
}