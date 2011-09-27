-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 13, 2011 at 02:41 PM
-- Server version: 5.5.8
-- PHP Version: 5.3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `spx_portal`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--
DROP DATABASE  IF EXISTS `spx_portal`;
CREATE DATABASE IF NOT EXISTS `spx_portal` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `spx_portal`;

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

CREATE TABLE IF NOT EXISTS `activities` (
  `ActivityID` int(11) NOT NULL AUTO_INCREMENT,
  `UserName` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `UserActivity` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `Module` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `UserActivityDateTime` datetime NOT NULL,
  PRIMARY KEY (`ActivityID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `activities`
--


-- --------------------------------------------------------

--
-- Table structure for table `emailtemplates`
--

CREATE TABLE IF NOT EXISTS `emailtemplates` (
  `EmailTemplateID` int(11) NOT NULL AUTO_INCREMENT,
  `EmailTemplateContent` text COLLATE utf8_unicode_ci NOT NULL,
  `EmailTemplateSubject` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `EmailTemplateStatus` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `EmailTemplateIsActive` char(1) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`EmailTemplateID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `emailtemplates`
--

INSERT INTO `emailtemplates` (`EmailTemplateID`, `EmailTemplateContent`, `EmailTemplateSubject`, `EmailTemplateStatus`, `EmailTemplateIsActive`) VALUES
(1, '&lt;p&gt;&lt;font class=&quot;Apple-style-span&quot; face=&quot;Verdana, sans-serif&quot; size=&quot;2&quot;&gt;&lt;/font&gt;&lt;/p&gt;&lt;p&gt;&lt;/p&gt;&lt;p&gt;&lt;font class=&quot;Apple-style-span&quot; face=&quot;Verdana, sans-serif&quot; size=&quot;2&quot;&gt;&lt;/font&gt;&lt;/p&gt;&lt;p&gt;&lt;font class=&quot;Apple-style-span&quot; face=&quot;Verdana, sans-serif&quot; size=&quot;2&quot;&gt;&lt;span class=&quot;Apple-style-span&quot; style=&quot;font-family: ''Times New Roman''; font-size: medium; &quot;&gt;&lt;p&gt;&lt;font class=&quot;Apple-style-span&quot; size=&quot;2&quot; face=&quot;Verdana, sans-serif&quot;&gt;&lt;span style=&quot;font-family: Verdana, sans-serif; font-size: 10pt; &quot;&gt;Dear Jonckers Team,&lt;o:p&gt;&lt;/o:p&gt;&lt;/span&gt;&lt;/font&gt;&lt;/p&gt;&lt;font class=&quot;Apple-style-span&quot; size=&quot;2&quot; face=&quot;Verdana, sans-serif&quot;&gt;&lt;p&gt;&lt;span style=&quot;font-family: Verdana, sans-serif; font-size: 10pt; &quot;&gt;A new handoff has been created&amp;nbsp;by {UserName}.&lt;o:p&gt;&lt;/o:p&gt;&lt;/span&gt;&lt;/p&gt;&lt;p&gt;&lt;span style=&quot;font-family: Verdana, sans-serif; font-size: 10pt; &quot;&gt;You can now login to confirm receipt and launch localization process by changing the HO status to &quot;Received&quot; on {LinkToHOEDIT}.&lt;o:p&gt;&lt;/o:p&gt;&lt;/span&gt;&lt;/p&gt;&lt;p&gt;&lt;span style=&quot;font-family: Verdana, sans-serif; font-size: 10pt; &quot;&gt;Thank you&lt;/span&gt;&lt;o:p&gt;&lt;/o:p&gt;&lt;/p&gt;&lt;p&gt;&lt;span style=&quot;font-family: Verdana, sans-serif; font-size: 10pt; &quot;&gt;--------------------------------- Details ---------------------------------&lt;/span&gt;&lt;o:p&gt;&lt;/o:p&gt;&lt;/p&gt;&lt;p&gt;&lt;span style=&quot;font-family: Verdana, sans-serif; font-size: 10pt; &quot;&gt;Source String: {HOTITLE}&lt;/span&gt;&lt;o:p&gt;&lt;/o:p&gt;&lt;/p&gt;&lt;p&gt;&lt;span style=&quot;font-family: Verdana, sans-serif; font-size: 10pt; &quot;&gt;Source Language:&amp;nbsp;&lt;/span&gt;{SRCLang}&lt;o:p&gt;&lt;/o:p&gt;&lt;/p&gt;&lt;p&gt;&lt;span style=&quot;font-family: Verdana, sans-serif; font-size: 10pt; &quot;&gt;Target&amp;nbsp;Language(s):&lt;/span&gt;{TGTLang}&lt;o:p&gt;&lt;/o:p&gt;&lt;/p&gt;&lt;p&gt;&lt;font size=&quot;+0&quot;&gt;&lt;font class=&quot;Apple-style-span&quot; size=&quot;2&quot; face=&quot;Verdana, sans-serif&quot;&gt;&lt;span style=&quot;font-family: Verdana, sans-serif; font-size: 10pt; &quot;&gt;&lt;span style=&quot;font-family: Verdana, sans-serif; font-size: 10pt; &quot;&gt;Link to Handoff Details: {LinkToHODetails}&lt;/span&gt;&lt;/span&gt;&lt;/font&gt;&lt;/font&gt;&lt;/p&gt;&lt;p&gt;&lt;font size=&quot;+0&quot;&gt;&lt;font class=&quot;Apple-style-span&quot; size=&quot;2&quot; face=&quot;Verdana, sans-serif&quot;&gt;&lt;span style=&quot;font-family: Verdana, sans-serif; font-size: 10pt; &quot;&gt;&lt;span style=&quot;font-family: Verdana, sans-serif; font-size: 10pt; &quot;&gt;&lt;/span&gt;&lt;/span&gt;&lt;/font&gt;&lt;/font&gt;&lt;span style=&quot;font-family: Verdana, sans-serif; font-size: 10pt; &quot;&gt;Instructions: {HandoffInstructions}&lt;/span&gt;&lt;o:p&gt;&lt;/o:p&gt;&lt;/p&gt;&lt;/font&gt;&lt;p&gt;&lt;/p&gt;&lt;font class=&quot;Apple-style-span&quot; size=&quot;2&quot; face=&quot;Verdana, sans-serif&quot;&gt;&lt;p&gt;&lt;a name=&quot;_MailAutoSig&quot;&gt;&lt;strong&gt;&lt;span style=&quot;font-family: Verdana, sans-serif; color: rgb(11, 119, 145); font-size: 10pt; &quot;&gt;Bento&lt;/span&gt;&lt;/strong&gt;&lt;/a&gt;&lt;span&gt;&lt;strong&gt;&lt;span style=&quot;font-family: Verdana, sans-serif; color: gray; font-size: 10pt; &quot;&gt;|&lt;/span&gt;&lt;/strong&gt;&lt;/span&gt;&lt;span&gt;&lt;span style=&quot;font-family: Verdana, sans-serif; color: gray; font-size: 10pt; &quot;&gt;&amp;nbsp;Automatic email&lt;/span&gt;&lt;/span&gt;&lt;span&gt;&lt;span style=&quot;font-family: Verdana, sans-serif; color: rgb(127, 127, 127); font-size: 10pt; &quot;&gt;, Customer Solutions Department&amp;nbsp;&lt;/span&gt;&lt;/span&gt;&lt;span&gt;&lt;span style=&quot;font-family: Verdana, sans-serif; color: gray; font-size: 10pt; &quot;&gt;| Jonckers Translation &amp;amp; Engineering |&lt;/span&gt;&lt;/span&gt;&lt;a href=&quot;http://www.jonckers.com/&quot;&gt;&lt;span&gt;&lt;span style=&quot;font-family: Verdana, sans-serif; font-size: 10pt; &quot;&gt;www.jonckers.com&lt;/span&gt;&lt;/span&gt;&lt;/a&gt;&lt;span&gt;&lt;o:p&gt;&lt;/o:p&gt;&lt;/span&gt;&lt;/p&gt;&lt;p&gt;&lt;span&gt;&lt;b&gt;&lt;i&gt;&lt;span style=&quot;font-family: Arial, sans-serif; color: rgb(74, 68, 42); font-size: 8pt; &quot;&gt;The information contained in this entire e-mail is confidential and/or privileged. This e-mail is intended to be read or used solely by the addressee. If the reader of this e-mail is not the intended recipient, you are hereby notified that any use, dissemination, distribution, publication or copying of this e-mail is prohibited. Please do not reply to this unmonitored email address. If you receive this e-mail in error, please destroy it and notify&amp;nbsp;&lt;/span&gt;&lt;/i&gt;&lt;/b&gt;&lt;/span&gt;&lt;a href=&quot;mailto:admin-fms@jonckers.be&quot;&gt;&lt;span&gt;&lt;b&gt;&lt;i&gt;&lt;span style=&quot;font-family: Arial, sans-serif; font-size: 8pt; &quot;&gt;admin-fms@jonckers.be&lt;/span&gt;&lt;/i&gt;&lt;/b&gt;&lt;/span&gt;&lt;/a&gt;&lt;span&gt;&lt;b&gt;&lt;i&gt;&lt;span style=&quot;font-family: Arial, sans-serif; color: rgb(74, 68, 42); font-size: 8pt; &quot;&gt;.&amp;nbsp;&lt;/span&gt;&lt;/i&gt;&lt;/b&gt;&lt;/span&gt;&lt;/p&gt;&lt;/font&gt;&lt;/span&gt;&lt;/font&gt;&lt;/p&gt;&lt;p&gt;&lt;/p&gt;&lt;p&gt;&lt;/p&gt;', 'A new string-based Handoff has been created', 'HO - ReadyToloc', 'Y'),
(2, '&lt;p&gt;&lt;font class=&quot;Apple-style-span&quot; face=&quot;verdana&quot; size=&quot;2&quot;&gt;&lt;/font&gt;&lt;/p&gt;&lt;p&gt;&lt;/p&gt;&lt;p&gt;&lt;font class=&quot;Apple-style-span&quot; face=&quot;Verdana, sans-serif&quot; size=&quot;2&quot;&gt;&lt;/font&gt;&lt;/p&gt;&lt;p&gt;&lt;font class=&quot;Apple-style-span&quot; face=&quot;Verdana, sans-serif&quot; size=&quot;2&quot;&gt;&lt;span class=&quot;Apple-style-span&quot; style=&quot;font-family: ''Times New Roman''; font-size: medium; &quot;&gt;&lt;/span&gt;&lt;/font&gt;&lt;/p&gt;&lt;p&gt;&lt;font class=&quot;Apple-style-span&quot; face=&quot;Verdana, sans-serif&quot; size=&quot;2&quot;&gt;&lt;font class=&quot;Apple-style-span&quot; size=&quot;2&quot; face=&quot;Verdana, sans-serif&quot;&gt;&lt;span style=&quot;font-family: Verdana, sans-serif; font-size: 10pt; &quot;&gt;Dear {UserName},&lt;o:p&gt;&lt;/o:p&gt;&lt;/span&gt;&lt;/font&gt;&lt;/font&gt;&lt;/p&gt;&lt;font class=&quot;Apple-style-span&quot; face=&quot;Verdana, sans-serif&quot; size=&quot;2&quot;&gt;&lt;font class=&quot;Apple-style-span&quot; size=&quot;2&quot; face=&quot;Verdana, sans-serif&quot;&gt;&lt;p&gt;&lt;span style=&quot;font-family: Verdana, sans-serif; font-size: 10pt; &quot;&gt;The translated strings for â€œ{HOTITLE}â€ are now ready.&lt;o:p&gt;&lt;/o:p&gt;&lt;/span&gt;&lt;/p&gt;&lt;p&gt;&lt;span style=&quot;font-family: Verdana, sans-serif; font-size: 10pt; &quot;&gt;You can now login and collect localized strings on the Bento portal on {LinkToHODetails}.&lt;o:p&gt;&lt;/o:p&gt;&lt;/span&gt;&lt;/p&gt;&lt;p&gt;&lt;span style=&quot;font-family: Verdana, sans-serif; font-size: 10pt; &quot;&gt;Please confirm receipt by changing the handoff status to &quot;Closed&quot; on&amp;nbsp;&lt;span style=&quot;font-family: Verdana, sans-serif; font-size: 10pt; &quot;&gt;{LinkToHOEDIT}.&lt;/span&gt;&lt;o:p&gt;&lt;/o:p&gt;&lt;/span&gt;&lt;/p&gt;&lt;p&gt;&lt;span style=&quot;font-family: Verdana, sans-serif; font-size: 10pt; &quot;&gt;If you have any questions please contact your project manager ({JTEPMEmail})&lt;o:p&gt;&lt;/o:p&gt;&lt;/span&gt;&lt;/p&gt;&lt;p&gt;&lt;span style=&quot;font-family: Verdana, sans-serif; font-size: 10pt; &quot;&gt;Thank you&amp;nbsp;&lt;o:p&gt;&lt;/o:p&gt;&lt;/span&gt;&lt;/p&gt;&lt;p&gt;&lt;span style=&quot;font-family: Verdana, sans-serif; font-size: 10pt; &quot;&gt;&lt;span&gt;&amp;nbsp;&lt;/span&gt;--------------------------------- Details ---------------------------------&lt;/span&gt;&lt;o:p&gt;&lt;/o:p&gt;&lt;/p&gt;&lt;p&gt;&lt;span style=&quot;font-family: Verdana, sans-serif; font-size: 10pt; &quot;&gt;Source String: {HOTITLE}&lt;/span&gt;&lt;/p&gt;&lt;p&gt;&lt;span style=&quot;font-family: Verdana, sans-serif; font-size: 10pt; &quot;&gt;Localized Strings:&lt;o:p&gt;&lt;/o:p&gt;&lt;/span&gt;&lt;/p&gt;&lt;p&gt;&lt;span style=&quot;font-family: Verdana, sans-serif; font-size: 10pt; &quot;&gt;{LocalizedStringsDetails}&lt;o:p&gt;&lt;/o:p&gt;&lt;/span&gt;&lt;/p&gt;&lt;p&gt;&lt;span style=&quot;font-family: Verdana, sans-serif; font-size: 10pt; &quot;&gt;Link to Handoff Details: {LinkToHODetails}&lt;/span&gt;&lt;o:p&gt;&lt;/o:p&gt;&lt;/p&gt;&lt;/font&gt;&lt;p&gt;&lt;/p&gt;&lt;font class=&quot;Apple-style-span&quot; size=&quot;2&quot; face=&quot;verdana&quot;&gt;&lt;p&gt;&lt;strong&gt;&lt;span style=&quot;font-family: Verdana, sans-serif; color: rgb(11, 119, 145); font-size: 10pt; &quot;&gt;Bento&lt;/span&gt;&lt;/strong&gt;&lt;strong&gt;&lt;span style=&quot;font-family: Verdana, sans-serif; color: gray; font-size: 10pt; &quot;&gt;|&lt;/span&gt;&lt;/strong&gt;&lt;span style=&quot;font-family: Verdana, sans-serif; color: gray; font-size: 10pt; &quot;&gt;&amp;nbsp;Automatic email&lt;/span&gt;&lt;span style=&quot;font-family: Verdana, sans-serif; color: rgb(127, 127, 127); font-size: 10pt; &quot;&gt;, Customer Solutions Department&amp;nbsp;&lt;/span&gt;&lt;span style=&quot;font-family: Verdana, sans-serif; color: gray; font-size: 10pt; &quot;&gt;| Jonckers Translation &amp;amp; Engineering |&lt;/span&gt;&lt;a href=&quot;http://www.jonckers.com/&quot;&gt;&lt;span style=&quot;font-family: Verdana, sans-serif; color: purple; font-size: 10pt; &quot;&gt;www.jonckers.com&lt;/span&gt;&lt;/a&gt;&lt;o:p&gt;&lt;/o:p&gt;&lt;/p&gt;&lt;p&gt;&lt;b&gt;&lt;i&gt;&lt;span style=&quot;font-family: Arial, sans-serif; color: rgb(74, 68, 42); font-size: 8pt; &quot;&gt;The information contained in this entire e-mail is confidential and/or privileged. This e-mail is intended to be read or used solely by the addressee. If the reader of this e-mail is not the intended recipient, you are hereby notified that any use, dissemination, distribution, publication or copying of this e-mail is prohibited. Please do not reply to this unmonitored email address. If you receive this e-mail in error, please destroy it and notify&amp;nbsp;&lt;/span&gt;&lt;/i&gt;&lt;/b&gt;&lt;a href=&quot;mailto:admin-fms@jonckers.be&quot;&gt;&lt;b&gt;&lt;i&gt;&lt;span style=&quot;font-family: Arial, sans-serif; font-size: 8pt; &quot;&gt;admin-fms@jonckers.be&lt;/span&gt;&lt;/i&gt;&lt;/b&gt;&lt;/a&gt;&lt;b&gt;&lt;i&gt;&lt;span style=&quot;font-family: Arial, sans-serif; color: rgb(74, 68, 42); font-size: 8pt; &quot;&gt;.&lt;/span&gt;&lt;/i&gt;&lt;/b&gt;&lt;/p&gt;&lt;/font&gt;&lt;/font&gt;&lt;p&gt;&lt;/p&gt;&lt;p&gt;&lt;/p&gt;&lt;p&gt;&lt;/p&gt;', 'A string-based Handoff has been localized', 'HB - Completed', 'Y'),
(3, '&lt;p class=&quot;MsoNormal&quot;&gt;&lt;font class=&quot;Apple-style-span&quot; face=&quot;verdana&quot; size=&quot;2&quot;&gt;&lt;/font&gt;&lt;/p&gt;&lt;p&gt;&lt;/p&gt;&lt;p&gt;&lt;font class=&quot;Apple-style-span&quot; face=&quot;Verdana, sans-serif&quot; size=&quot;2&quot;&gt;&lt;/font&gt;&lt;/p&gt;&lt;p&gt;&lt;font class=&quot;Apple-style-span&quot; face=&quot;Verdana, sans-serif&quot; size=&quot;2&quot;&gt;&lt;span style=&quot;font-size:10.0pt;font-family:&amp;quot;Verdana&amp;quot;,&amp;quot;sans-serif&amp;quot;&quot;&gt;Dear\r\nJonckers Team,&lt;o:p&gt;&lt;/o:p&gt;&lt;/span&gt;&lt;/font&gt;&lt;/p&gt;&lt;font class=&quot;Apple-style-span&quot; face=&quot;Verdana, sans-serif&quot; size=&quot;2&quot;&gt;\r\n\r\n&lt;p class=&quot;MsoNormal&quot; style=&quot;mso-margin-top-alt:auto;mso-margin-bottom-alt:auto&quot;&gt;&lt;span style=&quot;font-size:10.0pt;line-height:115%;font-family:&amp;quot;Verdana&amp;quot;,&amp;quot;sans-serif&amp;quot;&quot;&gt;This\r\nautomatic email is to confirm reception of localized Strings for â€œ{HOTITLE}â€ by\r\n{UserName}.&lt;/span&gt;&lt;span style=&quot;font-size:12.0pt;line-height:115%;font-family:\r\n&amp;quot;Times New Roman&amp;quot;,&amp;quot;serif&amp;quot;;mso-bidi-font-family:&amp;quot;Times New Roman&amp;quot;;mso-bidi-theme-font:\r\nminor-bidi&quot;&gt;&lt;o:p&gt;&lt;/o:p&gt;&lt;/span&gt;&lt;/p&gt;\r\n\r\n&lt;p class=&quot;MsoNormal&quot; style=&quot;mso-margin-top-alt:auto;mso-margin-bottom-alt:auto&quot;&gt;&lt;span style=&quot;font-size:10.0pt;line-height:115%;font-family:&amp;quot;Verdana&amp;quot;,&amp;quot;sans-serif&amp;quot;&quot;&gt;Thank\r\nyou&lt;/span&gt;&lt;o:p&gt;&lt;/o:p&gt;&lt;/p&gt;\r\n\r\n&lt;p&gt;&lt;span style=&quot;font-size:10.0pt;font-family:&amp;quot;Verdana&amp;quot;,&amp;quot;sans-serif&amp;quot;&quot;&gt;---------------------------------\r\nDetails ---------------------------------&lt;o:p&gt;&lt;/o:p&gt;&lt;/span&gt;&lt;/p&gt;\r\n\r\n&lt;p class=&quot;MsoNormal&quot; style=&quot;mso-margin-top-alt:auto;mso-margin-bottom-alt:auto&quot;&gt;&lt;span style=&quot;font-size:10.0pt;line-height:115%;font-family:&amp;quot;Verdana&amp;quot;,&amp;quot;sans-serif&amp;quot;&quot;&gt;{LinkToHODetails}&lt;/span&gt;&lt;o:p&gt;&lt;/o:p&gt;&lt;/p&gt;\r\n\r\n&lt;!--EndFragment--&gt;&lt;/font&gt;&lt;font class=&quot;Apple-style-span&quot; size=&quot;2&quot; face=&quot;verdana&quot;&gt;&lt;p&gt;&lt;strong&gt;&lt;span style=&quot;font-family: Verdana, sans-serif; color: rgb(11, 119, 145); font-size: 10pt; &quot;&gt;Bento&lt;/span&gt;&lt;/strong&gt;&lt;strong&gt;&lt;span style=&quot;font-family: Verdana, sans-serif; color: gray; font-size: 10pt; &quot;&gt;|&lt;/span&gt;&lt;/strong&gt;&lt;span style=&quot;font-family: Verdana, sans-serif; color: gray; font-size: 10pt; &quot;&gt;&amp;nbsp;Automatic email&lt;/span&gt;&lt;span style=&quot;font-family: Verdana, sans-serif; color: rgb(127, 127, 127); font-size: 10pt; &quot;&gt;, Customer Solutions Department&amp;nbsp;&lt;/span&gt;&lt;span style=&quot;font-family: Verdana, sans-serif; color: gray; font-size: 10pt; &quot;&gt;| Jonckers Translation &amp;amp; Engineering |&lt;/span&gt;&lt;a href=&quot;http://www.jonckers.com/&quot;&gt;&lt;span style=&quot;font-family: Verdana, sans-serif; color: purple; font-size: 10pt; &quot;&gt;www.jonckers.com&lt;/span&gt;&lt;/a&gt;&lt;o:p&gt;&lt;/o:p&gt;&lt;/p&gt;&lt;p&gt;&lt;b&gt;&lt;i&gt;&lt;span style=&quot;font-family: Arial, sans-serif; color: rgb(74, 68, 42); font-size: 8pt; &quot;&gt;The information contained in this entire e-mail is confidential and/or privileged. This e-mail is intended to be read or used solely by the addressee. If the reader of this e-mail is not the intended recipient, you are hereby notified that any use, dissemination, distribution, publication or copying of this e-mail is prohibited. Please do not reply to this unmonitored email address. If you receive this e-mail in error, please destroy it and notify&amp;nbsp;&lt;/span&gt;&lt;/i&gt;&lt;/b&gt;&lt;a href=&quot;mailto:admin-fms@jonckers.be&quot;&gt;&lt;b&gt;&lt;i&gt;&lt;span style=&quot;font-family: Arial, sans-serif; font-size: 8pt; &quot;&gt;admin-fms@jonckers.be&lt;/span&gt;&lt;/i&gt;&lt;/b&gt;&lt;/a&gt;&lt;b&gt;&lt;i&gt;&lt;span style=&quot;font-family: Arial, sans-serif; color: rgb(74, 68, 42); font-size: 8pt; &quot;&gt;.&lt;/span&gt;&lt;/i&gt;&lt;/b&gt;&lt;/p&gt;&lt;/font&gt;&lt;p&gt;&lt;/p&gt;&lt;p&gt;&lt;/p&gt;', 'A string-based Handoff has been closed', 'HO - Closed', 'Y'),
(4, '&lt;p class=&quot;MsoNormal&quot;&gt;&lt;font class=&quot;Apple-style-span&quot; face=&quot;verdana&quot; size=&quot;2&quot;&gt;&lt;/font&gt;&lt;/p&gt;&lt;p&gt;&lt;/p&gt;&lt;p&gt;&lt;font class=&quot;Apple-style-span&quot; face=&quot;Verdana, sans-serif&quot; size=&quot;2&quot;&gt;&lt;/font&gt;&lt;/p&gt;&lt;p class=&quot;MsoNormal&quot; style=&quot;mso-margin-top-alt:auto;mso-margin-bottom-alt:auto;\r\nline-height:normal&quot;&gt;&lt;font class=&quot;Apple-style-span&quot; face=&quot;Verdana, sans-serif&quot; size=&quot;2&quot;&gt;&lt;span class=&quot;Apple-style-span&quot; style=&quot;font-family: ''Times New Roman''; font-size: medium; &quot;&gt;&lt;/span&gt;&lt;/font&gt;&lt;/p&gt;&lt;p class=&quot;MsoNormal&quot; style=&quot;line-height: normal; &quot;&gt;&lt;font class=&quot;Apple-style-span&quot; face=&quot;Verdana, sans-serif&quot; size=&quot;2&quot;&gt;&lt;font class=&quot;Apple-style-span&quot; size=&quot;2&quot; face=&quot;Verdana, sans-serif&quot;&gt;&lt;span style=&quot;font-family: Verdana, sans-serif; font-size: 10pt; &quot;&gt;[Email Sent to Translators in BCC]&lt;/span&gt;&lt;/font&gt;&lt;/font&gt;&lt;/p&gt;&lt;font class=&quot;Apple-style-span&quot; face=&quot;Verdana, sans-serif&quot; size=&quot;2&quot;&gt;&lt;font class=&quot;Apple-style-span&quot; size=&quot;2&quot; face=&quot;Verdana, sans-serif&quot;&gt;&lt;p class=&quot;MsoNormal&quot; style=&quot;line-height: normal; &quot;&gt;&lt;span style=&quot;font-family: Verdana, sans-serif; font-size: 10pt; &quot;&gt;Dear Translators,&lt;o:p&gt;&lt;/o:p&gt;&lt;/span&gt;&lt;/p&gt;&lt;p class=&quot;MsoNormal&quot; style=&quot;line-height: normal; &quot;&gt;&lt;span style=&quot;font-family: Verdana, sans-serif; font-size: 10pt; &quot;&gt;The String&amp;nbsp;&lt;/span&gt;&lt;span style=&quot;font-family: Verdana, sans-serif; font-size: 10pt; &quot;&gt;â€œ{HOTITLE}â€&lt;/span&gt;&lt;span style=&quot;font-family: Verdana, sans-serif; font-size: 10pt; &quot;&gt;&amp;nbsp;is ready to be localized on Bento portal.&lt;o:p&gt;&lt;/o:p&gt;&lt;/span&gt;&lt;/p&gt;&lt;p class=&quot;MsoNormal&quot; style=&quot;line-height: normal; &quot;&gt;&lt;span style=&quot;font-family: Verdana, sans-serif; font-size: 10pt; &quot;&gt;Source Language: {SRCLang}&lt;o:p&gt;&lt;/o:p&gt;&lt;/span&gt;&lt;/p&gt;&lt;p class=&quot;MsoNormal&quot; style=&quot;line-height: normal; &quot;&gt;&lt;span style=&quot;font-family: Verdana, sans-serif; font-size: 10pt; &quot;&gt;Target Language(s): {TGTLang}&lt;o:p&gt;&lt;/o:p&gt;&lt;/span&gt;&lt;/p&gt;&lt;p class=&quot;MsoNormal&quot; style=&quot;line-height: normal; &quot;&gt;&lt;span style=&quot;font-family: Verdana, sans-serif; font-size: 10pt; &quot;&gt;You can now login and provide localized strings on the Bento portal on&amp;nbsp;&lt;span style=&quot;font-family: Verdana, sans-serif; font-size: 10pt; &quot;&gt;{LinkToHOEDIT}&lt;/span&gt;.&lt;/span&gt;&lt;span style=&quot;font-family: ''Times New Roman'', serif; font-size: 12pt; &quot;&gt;&lt;o:p&gt;&lt;/o:p&gt;&lt;/span&gt;&lt;/p&gt;&lt;p class=&quot;MsoNormal&quot; style=&quot;line-height: normal; &quot;&gt;&lt;span style=&quot;font-family: Verdana, sans-serif; font-size: 10pt; &quot;&gt;Thank you&lt;/span&gt;&lt;span style=&quot;font-family: ''Times New Roman'', serif; font-size: 12pt; &quot;&gt;&lt;o:p&gt;&lt;/o:p&gt;&lt;/span&gt;&lt;/p&gt;&lt;p&gt;&lt;span style=&quot;font-family: Verdana, sans-serif; font-size: 10pt; &quot;&gt;--------------------------------- Clientâ€™s Instructions ---------------------------------&lt;o:p&gt;&lt;/o:p&gt;&lt;/span&gt;&lt;/p&gt;&lt;p class=&quot;MsoNormal&quot;&gt;&lt;span style=&quot;line-height: 14px; font-family: Verdana, sans-serif; font-size: 10pt; &quot;&gt;{HandoffInstructions}&lt;/span&gt;&lt;o:p&gt;&lt;/o:p&gt;&lt;/p&gt;&lt;/font&gt;&lt;p&gt;&lt;/p&gt;&lt;font class=&quot;Apple-style-span&quot; size=&quot;2&quot; face=&quot;verdana&quot;&gt;&lt;p&gt;&lt;strong&gt;&lt;span style=&quot;font-family: Verdana, sans-serif; color: rgb(11, 119, 145); font-size: 10pt; &quot;&gt;Bento&lt;/span&gt;&lt;/strong&gt;&lt;strong&gt;&lt;span style=&quot;font-family: Verdana, sans-serif; color: gray; font-size: 10pt; &quot;&gt;|&lt;/span&gt;&lt;/strong&gt;&lt;span style=&quot;font-family: Verdana, sans-serif; color: gray; font-size: 10pt; &quot;&gt;&amp;nbsp;Automatic email&lt;/span&gt;&lt;span style=&quot;font-family: Verdana, sans-serif; color: rgb(127, 127, 127); font-size: 10pt; &quot;&gt;, Customer Solutions Department&amp;nbsp;&lt;/span&gt;&lt;span style=&quot;font-family: Verdana, sans-serif; color: gray; font-size: 10pt; &quot;&gt;| Jonckers Translation &amp;amp; Engineering |&lt;/span&gt;&lt;a href=&quot;http://www.jonckers.com/&quot;&gt;&lt;span style=&quot;font-family: Verdana, sans-serif; color: purple; font-size: 10pt; &quot;&gt;www.jonckers.com&lt;/span&gt;&lt;/a&gt;&lt;o:p&gt;&lt;/o:p&gt;&lt;/p&gt;&lt;p&gt;&lt;b&gt;&lt;i&gt;&lt;span style=&quot;font-family: Arial, sans-serif; color: rgb(74, 68, 42); font-size: 8pt; &quot;&gt;The information contained in this entire e-mail is confidential and/or privileged. This e-mail is intended to be read or used solely by the addressee. If the reader of this e-mail is not the intended recipient, you are hereby notified that any use, dissemination, distribution, publication or copying of this e-mail is prohibited. Please do not reply to this unmonitored email address. If you receive this e-mail in error, please destroy it and notify&amp;nbsp;&lt;/span&gt;&lt;/i&gt;&lt;/b&gt;&lt;a href=&quot;mailto:admin-fms@jonckers.be&quot;&gt;&lt;b&gt;&lt;i&gt;&lt;span style=&quot;font-family: Arial, sans-serif; font-size: 8pt; &quot;&gt;admin-fms@jonckers.be&lt;/span&gt;&lt;/i&gt;&lt;/b&gt;&lt;/a&gt;&lt;b&gt;&lt;i&gt;&lt;span style=&quot;font-family: Arial, sans-serif; color: rgb(74, 68, 42); font-size: 8pt; &quot;&gt;.&lt;/span&gt;&lt;/i&gt;&lt;/b&gt;&lt;/p&gt;&lt;/font&gt;&lt;/font&gt;&lt;p&gt;&lt;/p&gt;&lt;p&gt;&lt;/p&gt;&lt;p&gt;&lt;/p&gt;', 'A string-based Handoff is ready for translation', 'HO - Received', 'Y');


-- --------------------------------------------------------

--
-- Table structure for table `handoffs`
--

CREATE TABLE IF NOT EXISTS `handoffs` (
  `HandOffID` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) NOT NULL,
  `HandOffStringLocalization` text COLLATE utf8_unicode_ci NOT NULL,
  `HandOffStartProject` date NOT NULL,
  `HandOffClosedDate` date DEFAULT NULL,
  `HandOffStatus` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `HandOffSourceLanguageID` int(11) NOT NULL,
  `HandOffInstruction` text COLLATE utf8_unicode_ci NOT NULL,
  `SignatureName` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`HandOffID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `handoffs`
--


-- --------------------------------------------------------

--
-- Table structure for table `handofftargetlanguages`
--

CREATE TABLE IF NOT EXISTS `handofftargetlanguages` (
  `HandOffID` int(11) NOT NULL,
  `LanguageID` int(11) NOT NULL,
  `HandBackStringLocalization` text COLLATE utf8_unicode_ci,
  `TranslatedByTranslator` char(1) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`HandOffID`,`LanguageID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `handofftargetlanguages`
--
-- --------------------------------------------------------

--
-- Table structure for table `handofftargetlanguageshistory`
--

CREATE TABLE IF NOT EXISTS `handofftargetlanguageshistory` (
  `HandOffTargetLanguageTrackingID` int(11) NOT NULL AUTO_INCREMENT,
  `HandOffID` int(11) NOT NULL,
  `LanguageID` int(11) NOT NULL,
  `HandBackStringLocalization` text COLLATE utf8_unicode_ci NOT NULL,
  `UserID` int(11) NOT NULL,
  `DateCreated` datetime NOT NULL,
  PRIMARY KEY (`HandOffTargetLanguageTrackingID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `handofftargetlanguageshistory`
--

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE IF NOT EXISTS `languages` (
  `LanguageID` int(11) NOT NULL AUTO_INCREMENT,
  `LanguageName` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `LanguageIsActive` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `LanguageIsShowInSourceList` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `LanguageIsShowInTargetList` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `LanguageIsShowInTranslatorList` char(1) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`LanguageID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`LanguageID`, `LanguageName`, `LanguageIsActive`, `LanguageIsShowInSourceList`, `LanguageIsShowInTargetList`, `LanguageIsShowInTranslatorList`) VALUES
(1, 'French (France)', 'Y', 'N', 'Y', 'Y'),
(2, 'Japanese', 'Y', 'N', 'N', 'N'),
(3, 'German', 'Y', 'Y', 'Y', 'Y'),
(4, 'Italian', 'Y', 'N', 'Y', 'Y'),
(5, 'Swedish', 'Y', 'N', 'Y', 'Y'),
(6, 'Finnish', 'Y', 'N', 'Y', 'Y'),
(7, 'Danish', 'Y', 'N', 'Y', 'Y'),
(8, 'Norwegian (Bokmal)', 'Y', 'N', 'Y', 'Y'),
(9, 'Spanish (Spain)', 'Y', 'N', 'Y', 'Y'),
(10, 'Dutch (Netherlands)', 'Y', 'N', 'Y', 'Y'),
(11, 'Korean', 'Y', 'N', 'N', 'N'),
(12, 'Russian', 'Y', 'N', 'Y', 'Y'),
(13, 'Greek', 'Y', 'N', 'Y', 'Y'),
(14, 'Chinese (Traditional)', 'Y', 'N', 'N', 'N'),
(15, 'Chinese (Simplified)', 'Y', 'N', 'N', 'N'),
(16, 'Estonian', 'Y', 'N', 'N', 'N'),
(18, 'Latvian', 'Y', 'N', 'N', 'N'),
(19, 'Lithuanian', 'Y', 'N', 'N', 'N'),
(20, 'Portuguese (Brazil)', 'Y', 'N', 'N', 'N'),
(21, 'Polish', 'Y', 'N', 'Y', 'Y'),
(22, 'Hungarian', 'Y', 'N', 'Y', 'Y'),
(23, 'Spanish (US)', 'Y', 'N', 'N', 'N'),
(24, 'Hebrew', 'Y', 'N', 'N', 'N'),
(25, 'Arabic', 'Y', 'N', 'N', 'N'),
(26, 'Portuguese (Portugal)', 'Y', 'N', 'Y', 'Y'),
(27, 'Turkish', 'Y', 'N', 'Y', 'Y'),
(28, 'French (Canada)', 'Y', 'N', 'N', 'N'),
(29, 'Czech', 'Y', 'N', 'Y', 'Y'),
(30, 'Croatian', 'Y', 'N', 'N', 'N'),
(31, 'Slovenian', 'Y', 'N', 'N', 'N'),
(32, 'Slovak', 'Y', 'N', 'N', 'N'),
(33, 'Hindi', 'Y', 'N', 'N', 'N'),
(34, 'Romanian', 'Y', 'N', 'N', 'N'),
(35, 'Serbian (Cyrillic)', 'Y', 'N', 'N', 'N'),
(36, 'Dutch (Belgium)', 'Y', 'N', 'N', 'N'),
(37, 'Bulgarian', 'Y', 'N', 'N', 'N'),
(38, 'Ukrainian', 'Y', 'N', 'N', 'N'),
(39, 'Thai', 'Y', 'N', 'N', 'N'),
(40, 'Kazakh', 'Y', 'N', 'N', 'N'),
(41, 'Spanish (International)', 'Y', 'N', 'N', 'N'),
(42, 'English (United Kingdom)', 'Y', 'Y', 'Y', 'Y'),
(43, 'English (United States)', 'Y', 'N', 'N', 'N'),
(44, 'Spanish (Mexico)', 'Y', 'N', 'N', 'N'),
(45, 'Catalan', 'Y', 'N', 'N', 'N'),
(46, 'Spanish (Peru)', 'Y', 'N', 'N', 'N'),
(47, 'Indonesian', 'Y', 'N', 'N', 'N'),
(48, 'Icelandic', 'Y', 'N', 'N', 'N'),
(49, 'Bengali', 'Y', 'N', 'N', 'N'),
(50, 'Vietnamese', 'Y', 'N', 'N', 'N'),
(51, 'Serbian (Latin)', 'Y', 'N', 'N', 'N'),
(52, 'Norwegian (Nynorsk)', 'Y', 'N', 'N', 'N'),
(53, 'Spanish (Argentina)', 'Y', 'N', 'N', 'N');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE IF NOT EXISTS `message` (
  `message_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue_id` int(10) unsigned NOT NULL,
  `handle` char(32) DEFAULT NULL,
  `body` varchar(8192) NOT NULL,
  `md5` char(32) NOT NULL,
  `timeout` decimal(14,4) unsigned DEFAULT NULL,
  `created` int(10) unsigned NOT NULL,
  PRIMARY KEY (`message_id`),
  UNIQUE KEY `message_handle` (`handle`),
  KEY `message_queueid` (`queue_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `message`
--


-- --------------------------------------------------------

--
-- Table structure for table `paymentfortranslators`
--

CREATE TABLE IF NOT EXISTS `translationfortranslators` (
  `TranslationForTranslatorsID` int(11) NOT NULL AUTO_INCREMENT,
  `HandBackStringLocalization` text COLLATE utf8_unicode_ci NOT NULL,
  `HandOffID` int(4) NOT NULL,
  `UserID` int(11) NOT NULL,
  PRIMARY KEY (`TranslationForTranslatorsID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `paymentfortranslators`
--


-- --------------------------------------------------------

--
-- Table structure for table `queue`
--

CREATE TABLE IF NOT EXISTS `queue` (
  `queue_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `queue_name` varchar(100) NOT NULL,
  `timeout` smallint(5) unsigned NOT NULL DEFAULT '30',
  PRIMARY KEY (`queue_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `queue`
--

INSERT INTO `queue` (`queue_id`, `queue_name`, `timeout`) VALUES
(1, 'SendingEmail', 30);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE IF NOT EXISTS `sessions` (
  `id` char(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `modified` int(11) DEFAULT NULL,
  `lifetime` int(11) DEFAULT NULL,
  `data` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sessions`
--



-- --------------------------------------------------------

--
-- Table structure for table `userlanguages`
--

CREATE TABLE IF NOT EXISTS `userlanguages` (
  `UserID` int(11) NOT NULL,
  `SourceLanguageID` int(11) NOT NULL,
  `TargetLanguageID` int(11) NOT NULL,
  `UserLanguageID` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`UserLanguageID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `userlanguages`
--
-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `UserID` int(11) NOT NULL AUTO_INCREMENT,
  `UserName` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `UserEmail` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `JtepmEmail` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `UserLoginName` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `UserPassword` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `UserRole` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `UserIsActive` char(1) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`UserID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `users`
--


--
-- Constraints for dumped tables
--

--
-- Constraints for table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `message_ibfk_1` FOREIGN KEY (`queue_id`) REFERENCES `queue` (`queue_id`) ON DELETE CASCADE ON UPDATE CASCADE;
