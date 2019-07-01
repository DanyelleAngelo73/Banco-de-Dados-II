-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Tempo de geração: 01/07/2019 às 10:56
-- Versão do servidor: 5.7.26-0ubuntu0.18.04.1
-- Versão do PHP: 7.2.17-0ubuntu0.18.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `controleDeFinancas`
--
CREATE DATABASE IF NOT EXISTS `controleDeFinancas` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `controleDeFinancas`;

DELIMITER $$
--
-- Procedimentos
--
DROP PROCEDURE IF EXISTS `inicializaRelatorio`$$
CREATE DEFINER=`admin`@`localhost` PROCEDURE `inicializaRelatorio` (`cod` INT, `mes` NUMERIC(2), `ano` NUMERIC(4))  begin
	declare receitas numeric(5,2) default '0.0';
	declare dinheiro numeric(5,2) default '0.0';
    declare cartao numeric(5,2) default '0.0';
    declare valorObjetivos numeric(5,2) default '0.0';
    set receitas = (select sum(Valor) from Transacoes t inner join Receitas r on t.Id=r.Id where (mes = month(DataTra) and ano = year(DataTra)) and Id_User = cod);
	set dinheiro =  (select sum(Valor) from Transacoes t inner join DespesasNoDinheiro d on t.Id=d.Id where (mes = month(DataTra) and ano = year(DataTra)) and Id_User = cod);
	set cartao =  (select sum(Valor) from Transacoes t inner join DespesasNoCartao d on t.Id=d.Id where (mes = month(DataTra) and ano = year(DataTra)) and Id_User = cod);
	set valorObjetivos =  (select sum(Valor_Final) from Objetivos where (mes <= month(DataFim) and ano <= year(DataFim)) and Id_User = cod);
	
    update RelatorioMensal set Receitas = receitas, DespesasCartao=cartao, DespesasDinheiro = dinheiro, ProximosObjetivos= valorObjetivos where (mes = Mes and ano = Ano and Id_User = cod);
end$$

--
-- Funções
--
DROP FUNCTION IF EXISTS `DespesasCartaoPorCategoria`$$
CREATE DEFINER=`admin`@`localhost` FUNCTION `DespesasCartaoPorCategoria` (`IdUserR` INT, `categoria` INT) RETURNS INT(11) begin
	return (
	select sum(Valor) from DespesasNoCartao d inner join Transacoes t on d.Id = t.Id where t.Id_User = IdUser and t.IdCategoria = categoria);
end$$

DROP FUNCTION IF EXISTS `DespesasDinheiroPorCategoria`$$
CREATE DEFINER=`admin`@`localhost` FUNCTION `DespesasDinheiroPorCategoria` (`IdUserR` INT, `categoria` INT) RETURNS INT(11) begin
	return (
	select sum(Valor) from DespesasNoDinheiro d inner join Transacoes t on d.Id = t.Id where t.Id_User = IdUserR  and t.IdCategoria = categoria);
end$$

DROP FUNCTION IF EXISTS `InicioDeMes`$$
CREATE DEFINER=`admin`@`localhost` FUNCTION `InicioDeMes` (`IdUserR` INT) RETURNS INT(11) begin
	insert into RelatorioMensal values(IdUserR,month(CURDATE()),year(CURDATE()),0.0,0.0,0.0,0.0);
	return null;
end$$

DROP FUNCTION IF EXISTS `ReceitasPorCategoria`$$
CREATE DEFINER=`admin`@`localhost` FUNCTION `ReceitasPorCategoria` (`IdUserR` INT, `categoria` INT) RETURNS INT(11) begin
	return (
	select sum(Valor) from Receitas r inner join Transacoes t on r.Id = t.Id  where t.Id_User = IdUserR and t.IdCategoria = categoria);
end$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `Cartao`
--

