/* ============================= CMSGears Core ============================================== */

SET FOREIGN_KEY_CHECKS=0;

--
-- Dumping data for table `cmg_core_site`
--

INSERT INTO `cmg_core_site` VALUES
	(1,NULL,NULL,'main','main',0,1);

--
-- Dumping data for table `cmg_core_form`
--

INSERT INTO `cmg_core_form` (`siteId`,`templateId`,`createdBy`,`modifiedBy`,`name`,`slug`,`description`,`successMessage`,`captcha`,`visibility`,`active`,`userMail`,`adminMail`,`options`,`createdAt`,`modifiedAt`) VALUES
	(1,NULL,1,1,'Config Core','config-core','Core configuration form.','All configurations saved successfully.',0,10,1,0,0,NULL,'2014-10-11 14:22:54','2014-10-11 14:22:54'),
	(1,NULL,1,1,'Config Mail','config-email','Mail configuration form.','All configurations saved successfully.',0,10,1,0,0,NULL,'2014-10-11 14:22:54','2014-10-11 14:22:54'),
	(1,NULL,1,1,'Config Admin','config-backend','Backend site configuration form.','All configurations saved successfully.',0,10,1,0,0,NULL,'2014-10-11 14:22:54','2014-10-11 14:22:54'),
	(1,NULL,1,1,'Config Site','config-frontend','Frontend site configuration form.','All configurations saved successfully.',0,10,1,0,0,NULL,'2014-10-11 14:22:54','2014-10-11 14:22:54');

--
-- Dumping data for table `cmg_core_form_field`
--

INSERT INTO `cmg_core_form_field` (`formId`,`name`,`label`,`type`,`compress`,`validators`,`options`,`data`,`order`) VALUES 
	(1,'locale_message','Locale Message',20,0,'required','{\"title\":\"Check for i18n support.\"}',NULL,0),
	(1,'language','Language',0,0,'required','{\"title\":\"Language used on html tag.\",\"placeholder\":\"Language\"}',NULL,0),
	(1,'locale','Locale',0,0,'required','{\"title\":\"Site default locale.\",\"placeholder\":\"Locale\"}',NULL,0),
	(1,'charset','Charset',0,0,'required','{\"title\":\"Charset used on html head meta.\",\"placeholder\":\"Charset\"}',NULL,0),
	(1,'site_title','Site Title',0,0,'required','{\"title\":\"Site title used in forming page title.\",\"placeholder\":\"Site Title\"}',NULL,0),
	(1,'site_name','Site Name',0,0,'required','{\"title\":\"Site name used on footers etc.\",\"placeholder\":\"Site Name\"}',NULL,0),
	(1,'site_url','Frontend URL',0,0,'required','{\"title\":\"Frontend URL\",\"placeholder\":\"Frontend URL\"}',NULL,0),
	(1,'admin_url','Backend URL',0,0,'required','{\"title\":\"Backend URL\",\"placeholder\":\"Backend URL\"}',NULL,0),
	(1,'registration','Registration',20,0,'required','{\"title\":\"Check whether site registration is allowed.\"}',NULL,0),
	(1,'change_email','Change Email',20,0,'required','{\"title\":\"Check whether email change is allowed for user profile.\"}',NULL,0),
	(1,'change_username','Change Username',20,0,'required','{\"title\":\"Check whether username change is allowed for user profile.\"}',NULL,0),
	(2,'smtp','SMTP',20,0,'required','{\"title\":\"Check whether SMTP is required.\"}',NULL,0),
	(2,'smtp_username','SMTP Username',0,0,'required','{\"title\":\"SMTP username.\",\"placeholder\":\"SMTP Username\"}',NULL,0),
	(2,'smtp_password','SMTP Password',5,0,'required','{\"title\":\"SMTP password.\",\"placeholder\":\"SMTP Password\"}',NULL,0),
	(2,'smtp_host','SMTP Host',0,0,'required','{\"title\":\"SMTP host.\",\"placeholder\":\"SMTP Host\"}',NULL,0),
	(2,'smtp_port','SMTP Port',0,0,'required','{\"title\":\"SMTP port.\",\"placeholder\":\"SMTP Port\"}',NULL,0),
	(2,'smtp_encryption','SMTP Encryption',0,0,'required','{\"title\":\"SMTP encryption.\",\"placeholder\":\"SMTP Encryption\"}',NULL,0),
	(2,'debug','SMTP Debug',20,0,'required','{\"title\":\"Check whether SMTP debug is required.\"}',NULL,0),
	(2,'sender_name','Sender Name',0,0,'required','{\"title\":\"Sender name.\",\"placeholder\":\"Sender Name\"}',NULL,0),
	(2,'sender_email','Sender Email',0,0,'required','{\"title\":\"Sender email.\",\"placeholder\":\"Sender Email\"}',NULL,0),
	(2,'contact_name','Contact Name',0,0,'required','{\"title\":\"Contact name.\",\"placeholder\":\"Contact Name\"}',NULL,0),
	(2,'contact_email','Contact Email',0,0,'required','{\"title\":\"Contact email.\",\"placeholder\":\"Contact Email\"}',NULL,0),
	(2,'info_name','Info Name',0,0,'required','{\"title\":\"Info name.\",\"placeholder\":\"Info Name\"}',NULL,0),
	(2,'info_email','Info Email',0,0,'required','{\"title\":\"Info email.\",\"placeholder\":\"Info Email\"}',NULL,0),
	(3,'theme','Theme',0,0,'required','{\"title\":\"Current theme.\",\"placeholder\":\"Theme\"}',NULL,0),
	(3,'theme_version','Theme Version',0,0,'required','{\"title\":\"Theme version.\",\"placeholder\":\"Theme Version\"}',NULL,0),
	(4,'theme','Theme',0,0,'required','{\"title\":\"Current theme.\",\"placeholder\":\"Theme\"}',NULL,0),
	(4,'theme_version','Theme Version',0,0,'required','{\"title\":\"Theme version.\",\"placeholder\":\"Theme Version\"}',NULL,0);

