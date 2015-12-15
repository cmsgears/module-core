/* ============================= CMSGears Core ============================================== */

SET FOREIGN_KEY_CHECKS=0;

--
-- Main Site
--

INSERT INTO `cmg_core_site` (`avatarId`,`bannerId`,`name`,`slug`,`order`,`active`) VALUES
	(NULL,NULL,'main','main',0,1);

SELECT @site := `id` FROM cmg_core_site WHERE slug = 'main';

--
-- Core Config Form
--

INSERT INTO `cmg_core_form` (`siteId`,`templateId`,`createdBy`,`modifiedBy`,`name`,`slug`,`type`,`description`,`successMessage`,`captcha`,`visibility`,`active`,`userMail`,`adminMail`,`options`,`createdAt`,`modifiedAt`) VALUES
	(@site,NULL,1,1,'Config Core','config-core','system','Core configuration form.','All configurations saved successfully.',0,10,1,0,0,NULL,'2014-10-11 14:22:54','2014-10-11 14:22:54');

SELECT @form := `id` FROM cmg_core_form WHERE slug = 'config-core';

INSERT INTO `cmg_core_form_field` (`formId`,`name`,`label`,`type`,`compress`,`validators`,`options`,`data`,`order`) VALUES 
	(@form,'locale_message','Locale Message',20,0,'required','{\"title\":\"Check for i18n support.\"}',NULL,0),
	(@form,'language','Language',0,0,'required','{\"title\":\"Language used on html tag.\",\"placeholder\":\"Language\"}',NULL,0),
	(@form,'locale','Locale',0,0,'required','{\"title\":\"Site default locale.\",\"placeholder\":\"Locale\"}',NULL,0),
	(@form,'charset','Charset',0,0,'required','{\"title\":\"Charset used on html head meta.\",\"placeholder\":\"Charset\"}',NULL,0),
	(@form,'site_title','Site Title',0,0,'required','{\"title\":\"Site title used in forming page title.\",\"placeholder\":\"Site Title\"}',NULL,0),
	(@form,'site_name','Site Name',0,0,'required','{\"title\":\"Site name used on footers etc.\",\"placeholder\":\"Site Name\"}',NULL,0),
	(@form,'site_url','Frontend URL',0,0,'required','{\"title\":\"Frontend URL\",\"placeholder\":\"Frontend URL\"}',NULL,0),
	(@form,'admin_url','Backend URL',0,0,'required','{\"title\":\"Backend URL\",\"placeholder\":\"Backend URL\"}',NULL,0),
	(@form,'registration','Registration',20,0,'required','{\"title\":\"Check whether site registration is allowed.\"}',NULL,0),
	(@form,'change_email','Change Email',20,0,'required','{\"title\":\"Check whether email change is allowed for user profile.\"}',NULL,0),
	(@form,'change_username','Change Username',20,0,'required','{\"title\":\"Check whether username change is allowed for user profile.\"}',NULL,0);

--
-- Mail Config Form
--

INSERT INTO `cmg_core_form` (`siteId`,`templateId`,`createdBy`,`modifiedBy`,`name`,`slug`,`type`,`description`,`successMessage`,`captcha`,`visibility`,`active`,`userMail`,`adminMail`,`options`,`createdAt`,`modifiedAt`) VALUES
	(@site,NULL,1,1,'Config Mail','config-mail','system','Mail configuration form.','All configurations saved successfully.',0,10,1,0,0,NULL,'2014-10-11 14:22:54','2014-10-11 14:22:54');

SELECT @form := `id` FROM cmg_core_form WHERE slug = 'config-mail';

