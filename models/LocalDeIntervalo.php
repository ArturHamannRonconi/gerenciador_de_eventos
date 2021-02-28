<?php 

namespace models;

class LocalDeIntervalo {
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

interface LocalDeIntervaloDataBase {
  public function inserirLocal(LocalDeIntervalo $l): int;
  public function procurarPeloId(int $id): ?LocalDeIntervalo;
  public function procurarPeloNome(string $nome): ?LocalDeIntervalo;
  public function procurarParticipantesEmCadaEtapa(LocalDeIntervalo $l): ?array;
  public function checkExistLocais(): ?array;
}