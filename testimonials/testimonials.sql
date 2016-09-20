-- Testimonials Database 
-- version 1.0
-- http://www.musiccitygur.com
--

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `testimonials`
--

-- --------------------------------------------------------

--
-- Table structure for table `configuration`
--

DROP TABLE IF EXISTS `configuration`;
CREATE TABLE IF NOT EXISTS `configuration` (
  `configuration_group_id` int(1) NOT NULL AUTO_INCREMENT,
  `configuration_title` text NOT NULL,
  `configuration_key` varchar(255) NOT NULL,
  `configuration_value` text NOT NULL,
  `configuration_description` text NOT NULL,
  PRIMARY KEY (`configuration_group_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `configuration`
--

INSERT INTO `configuration` (`configuration_group_id`, `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`) VALUES
(1, 'Number Of Testimonials to display in Testimonials Sidebox', 'MAX_DISPLAY_TESTIMONIALS_MANAGER_TITLES', '5', 'Set the number of testimonials to display in the Latest Testimonials box.'),
(2, 'Testimonial Title Minimum Length', 'ENTRY_TESTIMONIALS_TITLE_MIN_LENGTH', '2', 'Minimum length of link title.'),
(3, 'Testimonial Text Minimum Length', 'ENTRY_TESTIMONIALS_TEXT_MIN_LENGTH', '10', 'Minimum length of Testimonial description.'),
(4, 'Testimonial Text Maximum Length', 'ENTRY_TESTIMONIALS_TEXT_MAX_LENGTH', '1000', 'Maximum length of Testimonial description.'),
(5, 'Testimonial Contact Name Minimum Length', 'ENTRY_TESTIMONIALS_CONTACT_NAME_MIN_LENGTH', '2', 'Minimum length of link contact name.'),
(6, 'Display Truncated Testimonials in Sidebox', 'DISPLAY_TESTIMONIALS_MANAGER_TRUNCATED_TEXT', 'true', 'Display truncated text in sidebox'),
(7, 'Length of truncated testimonials to display', 'TESTIMONIALS_MANAGER_DESCRIPTION_LENGTH', '150', 'If Display Truncated Testimonials in Sidebox is true - set the amount of characters to display from the Testimonials in the Testimonials Manager sidebox.'),
(8, 'Number Of Testimonials to display on all testimonials page', 'MAX_DISPLAY_TESTIMONIALS_MANAGER_ALL_TESTIMONIALS', '5', 'Set the number of testimonials to display on the all testimonials page.'),
(9, 'Display Date Published on Testimonials page', 'DISPLAY_TESTIMONIALS_DATE_PUBLISHED', 'true', 'Display date published on testimonials page'),
(10, 'Define Testimonial Status', 'DEFINE_TESTIMONIAL_STATUS', '1', 'Enable the Defined Testimonial Link/Text?<br />0= Link ON, Define Text OFF<br />1= Link ON, Define Text ON<br />2= Link OFF, Define Text ON<br />3= Link OFF, Define Text OFF');

-- --------------------------------------------------------

--
-- Table structure for table `testimonials_manager`
--

DROP TABLE IF EXISTS `testimonials_manager`;
CREATE TABLE IF NOT EXISTS `testimonials_manager` (
  `testimonials_id` int(11) NOT NULL AUTO_INCREMENT,
  `language_id` int(11) NOT NULL DEFAULT '1',
  `testimonials_title` varchar(64) NOT NULL DEFAULT '',
  `testimonials_url` varchar(255) DEFAULT NULL,
  `testimonials_name` text NOT NULL,
  `testimonials_image` varchar(254) NOT NULL DEFAULT '',
  `testimonials_html_text` text,
  `testimonials_mail` text NOT NULL,
  `testimonials_company` varchar(255) DEFAULT NULL,
  `testimonials_city` varchar(255) DEFAULT NULL,
  `testimonials_country` varchar(255) DEFAULT NULL,
  `testimonials_show_email` char(1) DEFAULT '0',
  `status` int(1) NOT NULL DEFAULT '0',
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`testimonials_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `testimonials_manager`
--

INSERT INTO `testimonials_manager` (`testimonials_id`, `language_id`, `testimonials_title`, `testimonials_url`, `testimonials_name`, `testimonials_image`, `testimonials_html_text`, `testimonials_mail`, `testimonials_company`, `testimonials_city`, `testimonials_country`, `testimonials_show_email`, `status`, `date_added`, `last_update`) VALUES
(2, 1, 'Great Service!', NULL, 'Scott Fleming', '', 'A truly amazing service, and very convenient too!', 'scott@musiccityguru.com', 'Music City Guru', 'Madison', 'TN, United States', '', 1, '2011-02-22 10:26:44', NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
