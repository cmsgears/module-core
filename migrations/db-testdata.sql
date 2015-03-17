SET FOREIGN_KEY_CHECKS=0;

/* ============================= CMSGears Core ============================================== */

--
-- Dumping data for table `cmg_config`
--

INSERT INTO `cmg_config` VALUES
	(1,'locale message','false',0, 'text', null ),
	(2,'language','en-US',0, 'text', null ),
	(3,'charset','UTF-8',0, 'text', null ),
	(4,'site title','CMG Demo',0, 'text', null ),
	(5,'site name','CMG Demo',0, 'text', null ),
	(6,'site url','http://localhost/cmsgears/frontend/web/',0, 'text', null ),
	(7,'smtp','false',10, 'text', null ),
	(8,'smtp username','',10, 'text', null ),
	(9,'smtp password','',10, '', null ),
	(10,'smtp host','',10, 'text', null ),
	(11,'smtp port','587',10, 'text', null ),
	(12,'debug','true',10, 'text', null ),	
	(13,'sender name','Admin',10, 'text', null ),
	(14,'sender email','demoadmin@cmsgears.org',10, 'text', null ),
	(15,'contact name','Contact Us',10, 'text', null ),
	(16,'contact email','democontact@cmsgears.org',10, 'text', null ),
	(17,'info name','Info',10, 'text', null ),
	(18,'info email','demoinfo@cmsgears.org',10, 'text', null ),
	(19,'theme','basic',20, 'text', null ),
	(20,'theme version','1',20, 'text', null ),
	(21,'admin url','http://localhost/cmsgears/admin/web/',30, 'text', null ),
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
	(1,NULL,'category type',NULL, 0),
	(2,NULL,'role type',NULL, 0),
	(3,NULL,'config type',NULL, 0),
	(4,NULL,'gender',NULL, 0);

--
-- Dumping data for table `cmg_option`
--

INSERT INTO `cmg_option` VALUES 
	(1,1,'Combo','0'),
	(2,2,'System','0'),
	(3,3,'Core',0),(4,3,'Email',10),(5,3,'Website',20),(6,3,'Admin',30),
	(7,4,'Male',NULL),(8,4,'Female',NULL),(9,4,'Other',NULL);

--
-- Dumping data for table `cmg_role`
--

INSERT INTO `cmg_role` VALUES 
	(1,1,1,'Super Admin','The Super Admin have all the permisisons to perform operations on the admin site and website.','/',0,'2014-10-11 14:22:54','2014-10-11 14:22:54'),
	(2,1,1,'Admin','The Admin have all the permisisons to perform operations on the admin site and website except RBAC module.','/',0,'2014-10-11 14:22:54','2014-10-11 14:22:54'),
	(3,1,1,'User','The role User is limited to website users.','/',0,'2014-10-11 14:22:54','2014-10-11 14:22:54'),
	(4,1,1,'Identity Manager','The role User Manager is limited to manage users from admin.','/',0,'2014-10-11 14:22:54','2014-10-11 14:22:54');

--
-- Dumping data for table `cmg_permission`
--

INSERT INTO `cmg_permission` VALUES 
	(1,1,1,'admin','The permission admin is to distinguish between admin and site user. It is a must have permission for admins.','2014-10-11 14:22:54','2014-10-11 14:22:54'),
	(2,1,1,'user','The permission user is to distinguish between admin and site user. It is a must have permission for users.','2014-10-11 14:22:54','2014-10-11 14:22:54'),
	(3,1,1,'settings','The permission settings is to manage settings from admin.','2014-10-11 14:22:54','2014-10-11 14:22:54'),
	(4,1,1,'category','The permission category is to manage drop down options from admin.','2014-10-11 14:22:54','2014-10-11 14:22:54'),
	(5,1,1,'identity','The permission identity is to manage user, roles and permissions modules from admin.','2014-10-11 14:22:54','2014-10-11 14:22:54'),
	(6,1,1,'identity-user','The permission user-crud-user is to manage users from admin.','2014-10-11 14:22:54','2014-10-11 14:22:54'),
	(7,1,1,'identity-rbac','The permission rbac is to manage Role Based Access Control(RBAC) module from admin.','2014-10-11 14:22:54','2014-10-11 14:22:54'),
	(8,1,1,'newsletter','The permission newsletter is to manage newsletters from admin.','2014-10-11 14:22:54','2014-10-11 14:22:54'),
	(9,1,1,'slider','The permission slider is to manage site sliders from admin.','2014-10-11 14:22:54','2014-10-11 14:22:54');
--
-- Dumping data for table `cmg_role_permission`
--

INSERT INTO `cmg_role_permission` VALUES 
	(1,1),(1,2),(1,3),(1,4),(1,5),(1,6),(1,7),(1,8),(1,9),
	(2,1),(2,2),(2,3),(2,4),(2,8),(2,9),
	(3,2),
	(4,1),(4,2),(4,5),(4,6),(5,7);

--
-- Dumping data for table `cmg_user`
--

INSERT INTO `cmg_user` VALUES 
	(1,1,NULL,NULL,NULL,10,'demomaster@cmsgears.org','demomaster','$2y$13$Ut5b2RskRpGA9Q0nKSO6Xe65eaBHdx/q8InO8Ln6Lt3HzOK4ECz8W','demo','master',NULL,NULL,1,NULL,NULL,'2014-10-11 14:22:54','2014-10-10 08:03:19',NULL,NULL,NULL,NULL),
	(2,2,NULL,NULL,NULL,10,'demoadmin@cmsgears.org','demoadmin','$2y$13$Ut5b2RskRpGA9Q0nKSO6Xe65eaBHdx/q8InO8Ln6Lt3HzOK4ECz8W','demo','admin',NULL,NULL,1,NULL,NULL,'2014-10-11 14:22:54','2014-10-10 08:03:19',NULL,NULL,NULL,NULL),
	(3,3,NULL,NULL,NULL,10,'demouser@cmsgears.org','demouser','$2y$13$Ut5b2RskRpGA9Q0nKSO6Xe65eaBHdx/q8InO8Ln6Lt3HzOK4ECz8W','demo','user',NULL,NULL,1,NULL,NULL,'2014-10-11 14:22:54','2014-10-10 08:03:19',NULL,NULL,NULL,NULL),
	(4,4,NULL,NULL,NULL,10,'demoidentitymanager@cmsgears.org','demoidentitymanager','$2y$13$Ut5b2RskRpGA9Q0nKSO6Xe65eaBHdx/q8InO8Ln6Lt3HzOK4ECz8W','demo','user',NULL,NULL,1,NULL,NULL,'2014-10-11 14:22:54','2014-10-10 08:03:19',NULL,NULL,NULL,NULL);

SET FOREIGN_KEY_CHECKS=1;