--
-- Dumping data for table `cmg_core_model_attribute`
--

INSERT INTO `cmg_core_model_attribute` (`parentId`,`parentType`,`name`,`value`,`type`) VALUES
	(1,'site','locale_message','0','core'),
	(1,'site','language','en-US','core'),
	(1,'site','locale','en_US','core'),
	(1,'site','charset','UTF-8','core'),
	(1,'site','site_title','CMG Demo','core'),
	(1,'site','site_name','CMSGears','core'),
	(1,'site','site_url','http://demo.cmsgears.com/templates/basic/','core'),
	(1,'site','admin_url','http://demo.cmsgears.com/templates/basic/admin/','core'),
	(1,'site','registration','1','core'),
	(1,'site','change_email','1','core'),
	(1,'site','change_username','1','core'),
	(1,'site','smtp','0','email'),
	(1,'site','smtp_username','','email'),
	(1,'site','smtp_password','','email'),
	(1,'site','smtp_host','','email'),
	(1,'site','smtp_port','587','email'),
	(1,'site','smtp_encryption','tls','email'),
	(1,'site','debug','1','email'),
	(1,'site','sender_name','Admin','email'),
	(1,'site','sender_email','demoadmin@cmsgears.com','email'),
	(1,'site','contact_name','Contact Us','email'),
	(1,'site','contact_email','democontact@cmsgears.com','email'),
	(1,'site','info_name','Info','email'),
	(1,'site','info_email','demoinfo@cmsgears.com','email'),
	(1,'site','theme','basic','frontend'),
	(1,'site','theme_version','1','frontend'),
	(1,'site','theme','basic','backend'),
	(1,'site','theme_version','1','backend');

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