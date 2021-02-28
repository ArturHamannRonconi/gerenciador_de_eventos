<?php

namespace models;

class Participante {
  private string $nome;
  private string $sobrenome;
  private ?int $id;

  public function __construct(string $nome, string $sobrenome, ?int $id = null) {
    $this->setNome($nome);
    $this->setSobrenome($sobrenome);
    $this->setId($id);
  }

  public function getNome(): string {
    return $this->nome;
  }
  private function setNome(string $nome) {
    $this->nome = ucwords(trim($nome));
  }

  public function getSobrenome(): string {
    return $this->sobrenome;
  }
  private function setSobrenome(string $sobrenome) {
    $this->sobrenome = ucwords(trim($sobrenome));
  }

  public function getId(): int {
    return $this->id;
  }
  private function setId(?int $id = null) {
    $this->id = $id;
  }
}

interface ParticipanteDataBase {
  public function inserirParticipante(Participante $p): int;
  public function procurarPeloId(int $id): ?Participante;
  public function procurarPeloNome(string $nome): ?Participante;
  public function procurarPeloSobrenome(string $sobrenome): ?Participante;
  public function chekcExists(string $nome, string $sobrenome): bool;
  public function procurarSalasEmCadaEtapa(int $id): ?array;
  public function procurarLocalDeIntervaloEmCadaEtapa(int $id): ?array;
}