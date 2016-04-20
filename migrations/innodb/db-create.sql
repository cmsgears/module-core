/* ============================= CMSGears Core ============================================== */

--
-- Table structure for table `cmg_core_locale`
--

DROP TABLE IF EXISTS `cmg_core_locale`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmg_core_locale` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cmg_core_theme`
--

DROP TABLE IF EXISTS `cmg_core_theme`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmg_core_theme` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `createdBy` bigint(20) NOT NULL,
  `modifiedBy` bigint(20) DEFAULT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `slug` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `renderer` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `basePath` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `createdAt` datetime NOT NULL,
  `modifiedAt` datetime DEFAULT NULL,
  `data` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cmg_theme_1` (`createdBy`),
  KEY `fk_cmg_theme_2` (`modifiedBy`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cmg_core_template`
--

DROP TABLE IF EXISTS `cmg_core_template`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmg_core_template` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `createdBy` bigint(20) NOT NULL,
  `modifiedBy` bigint(20) DEFAULT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `icon` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `renderer` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fileRender` tinyint(1) NOT NULL DEFAULT 0,
  `layout` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `layoutGroup` tinyint(1) NOT NULL DEFAULT 0,
  `viewPath` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `createdAt` datetime NOT NULL,
  `modifiedAt` datetime DEFAULT NULL,
  `content` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cmg_template_1` (`createdBy`),
  KEY `fk_cmg_template_2` (`modifiedBy`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cmg_core_object`
--

DROP TABLE IF EXISTS `cmg_core_object`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmg_core_object` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `siteId` bigint(20) NOT NULL,
  `templateId` bigint(20) DEFAULT NULL,
  `avatarId` bigint(20) DEFAULT NULL,
  `bannerId` bigint(20) DEFAULT NULL,
  `createdBy` bigint(20) NOT NULL,
  `modifiedBy` bigint(20) DEFAULT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `icon` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `createdAt` datetime NOT NULL,
  `modifiedAt` datetime DEFAULT NULL,
  `htmlOptions` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `content` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cmg_object_1` (`siteId`),
  KEY `fk_cmg_object_2` (`templateId`),
  KEY `fk_cmg_object_3` (`avatarId`),
  KEY `fk_cmg_object_4` (`bannerId`),
  KEY `fk_cmg_object_5` (`createdBy`),
  KEY `fk_cmg_object_6` (`modifiedBy`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cmg_core_country`
--

DROP TABLE IF EXISTS `cmg_core_country`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmg_core_country` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) DEFAULT NULL,
  `name` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cmg_core_province`
--

DROP TABLE IF EXISTS `cmg_core_province`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmg_core_province` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `countryId` bigint(20) NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `name` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cmg_province_1` (`countryId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cmg_core_city`
--

DROP TABLE IF EXISTS `cmg_core_city`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmg_core_city` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `countryId` bigint(20) NOT NULL,
  `provinceId` bigint(20) NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cmg_city_1` (`countryId`),
  KEY `fk_cmg_city_2` (`provinceId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cmg_core_role`
--

DROP TABLE IF EXISTS `cmg_core_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmg_core_role` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `createdBy` bigint(20) NOT NULL,
  `modifiedBy` bigint(20) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `slug` varchar(150) DEFAULT NULL,
  `type` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `icon` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `homeUrl` varchar(255) DEFAULT NULL,
  `createdAt` datetime NOT NULL,
  `modifiedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cmg_role_1` (`createdBy`),
  KEY `fk_cmg_role_2` (`modifiedBy`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cmg_core_permission`
--

DROP TABLE IF EXISTS `cmg_core_permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmg_core_permission` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `createdBy` bigint(20) NOT NULL,
  `modifiedBy` bigint(20) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `slug` varchar(150) DEFAULT NULL,
  `type` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `icon` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `createdAt` datetime NOT NULL,
  `modifiedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cmg_permission_1` (`createdBy`),
  KEY `fk_cmg_permission_2` (`modifiedBy`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cmg_core_role_permission`
--

DROP TABLE IF EXISTS `cmg_core_role_permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmg_core_role_permission` (
  `roleId` bigint(20) NOT NULL,
  `permissionId` bigint(20) NOT NULL,
  PRIMARY KEY (`roleId`,`permissionId`),
  KEY `fk_cmg_role_permission_1` (`roleId`),
  KEY `fk_cmg_role_permission_2` (`permissionId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cmg_core_user`
--

DROP TABLE IF EXISTS `cmg_core_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmg_core_user` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `localeId` bigint(20) DEFAULT NULL,
  `genderId` bigint(20) DEFAULT NULL,
  `avatarId` bigint(20) DEFAULT NULL,
  `status` smallint(6) DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `passwordHash` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,  
  `firstName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `avatarUrl` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `websiteUrl` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `verifyToken` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `resetToken` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `registeredAt` datetime DEFAULT NULL,  
  `lastLoginAt` datetime DEFAULT NULL,
  `lastActivityAt` datetime DEFAULT NULL,
  `authKey` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `accessToken` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `accessTokenCreatedAt` datetime DEFAULT NULL,
  `accessTokenAccessedAt` datetime DEFAULT NULL,
  `data` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `fk_cmg_user_1` (`localeId`),
  KEY `fk_cmg_user_2` (`genderId`),
  KEY `fk_cmg_user_3` (`avatarId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cmg_core_site`
--

DROP TABLE IF EXISTS `cmg_core_site`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmg_core_site` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `avatarId` bigint(20) DEFAULT NULL,
  `bannerId` bigint(20) DEFAULT NULL,
  `themeId` bigint(20) DEFAULT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `order` smallint(6) default 0,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `fk_cmg_site_1` (`avatarId`),
  KEY `fk_cmg_site_2` (`bannerId`),
  KEY `fk_cmg_site_3` (`themeId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cmg_core_site_member`
--

DROP TABLE IF EXISTS `cmg_core_site_member`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmg_core_site_member` (
  `siteId` bigint(20) NOT NULL, 
  `userId` bigint(20) NOT NULL,
  `roleId` bigint(20) NOT NULL,
  `createdAt` datetime NOT NULL,
  `modifiedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`siteId`, `userId`),
  KEY `fk_cmg_site_member_1` (`siteId`),
  KEY `fk_cmg_site_member_2` (`userId`),
  KEY `fk_cmg_site_member_3` (`roleId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cmg_core_file`
--

DROP TABLE IF EXISTS `cmg_core_file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmg_core_file` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `createdBy` bigint(20) NOT NULL,
  `modifiedBy` bigint(20) DEFAULT NULL,
  `title` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `extension` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `directory` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `size` float(8,2) NOT NULL DEFAULT 0.0,
  `visibility` smallint(6) NOT NULL DEFAULT 0,
  `type` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `medium` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `thumb` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `altText` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `link` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `shared` tinyint(1) NOT NULL DEFAULT 0,
  `createdAt` datetime NOT NULL,
  `modifiedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cmg_file_1` (`createdBy`),
  KEY `fk_cmg_file_2` (`modifiedBy`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cmg_core_tag`
--

DROP TABLE IF EXISTS `cmg_core_tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmg_core_tag` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `siteId` bigint(20) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `icon` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cmg_tag_1` (`siteId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cmg_core_category`
--

DROP TABLE IF EXISTS `cmg_core_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmg_core_category` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `siteId` bigint(20) NOT NULL,
  `parentId` bigint(20) DEFAULT NULL,
  `rootId` bigint(20) DEFAULT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `icon` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,   
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `featured` tinyint(1) NOT NULL DEFAULT 0,
  `lValue` smallint(6) NOT NULL DEFAULT '1',
  `rValue` smallint(6) NOT NULL DEFAULT '2',
  `htmlOptions` mediumtext COLLATE utf8_unicode_ci,
  `data` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `fk_cmg_category_1` (`siteId`),
  KEY `fk_cmg_category_2` (`parentId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cmg_core_option`
--

DROP TABLE IF EXISTS `cmg_core_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmg_core_option` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `categoryId` bigint(20) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `icon` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `htmlOptions` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cmg_option_1` (`categoryId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cmg_core_address`
--

DROP TABLE IF EXISTS `cmg_core_address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmg_core_address` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `countryId` bigint(20) NOT NULL,
  `provinceId` bigint(20) NOT NULL,
  `cityId` bigint(20) DEFAULT NULL,
  `line1` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `line2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `line3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `zip` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `firstName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fax` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `latitude` decimal(8,4) DEFAULT 0,
  `longitude` decimal(8,4) DEFAULT 0,
  `zoomLevel` smallint(6) DEFAULT 5,
  PRIMARY KEY (`id`),
  KEY `fk_cmg_address_1` (`countryId`),
  KEY `fk_cmg_address_2` (`provinceId`),
  KEY `fk_cmg_address_3` (`cityId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cmg_core_gallery`
--

DROP TABLE IF EXISTS `cmg_core_gallery`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmg_core_gallery` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `siteId` bigint(20) DEFAULT NULL,
  `templateId` bigint(20) DEFAULT NULL,
  `createdBy` bigint(20) NOT NULL,
  `modifiedBy` bigint(20) DEFAULT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `slug` varchar(150) DEFAULT NULL,
  `type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `createdAt` datetime NOT NULL,
  `modifiedAt` datetime NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cmg_gallery_1` (`siteId`),
  KEY `fk_cmg_gallery_2` (`templateId`),
  KEY `fk_cmg_gallery_3` (`createdBy`),
  KEY `fk_cmg_gallery_4` (`modifiedBy`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cmg_core_form`
--

DROP TABLE IF EXISTS `cmg_core_form`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmg_core_form` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `siteId` bigint(20) DEFAULT NULL,
  `templateId` bigint(20) DEFAULT NULL,
  `createdBy` bigint(20) NOT NULL,
  `modifiedBy` bigint(20) DEFAULT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `successMessage` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `captcha` tinyint(1) DEFAULT 0,
  `visibility` smallint(6) DEFAULT 0,
  `active` tinyint(1) DEFAULT 0,
  `userMail` tinyint(1) DEFAULT 0,
  `adminMail` tinyint(1) DEFAULT 0,
  `createdAt` datetime NOT NULL,
  `modifiedAt` datetime DEFAULT NULL,
  `htmlOptions` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `content` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cmg_form_1` (`siteId`),
  KEY `fk_cmg_form_2` (`templateId`),
  KEY `fk_cmg_form_3` (`createdBy`),
  KEY `fk_cmg_form_4` (`modifiedBy`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cmg_core_form_field`
--

DROP TABLE IF EXISTS `cmg_core_form_field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmg_core_form_field` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `formId` bigint(20) DEFAULT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `label` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` smallint(6) DEFAULT 0,
  `compress` tinyint(1) DEFAULT 0,
  `validators` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `order` smallint(6) DEFAULT 0,
  `icon` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `htmlOptions` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `content` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cmg_form_field_1` (`formId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

-- ======================== Model Traits =================================

--
-- Table structure for table `cmg_core_model_message`
--

DROP TABLE IF EXISTS `cmg_core_model_message`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmg_core_model_message` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `localeId` bigint(20) NOT NULL,
  `parentId` bigint(20) NOT NULL,
  `parentType` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `value` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cmg_model_message_1` (`localeId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cmg_core_model_comment`
--

DROP TABLE IF EXISTS `cmg_core_model_comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmg_core_model_comment` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `baseId` bigint(20) DEFAULT NULL,
  `parentId` bigint(20) NOT NULL,
  `createdBy` bigint(20) DEFAULT NULL,
  `modifiedBy` bigint(20) DEFAULT NULL,
  `parentType` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `avatarUrl` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `websiteUrl` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ip` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `agent` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` smallint(6) NOT NULL DEFAULT 0,
  `type` smallint(6) NOT NULL DEFAULT 0,
  `rating` smallint(6) NOT NULL DEFAULT 0,
  `featured` tinyint(1) NOT NULL DEFAULT 0,
  `createdAt` datetime NOT NULL,
  `modifiedAt` datetime DEFAULT NULL,
  `approvedAt` datetime DEFAULT NULL,
  `content` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cmg_model_comment_1` (`createdBy`),
  KEY `fk_cmg_model_comment_2` (`modifiedBy`),
  KEY `fk_cmg_model_comment_3` (`baseId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cmg_core_model_attribute`
--

DROP TABLE IF EXISTS `cmg_core_model_attribute`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmg_core_model_attribute` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `parentId` bigint(20) NOT NULL,
  `parentType` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `label` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'default',
  `valueType` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'text',
  `value` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cmg_core_model_activity`
--

DROP TABLE IF EXISTS `cmg_core_model_activity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmg_core_model_activity` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `userId` bigint(20) NOT NULL,
  `parentId` bigint(20) NOT NULL,
  `parentType` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'default',
  `ip` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `agent` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `createdAt` datetime NOT NULL,
  `modifiedAt` datetime DEFAULT NULL,
  `content` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cmg_model_activity_1` (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cmg_core_model_file`
--

DROP TABLE IF EXISTS `cmg_core_model_file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmg_core_model_file` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `fileId` bigint(20) NOT NULL,
  `parentId` bigint(20) NOT NULL,
  `parentType` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `order` smallint(6) NOT NULL DEFAULT 0,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `fk_cmg_model_file_1` (`fileId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cmg_core_model_tag`
--

DROP TABLE IF EXISTS `cmg_core_model_tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmg_core_model_tag` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `tagId` bigint(20) NOT NULL,
  `parentId` bigint(20) NOT NULL,
  `parentType` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `order` smallint(6) NOT NULL DEFAULT 0,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `fk_cmg_model_tag_1` (`tagId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cmg_core_model_category`
--

DROP TABLE IF EXISTS `cmg_core_model_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmg_core_model_category` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `categoryId` bigint(20) NOT NULL,
  `parentId` bigint(20) NOT NULL,
  `parentType` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `order` smallint(6) NOT NULL DEFAULT 0,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `fk_cmg_model_category_1` (`categoryId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cmg_core_model_option`
--

DROP TABLE IF EXISTS `cmg_core_model_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmg_core_model_option` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `optionId` bigint(20) NOT NULL,
  `parentId` bigint(20) NOT NULL,
  `parentType` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `order` smallint(6) NOT NULL DEFAULT 0,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `fk_cmg_model_option_1` (`optionId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cmg_core_model_address`
--

DROP TABLE IF EXISTS `cmg_core_model_address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmg_core_model_address` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `addressId` bigint(20) NOT NULL,
  `parentId` bigint(20) NOT NULL,
  `parentType` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` smallint(6) NOT NULL DEFAULT 0,
  `order` smallint(6) NOT NULL DEFAULT 0,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `fk_cmg_model_address_1` (`addressId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cmg_core_model_form`
--

DROP TABLE IF EXISTS `cmg_core_model_form`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmg_core_model_form` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `formId` bigint(20) NOT NULL,
  `parentId` bigint(20) NOT NULL,
  `parentType` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `order` smallint(6) NOT NULL DEFAULT 0,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `fk_cmg_model_form_1` (`formId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cmg_core_model_gallery`
--

DROP TABLE IF EXISTS `cmg_core_model_gallery`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmg_core_model_gallery` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `galleryId` bigint(20) NOT NULL,
  `parentId` bigint(20) NOT NULL,
  `parentType` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `order` smallint(6) NOT NULL DEFAULT 0,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `fk_cmg_model_gallery_1` (`galleryId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

SET FOREIGN_KEY_CHECKS=0;

--
-- Constraints for table `cmg_core_theme`
--
ALTER TABLE `cmg_core_theme`
	ADD CONSTRAINT `fk_cmg_theme_1` FOREIGN KEY (`createdBy`) REFERENCES `cmg_core_user` (`id`),
  	ADD CONSTRAINT `fk_cmg_theme_2` FOREIGN KEY (`modifiedBy`) REFERENCES `cmg_core_user` (`id`);

--
-- Constraints for table `cmg_core_template`
--
ALTER TABLE `cmg_core_template`
	ADD CONSTRAINT `fk_cmg_template_1` FOREIGN KEY (`createdBy`) REFERENCES `cmg_core_user` (`id`),
  	ADD CONSTRAINT `fk_cmg_template_2` FOREIGN KEY (`modifiedBy`) REFERENCES `cmg_core_user` (`id`);

--
-- Constraints for table `cmg_core_object`
--
ALTER TABLE `cmg_core_object`
	ADD CONSTRAINT `fk_cmg_object_1` FOREIGN KEY (`siteId`) REFERENCES `cmg_core_site` (`id`),
	ADD CONSTRAINT `fk_cmg_object_2` FOREIGN KEY (`templateId`) REFERENCES `cmg_core_template` (`id`),
	ADD CONSTRAINT `fk_cmg_object_3` FOREIGN KEY (`avatarId`) REFERENCES `cmg_core_file` (`id`),
	ADD CONSTRAINT `fk_cmg_object_4` FOREIGN KEY (`bannerId`) REFERENCES `cmg_core_file` (`id`),
	ADD CONSTRAINT `fk_cmg_object_5` FOREIGN KEY (`createdBy`) REFERENCES `cmg_core_user` (`id`),
  	ADD CONSTRAINT `fk_cmg_object_6` FOREIGN KEY (`modifiedBy`) REFERENCES `cmg_core_user` (`id`);

--
-- Constraints for table `cmg_core_province`
--
ALTER TABLE `cmg_core_province`
	ADD CONSTRAINT `fk_cmg_province_1` FOREIGN KEY (`countryId`) REFERENCES `cmg_core_country` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cmg_core_city`
--
ALTER TABLE `cmg_core_city`
	ADD CONSTRAINT `fk_cmg_city_1` FOREIGN KEY (`countryId`) REFERENCES `cmg_core_country` (`id`) ON DELETE CASCADE,
	ADD CONSTRAINT `fk_cmg_city_2` FOREIGN KEY (`provinceId`) REFERENCES `cmg_core_province` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cmg_core_role`
--
ALTER TABLE `cmg_core_role`
	ADD CONSTRAINT `fk_cmg_role_1` FOREIGN KEY (`createdBy`) REFERENCES `cmg_core_user` (`id`),
  	ADD CONSTRAINT `fk_cmg_role_2` FOREIGN KEY (`modifiedBy`) REFERENCES `cmg_core_user` (`id`);

--
-- Constraints for table `cmg_core_permission`
--
ALTER TABLE `cmg_core_permission`
  	ADD CONSTRAINT `fk_cmg_permission_1` FOREIGN KEY (`createdBy`) REFERENCES `cmg_core_user` (`id`),
  	ADD CONSTRAINT `fk_cmg_permission_2` FOREIGN KEY (`modifiedBy`) REFERENCES `cmg_core_user` (`id`);

--
-- Constraints for table `cmg_core_role_permission`
--

ALTER TABLE `cmg_core_role_permission`
  	ADD CONSTRAINT `fk_cmg_role_permission_1` FOREIGN KEY (`roleId`) REFERENCES `cmg_core_role` (`id`) ON DELETE CASCADE,
  	ADD CONSTRAINT `fk_cmg_role_permission_2` FOREIGN KEY (`permissionId`) REFERENCES `cmg_core_permission` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cmg_core_user`
--

ALTER TABLE `cmg_core_user`
  	ADD CONSTRAINT `fk_cmg_user_1` FOREIGN KEY (`localeId`) REFERENCES `cmg_core_locale` (`id`),
  	ADD CONSTRAINT `fk_cmg_user_2` FOREIGN KEY (`genderId`) REFERENCES `cmg_core_option` (`id`),
  	ADD CONSTRAINT `fk_cmg_user_3` FOREIGN KEY (`avatarId`) REFERENCES `cmg_core_file` (`id`);

--
-- Constraints for table `cmg_core_site`
--

ALTER TABLE `cmg_core_site`
  	ADD CONSTRAINT `fk_cmg_site_1` FOREIGN KEY (`avatarId`) REFERENCES `cmg_core_file` (`id`),
  	ADD CONSTRAINT `fk_cmg_site_2` FOREIGN KEY (`bannerId`) REFERENCES `cmg_core_file` (`id`),
  	ADD CONSTRAINT `fk_cmg_site_3` FOREIGN KEY (`themeId`) REFERENCES `cmg_core_theme` (`id`);

--
-- Constraints for table `cmg_core_site_member`
--

ALTER TABLE `cmg_core_site_member`
  	ADD CONSTRAINT `fk_cmg_site_member_1` FOREIGN KEY (`siteId`) REFERENCES `cmg_core_site` (`id`) ON DELETE CASCADE,
  	ADD CONSTRAINT `fk_cmg_site_member_2` FOREIGN KEY (`userId`) REFERENCES `cmg_core_user` (`id`) ON DELETE CASCADE,
  	ADD CONSTRAINT `fk_cmg_site_member_3` FOREIGN KEY (`roleId`) REFERENCES `cmg_core_role` (`id`);

--
-- Constraints for table `cmg_core_file`
--

ALTER TABLE `cmg_core_file`
  	ADD CONSTRAINT `fk_cmg_file_1` FOREIGN KEY (`createdBy`) REFERENCES `cmg_core_user` (`id`),
  	ADD CONSTRAINT `fk_cmg_file_2` FOREIGN KEY (`modifiedBy`) REFERENCES `cmg_core_user` (`id`);

--
-- Constraints for table `cmg_core_tag`
--
ALTER TABLE `cmg_core_tag`
	ADD CONSTRAINT `fk_cmg_tag_1` FOREIGN KEY (`siteId`) REFERENCES `cmg_core_site` (`id`);

--
-- Constraints for table `cmg_core_category`
--
ALTER TABLE `cmg_core_category`
	ADD CONSTRAINT `fk_cmg_category_1` FOREIGN KEY (`siteId`) REFERENCES `cmg_core_site` (`id`),
	ADD CONSTRAINT `fk_cmg_category_2` FOREIGN KEY (`parentId`) REFERENCES `cmg_core_category` (`id`);

--
-- Constraints for table `cmg_core_option`
--
ALTER TABLE `cmg_core_option`
	ADD CONSTRAINT `fk_cmg_option_1` FOREIGN KEY (`categoryId`) REFERENCES `cmg_core_category` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cmg_core_address`
--

ALTER TABLE `cmg_core_address`
  	ADD CONSTRAINT `fk_cmg_address_1` FOREIGN KEY (`countryId`) REFERENCES `cmg_core_country` (`id`),
  	ADD CONSTRAINT `fk_cmg_address_2` FOREIGN KEY (`provinceId`) REFERENCES `cmg_core_province` (`id`),
  	ADD CONSTRAINT `fk_cmg_address_3` FOREIGN KEY (`cityId`) REFERENCES `cmg_core_city` (`id`);

--
-- Constraints for table `cmg_core_gallery`
--

ALTER TABLE `cmg_core_gallery`
	ADD CONSTRAINT `fk_cmg_gallery_1` FOREIGN KEY (`siteId`) REFERENCES `cmg_core_site` (`id`),
	ADD CONSTRAINT `fk_cmg_gallery_2` FOREIGN KEY (`templateId`) REFERENCES `cmg_core_template` (`id`),
  	ADD CONSTRAINT `fk_cmg_gallery_3` FOREIGN KEY (`createdBy`) REFERENCES `cmg_core_user` (`id`),
  	ADD CONSTRAINT `fk_cmg_gallery_4` FOREIGN KEY (`modifiedBy`) REFERENCES `cmg_core_user` (`id`);

--
-- Constraints for table `cmg_core_form`
--
ALTER TABLE `cmg_core_form`
	ADD CONSTRAINT `fk_cmg_form_1` FOREIGN KEY (`siteId`) REFERENCES `cmg_core_site` (`id`),
	ADD CONSTRAINT `fk_cmg_form_2` FOREIGN KEY (`templateId`) REFERENCES `cmg_core_template` (`id`),
	ADD CONSTRAINT `fk_cmg_form_3` FOREIGN KEY (`createdBy`) REFERENCES `cmg_core_user` (`id`),
  	ADD CONSTRAINT `fk_cmg_form_4` FOREIGN KEY (`modifiedBy`) REFERENCES `cmg_core_user` (`id`);

--
-- Constraints for table `cmg_core_form_field`
--
ALTER TABLE `cmg_core_form_field`
	ADD CONSTRAINT `fk_cmg_form_field_1` FOREIGN KEY (`formId`) REFERENCES `cmg_core_form` (`id`);

--
-- Constraints for table `cmg_core_model_message`
--

ALTER TABLE `cmg_core_model_message`
  	ADD CONSTRAINT `fk_cmg_model_message_1` FOREIGN KEY (`localeId`) REFERENCES `cmg_core_locale` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cmg_core_model_comment`
--
ALTER TABLE `cmg_core_model_comment`
  	ADD CONSTRAINT `fk_cmg_model_comment_1` FOREIGN KEY (`createdBy`) REFERENCES `cmg_core_user` (`id`),
  	ADD CONSTRAINT `fk_cmg_model_comment_2` FOREIGN KEY (`modifiedBy`) REFERENCES `cmg_core_user` (`id`),
  	ADD CONSTRAINT `fk_cmg_model_comment_3` FOREIGN KEY (`baseId`) REFERENCES `cmg_core_model_comment` (`id`);

--
-- Constraints for table `cmg_core_model_activity`
--

ALTER TABLE `cmg_core_model_activity`
  	ADD CONSTRAINT `fk_cmg_model_activity_1` FOREIGN KEY (`userId`) REFERENCES `cmg_core_user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cmg_core_model_file`
--

ALTER TABLE `cmg_core_model_file`
  	ADD CONSTRAINT `fk_cmg_model_file_1` FOREIGN KEY (`fileId`) REFERENCES `cmg_core_file` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cmg_core_model_tag`
--

ALTER TABLE `cmg_core_model_tag`
  	ADD CONSTRAINT `fk_cmg_model_tag_1` FOREIGN KEY (`tagId`) REFERENCES `cmg_core_tag` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cmg_core_model_category`
--

ALTER TABLE `cmg_core_model_category`
  	ADD CONSTRAINT `fk_cmg_model_category_1` FOREIGN KEY (`categoryId`) REFERENCES `cmg_core_category` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cmg_core_model_option`
--

ALTER TABLE `cmg_core_model_option`
  	ADD CONSTRAINT `fk_cmg_model_option_1` FOREIGN KEY (`optionId`) REFERENCES `cmg_core_option` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cmg_core_model_address`
--

ALTER TABLE `cmg_core_model_address`
  	ADD CONSTRAINT `fk_cmg_model_address_1` FOREIGN KEY (`addressId`) REFERENCES `cmg_core_address` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cmg_core_model_form`
--

ALTER TABLE `cmg_core_model_form`
  	ADD CONSTRAINT `fk_cmg_model_form_1` FOREIGN KEY (`formId`) REFERENCES `cmg_core_form` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cmg_core_model_gallery`
--

ALTER TABLE `cmg_core_model_gallery`
  	ADD CONSTRAINT `fk_cmg_model_gallery_1` FOREIGN KEY (`galleryId`) REFERENCES `cmg_core_gallery` (`id`) ON DELETE CASCADE;

SET FOREIGN_KEY_CHECKS=1;