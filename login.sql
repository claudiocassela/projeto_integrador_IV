CREATE TABLE `login` (
  `id` int(11) NOT NULL COMMENT 'Código identificador',
  `us` varchar(100) NOT NULL COMMENT 'usuário do sistema',
  `no` varchar(100) NOT NULL COMMENT 'Nome do usuário',
  `ad` int(11) NOT NULL DEFAULT 0 COMMENT 'tipo de usuário',
  `pw` longtext NOT NULL COMMENT 'senha (MD5)'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `login` (`id`, `us`, `no`, `ad`, `pw`) VALUES
(1, 'univesp', 'Projeto Integrador IV', 0, '85fb2908ca1cb55c67c4d57eb6e0e46f');


ALTER TABLE `login`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Código identificador', AUTO_INCREMENT=2;
COMMIT;