INSERT INTO `cmg_core_form_field` (`formId`,`name`,`label`,`type`,`compress`,`validators`,`options`,`data`,`order`) VALUES 
	(@form,'smtp','SMTP',20,0,'required','{\"title\":\"Check whether SMTP is required.\"}',NULL,0),
	(@form,'smtp_username','SMTP Username',0,0,NULL,'{\"title\":\"SMTP username.\",\"placeholder\":\"SMTP Username\"}',NULL,0),
	(@form,'smtp_password','SMTP Password',5,0,NULL,'{\"title\":\"SMTP password.\",\"placeholder\":\"SMTP Password\"}',NULL,0),
	(@form,'smtp_host','SMTP Host',0,0,NULL,'{\"title\":\"SMTP host.\",\"placeholder\":\"SMTP Host\"}',NULL,0),
	(@form,'smtp_port','SMTP Port',0,0,NULL,'{\"title\":\"SMTP port.\",\"placeholder\":\"SMTP Port\"}',NULL,0),
	(@form,'smtp_encryption','SMTP Encryption',0,0,NULL,'{\"title\":\"SMTP encryption.\",\"placeholder\":\"SMTP Encryption\"}',NULL,0),
	(@form,'debug','SMTP Debug',20,0,'required','{\"title\":\"Check whether SMTP debug is required.\"}',NULL,0),
	(@form,'sender_name','Sender Name',0,0,'required','{\"title\":\"Sender name.\",\"placeholder\":\"Sender Name\"}',NULL,0),
	(@form,'sender_email','Sender Email',0,0,'required','{\"title\":\"Sender email.\",\"placeholder\":\"Sender Email\"}',NULL,0),
	(@form,'contact_name','Contact Name',0,0,'required','{\"title\":\"Contact name.\",\"placeholder\":\"Contact Name\"}',NULL,0),
	(@form,'contact_email','Contact Email',0,0,'required','{\"title\":\"Contact email.\",\"placeholder\":\"Contact Email\"}',NULL,0),
	(@form,'info_name','Info Name',0,0,'required','{\"title\":\"Info name.\",\"placeholder\":\"Info Name\"}',NULL,0),
	(@form,'info_email','Info Email',0,0,'required','{\"title\":\"Info email.\",\"placeholder\":\"Info Email\"}',NULL,0);

--
-- Backend Config Form
--

INSERT INTO `cmg_core_form` (`siteId`,`templateId`,`createdBy`,`modifiedBy`,`name`,`slug`,`type`,`description`,`successMessage`,`captcha`,`visibility`,`active`,`userMail`,`adminMail`,`options`,`createdAt`,`modifiedAt`) VALUES
	(@site,NULL,1,1,'Config Backend','config-backend','system','Backend site configuration form.','All configurations saved successfully.',0,10,1,0,0,NULL,'2014-10-11 14:22:54','2014-10-11 14:22:54');

SELECT @form := `id` FROM cmg_core_form WHERE slug = 'config-backend';

INSERT INTO `cmg_core_form_field` (`formId`,`name`,`label`,`type`,`compress`,`validators`,`options`,`data`,`order`) VALUES 
	(@form,'theme','Theme',0,0,'required','{\"title\":\"Current theme.\",\"placeholder\":\"Theme\"}',NULL,0),
	(@form,'theme_version','Theme Version',0,0,'required','{\"title\":\"Theme version.\",\"placeholder\":\"Theme Version\"}',NULL,0);

--
-- Frontend Config Form
--

INSERT INTO `cmg_core_form` (`siteId`,`templateId`,`createdBy`,`modifiedBy`,`name`,`slug`,`type`,`description`,`successMessage`,`captcha`,`visibility`,`active`,`userMail`,`adminMail`,`options`,`createdAt`,`modifiedAt`) VALUES
	(@site,NULL,1,1,'Config Site','config-frontend','system','Frontend site configuration form.','All configurations saved successfully.',0,10,1,0,0,NULL,'2014-10-11 14:22:54','2014-10-11 14:22:54');

SELECT @form := `id` FROM cmg_core_form WHERE slug = 'config-frontend';

INSERT INTO `cmg_core_form_field` (`formId`,`name`,`label`,`type`,`compress`,`validators`,`options`,`data`,`order`) VALUES 
	(@form,'theme','Theme',0,0,'required','{\"title\":\"Current theme.\",\"placeholder\":\"Theme\"}',NULL,0),
	(@form,'theme_version','Theme Version',0,0,'required','{\"title\":\"Theme version.\",\"placeholder\":\"Theme Version\"}',NULL,0);

