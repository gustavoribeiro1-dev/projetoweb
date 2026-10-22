-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 17/10/2026 às 13:13
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE TABLE `planos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(50) NOT NULL,
  `valor` double NOT NULL,
  `descricao` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO `planos` (`id`, `titulo`, `valor`, `descricao`) VALUES
(1, 'Plano Standard', 150.00, 'Plano mensal padrao.'),
(2, 'Plano Fit', 130.00, 'Plano com recorrencia mensal.'),
(3, 'Plano Trimestral', 360.00, 'Acesso por tres meses.'),
(4, 'Plano Semestral', 660.00, 'Acesso por seis meses.'),
(5, 'Plano Anual', 1200.00, 'Acesso por doze meses.');

-- --------------------------------------------------------

CREATE TABLE `alunos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `dt_nascimento` date NOT NULL,
  `id_plano` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_alunos_id_plano` (`id_plano`),
  CONSTRAINT `fk_alunos_plano` FOREIGN KEY (`id_plano`) REFERENCES `planos` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO `alunos` (`id`, `nome`, `email`, `telefone`, `dt_nascimento`, `id_plano`) VALUES
(1, 'Ana Souza', 'ana.souza@example.com', '(11) 99999-1001', '1998-04-12', 1),
(2, 'Bruno Lima', 'bruno.lima@example.com', '(11) 99999-1002', '1995-09-23', 2),
(3, 'Carla Mendes', 'carla.mendes@example.com', '(21) 99999-1003', '2000-01-15', 3),
(4, 'Diego Alves', 'diego.alves@example.com', '(31) 99999-1004', '1992-07-08', 4),
(5, 'Erika Costa', 'erika.costa@example.com', '(41) 99999-1005', '1999-11-30', 5);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
