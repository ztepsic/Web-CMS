-- phpMyAdmin SQL Dump
-- version 2.11.7
-- http://www.phpmyadmin.net
--
-- Računalo: localhost
-- Vrijeme generiranja: Sij 09, 2009 u 10:20 PM
-- Verzija poslužitelja: 5.0.51
-- PHP verzija: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Baza podataka: `zavrsni`
--

-- --------------------------------------------------------

--
-- Tablična struktura za tablicu `ci_sessions`
--

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `session_id` varchar(40) collate utf8_unicode_ci NOT NULL COMMENT 'Identifikator sessiona',
  `user_id` int(11) default NULL COMMENT 'Identifikator korisnika',
  `ip_address` varchar(16) collate utf8_unicode_ci NOT NULL COMMENT 'Ip adresa.',
  `user_agent` varchar(50) collate utf8_unicode_ci NOT NULL COMMENT 'User agent.',
  `last_page` varchar(200) collate utf8_unicode_ci default NULL COMMENT 'Posljednje pregledana stranica.',
  `last_activity` int(11) NOT NULL COMMENT 'Posljednja aktivnos.',
  `user_data` text collate utf8_unicode_ci COMMENT 'Session podaci.',
  PRIMARY KEY  (`session_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Izbacivanje podataka za tablicu `ci_sessions`
--

INSERT INTO `ci_sessions` (`session_id`, `user_id`, `ip_address`, `user_agent`, `last_page`, `last_activity`, `user_data`) VALUES
('0735522da02b27ce1e11f57834e57b25', NULL, '127.0.0.1', 'Opera/9.63 (Windows NT 5.1; U; en) Presto/2.1.1', NULL, 1231532364, 'a:4:{s:7:"user_id";s:1:"1";s:13:"user_username";s:7:"ztepsic";s:9:"user_name";s:16:"Željko Tepšić";s:15:"primary_role_id";s:2:"-1";}'),
('31f0422b43fd67983cc22b95ba798b0b', NULL, '127.0.0.1', 'Opera/9.63 (Windows NT 5.1; U; en) Presto/2.1.1', NULL, 1231534552, 'a:4:{s:7:"user_id";s:1:"1";s:13:"user_username";s:7:"ztepsic";s:9:"user_name";s:16:"Željko Tepšić";s:15:"primary_role_id";s:2:"-1";}'),
('781d8a7441d8ad0a04e7b2b6ea8c67e3', NULL, '127.0.0.1', 'Opera/9.63 (Windows NT 5.1; U; en) Presto/2.1.1', NULL, 1231528759, 'a:1:{s:7:"user_id";s:1:"0";}'),
('8fc20a11b2ccf0118c4103db860c8ded', NULL, '127.0.0.1', 'Opera/9.63 (Windows NT 5.1; U; en) Presto/2.1.1', NULL, 1231535685, 'a:4:{s:7:"user_id";s:1:"1";s:13:"user_username";s:5:"admin";s:9:"user_name";s:5:"admin";s:15:"primary_role_id";s:2:"-1";}'),
('e7125dc067753cd68d36173121286284', NULL, '127.0.0.1', 'Opera/9.63 (Windows NT 5.1; U; en) Presto/2.1.1', NULL, 1231533739, 'a:4:{s:7:"user_id";s:1:"1";s:13:"user_username";s:7:"ztepsic";s:9:"user_name";s:16:"Željko Tepšić";s:15:"primary_role_id";s:2:"-1";}');

-- --------------------------------------------------------

--
-- Tablična struktura za tablicu `zt_actions`
--

CREATE TABLE IF NOT EXISTS `zt_actions` (
  `action_id` int(11) NOT NULL auto_increment COMMENT 'Identifikator akcije.',
  `action_name` varchar(50) collate utf8_unicode_ci NOT NULL COMMENT 'Naziv akcije',
  `action_alias` varchar(50) collate utf8_unicode_ci NOT NULL COMMENT 'Kodni naziv za akciju',
  `action_description` text collate utf8_unicode_ci COMMENT 'Opis akcije',
  `action_locked` tinyint(1) NOT NULL default '0' COMMENT 'Akcija zakljucana za modifikaciju i brisanje.',
  PRIMARY KEY  (`action_id`),
  UNIQUE KEY `action_alias` (`action_alias`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Izbacivanje podataka za tablicu `zt_actions`
--

INSERT INTO `zt_actions` (`action_id`, `action_name`, `action_alias`, `action_description`, `action_locked`) VALUES
(1, 'Čitanje', 'read', 'Akcija omogučava čitanje odnosno pregledavanje sadržaja.', 1),
(2, 'Stvaranje', 'create', 'Akcija omogućava stvaranje novog sadržaja.', 1),
(3, 'Ažuriranje', 'update', 'Akcija omogućava ažuriranje postojećeg sadržaja.', 1),
(4, 'Brisanje', 'delete', 'Akcija omogućava brisanje sadržaja.', 1);

-- --------------------------------------------------------

--
-- Tablična struktura za tablicu `zt_components`
--

CREATE TABLE IF NOT EXISTS `zt_components` (
  `component_id` int(11) NOT NULL auto_increment COMMENT 'Identifikator komponente',
  `component_name` varchar(50) collate utf8_unicode_ci NOT NULL COMMENT 'Ime komponente',
  `component_description` text collate utf8_unicode_ci COMMENT 'Opis instance komponente.',
  `component_type_id` int(11) NOT NULL COMMENT 'Vrsta komponente.',
  `component_alias` varchar(200) collate utf8_unicode_ci default NULL COMMENT 'Alias',
  `component_params` text collate utf8_unicode_ci COMMENT 'Parametri komponente.',
  PRIMARY KEY  (`component_id`),
  KEY `component_type_id` (`component_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=12 ;

--
-- Izbacivanje podataka za tablicu `zt_components`
--

INSERT INTO `zt_components` (`component_id`, `component_name`, `component_description`, `component_type_id`, `component_alias`, `component_params`) VALUES
(1, 'Admin - FrontPage', 'Početna stranica', 1, 'home', NULL),
(2, 'Admin - Site settings', 'Postavke stranice', 2, 'sitesettings', NULL),
(3, 'Admin - Users', 'Korisnici', 3, 'users', NULL),
(4, 'Admin - Extensions', 'Ekstenzije', 4, 'extensions', NULL),
(5, 'Admin - Pages', 'Stranice', 5, 'pages', NULL),
(6, 'Admin - Roles', 'Uloge', 6, 'roles', NULL),
(8, 'Admin - Installer', 'Instalacija', 8, 'installer', NULL),
(9, 'Admin - Menus', 'Izbornici', 9, 'menu', NULL),
(10, 'Admin - Grupe modula', 'Grupe modula', 10, 'modules', NULL),
(11, 'Naslovnica', 'Naslovnica', 7, 'naslovnica', NULL);

-- --------------------------------------------------------

--
-- Tablična struktura za tablicu `zt_component_methods`
--

CREATE TABLE IF NOT EXISTS `zt_component_methods` (
  `component_method_id` int(11) NOT NULL auto_increment COMMENT 'Identifikator instance komponente.',
  `component_method_alias` varchar(100) collate utf8_unicode_ci NOT NULL COMMENT 'Alias metode.',
  `component_type_method_id` int(11) default NULL COMMENT 'Identifikator originalne metode tipa komponente.',
  `component_id` int(11) NOT NULL COMMENT 'Identifikator instance koponente.',
  PRIMARY KEY  (`component_method_id`),
  KEY `component_type_method_id` (`component_type_method_id`),
  KEY `component_id` (`component_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=48 ;

--
-- Izbacivanje podataka za tablicu `zt_component_methods`
--

INSERT INTO `zt_component_methods` (`component_method_id`, `component_method_alias`, `component_type_method_id`, `component_id`) VALUES
(1, 'naslovnica', 1, 1),
(2, 'login', 2, 1),
(3, 'logout', 3, 1),
(4, 'index', 4, 2),
(5, 'index', 5, 3),
(6, 'create', 6, 3),
(7, 'edit', 7, 3),
(8, 'delete', 8, 3),
(9, 'componenttypes', 9, 4),
(10, 'componenttype_activate', 10, 4),
(11, 'componenttype_deactivate', 11, 4),
(12, 'moduletypes', 12, 4),
(13, 'moduletype_activate', 13, 4),
(14, 'moduletype_deactivate', 14, 4),
(15, 'components', 15, 4),
(16, 'component_create', 16, 4),
(17, 'component_mehtods_create', 17, 4),
(18, 'component_edit', 18, 4),
(19, 'component_mehtods_edit', 19, 4),
(20, 'component_delete', 20, 4),
(21, 'modules', 21, 4),
(22, 'module_create', 22, 4),
(23, 'module_edit', 23, 4),
(24, 'module_delete', 24, 4),
(25, 'index', 25, 5),
(26, 'page_modules', 26, 5),
(27, 'page_permissions', 27, 5),
(28, 'index', 28, 6),
(29, 'create', 29, 6),
(30, 'edit', 30, 6),
(31, 'delete', 31, 6),
(33, 'index', 33, 8),
(34, 'index', 34, 9),
(35, 'menu_create', 35, 9),
(36, 'menu_edit', 36, 9),
(37, 'menu_delete', 37, 9),
(38, 'menu_items', 38, 9),
(39, 'menu_item_create', 39, 9),
(40, 'menu_item_edit', 40, 9),
(41, 'menu_item_delete', 41, 9),
(42, 'index', 42, 10),
(43, 'create', 43, 10),
(44, 'edit', 44, 10),
(45, 'delete', 45, 10),
(46, 'groupitems', 46, 10),
(47, 'naslovnica', 32, 11);

-- --------------------------------------------------------

--
-- Tablična struktura za tablicu `zt_component_types`
--

CREATE TABLE IF NOT EXISTS `zt_component_types` (
  `component_type_id` int(11) NOT NULL auto_increment COMMENT 'Identifikator tipa komponente.',
  `component_type_name` varchar(30) collate utf8_turkish_ci NOT NULL COMMENT 'Naziv tipa komponente.',
  `component_type_description` text collate utf8_turkish_ci NOT NULL COMMENT 'Opis tipa komponente.',
  `component_type_alias` varchar(50) collate utf8_turkish_ci NOT NULL COMMENT 'Alias komponente.',
  `package` varchar(100) collate utf8_turkish_ci NOT NULL COMMENT 'Paket',
  `component_type_mulltiple_instances` tinyint(1) NOT NULL COMMENT 'Da li su dozvoljene visestruke instance.',
  `component_type_admin` tinyint(1) NOT NULL COMMENT 'Da li je komponenta administratorska.',
  PRIMARY KEY  (`component_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci AUTO_INCREMENT=11 ;

--
-- Izbacivanje podataka za tablicu `zt_component_types`
--

INSERT INTO `zt_component_types` (`component_type_id`, `component_type_name`, `component_type_description`, `component_type_alias`, `package`, `component_type_mulltiple_instances`, `component_type_admin`) VALUES
(1, 'Admin - FrontPage', 'Početna stranica', 'home', 'core', 0, 1),
(2, 'Admin - Site settings', 'Postavke stranice', 'site_settings', 'core', 0, 1),
(3, 'Admin - Users', 'Korisnici', 'users', 'core', 0, 1),
(4, 'Admin - Extensions', 'Ekstenzije', 'extensions', 'core', 0, 1),
(5, 'Admin - Pages', 'Stranice', 'pages', 'core', 0, 1),
(6, 'Admin - Roles', 'Uloge', 'roles', 'core', 0, 1),
(7, 'FrontPage', 'Početna stranica', 'home', '', 0, 0),
(8, 'Admin - Installer', 'Instalacija', 'installer', 'core', 0, 1),
(9, 'Admin - Menus', 'Izbornici', 'menu', '', 0, 1),
(10, 'Admin - Grupe modula', 'Grupe modula', 'modules', 'core', 0, 1);

-- --------------------------------------------------------

--
-- Tablična struktura za tablicu `zt_component_type_methods`
--

CREATE TABLE IF NOT EXISTS `zt_component_type_methods` (
  `component_type_method_id` int(11) NOT NULL auto_increment COMMENT 'Identifikator metode tipa komponente.',
  `component_type_method_name` varchar(100) collate utf8_unicode_ci NOT NULL COMMENT 'Naziv metode',
  `component_type_id` int(11) NOT NULL COMMENT 'Identifikator tipa komponente',
  `compoent_type_method_has_params` tinyint(1) NOT NULL default '0' COMMENT 'Da li metoda prima parametre',
  `component_type_method_back` tinyint(1) NOT NULL default '0' COMMENT 'Da li metoda obraduje u pozadini podatke.',
  PRIMARY KEY  (`component_type_method_id`),
  KEY `component_type_id` (`component_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=47 ;

--
-- Izbacivanje podataka za tablicu `zt_component_type_methods`
--

INSERT INTO `zt_component_type_methods` (`component_type_method_id`, `component_type_method_name`, `component_type_id`, `compoent_type_method_has_params`, `component_type_method_back`) VALUES
(1, 'index', 1, 0, 0),
(2, 'login', 1, 0, 0),
(3, 'logout', 1, 0, 1),
(4, 'index', 2, 0, 0),
(5, 'index', 3, 0, 0),
(6, 'create', 3, 0, 0),
(7, 'edit', 3, 1, 0),
(8, 'delete', 3, 1, 1),
(9, 'componenttypes', 4, 0, 0),
(10, 'componenttype_activate', 4, 1, 1),
(11, 'componenttype_deactivate', 4, 1, 1),
(12, 'moduletypes', 4, 0, 0),
(13, 'moduletype_activate', 4, 1, 1),
(14, 'moduletype_deactivate', 4, 1, 1),
(15, 'components', 4, 0, 0),
(16, 'component_create', 4, 0, 0),
(17, 'component_mehtods_create', 4, 0, 0),
(18, 'component_edit', 4, 1, 0),
(19, 'component_mehtods_edit', 4, 1, 0),
(20, 'component_delete', 4, 1, 1),
(21, 'modules', 4, 0, 0),
(22, 'module_create', 4, 0, 0),
(23, 'module_edit', 4, 1, 0),
(24, 'module_delete', 4, 1, 1),
(25, 'index', 5, 0, 0),
(26, 'page_modules', 5, 1, 0),
(27, 'page_permissions', 5, 1, 0),
(28, 'index', 6, 0, 0),
(29, 'create', 6, 0, 0),
(30, 'edit', 6, 1, 0),
(31, 'delete', 6, 1, 1),
(32, 'index', 7, 0, 0),
(33, 'index', 8, 0, 0),
(34, 'index', 9, 0, 0),
(35, 'menu_create', 9, 0, 0),
(36, 'menu_edit', 9, 1, 0),
(37, 'menu_delete', 9, 1, 1),
(38, 'menu_items', 9, 1, 0),
(39, 'menu_item_create', 9, 1, 0),
(40, 'menu_item_edit', 9, 1, 0),
(41, 'menu_item_delete', 9, 1, 1),
(42, 'index', 10, 0, 0),
(43, 'create', 10, 0, 0),
(44, 'edit', 10, 1, 0),
(45, 'delete', 10, 1, 1),
(46, 'groupitems', 10, 1, 0);

-- --------------------------------------------------------

--
-- Tablična struktura za tablicu `zt_layouts`
--

CREATE TABLE IF NOT EXISTS `zt_layouts` (
  `layout_id` int(11) NOT NULL auto_increment COMMENT 'Identifikator layout-a',
  `layout_name` varchar(30) collate utf8_unicode_ci NOT NULL COMMENT 'Naziv layout-a',
  `layout_file` varchar(100) collate utf8_unicode_ci NOT NULL COMMENT 'Naziv datoteke bez .php ekstenzije.',
  PRIMARY KEY  (`layout_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Izbacivanje podataka za tablicu `zt_layouts`
--

INSERT INTO `zt_layouts` (`layout_id`, `layout_name`, `layout_file`) VALUES
(1, 'Admin', 'admin_view'),
(2, 'Master', 'master_view');

-- --------------------------------------------------------

--
-- Tablična struktura za tablicu `zt_layout_positions`
--

CREATE TABLE IF NOT EXISTS `zt_layout_positions` (
  `layout_position_id` int(11) NOT NULL auto_increment COMMENT 'Identifikator layout pozicijie.',
  `layout_position_name` varchar(30) collate utf8_unicode_ci NOT NULL COMMENT 'Naziv pozicije.',
  `layout_position_description` text collate utf8_unicode_ci COMMENT 'Opis layout pozicije.',
  `layout_position_alias` varchar(50) collate utf8_unicode_ci NOT NULL COMMENT 'Alias layout pozicije.',
  `layout_id` int(11) NOT NULL COMMENT 'Identifikator layout-a.',
  PRIMARY KEY  (`layout_position_id`),
  KEY `layout_id` (`layout_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Izbacivanje podataka za tablicu `zt_layout_positions`
--

INSERT INTO `zt_layout_positions` (`layout_position_id`, `layout_position_name`, `layout_position_description`, `layout_position_alias`, `layout_id`) VALUES
(1, 'Header', NULL, 'header', 2),
(5, 'Side column', NULL, 'side_column', 2),
(6, 'Footer', NULL, 'footer', 2);

-- --------------------------------------------------------

--
-- Tablična struktura za tablicu `zt_menus`
--

CREATE TABLE IF NOT EXISTS `zt_menus` (
  `menu_id` int(11) NOT NULL auto_increment COMMENT 'Identifikator izbornika.',
  `menu_type` varchar(50) collate utf8_unicode_ci NOT NULL COMMENT 'Vrsta izbornika',
  `menu_name` varchar(50) collate utf8_unicode_ci NOT NULL COMMENT 'Ime izbornika.',
  `menu_description` text collate utf8_unicode_ci NOT NULL COMMENT 'Opis izbornika.',
  PRIMARY KEY  (`menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Izbacivanje podataka za tablicu `zt_menus`
--


-- --------------------------------------------------------

--
-- Tablična struktura za tablicu `zt_menu_items`
--

CREATE TABLE IF NOT EXISTS `zt_menu_items` (
  `menu_item_id` int(11) NOT NULL auto_increment COMMENT 'Identifikator elementa izbornika.',
  `menu_id` int(11) NOT NULL COMMENT 'Identifikator izbornika.',
  `menu_item_name` varchar(50) collate utf8_unicode_ci NOT NULL COMMENT 'Ime elementa izbornika.',
  `menu_item_description` text collate utf8_unicode_ci COMMENT 'Opis elementa izbornika',
  `page_id` int(11) NOT NULL COMMENT 'Identifikator stranice na koju element izbornika pokazuje.',
  `menu_item_id_parent` int(11) default NULL COMMENT 'Identifikator roditelje elementa izbornika.',
  `menu_item_params` text collate utf8_unicode_ci COMMENT 'Paremetri elementa izbornika.',
  `menu_item_order` mediumint(9) NOT NULL COMMENT 'Redoslijed elementa izbornika.',
  `menu_item_published` tinyint(1) NOT NULL COMMENT 'Da li je element izbornika omogucen/objavljen.',
  PRIMARY KEY  (`menu_item_id`),
  KEY `menu_item_id_parent` (`menu_item_id_parent`),
  KEY `page_id` (`page_id`),
  KEY `menu_id` (`menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Izbacivanje podataka za tablicu `zt_menu_items`
--


-- --------------------------------------------------------

--
-- Tablična struktura za tablicu `zt_modules`
--

CREATE TABLE IF NOT EXISTS `zt_modules` (
  `module_id` int(11) NOT NULL auto_increment COMMENT 'Identifikator modula.',
  `module_name` varchar(50) collate utf8_unicode_ci NOT NULL COMMENT 'Ime modula.',
  `module_description` text collate utf8_unicode_ci COMMENT 'Opis instance modula.',
  `module_type_id` int(11) NOT NULL COMMENT 'Vrsta modula',
  `component_id` int(11) default NULL COMMENT 'Identifikator instance komponente na koju je instanca modula vezana.',
  `module_published` tinyint(1) NOT NULL default '0' COMMENT 'Da li je modul omogucen/objavljen.',
  `module_params` text collate utf8_unicode_ci COMMENT 'Parametri potrebni modulu.',
  `module_locked` tinyint(1) NOT NULL default '0' COMMENT 'Modul nije moguce brisati.',
  `module_default` tinyint(1) NOT NULL default '0' COMMENT 'Osnovni modul, dolazi po defaultu za svaku stranicu.',
  PRIMARY KEY  (`module_id`),
  KEY `component_id` (`component_id`),
  KEY `module_type_id` (`module_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Izbacivanje podataka za tablicu `zt_modules`
--


-- --------------------------------------------------------

--
-- Tablična struktura za tablicu `zt_modules_groups`
--

CREATE TABLE IF NOT EXISTS `zt_modules_groups` (
  `modules_group_id` int(11) NOT NULL auto_increment COMMENT 'Identifikator grupe modula.',
  `modules_group_name` varchar(50) collate utf8_unicode_ci NOT NULL COMMENT 'Ime grupe modula',
  `modules_group_published` tinyint(1) NOT NULL default '0' COMMENT 'Da li je grupa omogucena/objavljena',
  `modules_group_locked` tinyint(1) NOT NULL default '0' COMMENT 'Da li je grupa zakljucana za modifikaciju i brisanje',
  `modules_group_default` tinyint(1) NOT NULL default '0' COMMENT 'Da li je to osnovna grupa, prikazuje se na svakoj stranici.',
  PRIMARY KEY  (`modules_group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Izbacivanje podataka za tablicu `zt_modules_groups`
--


-- --------------------------------------------------------

--
-- Tablična struktura za tablicu `zt_modules_group_items`
--

CREATE TABLE IF NOT EXISTS `zt_modules_group_items` (
  `modules_group_item_id` int(11) NOT NULL auto_increment COMMENT 'Identifikator elementata grupe modula',
  `modules_group_id` int(11) NOT NULL COMMENT 'Identifikator grupe kojoj pripada element.',
  `modules_group_id_element` int(11) default NULL COMMENT 'Identifikator grupe modula kao element parent grupe.',
  `module_id` int(11) default NULL COMMENT 'Identifikator modula kao element parent grupe.',
  `modules_group_item_order` mediumint(9) NOT NULL COMMENT 'Redoslijed elemenata u grupi',
  PRIMARY KEY  (`modules_group_item_id`),
  KEY `module_id` (`module_id`),
  KEY `modules_group_id` (`modules_group_id`),
  KEY `modules_group_id_element` (`modules_group_id_element`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Izbacivanje podataka za tablicu `zt_modules_group_items`
--


-- --------------------------------------------------------

--
-- Tablična struktura za tablicu `zt_module_types`
--

CREATE TABLE IF NOT EXISTS `zt_module_types` (
  `module_type_id` int(11) NOT NULL auto_increment COMMENT 'Identifikator tipa modula.',
  `module_type_name` varchar(30) collate utf8_unicode_ci NOT NULL COMMENT 'Naziv tipa modula.',
  `module_type_description` text collate utf8_unicode_ci NOT NULL COMMENT 'Opis tipa modula.',
  `component_type_id` int(11) default NULL COMMENT 'Veza sa tipom komponente.',
  `module_type_active` tinyint(1) NOT NULL COMMENT 'Da li je tip modula aktivan.',
  `package` varchar(100) collate utf8_unicode_ci NOT NULL COMMENT 'Naziv paketa',
  `module_type_alias` varchar(50) collate utf8_unicode_ci NOT NULL COMMENT 'Alias tipa modula.',
  `module_type_mulltiple_instances` tinyint(1) NOT NULL COMMENT 'Da li su dozvoljene visetruke instance tipa modula.',
  `module_type_admin` tinyint(1) NOT NULL COMMENT 'Modul za administratorski dio',
  PRIMARY KEY  (`module_type_id`),
  UNIQUE KEY `module_type_alias` (`module_type_alias`),
  KEY `component_type_id` (`component_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Izbacivanje podataka za tablicu `zt_module_types`
--

INSERT INTO `zt_module_types` (`module_type_id`, `module_type_name`, `module_type_description`, `component_type_id`, `module_type_active`, `package`, `module_type_alias`, `module_type_mulltiple_instances`, `module_type_admin`) VALUES
(1, 'Menus', 'Izbornik', NULL, 1, 'menu', 'menu', 0, 0);

-- --------------------------------------------------------

--
-- Tablična struktura za tablicu `zt_pages`
--

CREATE TABLE IF NOT EXISTS `zt_pages` (
  `page_id` int(11) NOT NULL auto_increment COMMENT 'Identifikator stranice.',
  `page_name` varchar(50) collate utf8_unicode_ci NOT NULL COMMENT 'Ime stranice.',
  `component_method_id` int(11) NOT NULL COMMENT 'Identifikator komponente koja je zaduzena za prikaz stranice.',
  `page_link` varchar(200) collate utf8_unicode_ci default NULL COMMENT 'Link na stranicu',
  `page_pattern` varchar(200) collate utf8_unicode_ci NOT NULL COMMENT 'Pattern po kojemu se odreduje da li vanjski URL zadovoljava navedeni pattern.',
  `page_route` varchar(200) collate utf8_unicode_ci NOT NULL COMMENT 'Ruta preko kojese aktivira/poziva/pokrece kontroler odgovoran za prikaz stranice.',
  `page_params` text collate utf8_unicode_ci COMMENT 'Parametri za stranicu.',
  `page_locked_by_component` tinyint(1) NOT NULL COMMENT 'Stranica zatvorena od strane komponente i nije ju moguce direktno obrisati.',
  `layout_id` int(11) default NULL COMMENT 'Layout u kojem ce se prikazati sadrzaj',
  `page_public` tinyint(1) NOT NULL default '1' COMMENT 'Da li je stranicu moguce linkati.',
  PRIMARY KEY  (`page_id`),
  KEY `layout_id` (`layout_id`),
  KEY `component_method_id` (`component_method_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=47 ;

--
-- Izbacivanje podataka za tablicu `zt_pages`
--

INSERT INTO `zt_pages` (`page_id`, `page_name`, `component_method_id`, `page_link`, `page_pattern`, `page_route`, `page_params`, `page_locked_by_component`, `layout_id`, `page_public`) VALUES
(1, 'Admin - FrontPage - index', 1, NULL, '^(admin|admin/(home|naslovnica))$', 'admin/home/index', NULL, 1, 1, 1),
(2, 'Admin - FrontPage - login', 2, NULL, '^admin/login$', 'admin/home/login', NULL, 1, 1, 0),
(3, 'Admin - FrontPage - logout', 3, NULL, '^admin/logout$', 'admin/home/logout', NULL, 1, 1, 0),
(4, 'Admin - Site settings - index', 4, NULL, '^admin/(site_settings|site_settings/index)$', 'admin/site_settings/index', NULL, 1, 1, 1),
(5, 'Admin - Users -index', 5, NULL, '^admin/users$', 'admin/users/index', NULL, 1, 1, 1),
(6, 'Admin - Users - create', 6, NULL, '^admin/users/create$', 'admin/users/create', NULL, 1, 1, 1),
(7, 'Admin - Users - edit', 7, NULL, '^admin/users/edit/([0-9]+)$', 'admin/users/edit/$1', NULL, 1, 1, 1),
(8, 'Admin - Users - delete', 8, NULL, '^admin/users/delete/([0-9]+)$', 'admin/users/delete/$1', NULL, 1, 1, 0),
(9, 'Admin - Extensions - component types', 9, NULL, '^admin/extensions/componenttypes$', 'admin/extensions/componenttypes', NULL, 1, 1, 1),
(12, 'Admin - Extensions - module types', 12, NULL, '^admin/extensions/moduletypes$', 'admin/extensions/moduletypes', NULL, 1, 1, 1),
(15, 'Admin - Extensions - components', 15, NULL, '^admin/extensions/components', 'admin/extensions/components', NULL, 1, 1, 1),
(16, 'Admin - Extensions - component create', 16, NULL, '^admin/extensions/component_create', 'admin/extensions/component_create', NULL, 1, 1, 1),
(17, 'Admin - Extensions - component mehtods create', 17, NULL, '^admin/extensions/component_mehtods_create$', 'admin/extensions/component_mehtods_create', NULL, 1, 1, 1),
(18, 'Admin - Extensions - component edit', 18, NULL, '^admin/extensions/component_edit/([0-9]+)$', 'admin/extensions/component_edit/$1', NULL, 1, 1, 1),
(19, 'Admin - Extensions - component mehtods edit', 19, NULL, '^admin/extensions/component_mehtods_edit/([0-9]+)$', 'admin/extensions/component_mehtods_edit/$1', NULL, 1, 1, 1),
(20, 'Admin - Extensions - component delete', 20, NULL, '^admin/extensions/component_delete/([0-9]+)$', 'admin/extensions/component_delete/$1', NULL, 1, NULL, 0),
(21, 'Admin - Extensions - modules', 21, NULL, '^admin/extensions/modules$', 'admin/extensions/modules', NULL, 1, 1, 1),
(22, 'Admin - Extensions - module create', 22, NULL, '^admin/extensions/module_create$', 'admin/extensions/module_create', NULL, 1, 1, 1),
(23, 'Admin - Extensions - module edit', 23, NULL, '^admin/extensions/module_edit/([0-9]+)$', 'admin/extensions/module_edit/$1', NULL, 1, 1, 1),
(24, 'Admin - Extensions - module delete', 24, NULL, '^admin/extensions/module_delete/([0-9]+)$', 'admin/extensions/module_delete/$1', NULL, 1, NULL, 0),
(25, 'Admin - Pages - index', 25, NULL, '^(admin/pages|admin/pages/([0-9]+))$', 'admin/pages/index/$3', NULL, 1, 1, 1),
(26, 'Admin - Pages - page modules', 26, NULL, '^admin/pages/page_modules/([0-9]+)$', 'admin/pages/page_modules/$1', NULL, 1, 1, 1),
(27, 'Admin - Pages - page permissions', 27, NULL, '^admin/pages/page_permissions/([0-9]+)$', 'admin/pages/page_permissions/$1', NULL, 1, 1, 1),
(28, 'Admin - Roles - index', 28, NULL, '^admin/roles$', 'admin/roles', NULL, 1, 1, 1),
(29, 'Admin - Roles - create', 29, NULL, '^admin/roles/create$', 'admin/roles/create', NULL, 1, 1, 1),
(30, 'Admin - Roles - edit', 30, NULL, '^admin/roles/edit/([0-9]+)$', 'admin/roles/edit/$1', NULL, 1, 1, 1),
(31, 'Admin - Roles - delete', 30, NULL, '^admin/roles/delete/([0-9]+)$', 'admin/roles/delete/$1', NULL, 1, NULL, 0),
(32, 'Admin - Installer - index', 33, NULL, '^admin/installer$', 'admin/installer', NULL, 1, 1, 1),
(33, 'Admin - Menus - index', 34, NULL, '^admin/menus$', 'admin/menus/index', NULL, 1, 1, 1),
(34, 'Admin - Menu - menu create', 35, NULL, '^admin/menus/menu_create$', 'admin/menus/menu_create', NULL, 1, 1, 1),
(35, 'Admin - Menu - menu edit', 36, NULL, '^admin/menus/menu_edit/([0-9]+)$', 'admin/menus/menu_edit/$1', NULL, 1, 1, 1),
(36, 'Admin - Menu - menu delete', 37, NULL, '^admin/menus/menu_delete/([0-9]+)$', 'admin/menus/menu_delete/$1', NULL, 1, 1, 0),
(37, 'Admin - Menu - menu items', 38, NULL, '^admin/menus/menu_items/([0-9]+)$', 'admin/menus/menu_items/$1', NULL, 1, 1, 1),
(38, 'Admin - Menu - menu item create', 39, NULL, '^admin/menus/menu_item_create/([0-9]+)$', 'admin/menus/menu_item_create/$1', NULL, 1, 1, 1),
(39, 'Admin - Menu - menu item edit', 40, NULL, '^admin/menus/menu_item_edit/([0-9]+)$', 'admin/menus/menu_item_edit/$1', NULL, 1, 1, 1),
(40, 'Admin - Menu - menu item delete', 41, NULL, '^admin/menus/menu_item_delete/([0-9]+)$', 'admin/menus/menu_item_delete/$1', NULL, 1, 1, 0),
(41, 'Admin - Grupe modula - index', 42, NULL, '^admin/modules$', 'admin/modules/index', NULL, 1, 1, 1),
(42, 'Admin - Grupe modula - create', 43, NULL, '^admin/modules/create$', 'admin/modules/create', NULL, 1, 1, 1),
(43, 'Admin - Grupe modula - edit', 44, NULL, '^admin/modules/edit/([0-9]+)$', 'admin/modules/edit/$1', NULL, 1, 1, 1),
(44, 'Admin - Grupe modula - delete', 45, NULL, '^admin/modules/delete/([0-9]+)$', 'admin/modules/delete/$1', NULL, 1, 1, 0),
(45, 'Admin - Grupe modula - groupitems', 46, NULL, '^admin/modules/groupitems/([0-9]+)$', 'admin/modules/groupitems/$1', NULL, 1, 1, 1),
(46, 'Naslovnica - naslovnica', 47, NULL, '^(naslovnica|home|naslovnica/naslovnica)$', 'home/index', NULL, 1, 2, 1);

-- --------------------------------------------------------

--
-- Tablična struktura za tablicu `zt_page_modules_groups`
--

CREATE TABLE IF NOT EXISTS `zt_page_modules_groups` (
  `page_modules_group_id` int(11) NOT NULL auto_increment COMMENT 'Identifikator',
  `page_id` int(11) NOT NULL COMMENT 'Identifikator stranice.',
  `modules_group_id` int(11) NOT NULL COMMENT 'Identifikator root grupe modula koja ce se prikazivati na navedenoj stranici.',
  `layout_position_id` int(11) default NULL COMMENT 'Pozicija u layout-u u kojem ce se grupa modula prikazivati.',
  `page_modules_group_order` mediumint(9) NOT NULL COMMENT 'Redoslijed grupe u poziciji layout-a.',
  `page_modules_group_locked` tinyint(1) NOT NULL default '0' COMMENT 'Grupu nije moguce brisati niti mijenjati.',
  PRIMARY KEY  (`page_modules_group_id`),
  KEY `modules_group_id` (`modules_group_id`),
  KEY `layout_position_id` (`layout_position_id`),
  KEY `page_id` (`page_id`,`modules_group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Izbacivanje podataka za tablicu `zt_page_modules_groups`
--


-- --------------------------------------------------------

--
-- Tablična struktura za tablicu `zt_roles`
--

CREATE TABLE IF NOT EXISTS `zt_roles` (
  `role_id` int(11) NOT NULL auto_increment COMMENT 'Identifikator uloge',
  `role_name` varchar(50) collate utf8_unicode_ci NOT NULL COMMENT 'Naziv uloge.',
  `role_description` text collate utf8_unicode_ci COMMENT 'Opis uloge.',
  `role_parent_id` int(11) default NULL COMMENT 'Identifikator roditelja.',
  `role_lft` int(11) NOT NULL,
  `role_rgt` int(11) NOT NULL,
  `role_locked` tinyint(1) NOT NULL default '0' COMMENT 'Da li je uloga zakljucana za modifikaciju i brisanje.',
  PRIMARY KEY  (`role_id`),
  KEY `role_parent_id` (`role_parent_id`),
  KEY `role_lft` (`role_lft`,`role_rgt`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Izbacivanje podataka za tablicu `zt_roles`
--

INSERT INTO `zt_roles` (`role_id`, `role_name`, `role_description`, `role_parent_id`, `role_lft`, `role_rgt`, `role_locked`) VALUES
(-1, 'Super Administrator', 'Ima sve ovlasti', NULL, 3, 8, 1),
(0, 'Gost', NULL, NULL, 1, 2, 1),
(1, 'Administrator', 'Administratorske uloge', -1, 4, 7, 0),
(2, 'Moderator', 'Moderatorske uloge', 1, 5, 6, 0);

-- --------------------------------------------------------

--
-- Tablična struktura za tablicu `zt_roles_assignment`
--

CREATE TABLE IF NOT EXISTS `zt_roles_assignment` (
  `role_assignment_id` int(11) NOT NULL auto_increment COMMENT 'Identifikator dozvola ulogama.',
  `role_id` int(11) NOT NULL COMMENT 'Identifikator uloga.',
  `role_member_alias` varchar(100) collate utf8_unicode_ci default NULL COMMENT 'Clan uloge.',
  `role_member_db_table` varchar(100) collate utf8_unicode_ci default NULL COMMENT 'Ime tablice koja sadrzi clana uloge.',
  `role_member_foreign_key` int(11) default NULL COMMENT 'Strani kljuc',
  PRIMARY KEY  (`role_assignment_id`),
  UNIQUE KEY `role_member_alias` (`role_member_alias`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Izbacivanje podataka za tablicu `zt_roles_assignment`
--

INSERT INTO `zt_roles_assignment` (`role_assignment_id`, `role_id`, `role_member_alias`, `role_member_db_table`, `role_member_foreign_key`) VALUES
(1, 0, 'guest', NULL, NULL);

-- --------------------------------------------------------

--
-- Tablična struktura za tablicu `zt_role_permissions`
--

CREATE TABLE IF NOT EXISTS `zt_role_permissions` (
  `role_permission_id` int(11) NOT NULL auto_increment COMMENT 'Identifikator dozvole ulozi.',
  `role_id` int(11) NOT NULL COMMENT 'Identifikator uloge.',
  `action_id` int(11) default NULL COMMENT 'Identifikator akcije.',
  `requested_object_alias` varchar(50) collate utf8_unicode_ci default NULL COMMENT 'Objekt nad kojim se trazi dozvola.',
  `requested_db_table` varchar(50) collate utf8_unicode_ci default NULL COMMENT 'Tablica koja sadrzi objekt za koji se trazi dozvola.',
  `requested_foreign_key` int(11) default NULL COMMENT 'Strani kljuc.',
  `role_permission_locked` tinyint(1) NOT NULL default '0' COMMENT 'Dozvola je zakljucana.',
  PRIMARY KEY  (`role_permission_id`),
  KEY `action_id` (`action_id`),
  KEY `role_id` (`role_id`,`action_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=150 ;

--
-- Izbacivanje podataka za tablicu `zt_role_permissions`
--

INSERT INTO `zt_role_permissions` (`role_permission_id`, `role_id`, `action_id`, `requested_object_alias`, `requested_db_table`, `requested_foreign_key`, `role_permission_locked`) VALUES
(1, 1, 1, NULL, 'zt_pages', 16, 0),
(2, 1, 2, NULL, 'zt_pages', 16, 0),
(3, 1, 3, NULL, 'zt_pages', 16, 0),
(4, 1, 4, NULL, 'zt_pages', 16, 0),
(5, 2, 1, NULL, 'zt_pages', 16, 0),
(6, 0, 1, NULL, 'zt_pages', 46, 0),
(7, 2, 1, NULL, 'zt_pages', 1, 0),
(8, 2, 1, NULL, 'zt_pages', 42, 0),
(9, 2, 2, NULL, 'zt_pages', 42, 0),
(10, 2, 1, NULL, 'zt_pages', 43, 0),
(11, 2, 3, NULL, 'zt_pages', 43, 0),
(12, 2, 1, NULL, 'zt_pages', 45, 0),
(13, 2, 3, NULL, 'zt_pages', 45, 0),
(14, 2, 1, NULL, 'zt_pages', 41, 0),
(15, 2, 3, NULL, 'zt_pages', 41, 0),
(16, 2, 1, NULL, 'zt_pages', 35, 0),
(17, 2, 3, NULL, 'zt_pages', 35, 0),
(18, 2, 1, NULL, 'zt_pages', 38, 0),
(19, 2, 2, NULL, 'zt_pages', 38, 0),
(20, 2, 1, NULL, 'zt_pages', 39, 0),
(21, 2, 3, NULL, 'zt_pages', 39, 0),
(22, 2, 1, NULL, 'zt_pages', 33, 0),
(23, 2, 2, NULL, 'zt_pages', 33, 0),
(24, 2, 3, NULL, 'zt_pages', 33, 0),
(26, 1, 1, NULL, 'zt_pages', 18, 0),
(27, 1, 2, NULL, 'zt_pages', 18, 0),
(28, 1, 3, NULL, 'zt_pages', 18, 0),
(29, 1, 4, NULL, 'zt_pages', 18, 0),
(30, 1, 1, NULL, 'zt_pages', 17, 0),
(31, 1, 2, NULL, 'zt_pages', 17, 0),
(32, 1, 3, NULL, 'zt_pages', 17, 0),
(33, 1, 4, NULL, 'zt_pages', 17, 0),
(34, 1, 1, NULL, 'zt_pages', 19, 0),
(35, 1, 2, NULL, 'zt_pages', 19, 0),
(36, 1, 3, NULL, 'zt_pages', 19, 0),
(37, 1, 4, NULL, 'zt_pages', 19, 0),
(38, 1, 1, NULL, 'zt_pages', 9, 0),
(39, 1, 2, NULL, 'zt_pages', 9, 0),
(40, 1, 3, NULL, 'zt_pages', 9, 0),
(41, 1, 4, NULL, 'zt_pages', 9, 0),
(42, 1, 1, NULL, 'zt_pages', 15, 0),
(43, 1, 2, NULL, 'zt_pages', 15, 0),
(44, 1, 3, NULL, 'zt_pages', 15, 0),
(45, 1, 4, NULL, 'zt_pages', 15, 0),
(46, 1, 1, NULL, 'zt_pages', 22, 0),
(47, 1, 2, NULL, 'zt_pages', 22, 0),
(48, 1, 3, NULL, 'zt_pages', 22, 0),
(49, 1, 4, NULL, 'zt_pages', 22, 0),
(50, 1, 1, NULL, 'zt_pages', 23, 0),
(51, 1, 2, NULL, 'zt_pages', 23, 0),
(52, 1, 3, NULL, 'zt_pages', 23, 0),
(53, 1, 4, NULL, 'zt_pages', 23, 0),
(54, 1, 1, NULL, 'zt_pages', 12, 0),
(55, 1, 2, NULL, 'zt_pages', 12, 0),
(56, 1, 3, NULL, 'zt_pages', 12, 0),
(57, 1, 4, NULL, 'zt_pages', 12, 0),
(58, 1, 1, NULL, 'zt_pages', 21, 0),
(59, 1, 2, NULL, 'zt_pages', 21, 0),
(60, 1, 3, NULL, 'zt_pages', 21, 0),
(61, 1, 4, NULL, 'zt_pages', 21, 0),
(62, 1, 1, NULL, 'zt_pages', 1, 0),
(63, 1, 2, NULL, 'zt_pages', 1, 0),
(64, 1, 3, NULL, 'zt_pages', 1, 0),
(65, 1, 4, NULL, 'zt_pages', 1, 0),
(66, 1, 1, NULL, 'zt_pages', 5, 0),
(67, 1, 2, NULL, 'zt_pages', 5, 0),
(68, 1, 3, NULL, 'zt_pages', 5, 0),
(69, 1, 4, NULL, 'zt_pages', 5, 0),
(70, 1, 1, NULL, 'zt_pages', 7, 0),
(71, 1, 2, NULL, 'zt_pages', 7, 0),
(72, 1, 3, NULL, 'zt_pages', 7, 0),
(73, 1, 4, NULL, 'zt_pages', 7, 0),
(74, 1, 1, NULL, 'zt_pages', 6, 0),
(75, 1, 2, NULL, 'zt_pages', 6, 0),
(76, 1, 3, NULL, 'zt_pages', 6, 0),
(77, 1, 4, NULL, 'zt_pages', 6, 0),
(78, 1, 1, NULL, 'zt_pages', 4, 0),
(79, 1, 2, NULL, 'zt_pages', 4, 0),
(80, 1, 3, NULL, 'zt_pages', 4, 0),
(81, 1, 4, NULL, 'zt_pages', 4, 0),
(82, 1, 1, NULL, 'zt_pages', 28, 0),
(83, 1, 2, NULL, 'zt_pages', 28, 0),
(84, 1, 3, NULL, 'zt_pages', 28, 0),
(85, 1, 4, NULL, 'zt_pages', 28, 0),
(86, 1, 1, NULL, 'zt_pages', 30, 0),
(87, 1, 2, NULL, 'zt_pages', 30, 0),
(88, 1, 3, NULL, 'zt_pages', 30, 0),
(89, 1, 4, NULL, 'zt_pages', 30, 0),
(90, 1, 1, NULL, 'zt_pages', 29, 0),
(91, 1, 2, NULL, 'zt_pages', 29, 0),
(92, 1, 3, NULL, 'zt_pages', 29, 0),
(93, 1, 4, NULL, 'zt_pages', 29, 0),
(94, 1, 1, NULL, 'zt_pages', 27, 0),
(95, 1, 2, NULL, 'zt_pages', 27, 0),
(96, 1, 3, NULL, 'zt_pages', 27, 0),
(97, 1, 4, NULL, 'zt_pages', 27, 0),
(98, 1, 1, NULL, 'zt_pages', 26, 0),
(99, 1, 2, NULL, 'zt_pages', 26, 0),
(100, 1, 3, NULL, 'zt_pages', 26, 0),
(101, 1, 4, NULL, 'zt_pages', 26, 0),
(102, 1, 1, NULL, 'zt_pages', 25, 0),
(103, 1, 2, NULL, 'zt_pages', 25, 0),
(104, 1, 3, NULL, 'zt_pages', 25, 0),
(105, 1, 4, NULL, 'zt_pages', 25, 0),
(106, 1, 1, NULL, 'zt_pages', 33, 0),
(107, 1, 2, NULL, 'zt_pages', 33, 0),
(108, 1, 3, NULL, 'zt_pages', 33, 0),
(109, 1, 4, NULL, 'zt_pages', 33, 0),
(110, 1, 1, NULL, 'zt_pages', 37, 0),
(111, 1, 2, NULL, 'zt_pages', 37, 0),
(112, 1, 3, NULL, 'zt_pages', 37, 0),
(113, 1, 4, NULL, 'zt_pages', 37, 0),
(114, 1, 1, NULL, 'zt_pages', 39, 0),
(115, 1, 2, NULL, 'zt_pages', 39, 0),
(116, 1, 3, NULL, 'zt_pages', 39, 0),
(117, 1, 4, NULL, 'zt_pages', 39, 0),
(118, 1, 1, NULL, 'zt_pages', 38, 0),
(119, 1, 2, NULL, 'zt_pages', 38, 0),
(120, 1, 3, NULL, 'zt_pages', 38, 0),
(121, 1, 4, NULL, 'zt_pages', 38, 0),
(122, 1, 1, NULL, 'zt_pages', 35, 0),
(123, 1, 2, NULL, 'zt_pages', 35, 0),
(124, 1, 3, NULL, 'zt_pages', 35, 0),
(125, 1, 4, NULL, 'zt_pages', 35, 0),
(126, 1, 1, NULL, 'zt_pages', 34, 0),
(127, 1, 2, NULL, 'zt_pages', 34, 0),
(128, 1, 3, NULL, 'zt_pages', 34, 0),
(129, 1, 4, NULL, 'zt_pages', 34, 0),
(130, 1, 1, NULL, 'zt_pages', 32, 0),
(131, 1, 2, NULL, 'zt_pages', 32, 0),
(132, 1, 3, NULL, 'zt_pages', 32, 0),
(133, 1, 4, NULL, 'zt_pages', 32, 0),
(134, 1, 1, NULL, 'zt_pages', 41, 0),
(135, 1, 2, NULL, 'zt_pages', 41, 0),
(136, 1, 3, NULL, 'zt_pages', 41, 0),
(137, 1, 4, NULL, 'zt_pages', 41, 0),
(138, 1, 1, NULL, 'zt_pages', 45, 0),
(139, 1, 2, NULL, 'zt_pages', 45, 0),
(140, 1, 3, NULL, 'zt_pages', 45, 0),
(141, 1, 4, NULL, 'zt_pages', 45, 0),
(142, 1, 1, NULL, 'zt_pages', 43, 0),
(143, 1, 2, NULL, 'zt_pages', 43, 0),
(144, 1, 3, NULL, 'zt_pages', 43, 0),
(145, 1, 4, NULL, 'zt_pages', 43, 0),
(146, 1, 1, NULL, 'zt_pages', 42, 0),
(147, 1, 2, NULL, 'zt_pages', 42, 0),
(148, 1, 3, NULL, 'zt_pages', 42, 0),
(149, 1, 4, NULL, 'zt_pages', 42, 0);

-- --------------------------------------------------------

--
-- Tablična struktura za tablicu `zt_site_settings`
--

CREATE TABLE IF NOT EXISTS `zt_site_settings` (
  `site_setting_id` int(11) NOT NULL auto_increment COMMENT 'Identifikator postavki stranice.',
  `site_setting_alias` varchar(100) collate utf8_unicode_ci NOT NULL COMMENT 'Alias postavke.',
  `site_setting_value` text collate utf8_unicode_ci COMMENT 'Serijalizirane vrijednosti.',
  PRIMARY KEY  (`site_setting_id`),
  UNIQUE KEY `site_setting_alias` (`site_setting_alias`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Izbacivanje podataka za tablicu `zt_site_settings`
--

INSERT INTO `zt_site_settings` (`site_setting_id`, `site_setting_alias`, `site_setting_value`) VALUES
(1, 'general_setting', NULL),
(2, 'metadata_setting', NULL);

-- --------------------------------------------------------

--
-- Tablična struktura za tablicu `zt_users`
--

CREATE TABLE IF NOT EXISTS `zt_users` (
  `user_id` int(11) NOT NULL auto_increment COMMENT 'Identifikator korisnika',
  `user_name` varchar(50) collate utf8_unicode_ci NOT NULL COMMENT 'Lijepo ime korisnika',
  `user_username` varchar(50) collate utf8_unicode_ci NOT NULL COMMENT 'Korisnicko ime.',
  `user_email` varchar(100) collate utf8_unicode_ci NOT NULL COMMENT 'Korisnicka email adresa.',
  `user_password` varchar(32) collate utf8_unicode_ci NOT NULL COMMENT 'Korisnicka lozinka',
  `primary_role_id` int(11) default NULL COMMENT 'Identifikator primarne greupe',
  `user_active` tinyint(1) NOT NULL default '0' COMMENT 'Da li je korisnik aktivan',
  `user_activation_key` varchar(32) collate utf8_unicode_ci default NULL COMMENT 'Kljuc za aktivaciju korisnika',
  `user_register_datetime` datetime NOT NULL COMMENT 'Datum registracije.',
  `user_last_visit_datetime` datetime default NULL COMMENT 'Datum posljednje posjete.',
  `user_ip` varchar(16) collate utf8_unicode_ci default NULL COMMENT 'Korisnicka ip adresa',
  `user_params` text collate utf8_unicode_ci COMMENT 'Korisnicki parametri',
  `user_new_password` varchar(32) collate utf8_unicode_ci default NULL COMMENT 'Nova korisnicka lozinka.',
  `user_new_password_key` varchar(32) collate utf8_unicode_ci default NULL COMMENT 'Kljuc za aktivaciju nove korisnicke lozinke.',
  `user_new_password_datetime` datetime default NULL COMMENT 'Datum generiranja nove lozinke.',
  PRIMARY KEY  (`user_id`),
  UNIQUE KEY `user_email` (`user_email`),
  UNIQUE KEY `user_activation_key` (`user_activation_key`),
  UNIQUE KEY `user_new_password_key` (`user_new_password_key`),
  KEY `user_username` (`user_username`),
  KEY `primary_role_id` (`primary_role_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Izbacivanje podataka za tablicu `zt_users`
--

INSERT INTO `zt_users` (`user_id`, `user_name`, `user_username`, `user_email`, `user_password`, `primary_role_id`, `user_active`, `user_activation_key`, `user_register_datetime`, `user_last_visit_datetime`, `user_ip`, `user_params`, `user_new_password`, `user_new_password_key`, `user_new_password_datetime`) VALUES
(1, 'admin', 'admin', 'admin@admin.com', '9c428aef1794452dc229870552e86a48', -1, 1, NULL, '2009-01-08 19:16:22', '2009-01-09 21:36:12', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Tablična struktura za tablicu `zt_users_autologin`
--

CREATE TABLE IF NOT EXISTS `zt_users_autologin` (
  `user_autologin_key` varchar(32) collate utf8_unicode_ci NOT NULL COMMENT 'Identifikator',
  `user_id` int(11) NOT NULL COMMENT 'Identifikator korisnika',
  `user_autologin_expiration_datetime` datetime NOT NULL COMMENT 'Vrijeme isteka autologina.',
  PRIMARY KEY  (`user_autologin_key`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Izbacivanje podataka za tablicu `zt_users_autologin`
--


--
-- Ograničenja za izbačene tablice
--

--
-- Ograničenja za tablicu `ci_sessions`
--
ALTER TABLE `ci_sessions`
  ADD CONSTRAINT `ci_sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `zt_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograničenja za tablicu `zt_components`
--
ALTER TABLE `zt_components`
  ADD CONSTRAINT `zt_components_ibfk_1` FOREIGN KEY (`component_type_id`) REFERENCES `zt_component_types` (`component_type_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograničenja za tablicu `zt_component_methods`
--
ALTER TABLE `zt_component_methods`
  ADD CONSTRAINT `zt_component_methods_ibfk_1` FOREIGN KEY (`component_type_method_id`) REFERENCES `zt_component_type_methods` (`component_type_method_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `zt_component_methods_ibfk_2` FOREIGN KEY (`component_id`) REFERENCES `zt_components` (`component_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograničenja za tablicu `zt_component_type_methods`
--
ALTER TABLE `zt_component_type_methods`
  ADD CONSTRAINT `zt_component_type_methods_ibfk_1` FOREIGN KEY (`component_type_id`) REFERENCES `zt_component_types` (`component_type_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograničenja za tablicu `zt_layout_positions`
--
ALTER TABLE `zt_layout_positions`
  ADD CONSTRAINT `zt_layout_positions_ibfk_1` FOREIGN KEY (`layout_id`) REFERENCES `zt_layouts` (`layout_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograničenja za tablicu `zt_menu_items`
--
ALTER TABLE `zt_menu_items`
  ADD CONSTRAINT `zt_menu_items_ibfk_1` FOREIGN KEY (`menu_item_id_parent`) REFERENCES `zt_menu_items` (`menu_item_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `zt_menu_items_ibfk_2` FOREIGN KEY (`page_id`) REFERENCES `zt_pages` (`page_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `zt_menu_items_ibfk_3` FOREIGN KEY (`menu_id`) REFERENCES `zt_menus` (`menu_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograničenja za tablicu `zt_modules`
--
ALTER TABLE `zt_modules`
  ADD CONSTRAINT `zt_modules_ibfk_1` FOREIGN KEY (`module_type_id`) REFERENCES `zt_module_types` (`module_type_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `zt_modules_ibfk_2` FOREIGN KEY (`component_id`) REFERENCES `zt_components` (`component_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograničenja za tablicu `zt_modules_group_items`
--
ALTER TABLE `zt_modules_group_items`
  ADD CONSTRAINT `zt_modules_group_items_ibfk_1` FOREIGN KEY (`module_id`) REFERENCES `zt_modules` (`module_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `zt_modules_group_items_ibfk_2` FOREIGN KEY (`modules_group_id`) REFERENCES `zt_modules_groups` (`modules_group_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `zt_modules_group_items_ibfk_3` FOREIGN KEY (`modules_group_id_element`) REFERENCES `zt_modules_groups` (`modules_group_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograničenja za tablicu `zt_module_types`
--
ALTER TABLE `zt_module_types`
  ADD CONSTRAINT `zt_module_types_ibfk_1` FOREIGN KEY (`component_type_id`) REFERENCES `zt_component_types` (`component_type_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograničenja za tablicu `zt_pages`
--
ALTER TABLE `zt_pages`
  ADD CONSTRAINT `zt_pages_ibfk_2` FOREIGN KEY (`layout_id`) REFERENCES `zt_layouts` (`layout_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `zt_pages_ibfk_3` FOREIGN KEY (`component_method_id`) REFERENCES `zt_component_methods` (`component_method_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograničenja za tablicu `zt_page_modules_groups`
--
ALTER TABLE `zt_page_modules_groups`
  ADD CONSTRAINT `zt_page_modules_groups_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `zt_pages` (`page_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `zt_page_modules_groups_ibfk_2` FOREIGN KEY (`modules_group_id`) REFERENCES `zt_modules_groups` (`modules_group_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `zt_page_modules_groups_ibfk_3` FOREIGN KEY (`layout_position_id`) REFERENCES `zt_layout_positions` (`layout_position_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ograničenja za tablicu `zt_roles`
--
ALTER TABLE `zt_roles`
  ADD CONSTRAINT `zt_roles_ibfk_1` FOREIGN KEY (`role_parent_id`) REFERENCES `zt_roles` (`role_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograničenja za tablicu `zt_roles_assignment`
--
ALTER TABLE `zt_roles_assignment`
  ADD CONSTRAINT `zt_roles_assignment_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `zt_roles` (`role_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograničenja za tablicu `zt_role_permissions`
--
ALTER TABLE `zt_role_permissions`
  ADD CONSTRAINT `zt_role_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `zt_roles` (`role_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `zt_role_permissions_ibfk_2` FOREIGN KEY (`action_id`) REFERENCES `zt_actions` (`action_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograničenja za tablicu `zt_users`
--
ALTER TABLE `zt_users`
  ADD CONSTRAINT `zt_users_ibfk_1` FOREIGN KEY (`primary_role_id`) REFERENCES `zt_roles` (`role_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ograničenja za tablicu `zt_users_autologin`
--
ALTER TABLE `zt_users_autologin`
  ADD CONSTRAINT `zt_users_autologin_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `zt_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
