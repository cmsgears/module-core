--
-- Table structure for table `cmg_site`
--

DROP TABLE IF EXISTS `cmg_site`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmg_site` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cmg_site_member`
--

DROP TABLE IF EXISTS `cmg_site_member`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmg_site_member` (
  `siteId` bigint(20) NOT NULL, 
  `memberId` bigint(20) NOT NULL,
  `roleId` bigint(20) NOT NULL, 
  PRIMARY KEY (`siteId`, `memberId`),
  KEY `fk_site_member_1` (`siteId`),
  KEY `fk_site_member_2` (`memberId`),
  KEY `fk_site_member_3` (`roleId`),
  CONSTRAINT `fk_site_member_1` FOREIGN KEY (`siteId`) REFERENCES `cmg_site` (`id`),
  CONSTRAINT `fk_site_member_2` FOREIGN KEY (`memberId`) REFERENCES `cmg_user` (`id`),
  CONSTRAINT `fk_site_member_3` FOREIGN KEY (`roleId`) REFERENCES `cmg_role` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;