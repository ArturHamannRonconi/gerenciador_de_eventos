-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 27-Fev-2021 às 23:50
-- Versão do servidor: 10.4.17-MariaDB
-- versão do PHP: 8.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `gerenciador_de_eventos`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `locais_de_intervalo`
--

CREATE TABLE `locais_de_intervalo` (
  `id` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `capacidade` smallint(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `participantes`
--

CREATE TABLE `participantes` (
  `id` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `sobrenome` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `relacao_participante_sala_local`
--

CREATE TABLE `relacao_participante_sala_local` (
  `id` int(11) NOT NULL,
  `fk_participante` int(11) NOT NULL,
  `fk_sala` int(11) NOT NULL,
  `fk_local` int(11) NOT NULL,
  `etapa` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `salas_do_evento`
--

CREATE TABLE `salas_do_evento` (
  `id` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `capacidade` smallint(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `locais_de_intervalo`
--
ALTER TABLE `locais_de_intervalo`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `participantes`
--
ALTER TABLE `participantes`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `relacao_participante_sala_local`
--
ALTER TABLE `relacao_participante_sala_local`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_participante_id` (`fk_participante`),
  ADD KEY `fk_sala_id` (`fk_sala`),
  ADD KEY `fk_local_id` (`fk_local`);

--
-- Índices para tabela `salas_do_evento`
--
ALTER TABLE `salas_do_evento`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `locais_de_intervalo`
--
ALTER TABLE `locais_de_intervalo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `participantes`
--
ALTER TABLE `participantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `relacao_participante_sala_local`
--
ALTER TABLE `relacao_participante_sala_local`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `salas_do_evento`
--
ALTER TABLE `salas_do_evento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `relacao_participante_sala_local`
--
ALTER TABLE `relacao_participante_sala_local`
  ADD CONSTRAINT `relacao_participante_sala_local_ibfk_1` FOREIGN KEY (`fk_participante`) REFERENCES `participantes` (`id`),
  ADD CONSTRAINT `relacao_participante_sala_local_ibfk_2` FOREIGN KEY (`fk_sala`) REFERENCES `salas_do_evento` (`id`),
  ADD CONSTRAINT `relacao_participante_sala_local_ibfk_3` FOREIGN KEY (`fk_local`) REFERENCES `locais_de_intervalo` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
