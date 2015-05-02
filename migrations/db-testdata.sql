SET FOREIGN_KEY_CHECKS=0;

/* == Reserved Id's - 0 to 1000 == */

/* ============================= CMSGears Core ============================================== */

--
-- Dumping data for table `cmg_config`
--

INSERT INTO `cmg_config` VALUES
	(1,'locale message','false',0, 'text', null ),
	(2,'language','en-US',0, 'text', null ),
	(3,'charset','UTF-8',0, 'text', null ),
	(4,'site title','CMG Demo',0, 'text', null ),
	(5,'site name','CMSGears',0, 'text', null ),
	(6,'site url','http://demo.cmsgears.com/templates/basic/frontend/web/',0, 'text', null ),
	(7,'smtp','false',10, 'text', null ),
	(8,'smtp username','',10, 'text', null ),
	(9,'smtp password','',10, '', null ),
	(10,'smtp host','',10, 'text', null ),
	(11,'smtp port','587',10, 'text', null ),
	(12,'debug','true',10, 'text', null ),
	(13,'sender name','Admin',10, 'text', null ),
	(14,'sender email','demoadmin@cmsgears.com',10, 'text', null ),
	(15,'contact name','Contact Us',10, 'text', null ),
	(16,'contact email','democontact@cmsgears.com',10, 'text', null ),
	(17,'info name','Info',10, 'text', null ),
	(18,'info email','demoinfo@cmsgears.com',10, 'text', null ),
	(19,'theme','basic',20, 'text', null ),
	(20,'theme version','1',20, 'text', null ),
	(21,'admin url','http://demo.cmsgears.com/templates/basic/admin/web/',30, 'text', null ),
	(22,'theme','basic',30, 'text', null ),
	(23,'theme version','1',30, 'text', null );

--
-- Dumping data for table `cmg_locale`
--

INSERT INTO `cmg_locale` VALUES (1,'en_US','English US');

--
-- Dumping data for table `cmg_category`
--

INSERT INTO `cmg_category` VALUES 
	(1,NULL,'category type',NULL,0,NULL),
	(2,NULL,'role type',NULL,0,NULL),
	(3,NULL,'config type',NULL,0,NULL),
	(4,NULL,'gender',NULL,0,NULL);

--
-- Dumping data for table `cmg_option`
--

INSERT INTO `cmg_option` VALUES 
	(1,1,'Combo',0,NULL),
	(2,2,'System',0,NULL),
	(3,3,'Core',0,NULL),(4,3,'Email',10,NULL),(5,3,'Website',20,NULL),(6,3,'Admin',30,NULL),
	(7,4,'Male',NULL,NULL),(8,4,'Female',NULL,NULL),(9,4,'Other',NULL,NULL);

--
-- Dumping data for table `cmg_role`
--

INSERT INTO `cmg_role` VALUES 
	(1,1,1,'Super Admin','The Super Admin have all the permisisons to perform operations on the admin site and website.','/dashboard',0,'2014-10-11 14:22:54','2014-10-11 14:22:54'),
	(2,1,1,'Admin','The Admin have all the permisisons to perform operations on the admin site and website except RBAC module.','/dashboard',0,'2014-10-11 14:22:54','2014-10-11 14:22:54'),
	(3,1,1,'User','The role User is limited to website users.','/home',0,'2014-10-11 14:22:54','2014-10-11 14:22:54'),
	(4,1,1,'Identity Manager','The role Identity Manager is limited to manage users from admin.','/dashboard',0,'2014-10-11 14:22:54','2014-10-11 14:22:54');

--
-- Dumping data for table `cmg_permission`
--

INSERT INTO `cmg_permission` VALUES 
	(1,1,1,'admin','The permission admin is to distinguish between admin and site user. It is a must have permission for admins.','2014-10-11 14:22:54','2014-10-11 14:22:54'),
	(2,1,1,'user','The permission user is to distinguish between admin and site user. It is a must have permission for users.','2014-10-11 14:22:54','2014-10-11 14:22:54'),
	(3,1,1,'core','The permission core is to manage settings, drop downs, galleries and newsletters from admin.','2014-10-11 14:22:54','2014-10-11 14:22:54'),
	(4,1,1,'identity','The permission identity is to manage user, roles and permissions modules from admin.','2014-10-11 14:22:54','2014-10-11 14:22:54');

--
-- Dumping data for table `cmg_role_permission`
--

INSERT INTO `cmg_role_permission` VALUES 
	(1,1),(1,2),(1,3),(1,4),
	(2,1),(2,2),(2,3),
	(3,2),
	(4,1),(4,2),(4,4);

--
-- Dumping data for table `cmg_user`
--

INSERT INTO `cmg_user` VALUES 
	(1,1,NULL,NULL,NULL,500,'demomaster@cmsgears.com','demomaster','$2y$13$Ut5b2RskRpGA9Q0nKSO6Xe65eaBHdx/q8InO8Ln6Lt3HzOK4ECz8W','demo','master',NULL,NULL,1,NULL,NULL,'2014-10-11 14:22:54','2014-10-10 08:03:19',NULL,NULL,NULL,NULL,NULL),
	(2,2,NULL,NULL,NULL,500,'demoadmin@cmsgears.com','demoadmin','$2y$13$Ut5b2RskRpGA9Q0nKSO6Xe65eaBHdx/q8InO8Ln6Lt3HzOK4ECz8W','demo','admin',NULL,NULL,1,NULL,NULL,'2014-10-11 14:22:54','2014-10-10 08:03:19',NULL,NULL,NULL,NULL,NULL),
	(3,3,NULL,NULL,NULL,500,'demouser@cmsgears.com','demouser','$2y$13$Ut5b2RskRpGA9Q0nKSO6Xe65eaBHdx/q8InO8Ln6Lt3HzOK4ECz8W','demo','user',NULL,NULL,1,NULL,NULL,'2014-10-11 14:22:54','2014-10-10 08:03:19',NULL,NULL,NULL,NULL,NULL),
	(4,4,NULL,NULL,NULL,500,'demoidentitymanager@cmsgears.com','demoidentitymanager','$2y$13$Ut5b2RskRpGA9Q0nKSO6Xe65eaBHdx/q8InO8Ln6Lt3HzOK4ECz8W','demo','user',NULL,NULL,1,NULL,NULL,'2014-10-11 14:22:54','2014-10-10 08:03:19',NULL,NULL,NULL,NULL,NULL);

SET FOREIGN_KEY_CHECKS=1;