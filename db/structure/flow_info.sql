-- phpMyAdmin SQL Dump
-- version 3.3.2deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tempo de Geração: Ago 12, 2011 as 02:42 PM
-- Versão do Servidor: 5.1.41
-- Versão do PHP: 5.3.2-1ubuntu4.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Banco de Dados: `meican`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `flow_info`
--

DROP TABLE IF EXISTS `flow_info`;
CREATE TABLE IF NOT EXISTS `flow_info` (
  `flw_id` int(11) NOT NULL AUTO_INCREMENT,
  `src_meican_id` int(11) NOT NULL,
  `src_urn_string` varchar(128) NOT NULL,
  `src_vlan` int(11) DEFAULT NULL,
  `dst_meican_id` int(11) NOT NULL,
  `dst_urn_string` varchar(128) NOT NULL,
  `dst_vlan` int(11) DEFAULT NULL,
  `path` varchar(2056) DEFAULT NULL,
  PRIMARY KEY (`flw_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
