SET FOREIGN_KEY_CHECKS=0;

/* ============================= CMSGears Core ============================================== */

--
-- Dumping data for table `cmg_core_site`
--

INSERT INTO `cmg_core_site` VALUES
	(1,'main');

--
-- Dumping data for table `cmg_core_model_meta`
--

INSERT INTO `cmg_core_model_meta` VALUES
	(1,'site','locale message','false','core','text',null),
	(1,'site','language','en-US','core','text',null),
	(1,'site','charset','UTF-8','core','text',null),
	(1,'site','site title','CMG Demo','core','text',null),
	(1,'site','site name','CMSGears','core','text',null),
	(1,'site','site url','http://demo.cmsgears.com/templates/basic/frontend/web/','core','text',null),
	(1,'site','smtp','false','email','text',null),
	(1,'site','smtp username','','email','text',null),
	(1,'site','smtp password','','email','',null),
	(1,'site','smtp host','','email','text',null),
	(1,'site','smtp port','587','email','text',null),
	(1,'site','debug','true','email','text',null),
	(1,'site','sender name','Admin','email','text',null),
	(1,'site','sender email','demoadmin@cmsgears.com','email','text',null),
	(1,'site','contact name','Contact Us','email','text',null),
	(1,'site','contact email','democontact@cmsgears.com','email','text',null),
	(1,'site','info name','Info','email','text',null),
	(1,'site','info email','demoinfo@cmsgears.com','email','text',null),
	(1,'site','theme','basic','site','text',null),
	(1,'site','theme version','1','site','text',null),
	(1,'site','admin url','http://demo.cmsgears.com/templates/basic/admin/web/','admin','text',null),
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
	(1,NULL,'role type',NULL,NULL,'combo',NULL),
	(3,NULL,'gender',NULL,NULL,'combo',NULL);

--
-- Dumping data for table `cmg_core_option`
--

INSERT INTO `cmg_core_option` (`categoryId`,`name`,`value`,`icon`) VALUES 
	(1,'system','System Roles',NULL),
	(3,'Male',NULL,NULL),(3,'Female',NULL,NULL),(3,'Other',NULL,NULL);

--
-- Dumping data for table `cmg_core_role`
--

INSERT INTO `cmg_core_role` VALUES 
	(1,1,1,'Super Admin','The Super Admin have all the permisisons to perform operations on the admin site and website.','/dashboard',0,'2014-10-11 14:22:54','2014-10-11 14:22:54',NULL),
	(2,1,1,'Admin','The Admin have all the permisisons to perform operations on the admin site and website except RBAC module.','/dashboard',0,'2014-10-11 14:22:54','2014-10-11 14:22:54',NULL),
	(3,1,1,'User','The role User is limited to website users.','/home',0,'2014-10-11 14:22:54','2014-10-11 14:22:54',NULL),
	(4,1,1,'Identity Manager','The role Identity Manager is limited to manage users from admin.','/dashboard',0,'2014-10-11 14:22:54','2014-10-11 14:22:54',NULL);

--
-- Dumping data for table `cmg_core_permission`
--

INSERT INTO `cmg_core_permission` VALUES 
	(1,1,1,'admin','The permission admin is to distinguish between admin and site user. It is a must have permission for admins.','2014-10-11 14:22:54','2014-10-11 14:22:54',NULL),
	(2,1,1,'user','The permission user is to distinguish between admin and site user. It is a must have permission for users.','2014-10-11 14:22:54','2014-10-11 14:22:54',NULL),
	(3,1,1,'core','The permission core is to manage settings, drop downs, galleries and newsletters from admin.','2014-10-11 14:22:54','2014-10-11 14:22:54',NULL),
	(4,1,1,'identity','The permission identity is to manage user, roles and permissions modules from admin.','2014-10-11 14:22:54','2014-10-11 14:22:54',NULL);

--
-- Dumping data for table `cmg_core_role_permission`
--

INSERT INTO `cmg_core_role_permission` VALUES 
	(1,1),(1,2),(1,3),(1,4),
	(2,1),(2,2),(2,3),
	(3,2),
	(4,1),(4,2),(4,4);

--
-- Dumping data for table `cmg_core_user`
--

INSERT INTO `cmg_core_user` VALUES 
	(1,NULL,NULL,NULL,500,'demomaster@cmsgears.com','demomaster','$2y$13$Ut5b2RskRpGA9Q0nKSO6Xe65eaBHdx/q8InO8Ln6Lt3HzOK4ECz8W','demo','master',NULL,NULL,1,NULL,NULL,'2014-10-11 14:22:54','2014-10-10 08:03:19',NULL,NULL,NULL,NULL,NULL),
	(2,NULL,NULL,NULL,500,'demoadmin@cmsgears.com','demoadmin','$2y$13$Ut5b2RskRpGA9Q0nKSO6Xe65eaBHdx/q8InO8Ln6Lt3HzOK4ECz8W','demo','admin',NULL,NULL,1,NULL,NULL,'2014-10-11 14:22:54','2014-10-10 08:03:19',NULL,NULL,NULL,NULL,NULL),
	(3,NULL,NULL,NULL,500,'demouser@cmsgears.com','demouser','$2y$13$Ut5b2RskRpGA9Q0nKSO6Xe65eaBHdx/q8InO8Ln6Lt3HzOK4ECz8W','demo','user',NULL,NULL,1,NULL,NULL,'2014-10-11 14:22:54','2014-10-10 08:03:19',NULL,NULL,NULL,NULL,NULL);

--
-- Dumping data for table `cmg_core_site_member`
--

INSERT INTO `cmg_core_site_member` VALUES
	(1,1,1,'2014-10-11 14:22:54'),
	(1,2,2,'2014-10-11 14:22:54'),
	(1,3,3,'2014-10-11 14:22:54');

SET FOREIGN_KEY_CHECKS=1;