DROP TABLE IF EXISTS `Cartao`;
CREATE TABLE `Cartao` (
  `Id` int(11) NOT NULL,
  `Id_User` int(11) NOT NULL,
  `Nome` varchar(30) NOT NULL,
  `Bandeira` varchar(30) DEFAULT NULL,
  `Limite` decimal(20,2) DEFAULT NULL,
  `DataFechamento` decimal(2,0) DEFAULT NULL,
  `DataPagamento` decimal(2,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Fazendo dump de dados para tabela `Cartao`
--

INSERT INTO `Cartao` (`Id`, `Id_User`, `Nome`, `Bandeira`, `Limite`, `DataFechamento`, `DataPagamento`) VALUES
(4, 4, 'CartÃ£o', 'Visa', '1800.00', '27', '5'),
(5, 4, 'CartÃ£o 2', 'Elo', '300.00', '27', '5'),
(6, 19, 'Eloy sim', 'Elo', '120.00', '5', '16');

-- --------------------------------------------------------

--
-- Estrutura para tabela `Categoria`
--

DROP TABLE IF EXISTS `Categoria`;
CREATE TABLE `Categoria` (
  `Id` int(11) NOT NULL,
  `Id_User` int(11) NOT NULL,
  `Nome` varchar(30) NOT NULL,
  `Cor` varchar(7) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Fazendo dump de dados para tabela `Categoria`
--

INSERT INTO `Categoria` (`Id`, `Id_User`, `Nome`, `Cor`) VALUES
(7, 4, 'AlimentaÃ§Ã£o', '#ffa500'),
(25, 4, 'SaÃºde', '#00c6c9'),
(27, 19, 'AlimentaÃ§Ã£o', '#4b0082');

-- --------------------------------------------------------

--
-- Estrutura para tabela `DespesasNoCartao`
--

DROP TABLE IF EXISTS `DespesasNoCartao`;
CREATE TABLE `DespesasNoCartao` (
  `Id` int(11) NOT NULL,
  `Id_User` int(11) NOT NULL,
  `Juros` decimal(4,2) DEFAULT '0.00',
  `NumeroCartao` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Fazendo dump de dados para tabela `DespesasNoCartao`
--

INSERT INTO `DespesasNoCartao` (`Id`, `Id_User`, `Juros`, `NumeroCartao`) VALUES
(37, 4, '0.00', 4);

-- --------------------------------------------------------

--
-- Estrutura para tabela `DespesasNoDinheiro`
--

DROP TABLE IF EXISTS `DespesasNoDinheiro`;
CREATE TABLE `DespesasNoDinheiro` (
  `Id` int(11) NOT NULL,
  `Id_User` int(11) NOT NULL,
  `Juros` decimal(4,2) DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `Objetivos`
--

DROP TABLE IF EXISTS `Objetivos`;
CREATE TABLE `Objetivos` (
  `Id` int(11) NOT NULL,
  `Id_User` int(11) NOT NULL,
  `Nome` varchar(30) NOT NULL,
  `Descricao` varchar(200) DEFAULT NULL,
  `Valor_Inicial` decimal(20,2) DEFAULT NULL,
  `Valor_Final` decimal(20,2) DEFAULT NULL,
  `DataFim` date DEFAULT NULL,
  `Cor` varchar(7) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Fazendo dump de dados para tabela `Objetivos`
--

INSERT INTO `Objetivos` (`Id`, `Id_User`, `Nome`, `Descricao`, `Valor_Inicial`, `Valor_Final`, `DataFim`, `Cor`) VALUES
(20, 4, 'L', 'SalÃ¡rio', '50000.00', '60000.00', '2025-05-30', ''),
(21, 19, 'Comprar comida', 'Comprar comida', '10.00', '50.00', '2019-02-03', '');

--
-- Gatilhos `Objetivos`
--
DROP TRIGGER IF EXISTS `deleteObjetivos`;
DELIMITER $$
CREATE TRIGGER `deleteObjetivos` BEFORE DELETE ON `Objetivos` FOR EACH ROW begin
	declare DataObjetivo date;
    set DataObjetivo = (select DataFim from Objetivos where Id=OLD.Id and Id_User = OLD.Id_User);
    update RelatorioMensal set  ProximosObjetivos = ProximosObjetivos - (select Valor_Final from Objetivos where Id=OLD.Id and Id_User = OLD.Id_User) where Mes<=month(DataObjetivo) and Ano<=year(DataObjetivo);
end
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `insereObjetivos`;
DELIMITER $$
CREATE TRIGGER `insereObjetivos` AFTER INSERT ON `Objetivos` FOR EACH ROW begin
	declare DataObjetivo date;
    set DataObjetivo = (select DataFim from Objetivos where Id=NEW.Id and Id_User = NEW.Id_User);
    update RelatorioMensal set  ProximosObjetivos = ProximosObjetivos + (select Valor_Final from Objetivos where Id=NEW.Id and Id_User = NEW.Id_User) where Mes<=month(DataObjetivo) and Ano<=year(DataObjetivo);
end
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `updateObjetivos`;
DELIMITER $$
CREATE TRIGGER `updateObjetivos` AFTER UPDATE ON `Objetivos` FOR EACH ROW begin
	declare DataObjetivo date;
    set DataObjetivo = (select DataFim from Objetivos where Id=NEW.Id and Id_User = NEW.Id_User);
    update RelatorioMensal set  ProximosObjetivos = ProximosObjetivos - ((select Valor_Final from Objetivos where Id=NEW.Id and Id_User = NEW.Id_User) - (select Valor_Final from Objetivos where Id=OLD.Id and Id_User = OLD.Id_User)) where Mes<=month(DataObjetivo) and Ano<=year(DataObjetivo);
end
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `Receitas`
--

DROP TABLE IF EXISTS `Receitas`;
CREATE TABLE `Receitas` (
  `Id` int(11) NOT NULL,
  `Id_User` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Fazendo dump de dados para tabela `Receitas`
--

INSERT INTO `Receitas` (`Id`, `Id_User`) VALUES
(34, 4),
(38, 4),
(39, 4),
(40, 4);

-- --------------------------------------------------------

--
-- Estrutura para tabela `RelatorioMensal`
--

DROP TABLE IF EXISTS `RelatorioMensal`;
CREATE TABLE `RelatorioMensal` (
  `Id_User` int(11) NOT NULL,
  `Mes` decimal(2,0) NOT NULL,
  `Ano` decimal(4,0) NOT NULL,
  `Receitas` decimal(30,2) DEFAULT NULL,
  `DespesasDinheiro` decimal(30,2) DEFAULT NULL,
  `DespesasCartao` decimal(30,2) DEFAULT NULL,
  `ProximosObjetivos` decimal(30,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Fazendo dump de dados para tabela `RelatorioMensal`
--

INSERT INTO `RelatorioMensal` (`Id_User`, `Mes`, `Ano`, `Receitas`, `DespesasDinheiro`, `DespesasCartao`, `ProximosObjetivos`) VALUES
(4, '7', '2019', '0.00', '0.00', '0.00', '0.00'),
(19, '7', '2019', '0.00', '0.00', '0.00', '0.00');

-- --------------------------------------------------------

--
-- Estrutura para tabela `Transacoes`
--

DROP TABLE IF EXISTS `Transacoes`;
CREATE TABLE `Transacoes` (
  `Id` int(11) NOT NULL,
  `Id_User` int(11) NOT NULL,
  `Valor` decimal(20,2) DEFAULT NULL,
  `Situacao` varchar(16) NOT NULL,
  `Peridiocidade` varchar(16) DEFAULT 'Nenhuma',
  `Descricao` varchar(200) DEFAULT NULL,
  `DataTra` date DEFAULT NULL,
  `IdCategoria` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Fazendo dump de dados para tabela `Transacoes`
--

INSERT INTO `Transacoes` (`Id`, `Id_User`, `Valor`, `Situacao`, `Peridiocidade`, `Descricao`, `DataTra`, `IdCategoria`) VALUES
(34, 4, '0.00', 'value=A receber', 'Semanal', 'SalÃ¡rio1', '2019-07-05', 7),
(37, 4, '1000.00', 'A pagar', 'Semanal', 'fd', '2019-07-05', 7),
(38, 4, '0.00', 'Recebida', 'Semanal', 'Teste', '2019-07-05', 7),
(39, 4, '1000.00', 'Recebida', 'Semanal', 'SalÃ¡rio', '2019-07-05', 7),
(40, 4, '1000.00', 'Recebida', 'Semanal', 'Banana', '2019-07-05', 7);

-- --------------------------------------------------------

--
-- Estrutura para tabela `Usuario`
--

DROP TABLE IF EXISTS `Usuario`;
CREATE TABLE `Usuario` (
  `Id` int(11) NOT NULL,
  `Email` varchar(30) NOT NULL,
  `Nome` varchar(60) DEFAULT NULL,
  `Senha` varchar(32) DEFAULT NULL,
  `CPF` decimal(11,0) DEFAULT NULL,
  `Telefone` decimal(11,0) DEFAULT NULL,
  `Avatar` varchar(60) DEFAULT NULL,
  `Profissao` varchar(30) DEFAULT NULL,
  `CEP` decimal(8,0) DEFAULT NULL,
  `Endereco` varchar(30) DEFAULT NULL,
  `Idioma` varchar(16) DEFAULT NULL,
  `Moeda` varchar(16) DEFAULT NULL,
  `Data_Nascimento` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Fazendo dump de dados para tabela `Usuario`
--

INSERT INTO `Usuario` (`Id`, `Email`, `Nome`, `Senha`, `CPF`, `Telefone`, `Avatar`, `Profissao`, `CEP`, `Endereco`, `Idioma`, `Moeda`, `Data_Nascimento`) VALUES
(1, 'fernanda@gmail.com', 'Fernanda Lopes', '123456789', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'teste@gmail.com', 'teste', '123456789', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 'danyangelofsa@gmail.com', 'Danyelle da Silva Oliveira Angelo', '25f9e794323b453885f5181f1b624d0b', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 'ifb@ifb.com', 'ifb', '698dc19d489c4e4db73e28a713eab07b', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(17, 'emilia@gmail.com', 'taree', 'f778d4000d8ae1434d04eb8546ec3de2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(19, 'eloy@sim.com', 'Eloy sim', 'e9064b74d28acc053231170bb8c858b3', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `visualizaDespesasCartao`
-- (Veja abaixo para a visão atual)
--
DROP VIEW IF EXISTS `visualizaDespesasCartao`;
CREATE TABLE `visualizaDespesasCartao` (
`NumeroCartao` int(11)
,`IdCategoria` int(11)
,`NomeCategoria` varchar(30)
,`Cor` varchar(7)
,`IdTransacao` int(11)
,`Id_User` int(11)
,`Valor` decimal(20,2)
,`Situacao` varchar(16)
,`Peridiocidade` varchar(16)
,`Descricao` varchar(200)
,`DataTra` date
,`Juros` decimal(4,2)
,`IdCartao` int(11)
,`NomeCartao` varchar(30)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `visualizaDespesasDinheiro`
-- (Veja abaixo para a visão atual)
--
DROP VIEW IF EXISTS `visualizaDespesasDinheiro`;
CREATE TABLE `visualizaDespesasDinheiro` (
`IdCategoria` int(11)
,`NomeCategoria` varchar(30)
,`Cor` varchar(7)
,`IdTransacao` int(11)
,`Id_User` int(11)
,`Valor` decimal(20,2)
,`Situacao` varchar(16)
,`Peridiocidade` varchar(16)
,`Descricao` varchar(200)
,`DataTra` date
,`Juros` decimal(4,2)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `visualizaReceitas`
-- (Veja abaixo para a visão atual)
--
DROP VIEW IF EXISTS `visualizaReceitas`;
CREATE TABLE `visualizaReceitas` (
`IdCategoria` int(11)
,`NomeCategoria` varchar(30)
,`Cor` varchar(7)
,`IdTransacao` int(11)
,`Id_User` int(11)
,`Valor` decimal(20,2)
,`Situacao` varchar(16)
,`Peridiocidade` varchar(16)
,`Descricao` varchar(200)
,`DataTra` date
);

-- --------------------------------------------------------

--
-- Estrutura para view `visualizaDespesasCartao`
--
DROP TABLE IF EXISTS `visualizaDespesasCartao`;

CREATE ALGORITHM=UNDEFINED DEFINER=`admin`@`localhost` SQL SECURITY DEFINER VIEW `visualizaDespesasCartao`  AS  select `d`.`NumeroCartao` AS `NumeroCartao`,`c`.`Id` AS `IdCategoria`,`c`.`Nome` AS `NomeCategoria`,`c`.`Cor` AS `Cor`,`t`.`Id` AS `IdTransacao`,`t`.`Id_User` AS `Id_User`,`t`.`Valor` AS `Valor`,`t`.`Situacao` AS `Situacao`,`t`.`Peridiocidade` AS `Peridiocidade`,`t`.`Descricao` AS `Descricao`,`t`.`DataTra` AS `DataTra`,`d`.`Juros` AS `Juros`,`ca`.`Id` AS `IdCartao`,`ca`.`Nome` AS `NomeCartao` from (((`DespesasNoCartao` `d` join `Transacoes` `t` on((`t`.`Id` = `d`.`Id`))) join `Categoria` `c` on((`t`.`IdCategoria` = `c`.`Id`))) join `Cartao` `ca` on((`d`.`NumeroCartao` = `ca`.`Id`))) WITH CASCADED CHECK OPTION ;

-- --------------------------------------------------------

--
-- Estrutura para view `visualizaDespesasDinheiro`
--
DROP TABLE IF EXISTS `visualizaDespesasDinheiro`;

CREATE ALGORITHM=UNDEFINED DEFINER=`admin`@`localhost` SQL SECURITY DEFINER VIEW `visualizaDespesasDinheiro`  AS  select `c`.`Id` AS `IdCategoria`,`c`.`Nome` AS `NomeCategoria`,`c`.`Cor` AS `Cor`,`t`.`Id` AS `IdTransacao`,`t`.`Id_User` AS `Id_User`,`t`.`Valor` AS `Valor`,`t`.`Situacao` AS `Situacao`,`t`.`Peridiocidade` AS `Peridiocidade`,`t`.`Descricao` AS `Descricao`,`t`.`DataTra` AS `DataTra`,`d`.`Juros` AS `Juros` from ((`DespesasNoDinheiro` `d` join `Transacoes` `t` on((`t`.`Id` = `d`.`Id`))) join `Categoria` `c` on((`t`.`IdCategoria` = `c`.`Id`))) WITH CASCADED CHECK OPTION ;

-- --------------------------------------------------------

--
-- Estrutura para view `visualizaReceitas`
--
DROP TABLE IF EXISTS `visualizaReceitas`;

CREATE ALGORITHM=UNDEFINED DEFINER=`admin`@`localhost` SQL SECURITY DEFINER VIEW `visualizaReceitas`  AS  select `c`.`Id` AS `IdCategoria`,`c`.`Nome` AS `NomeCategoria`,`c`.`Cor` AS `Cor`,`t`.`Id` AS `IdTransacao`,`t`.`Id_User` AS `Id_User`,`t`.`Valor` AS `Valor`,`t`.`Situacao` AS `Situacao`,`t`.`Peridiocidade` AS `Peridiocidade`,`t`.`Descricao` AS `Descricao`,`t`.`DataTra` AS `DataTra` from ((`Receitas` `r` join `Transacoes` `t` on((`t`.`Id` = `r`.`Id`))) join `Categoria` `c` on((`t`.`IdCategoria` = `c`.`Id`))) WITH CASCADED CHECK OPTION ;

--
-- Índices de tabelas apagadas
--

--
-- Índices de tabela `Cartao`
--
ALTER TABLE `Cartao`
  ADD PRIMARY KEY (`Id`,`Id_User`),
  ADD KEY `fk_user` (`Id_User`);

--
-- Índices de tabela `Categoria`
--
ALTER TABLE `Categoria`
  ADD PRIMARY KEY (`Id`,`Id_User`),
  ADD KEY `fk_user` (`Id_User`);

--
-- Índices de tabela `DespesasNoCartao`
--
ALTER TABLE `DespesasNoCartao`
  ADD PRIMARY KEY (`Id`,`Id_User`),
  ADD KEY `fk_despesasCartao` (`NumeroCartao`);

--
-- Índices de tabela `DespesasNoDinheiro`
--
ALTER TABLE `DespesasNoDinheiro`
  ADD PRIMARY KEY (`Id`,`Id_User`);

--
-- Índices de tabela `Objetivos`
--
ALTER TABLE `Objetivos`
  ADD PRIMARY KEY (`Id`,`Id_User`),
  ADD KEY `fk_user` (`Id_User`);

--
-- Índices de tabela `Receitas`
--
ALTER TABLE `Receitas`
  ADD PRIMARY KEY (`Id`,`Id_User`);

--
-- Índices de tabela `RelatorioMensal`
--
ALTER TABLE `RelatorioMensal`
  ADD PRIMARY KEY (`Id_User`,`Mes`,`Ano`);

--
-- Índices de tabela `Transacoes`
--
ALTER TABLE `Transacoes`
  ADD PRIMARY KEY (`Id`,`Id_User`),
  ADD KEY `fk_user` (`Id_User`),
  ADD KEY `fk_transacoesCategoria` (`IdCategoria`);

--
-- Índices de tabela `Usuario`
--
ALTER TABLE `Usuario`
  ADD PRIMARY KEY (`Id`,`Email`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT de tabelas apagadas
--

--
-- AUTO_INCREMENT de tabela `Cartao`
--
ALTER TABLE `Cartao`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT de tabela `Categoria`
--
ALTER TABLE `Categoria`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
--
-- AUTO_INCREMENT de tabela `Objetivos`
--
ALTER TABLE `Objetivos`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT de tabela `Transacoes`
--
ALTER TABLE `Transacoes`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;
--
-- AUTO_INCREMENT de tabela `Usuario`
--
ALTER TABLE `Usuario`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- Restrições para dumps de tabelas
--

--
-- Restrições para tabelas `Cartao`
--
ALTER TABLE `Cartao`
  ADD CONSTRAINT `Cartao_ibfk_1` FOREIGN KEY (`Id_User`) REFERENCES `Usuario` (`Id`);

--
-- Restrições para tabelas `Categoria`
--
ALTER TABLE `Categoria`
  ADD CONSTRAINT `Categoria_ibfk_1` FOREIGN KEY (`Id_User`) REFERENCES `Usuario` (`Id`);

--
-- Restrições para tabelas `DespesasNoCartao`
--
ALTER TABLE `DespesasNoCartao`
  ADD CONSTRAINT `DespesasNoCartao_ibfk_1` FOREIGN KEY (`Id`,`Id_User`) REFERENCES `Transacoes` (`Id`, `Id_User`),
  ADD CONSTRAINT `DespesasNoCartao_ibfk_2` FOREIGN KEY (`NumeroCartao`) REFERENCES `Cartao` (`Id`);

--
-- Restrições para tabelas `DespesasNoDinheiro`
--
ALTER TABLE `DespesasNoDinheiro`
  ADD CONSTRAINT `DespesasNoDinheiro_ibfk_1` FOREIGN KEY (`Id`,`Id_User`) REFERENCES `Transacoes` (`Id`, `Id_User`);

--
-- Restrições para tabelas `Objetivos`
--
ALTER TABLE `Objetivos`
  ADD CONSTRAINT `Objetivos_ibfk_1` FOREIGN KEY (`Id_User`) REFERENCES `Usuario` (`Id`);

--
-- Restrições para tabelas `Receitas`
--
ALTER TABLE `Receitas`
  ADD CONSTRAINT `Receitas_ibfk_1` FOREIGN KEY (`Id`,`Id_User`) REFERENCES `Transacoes` (`Id`, `Id_User`);

--
-- Restrições para tabelas `RelatorioMensal`
--
ALTER TABLE `RelatorioMensal`
  ADD CONSTRAINT `RelatorioMensal_ibfk_1` FOREIGN KEY (`Id_User`) REFERENCES `Usuario` (`Id`);

--
-- Restrições para tabelas `Transacoes`
--
ALTER TABLE `Transacoes`
  ADD CONSTRAINT `Transacoes_ibfk_1` FOREIGN KEY (`Id_User`) REFERENCES `Usuario` (`Id`),
  ADD CONSTRAINT `Transacoes_ibfk_2` FOREIGN KEY (`IdCategoria`) REFERENCES `Categoria` (`Id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
