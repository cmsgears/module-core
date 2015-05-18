SET FOREIGN_KEY_CHECKS=0;

/* == Reserved Id's - 0 to 1000 == */

/* ============================= CMSGears Core ============================================== */

--
-- Dumping data for table `cmg_core_site`
--

INSERT INTO `cmg_core_site` VALUES
	(1,'main');

--
-- Dumping data for table `cmg_core_config`
--

INSERT INTO `cmg_core_config` VALUES
	(1,'locale message','false','core','text',null),
	(2,'language','en-US','core','text',null),
	(3,'charset','UTF-8','core','text',null),
	(4,'site title','CMG Demo','core','text',null),
	(5,'site name','CMSGears','core','text',null),
	(6,'site url','http://demo.cmsgears.com/templates/basic/frontend/web/','core','text',null),
	(7,'smtp','false','mail','text',null),
	(8,'smtp username','','mail','text',null),
	(9,'smtp password','','mail','',null),
	(10,'smtp host','','mail','text',null),
	(11,'smtp port','587','mail','text',null),
	(12,'debug','true','mail','text',null),
	(13,'sender name','Admin','mail','text',null),
	(14,'sender email','demoadmin@cmsgears.com','mail','text',null),
	(15,'contact name','Contact Us','mail','text',null),
	(16,'contact email','democontact@cmsgears.com','mail','text',null),
	(17,'info name','Info','mail','text',null),
	(18,'info email','demoinfo@cmsgears.com','mail','text',null),
	(19,'theme','basic','site','text',null),
	(20,'theme version','1','site','text',null),
	(21,'admin url','http://demo.cmsgears.com/templates/basic/admin/web/','admin','text',null),
	(22,'theme','basic','admin','text',null),
	(23,'theme version','1','admin','text',null);

--
-- Dumping data for table `cmg_core_locale`
--

INSERT INTO `cmg_core_locale` VALUES (1,'en_US','English US');

--
-- Dumping data for table `cmg_core_category`
--

INSERT INTO `cmg_core_category` VALUES 
	(1,NULL,'role type',NULL,'combo',NULL),
	(2,NULL,'config type',NULL,'combo',NULL),
	(3,NULL,'gender',NULL,'combo',NULL);

--
-- Dumping data for table `cmg_core_option`
--

INSERT INTO `cmg_core_option` VALUES 
	(1,1,'System','system',NULL),
	(2,2,'Core','core',NULL),(3,2,'Email','email',NULL),(4,2,'Website','site',NULL),(5,2,'Admin','admin',NULL),
	(6,3,'Male',NULL,NULL),(7,3,'Female',NULL,NULL),(8,3,'Other',NULL,NULL);

--
-- Dumping data for table `cmg_core_role`
--

INSERT INTO `cmg_core_role` VALUES 
	(1,1,1,'Super Admin','The Super Admin have all the permisisons to perform operations on the admin site and website.','/dashboard',0,'2014-10-11 14:22:54','2014-10-11 14:22:54'),
	(2,1,1,'Admin','The Admin have all the permisisons to perform operations on the admin site and website except RBAC module.','/dashboard',0,'2014-10-11 14:22:54','2014-10-11 14:22:54'),
	(3,1,1,'User','The role User is limited to website users.','/home',0,'2014-10-11 14:22:54','2014-10-11 14:22:54'),
	(4,1,1,'Identity Manager','The role Identity Manager is limited to manage users from admin.','/dashboard',0,'2014-10-11 14:22:54','2014-10-11 14:22:54');

--
-- Dumping data for table `cmg_core_permission`
--

INSERT INTO `cmg_core_permission` VALUES 
	(1,1,1,'admin','The permission admin is to distinguish between admin and site user. It is a must have permission for admins.','2014-10-11 14:22:54','2014-10-11 14:22:54'),
	(2,1,1,'user','The permission user is to distinguish between admin and site user. It is a must have permission for users.','2014-10-11 14:22:54','2014-10-11 14:22:54'),
	(3,1,1,'core','The permission core is to manage settings, drop downs, galleries and newsletters from admin.','2014-10-11 14:22:54','2014-10-11 14:22:54'),
	(4,1,1,'identity','The permission identity is to manage user, roles and permissions modules from admin.','2014-10-11 14:22:54','2014-10-11 14:22:54');

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
	(1,1,NULL,NULL,NULL,500,'demomaster@cmsgears.com','demomaster','$2y$13$Ut5b2RskRpGA9Q0nKSO6Xe65eaBHdx/q8InO8Ln6Lt3HzOK4ECz8W','demo','master',NULL,NULL,1,NULL,NULL,'2014-10-11 14:22:54','2014-10-10 08:03:19',NULL,NULL,NULL,NULL,NULL),
	(2,2,NULL,NULL,NULL,500,'demoadmin@cmsgears.com','demoadmin','$2y$13$Ut5b2RskRpGA9Q0nKSO6Xe65eaBHdx/q8InO8Ln6Lt3HzOK4ECz8W','demo','admin',NULL,NULL,1,NULL,NULL,'2014-10-11 14:22:54','2014-10-10 08:03:19',NULL,NULL,NULL,NULL,NULL),
	(3,3,NULL,NULL,NULL,500,'demouser@cmsgears.com','demouser','$2y$13$Ut5b2RskRpGA9Q0nKSO6Xe65eaBHdx/q8InO8Ln6Lt3HzOK4ECz8W','demo','user',NULL,NULL,1,NULL,NULL,'2014-10-11 14:22:54','2014-10-10 08:03:19',NULL,NULL,NULL,NULL,NULL),
	(4,4,NULL,NULL,NULL,500,'demoidentitymanager@cmsgears.com','demoidentitymanager','$2y$13$Ut5b2RskRpGA9Q0nKSO6Xe65eaBHdx/q8InO8Ln6Lt3HzOK4ECz8W','demo','user',NULL,NULL,1,NULL,NULL,'2014-10-11 14:22:54','2014-10-10 08:03:19',NULL,NULL,NULL,NULL,NULL);

SET FOREIGN_KEY_CHECKS=1;