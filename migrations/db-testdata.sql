/* ============================= CMSGears Core ============================================== */

SET FOREIGN_KEY_CHECKS=0;

--
-- Dumping data for table `cmg_core_site`
--

INSERT INTO `cmg_core_site` VALUES
	(1,NULL,NULL,'main','main',0,1);

--
-- Dumping data for table `cmg_core_model_meta`
--

INSERT INTO `cmg_core_model_meta` (`parentId`,`parentType`,`name`,`value`,`type`,`fieldType`,`fieldMeta`) VALUES
	(1,'site','locale message','0','core','text',null),
	(1,'site','language','en-US','core','text',null),
	(1,'site','locale','en_US','core','text',null),
	(1,'site','charset','UTF-8','core','text',null),
	(1,'site','site title','CMG Demo','core','text',null),
	(1,'site','site name','CMSGears','core','text',null),
	(1,'site','site url','http://demo.cmsgears.com/templates/basic/','core','text',null),
	(1,'site','admin url','http://demo.cmsgears.com/templates/basic/admin/','core','text',null),
	(1,'site','registration','1','core','text',null),
	(1,'site','change email','1','core','text',null),
	(1,'site','change username','1','core','text',null),
	(1,'site','smtp','0','email','text',null),
	(1,'site','smtp username','','email','text',null),
	(1,'site','smtp password','','email','password',null),
	(1,'site','smtp host','','email','text',null),
	(1,'site','smtp port','587','email','text',null),
	(1,'site','smtp encryption','tls','email','text',null),
	(1,'site','debug','1','email','text',null),
	(1,'site','sender name','Admin','email','text',null),
	(1,'site','sender email','demoadmin@cmsgears.com','email','text',null),
	(1,'site','contact name','Contact Us','email','text',null),
	(1,'site','contact email','democontact@cmsgears.com','email','text',null),
	(1,'site','info name','Info','email','text',null),
	(1,'site','info email','demoinfo@cmsgears.com','email','text',null),
	(1,'site','theme','basic','frontend','text',null),
	(1,'site','theme version','1','frontend','text',null),
	(1,'site','theme','basic','admin','text',null),
	(1,'site','theme version','1','admin','text',null);

--
-- Dumping data for table `cmg_core_locale`
--

INSERT INTO `cmg_core_locale` VALUES (1,'en_US','English US');

--
-- Dumping data for table `cmg_core_category`
--

INSERT INTO `cmg_core_category` VALUES 
	(1,NULL,NULL,'gender',NULL,'gender','combo',NULL,0);

--
-- Dumping data for table `cmg_core_option`
--

INSERT INTO `cmg_core_option` (`categoryId`,`name`,`value`,`icon`) VALUES 
	(1,'Male','Male',NULL),(1,'Female','Female',NULL),(1,'Other','Other',NULL);

--
-- Dumping data for table `cmg_core_role`
--

INSERT INTO `cmg_core_role` VALUES 
	(1,1,1,'Super Admin','super-admin','The Super Admin have all the permisisons to perform operations on the admin site and website.','dashboard','system',NULL,'2014-10-11 14:22:54','2014-10-11 14:22:54'),
	(2,1,1,'Admin','admin','The Admin have all the permisisons to perform operations on the admin site and website except RBAC module.','dashboard','system',NULL,'2014-10-11 14:22:54','2014-10-11 14:22:54'),
	(3,1,1,'User','user','The role User is limited to website users.',NULL,'system',NULL,'2014-10-11 14:22:54','2014-10-11 14:22:54'),
	(4,1,1,'User Manager','user-manager','The role User Manager is limited to manage site users from admin.','dashboard','system',NULL,'2014-10-11 14:22:54','2014-10-11 14:22:54');

--
-- Dumping data for table `cmg_core_permission`
--

INSERT INTO `cmg_core_permission` VALUES 
	(1,1,1,'Admin','admin','The permission admin is to distinguish between admin and site user. It is a must have permission for admins.','system',NULL,'2014-10-11 14:22:54','2014-10-11 14:22:54'),
	(2,1,1,'User','user','The permission user is to distinguish between admin and site user. It is a must have permission for users.','system',NULL,'2014-10-11 14:22:54','2014-10-11 14:22:54'),
	(3,1,1,'Core','core','The permission core is to manage settings, drop downs, world countries, galleries and newsletters from admin.','system',NULL,'2014-10-11 14:22:54','2014-10-11 14:22:54'),
	(4,1,1,'Identity','identity','The permission identity is to manage users from admin.','system',NULL,'2014-10-11 14:22:54','2014-10-11 14:22:54'),
	(5,1,1,'RBAC','rbac','The permission rbac is to manage roles and permissions from admin.','system',NULL,'2014-10-11 14:22:54','2014-10-11 14:22:54');

--
-- Dumping data for table `cmg_core_role_permission`
--

INSERT INTO `cmg_core_role_permission` VALUES 
	(1,1),(1,2),(1,3),(1,4),(1,5),
	(2,1),(2,2),(2,3),
	(3,2),
	(4,1),(4,2),(4,4);

--
-- Dumping data for table `cmg_core_user`
--

INSERT INTO `cmg_core_user` VALUES 
	(1,NULL,NULL,NULL,NULL,500,'demomaster@cmsgears.com','demomaster','$2y$13$Ut5b2RskRpGA9Q0nKSO6Xe65eaBHdx/q8InO8Ln6Lt3HzOK4ECz8W','demo','master',NULL,NULL,NULL,NULL,'2014-10-11 14:22:54','2014-10-10 08:03:19',NULL,'JuL37UBqGpjnA7kaPiRnlsiWRwbRvXx7',NULL,NULL,NULL),
	(2,NULL,NULL,NULL,NULL,500,'demoadmin@cmsgears.com','demoadmin','$2y$13$Ut5b2RskRpGA9Q0nKSO6Xe65eaBHdx/q8InO8Ln6Lt3HzOK4ECz8W','demo','admin',NULL,NULL,NULL,NULL,'2014-10-11 14:22:54','2014-10-10 08:03:19',NULL,'SQ1LLCWEPva4IKuQklILLGDpmUTGzq8E',NULL,NULL,NULL),
	(3,NULL,NULL,NULL,NULL,500,'demouser@cmsgears.com','demouser','$2y$13$Ut5b2RskRpGA9Q0nKSO6Xe65eaBHdx/q8InO8Ln6Lt3HzOK4ECz8W','demo','user',NULL,NULL,NULL,NULL,'2014-10-11 14:22:54','2014-10-10 08:03:19',NULL,'-jG5ExHS0Y39ucSxHhl3PZ4xmPsfvQFC',NULL,NULL,NULL);

--
-- Dumping data for table `cmg_core_site_member`
--

INSERT INTO `cmg_core_site_member` VALUES
	(1,1,1,'2014-10-11 14:22:54','2014-10-11 14:22:54'),
	(1,2,2,'2014-10-11 14:22:54','2014-10-11 14:22:54'),
	(1,3,3,'2014-10-11 14:22:54','2014-10-11 14:22:54');

SET FOREIGN_KEY_CHECKS=1;