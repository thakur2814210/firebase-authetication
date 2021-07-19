-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 18, 2014 at 10:08 AM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `bcfolder`
--
DROP DATABASE `bcfolder`;

CREATE DATABASE IF NOT EXISTS `bcfolder` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `bcfolder`;

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE IF NOT EXISTS `address` (
  `address_id` char(36) NOT NULL,
  `address_1` varchar(50) NOT NULL,
  `address_2` varchar(50) DEFAULT NULL,
  `city` varchar(50) NOT NULL,
  `country_id` char(36) NOT NULL,
  `post_code` varchar(50) NOT NULL,
  PRIMARY KEY (`address_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `card`
--

CREATE TABLE IF NOT EXISTS `card` (
  `card_id` char(36) NOT NULL DEFAULT '',
  `user_id` char(36) DEFAULT NULL,
  `card_type` char(36) DEFAULT NULL,
  `distributed_brand` varchar(50) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `sub_category` varchar(50) DEFAULT NULL,
  `card_name` varchar(50) DEFAULT NULL,
  `assigned_id` varchar(50) DEFAULT NULL,
  `premium_paid` tinyint(1) NOT NULL,
  `stripe_charge_id` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`card_id`),
  KEY `fk_user_to_card` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `card_comment`
--

CREATE TABLE IF NOT EXISTS `card_comment` (
  `comment_id` char(36) NOT NULL,
  `user_id` char(36) NOT NULL,
  `card_id` char(36) NOT NULL,
  `comment` varchar(500) NOT NULL,
  KEY `fk_card_to_card_comment` (`card_id`),
  KEY `fk_user_to_card_comment` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `card_contact`
--

CREATE TABLE IF NOT EXISTS `card_contact` (
  `user_id` char(36) NOT NULL,
  `card_id` char(36) NOT NULL,
  `private` tinyint(4) NOT NULL,
  `rating` decimal(4,1) DEFAULT NULL,
  UNIQUE KEY `unique_card_contact` (`user_id`,`card_id`),
  KEY `fk_card_to_card_contact` (`card_id`),
  KEY `fk_user_to_card_contact` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `card_data`
--

CREATE TABLE IF NOT EXISTS `card_data` (
  `user_id` char(36) NOT NULL,
  `card_id` char(36) NOT NULL,
  `landscape` tinyint(4) NOT NULL,
  `canvas_back` text,
  `canvas_front` text,
  `widgets_back` text,
  `widgets_front` text,
  `links_back` text,
  `links_front` text,
  KEY `fk_card_to_card_data` (`card_id`),
  KEY `fk_user_to_card_data` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `card_folder`
--

CREATE TABLE IF NOT EXISTS `card_folder` (
  `user_id` char(36) NOT NULL,
  `card_id` char(36) NOT NULL,
  `folder_id` char(36) NOT NULL,
  UNIQUE KEY `unique_card_folder` (`user_id`,`card_id`,`folder_id`),
  KEY `fk_card_to_card_folder` (`card_id`),
  KEY `fk_user_to_card_folder` (`user_id`),
  KEY `fk_folder_to_card_folder` (`folder_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `card_link`
--

CREATE TABLE IF NOT EXISTS `card_link` (
  `card_link` int(11) NOT NULL AUTO_INCREMENT,
  `user_requested_by` char(36) NOT NULL,
  `card_id_requested` char(36) NOT NULL,
  `link_status` varchar(50) NOT NULL DEFAULT 'requested',
  `date_requested` datetime NOT NULL,
  `date_accepted` datetime DEFAULT NULL,
  `request_origin` varchar(50) NOT NULL,
  PRIMARY KEY (`card_link`),
  KEY `fk_card_to_card_link` (`card_id_requested`),
  KEY `fk_user_to_card_link` (`user_requested_by`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE IF NOT EXISTS `countries` (
  `country_id` int(11) NOT NULL AUTO_INCREMENT,
  `country` varchar(100) NOT NULL,
  `code` varchar(2) NOT NULL,
  PRIMARY KEY (`country_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=273 ;

-- --------------------------------------------------------

--
-- Table structure for table `email_token`
--

CREATE TABLE IF NOT EXISTS `email_token` (
  `token_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` char(36) NOT NULL,
  `token` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `validity` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `type` varchar(50) NOT NULL,
  PRIMARY KEY (`token_id`),
  KEY `fk_user_to_email_token` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `folder`
--

CREATE TABLE IF NOT EXISTS `folder` (
  `folder_id` char(36) NOT NULL,
  `user_id` char(36) NOT NULL,
  `description` varchar(50) NOT NULL,
  PRIMARY KEY (`folder_id`),
  KEY `fk_user_to_folder` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `personal_card_setting`
--

CREATE TABLE IF NOT EXISTS `personal_card_setting` (
  `user_id` char(36) NOT NULL,
  `card_id` char(36) NOT NULL,
  `global_search` tinyint(1) NOT NULL DEFAULT '0',
  `share_among_users` tinyint(1) NOT NULL DEFAULT '0',
  `seen_in_user_folder` tinyint(1) NOT NULL DEFAULT '0',
  `requires_reciprocity` tinyint(1) NOT NULL DEFAULT '1',
  `need_approval` tinyint(1) NOT NULL DEFAULT '1',
  UNIQUE KEY `card_id` (`card_id`),
  KEY `fk_card_to_personal_card_setting` (`card_id`),
  KEY `fk_user_to_personal_card_setting` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `plan`
--

CREATE TABLE IF NOT EXISTS `plan` (
  `plan_id` int(11) NOT NULL AUTO_INCREMENT,
  `stripe_plan_id` varchar(50) DEFAULT NULL,
  `card_setting` varchar(50) DEFAULT NULL,
  `period` varchar(7) DEFAULT NULL,
  `plan_name` varchar(100) DEFAULT NULL,
  `shortcut` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`plan_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- Table structure for table `professional_card_setting`
--

CREATE TABLE IF NOT EXISTS `professional_card_setting` (
  `user_id` char(36) NOT NULL,
  `card_id` char(36) NOT NULL,
  `visible_pp_search` tinyint(1) NOT NULL DEFAULT '0',
  `share_among_users` tinyint(1) NOT NULL DEFAULT '0',
  `allow_rating` tinyint(1) NOT NULL DEFAULT '0',
  UNIQUE KEY `card_id` (`card_id`),
  KEY `fk_card_to_professional_card_setting` (`card_id`),
  KEY `fk_user_to_professional_card_setting` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `subscription`
--

CREATE TABLE IF NOT EXISTS `subscription` (
  `subscription_id` int(11) NOT NULL AUTO_INCREMENT,
  `stripe_subscription_id` varchar(50) DEFAULT NULL,
  `plan_id` int(11) NOT NULL,
  `card_id` char(36) NOT NULL,
  PRIMARY KEY (`subscription_id`),
  KEY `fk_plan_to_subscription` (`plan_id`),
  KEY `fk_card_to_subscription` (`card_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `user_id` varchar(36) NOT NULL,
  `admin` int(11) NOT NULL DEFAULT '0',
  `title` varchar(50) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `mobile` varchar(50) DEFAULT NULL,
  `email_address` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `website_link` varchar(50) DEFAULT NULL,
  `additional_info` varchar(50) DEFAULT NULL,
  `personal_address_id` char(36) DEFAULT NULL,
  `personal_country_id` char(36) DEFAULT NULL,
  `company_name` varchar(50) DEFAULT NULL,
  `department_name` varchar(50) DEFAULT NULL,
  `position` varchar(50) DEFAULT NULL,
  `corporate_code` varchar(50) DEFAULT NULL,
  `company_address_id` char(36) DEFAULT NULL,
  `company_country_id` char(36) DEFAULT NULL,
  `company_phone` varchar(50) DEFAULT NULL,
  `company_mobile` varchar(50) DEFAULT NULL,
  `company_email_address` varchar(50) DEFAULT NULL,
  `company_website_link` varchar(50) DEFAULT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT '0',
  `default_card` char(36) DEFAULT NULL,
  `date_created` datetime NOT NULL,
  `stripe_customer_id` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_log`
--

CREATE TABLE IF NOT EXISTS `user_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` char(36) NOT NULL,
  `logged_in` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `card`
--
ALTER TABLE `card`
  ADD CONSTRAINT `fk_user_to_card` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `card_comment`
--
ALTER TABLE `card_comment`
  ADD CONSTRAINT `fk_card_to_card_comment` FOREIGN KEY (`card_id`) REFERENCES `card` (`card_id`),
  ADD CONSTRAINT `fk_user_to_card_comment` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `card_contact`
--
ALTER TABLE `card_contact`
  ADD CONSTRAINT `fk_card_to_card_contact` FOREIGN KEY (`card_id`) REFERENCES `card` (`card_id`),
  ADD CONSTRAINT `fk_user_to_card_contact` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `card_data`
--
ALTER TABLE `card_data`
  ADD CONSTRAINT `fk_card_to_card_data` FOREIGN KEY (`card_id`) REFERENCES `card` (`card_id`),
  ADD CONSTRAINT `fk_user_to_card_data` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `card_folder`
--
ALTER TABLE `card_folder`
  ADD CONSTRAINT `fk_card_to_card_folder` FOREIGN KEY (`card_id`) REFERENCES `card` (`card_id`),
  ADD CONSTRAINT `fk_user_to_card_folder` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`),
  ADD CONSTRAINT `fk_folder_to_card_folder` FOREIGN KEY (`folder_id`) REFERENCES `folder` (`folder_id`);

--
-- Constraints for table `card_link`
--
ALTER TABLE `card_link`
  ADD CONSTRAINT `fk_card_to_card_link` FOREIGN KEY (`card_id_requested`) REFERENCES `card` (`card_id`),
  ADD CONSTRAINT `fk_user_to_card_link` FOREIGN KEY (`user_requested_by`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `email_token`
--
ALTER TABLE `email_token`
  ADD CONSTRAINT `fk_user_to_email_token` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `folder`
--
ALTER TABLE `folder`
  ADD CONSTRAINT `fk_user_to_folder` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `personal_card_setting`
--
ALTER TABLE `personal_card_setting`
  ADD CONSTRAINT `fk_card_to_personal_card_setting` FOREIGN KEY (`card_id`) REFERENCES `card` (`card_id`),
  ADD CONSTRAINT `fk_user_to_personal_card_setting` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `professional_card_setting`
--
ALTER TABLE `professional_card_setting`
  ADD CONSTRAINT `fk_card_to_professional_card_setting` FOREIGN KEY (`card_id`) REFERENCES `card` (`card_id`),
  ADD CONSTRAINT `fk_user_to_professional_card_setting` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `subscription`
--
ALTER TABLE `subscription`
  ADD CONSTRAINT `fk_plan_to_subscription` FOREIGN KEY (`plan_id`) REFERENCES `plan` (`plan_id`),
  ADD CONSTRAINT `fk_card_to_subscription` FOREIGN KEY (`card_id`) REFERENCES `card` (`card_id`);

INSERT INTO `plan` (`plan_id`,`stripe_plan_id`,`card_setting`,`period`,`plan_name`,`shortcut`) VALUES (1,'monthly_1','share','monthly','Shareable Monthly Plan','1_m');
INSERT INTO `plan` (`plan_id`,`stripe_plan_id`,`card_setting`,`period`,`plan_name`,`shortcut`) VALUES (2,'monthly_2','visible','monthly','Visible Monthly Plan','2_m');
INSERT INTO `plan` (`plan_id`,`stripe_plan_id`,`card_setting`,`period`,`plan_name`,`shortcut`) VALUES (3,'monthly_3','rating','monthly','Rating Allowed Monthly Plan','3_m');
INSERT INTO `plan` (`plan_id`,`stripe_plan_id`,`card_setting`,`period`,`plan_name`,`shortcut`) VALUES (4,'yearly_1','share','yearly','Shareable Yearly Plan','1_y');
INSERT INTO `plan` (`plan_id`,`stripe_plan_id`,`card_setting`,`period`,`plan_name`,`shortcut`) VALUES (5,'yearly_2','visible','yearly','Visible Yearly Plan','2_y');
INSERT INTO `plan` (`plan_id`,`stripe_plan_id`,`card_setting`,`period`,`plan_name`,`shortcut`) VALUES (6,'yearly_3','rating','yearly','Rating Allowed Yearly Plan','3_y');
INSERT INTO `plan` (`plan_id`,`stripe_plan_id`,`card_setting`,`period`,`plan_name`,`shortcut`) VALUES (7,'monthly_1_2_3','all','monthly','All Included Monthly Plan','123_m');
INSERT INTO `plan` (`plan_id`,`stripe_plan_id`,`card_setting`,`period`,`plan_name`,`shortcut`) VALUES (8,'yearly_1_2_3','all','yearly','All Included Yearly Plan','123_y');
INSERT INTO `plan` (`plan_id`,`stripe_plan_id`,`card_setting`,`period`,`plan_name`,`shortcut`) VALUES (9,'monthly_1_2','share_and_visible','monthly','Shareable and Visible Monthly Plan','12_m');
INSERT INTO `plan` (`plan_id`,`stripe_plan_id`,`card_setting`,`period`,`plan_name`,`shortcut`) VALUES (10,'monthly_1_3','share_and_rating','monthly','Shareable and Rating Allowed Monthly Plan','13_m');
INSERT INTO `plan` (`plan_id`,`stripe_plan_id`,`card_setting`,`period`,`plan_name`,`shortcut`) VALUES (11,'monthly_2_3','visible_and_rating','monthly','Visible and Rating Allowed Monthly Plan','23_m');
INSERT INTO `plan` (`plan_id`,`stripe_plan_id`,`card_setting`,`period`,`plan_name`,`shortcut`) VALUES (12,'yearly_1_2','share_and_visible','yearly','Shareable and Visible Yearly Plan','12_y');
INSERT INTO `plan` (`plan_id`,`stripe_plan_id`,`card_setting`,`period`,`plan_name`,`shortcut`) VALUES (13,'yearly_1_3','share_and_rating','yearly','Shareable and Rating Allowed Yearly Plan','13_y');
INSERT INTO `plan` (`plan_id`,`stripe_plan_id`,`card_setting`,`period`,`plan_name`,`shortcut`) VALUES (14,'yearly_2_3','visible_and_rating','yearly','Visible and Rating Allowed Yearly Plan','23_y');

INSERT INTO `countries` (`country_id`, `country`, `code`) VALUES
(1, 'Afghanistan', 'AF'),
(2, 'Albania', 'AL'),
(3, 'Algeria', 'DZ'),
(4, 'Andorra', 'AD'),
(5, 'Angola', 'AO'),
(6, 'Antigua and Barbuda', 'AG'),
(7, 'Argentina', 'AR'),
(8, 'Armenia', 'AM'),
(9, 'Australia', 'AU'),
(10, 'Austria', 'AT'),
(11, 'Azerbaijan', 'AZ'),
(12, 'Bahamas, The', 'BS'),
(13, 'Bahrain', 'BH'),
(14, 'Bangladesh', 'BD'),
(15, 'Barbados', 'BB'),
(16, 'Belarus', 'BY'),
(17, 'Belgium', 'BE'),
(18, 'Belize', 'BZ'),
(19, 'Benin', 'BJ'),
(20, 'Bhutan', 'BT'),
(21, 'Bolivia', 'BO'),
(22, 'Bosnia and Herzegovina', 'BA'),
(23, 'Botswana', 'BW'),
(24, 'Brazil', 'BR'),
(25, 'Brunei', 'BN'),
(26, 'Bulgaria', 'BG'),
(27, 'Burkina Faso', 'BF'),
(28, 'Burundi', 'BI'),
(29, 'Cambodia', 'KH'),
(30, 'Cameroon', 'CM'),
(31, 'Canada', 'CA'),
(32, 'Cape Verde', 'CV'),
(33, 'Central African Republic', 'CF'),
(34, 'Chad', 'TD'),
(35, 'Chile', 'CL'),
(36, 'China, People''s Republic of', 'CN'),
(37, 'Colombia', 'CO'),
(38, 'Comoros', 'KM'),
(39, 'Congo, (Congo ? Kinshasa)', 'CD'),
(40, 'Congo, (Congo ? Brazzaville)', 'CG'),
(41, 'Costa Rica', 'CR'),
(42, 'Cote d''Ivoire (Ivory Coast)', 'CI'),
(43, 'Croatia', 'HR'),
(44, 'Cuba', 'CU'),
(45, 'Cyprus', 'CY'),
(46, 'Czech Republic', 'CZ'),
(47, 'Denmark', 'DK'),
(48, 'Djibouti', 'DJ'),
(49, 'Dominica', 'DM'),
(50, 'Dominican Republic', 'DO'),
(51, 'Ecuador', 'EC'),
(52, 'Egypt', 'EG'),
(53, 'El Salvador', 'SV'),
(54, 'Equatorial Guinea', 'GQ'),
(55, 'Eritrea', 'ER'),
(56, 'Estonia', 'EE'),
(57, 'Ethiopia', 'ET'),
(58, 'Fiji', 'FJ'),
(59, 'Finland', 'FI'),
(60, 'France', 'FR'),
(61, 'Gabon', 'GA'),
(62, 'Gambia, The', 'GM'),
(63, 'Georgia', 'GE'),
(64, 'Germany', 'DE'),
(65, 'Ghana', 'GH'),
(66, 'Greece', 'GR'),
(67, 'Grenada', 'GD'),
(68, 'Guatemala', 'GT'),
(69, 'Guinea', 'GN'),
(70, 'Guinea-Bissau', 'GW'),
(71, 'Guyana', 'GY'),
(72, 'Haiti', 'HT'),
(73, 'Honduras', 'HN'),
(74, 'Hungary', 'HU'),
(75, 'Iceland', 'IS'),
(76, 'India', 'IN'),
(77, 'Indonesia', 'ID'),
(78, 'Iran', 'IR'),
(79, 'Iraq', 'IQ'),
(80, 'Ireland', 'IE'),
(81, 'Israel', 'IL'),
(82, 'Italy', 'IT'),
(83, 'Jamaica', 'JM'),
(84, 'Japan', 'JP'),
(85, 'Jordan', 'JO'),
(86, 'Kazakhstan', 'KZ'),
(87, 'Kenya', 'KE'),
(88, 'Kiribati', 'KI'),
(89, 'Korea, North', 'KP'),
(90, 'Korea, South', 'KR'),
(91, 'Kuwait', 'KW'),
(92, 'Kyrgyzstan', 'KG'),
(93, 'Laos', 'LA'),
(94, 'Latvia', 'LV'),
(95, 'Lebanon', 'LB'),
(96, 'Lesotho', 'LS'),
(97, 'Liberia', 'LR'),
(98, 'Libya', 'LY'),
(99, 'Liechtenstein', 'LI'),
(100, 'Lithuania', 'LT'),
(101, 'Luxembourg', 'LU'),
(102, 'Macedonia', 'MK'),
(103, 'Madagascar', 'MG'),
(104, 'Malawi', 'MW'),
(105, 'Malaysia', 'MY'),
(106, 'Maldives', 'MV'),
(107, 'Mali', 'ML'),
(108, 'Malta', 'MT'),
(109, 'Marshall Islands', 'MH'),
(110, 'Mauritania', 'MR'),
(111, 'Mauritius', 'MU'),
(112, 'Mexico', 'MX'),
(113, 'Micronesia', 'FM'),
(114, 'Moldova', 'MD'),
(115, 'Monaco', 'MC'),
(116, 'Mongolia', 'MN'),
(117, 'Montenegro', 'ME'),
(118, 'Morocco', 'MA'),
(119, 'Mozambique', 'MZ'),
(120, 'Myanmar (Burma)', 'MM'),
(121, 'Namibia', 'NA'),
(122, 'Nauru', 'NR'),
(123, 'Nepal', 'NP'),
(124, 'Netherlands', 'NL'),
(125, 'New Zealand', 'NZ'),
(126, 'Nicaragua', 'NI'),
(127, 'Niger', 'NE'),
(128, 'Nigeria', 'NG'),
(129, 'Norway', 'NO'),
(130, 'Oman', 'OM'),
(131, 'Pakistan', 'PK'),
(132, 'Palau', 'PW'),
(133, 'Panama', 'PA'),
(134, 'Papua New Guinea', 'PG'),
(135, 'Paraguay', 'PY'),
(136, 'Peru', 'PE'),
(137, 'Philippines', 'PH'),
(138, 'Poland', 'PL'),
(139, 'Portugal', 'PT'),
(140, 'Qatar', 'QA'),
(141, 'Romania', 'RO'),
(142, 'Russia', 'RU'),
(143, 'Rwanda', 'RW'),
(144, 'Saint Kitts and Nevis', 'KN'),
(145, 'Saint Lucia', 'LC'),
(146, 'Saint Vincent and the Grenadines', 'VC'),
(147, 'Samoa', 'WS'),
(148, 'San Marino', 'SM'),
(149, 'Sao Tome and Principe', 'ST'),
(150, 'Saudi Arabia', 'SA'),
(151, 'Senegal', 'SN'),
(152, 'Serbia', 'RS'),
(153, 'Seychelles', 'SC'),
(154, 'Sierra Leone', 'SL'),
(155, 'Singapore', 'SG'),
(156, 'Slovakia', 'SK'),
(157, 'Slovenia', 'SI'),
(158, 'Solomon Islands', 'SB'),
(159, 'Somalia', 'SO'),
(160, 'South Africa', 'ZA'),
(161, 'Spain', 'ES'),
(162, 'Sri Lanka', 'LK'),
(163, 'Sudan', 'SD'),
(164, 'Suriname', 'SR'),
(165, 'Swaziland', 'SZ'),
(166, 'Sweden', 'SE'),
(167, 'Switzerland', 'CH'),
(168, 'Syria', 'SY'),
(169, 'Tajikistan', 'TJ'),
(170, 'Tanzania', 'TZ'),
(171, 'Thailand', 'TH'),
(172, 'Timor-Leste (East Timor)', 'TL'),
(173, 'Togo', 'TG'),
(174, 'Tonga', 'TO'),
(175, 'Trinidad and Tobago', 'TT'),
(176, 'Tunisia', 'TN'),
(177, 'Turkey', 'TR'),
(178, 'Turkmenistan', 'TM'),
(179, 'Tuvalu', 'TV'),
(180, 'Uganda', 'UG'),
(181, 'Ukraine', 'UA'),
(182, 'United Arab Emirates', 'AE'),
(183, 'United Kingdom', 'GB'),
(184, 'United States', 'US'),
(185, 'Uruguay', 'UY'),
(186, 'Uzbekistan', 'UZ'),
(187, 'Vanuatu', 'VU'),
(188, 'Vatican City', 'VA'),
(189, 'Venezuela', 'VE'),
(190, 'Vietnam', 'VN'),
(191, 'Yemen', 'YE'),
(192, 'Zambia', 'ZM'),
(193, 'Zimbabwe', 'ZW'),
(194, 'Abkhazia', 'GE'),
(195, 'China, Republic of (Taiwan)', 'TW'),
(196, 'Nagorno-Karabakh', 'AZ'),
(197, 'Northern Cyprus', 'CY'),
(198, 'Pridnestrovie (Transnistria)', 'MD'),
(199, 'Somaliland', 'SO'),
(200, 'South Ossetia', 'GE'),
(201, 'Ashmore and Cartier Islands', 'AU'),
(202, 'Christmas Island', 'CX'),
(203, 'Cocos (Keeling) Islands', 'CC'),
(204, 'Coral Sea Islands', 'AU'),
(205, 'Heard Island and McDonald Islands', 'HM'),
(206, 'Norfolk Island', 'NF'),
(207, 'New Caledonia', 'NC'),
(208, 'French Polynesia', 'PF'),
(209, 'Mayotte', 'YT'),
(210, 'Saint Barthelemy', 'GP'),
(211, 'Saint Martin', 'GP'),
(212, 'Saint Pierre and Miquelon', 'PM'),
(213, 'Wallis and Futuna', 'WF'),
(214, 'French Southern and Antarctic Lands', 'TF'),
(215, 'Clipperton Island', 'PF'),
(216, 'Bouvet Island', 'BV'),
(217, 'Cook Islands', 'CK'),
(218, 'Niue', 'NU'),
(219, 'Tokelau', 'TK'),
(220, 'Guernsey', 'GG'),
(221, 'Isle of Man', 'IM'),
(222, 'Jersey', 'JE'),
(223, 'Anguilla', 'AI'),
(224, 'Bermuda', 'BM'),
(225, 'British Indian Ocean Territory', 'IO'),
(226, 'British Sovereign Base Areas', ''),
(227, 'British Virgin Islands', 'VG'),
(228, 'Cayman Islands', 'KY'),
(229, 'Falkland Islands (Islas Malvinas)', 'FK'),
(230, 'Gibraltar', 'GI'),
(231, 'Montserrat', 'MS'),
(232, 'Pitcairn Islands', 'PN'),
(233, 'Saint Helena', 'SH'),
(234, 'South Georgia & South Sandwich Islands', 'GS'),
(235, 'Turks and Caicos Islands', 'TC'),
(236, 'Northern Mariana Islands', 'MP'),
(237, 'Puerto Rico', 'PR'),
(238, 'American Samoa', 'AS'),
(239, 'Baker Island', 'UM'),
(240, 'Guam', 'GU'),
(241, 'Howland Island', 'UM'),
(242, 'Jarvis Island', 'UM'),
(243, 'Johnston Atoll', 'UM'),
(244, 'Kingman Reef', 'UM'),
(245, 'Midway Islands', 'UM'),
(246, 'Navassa Island', 'UM'),
(247, 'Palmyra Atoll', 'UM'),
(248, 'U.S. Virgin Islands', 'VI'),
(249, 'Wake Island', 'UM'),
(250, 'Hong Kong', 'HK'),
(251, 'Macau', 'MO'),
(252, 'Faroe Islands', 'FO'),
(253, 'Greenland', 'GL'),
(254, 'French Guiana', 'GF'),
(255, 'Guadeloupe', 'GP'),
(256, 'Martinique', 'MQ'),
(257, 'Reunion', 'RE'),
(258, 'Aland', 'AX'),
(259, 'Aruba', 'AW'),
(260, 'Netherlands Antilles', 'AN'),
(261, 'Svalbard', 'SJ'),
(262, 'Ascension', 'AC'),
(263, 'Tristan da Cunha', 'TA'),
(268, 'Australian Antarctic Territory', 'AQ'),
(269, 'Ross Dependency', 'AQ'),
(270, 'Peter I Island', 'AQ'),
(271, 'Queen Maud Land', 'AQ'),
(272, 'British Antarctic Territory', 'AQ'); 