--
-- Dumping data for table `cmg_core_model_attribute`
--

INSERT INTO `cmg_core_model_attribute` (`parentId`,`parentType`,`name`,`value`,`type`) VALUES
	(@site,'site','locale_message','0','core'),
	(@site,'site','language','en-US','core'),
	(@site,'site','locale','en_US','core'),
	(@site,'site','charset','UTF-8','core'),
	(@site,'site','site_title','CMG Demo','core'),
	(@site,'site','site_name','CMSGears','core'),
	(@site,'site','site_url','http://demo.cmsgears.com/templates/basic/','core'),
	(@site,'site','admin_url','http://demo.cmsgears.com/templates/basic/admin/','core'),
	(@site,'site','registration','1','core'),
	(@site,'site','change_email','1','core'),
	(@site,'site','change_username','1','core'),
	(@site,'site','smtp','0','mail'),
	(@site,'site','smtp_username','','mail'),
	(@site,'site','smtp_password','','mail'),
	(@site,'site','smtp_host','','mail'),
	(@site,'site','smtp_port','587','mail'),
	(@site,'site','smtp_encryption','tls','mail'),
	(@site,'site','debug','1','mail'),
	(@site,'site','sender_name','Admin','mail'),
	(@site,'site','sender_email','demoadmin@cmsgears.com','mail'),
	(@site,'site','contact_name','Contact Us','mail'),
	(@site,'site','contact_email','democontact@cmsgears.com','mail'),
	(@site,'site','info_name','Info','mail'),
	(@site,'site','info_email','demoinfo@cmsgears.com','mail'),
	(@site,'site','theme','basic','frontend'),
	(@site,'site','theme_version','1','frontend'),
	(@site,'site','theme','admin','backend'),
	(@site,'site','theme_version','1','backend');

--
-- Default Locale
--

INSERT INTO `cmg_core_locale` (`code`,`name`) VALUES 
	('en_US','English US');

--
-- Default Categories and their options
--

INSERT INTO `cmg_core_category` (`avatarId`,`parentId`,`name`,`description`,`slug`,`type`,`icon`,`featured`) VALUES  
	(NULL,NULL,'gender',NULL,'gender','combo',NULL,0);

SELECT @category := `id` FROM cmg_core_category WHERE slug = 'gender';

INSERT INTO `cmg_core_option` (`categoryId`,`name`,`value`,`icon`,`data`) VALUES 
	(@category,'Male','Male',NULL,NULL),
	(@category,'Female','Female',NULL,NULL),
	(@category,'Other','Other',NULL,NULL);

--
-- Core module roles and permissions
--

INSERT INTO `cmg_core_role` (`createdBy`,`modifiedBy`,`name`,`slug`,`homeUrl`,`type`,`description`,`icon`,`createdAt`,`modifiedAt`) VALUES
	(1,1,'Super Admin','super-admin','dashboard','system','The Super Admin have all the permisisons to perform operations on the admin site and website.',NULL,'2014-10-11 14:22:54','2014-10-11 14:22:54'),
	(1,1,'Admin','admin','dashboard','system','The Admin have all the permisisons to perform operations on the admin site and website except RBAC module.',NULL,'2014-10-11 14:22:54','2014-10-11 14:22:54'),
	(1,1,'User','user',NULL,'system','The role User is limited to website users.',NULL,'2014-10-11 14:22:54','2014-10-11 14:22:54'),
	(1,1,'User Manager','user-manager','dashboard','system','The role User Manager is limited to manage site users from admin.',NULL,'2014-10-11 14:22:54','2014-10-11 14:22:54');

SELECT @rolesadmin := `id` FROM cmg_core_role WHERE slug = 'super-admin';
SELECT @roleadmin := `id` FROM cmg_core_role WHERE slug = 'admin';
SELECT @roleuser := `id` FROM cmg_core_role WHERE slug = 'user';
SELECT @roleuserm := `id` FROM cmg_core_role WHERE slug = 'user-manager';

INSERT INTO `cmg_core_permission` (`createdBy`,`modifiedBy`,`name`,`slug`,`type`,`description`,`icon`,`createdAt`,`modifiedAt`) VALUES 
	(1,1,'Admin','admin','system','The permission admin is to distinguish between admin and site user. It is a must have permission for admins.',NULL,'2014-10-11 14:22:54','2014-10-11 14:22:54'),
	(1,1,'User','user','system','The permission user is to distinguish between admin and site user. It is a must have permission for users.',NULL,'2014-10-11 14:22:54','2014-10-11 14:22:54'),
	(1,1,'Core','core','system','The permission core is to manage settings, drop downs, world countries, galleries and newsletters from admin.',NULL,'2014-10-11 14:22:54','2014-10-11 14:22:54'),
	(1,1,'Identity','identity','system','The permission identity is to manage users from admin.',NULL,'2014-10-11 14:22:54','2014-10-11 14:22:54'),
	(1,1,'RBAC','rbac','system','The permission rbac is to manage roles and permissions from admin.',NULL,'2014-10-11 14:22:54','2014-10-11 14:22:54');

SELECT @permadmin := `id` FROM cmg_core_permission WHERE slug = 'admin';
SELECT @permuser := `id` FROM cmg_core_permission WHERE slug = 'user';
SELECT @permcore := `id` FROM cmg_core_permission WHERE slug = 'core';
SELECT @permidentity := `id` FROM cmg_core_permission WHERE slug = 'identity';
SELECT @permrbac := `id` FROM cmg_core_permission WHERE slug = 'rbac';

INSERT INTO `cmg_core_role_permission` VALUES 
	(@rolesadmin,@permadmin),(@rolesadmin,@permuser),(@rolesadmin,@permcore),(@rolesadmin,@permidentity),(@rolesadmin,@permrbac),
	(@roleadmin,@permadmin),(@roleadmin,@permuser),(@roleadmin,@permcore),
	(@roleuser,@permuser),
	(@roleuserm,@permadmin),(@roleuserm,@permuser),(@roleuserm,@permidentity);

--
-- Default site users
--

INSERT INTO `cmg_core_user` VALUES 
	(1,NULL,NULL,NULL,NULL,500,'demomaster@cmsgears.com','demomaster','$2y$13$Ut5b2RskRpGA9Q0nKSO6Xe65eaBHdx/q8InO8Ln6Lt3HzOK4ECz8W','demo','master',NULL,NULL,NULL,NULL,'2014-10-11 14:22:54','2014-10-10 08:03:19',NULL,'JuL37UBqGpjnA7kaPiRnlsiWRwbRvXx7',NULL,NULL,NULL),
	(2,NULL,NULL,NULL,NULL,500,'demoadmin@cmsgears.com','demoadmin','$2y$13$Ut5b2RskRpGA9Q0nKSO6Xe65eaBHdx/q8InO8Ln6Lt3HzOK4ECz8W','demo','admin',NULL,NULL,NULL,NULL,'2014-10-11 14:22:54','2014-10-10 08:03:19',NULL,'SQ1LLCWEPva4IKuQklILLGDpmUTGzq8E',NULL,NULL,NULL),
	(3,NULL,NULL,NULL,NULL,500,'demouser@cmsgears.com','demouser','$2y$13$Ut5b2RskRpGA9Q0nKSO6Xe65eaBHdx/q8InO8Ln6Lt3HzOK4ECz8W','demo','user',NULL,NULL,NULL,NULL,'2014-10-11 14:22:54','2014-10-10 08:03:19',NULL,'-jG5ExHS0Y39ucSxHhl3PZ4xmPsfvQFC',NULL,NULL,NULL);

--
-- Default site members
--

INSERT INTO `cmg_core_site_member`(`siteId`,`userId`,`roleId`,`createdAt`,`modifiedAt`) VALUES
	(@site,1,@rolesadmin,'2014-10-11 14:22:54','2014-10-11 14:22:54'),
	(@site,2,@roleadmin,'2014-10-11 14:22:54','2014-10-11 14:22:54'),
	(@site,3,@roleuser,'2014-10-11 14:22:54','2014-10-11 14:22:54');

SET FOREIGN_KEY_CHECKS=1;