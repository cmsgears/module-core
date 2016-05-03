/* ============================= CMSGears Core ============================================== */

SET FOREIGN_KEY_CHECKS=0;

--
-- Main Site
--

INSERT INTO `cmg_core_site` (`avatarId`,`bannerId`,`themeId`,`name`,`slug`,`order`,`active`) VALUES
	(NULL,NULL,NULL,'main','main',0,1);

SELECT @site := `id` FROM cmg_core_site WHERE slug = 'main';

--
-- Core Config Form
--

INSERT INTO `cmg_core_form` (`siteId`,`templateId`,`createdBy`,`modifiedBy`,`name`,`slug`,`type`,`description`,`successMessage`,`captcha`,`visibility`,`active`,`userMail`,`adminMail`,`createdAt`,`modifiedAt`,`htmlOptions`,`data`) VALUES
	(@site,NULL,1,1,'Config Core','config-core','system','Core configuration form.','All configurations saved successfully.',0,10,1,0,0,'2014-10-11 14:22:54','2014-10-11 14:22:54',NULL,NULL);

SELECT @form := `id` FROM cmg_core_form WHERE slug = 'config-core';

INSERT INTO `cmg_core_form_field` (`formId`,`name`,`label`,`type`,`compress`,`validators`,`order`,`icon`,`htmlOptions`,`data`) VALUES 
	(@form,'locale_message','Locale Message',40,0,'required',0,NULL,'{\"title\":\"Check for i18n support.\"}',NULL),
	(@form,'language','Language',0,0,'required',0,NULL,'{\"title\":\"Language used on html tag.\",\"placeholder\":\"Language\"}',NULL),
	(@form,'locale','Locale',0,0,'required',0,NULL,'{\"title\":\"Site default locale.\",\"placeholder\":\"Locale\"}',NULL),
	(@form,'charset','Charset',0,0,'required',0,NULL,'{\"title\":\"Charset used on html head meta.\",\"placeholder\":\"Charset\"}',NULL),
	(@form,'site_title','Site Title',0,0,'required',0,NULL,'{\"title\":\"Site title used in forming page title.\",\"placeholder\":\"Site Title\"}',NULL),
	(@form,'site_name','Site Name',0,0,'required',0,NULL,'{\"title\":\"Site name used on footers etc.\",\"placeholder\":\"Site Name\"}',NULL),
	(@form,'site_url','Frontend URL',0,0,'required',0,NULL,'{\"title\":\"Frontend URL\",\"placeholder\":\"Frontend URL\"}',NULL),
	(@form,'admin_url','Backend URL',0,0,'required',0,NULL,'{\"title\":\"Backend URL\",\"placeholder\":\"Backend URL\"}',NULL),
	(@form,'registration','Registration',40,0,'required',0,NULL,'{\"title\":\"Check whether site registration is allowed.\"}',NULL),
	(@form,'change_email','Change Email',40,0,'required',0,NULL,'{\"title\":\"Check whether email change is allowed for user profile.\"}',NULL),
	(@form,'change_username','Change Username',40,0,'required',0,NULL,'{\"title\":\"Check whether username change is allowed for user profile.\"}',NULL),
	(@form,'date_format','Date Format',0,0,'required',0,NULL,'{\"title\":\"Date format used by the formatter.\",\"placeholder\":\"Date Format\"}',NULL),
	(@form,'time_format','Time Format',0,0,'required',0,NULL,'{\"title\":\"Time format used by the formatter.\",\"placeholder\":\"Time Format\"}',NULL),
	(@form,'timezone','Timezone',0,0,'required',0,NULL,'{\"title\":\"Time format used by the formatter.\",\"placeholder\":\"Time Format\"}',NULL);

--
-- Mail Config Form
--

INSERT INTO `cmg_core_form` (`siteId`,`templateId`,`createdBy`,`modifiedBy`,`name`,`slug`,`type`,`description`,`successMessage`,`captcha`,`visibility`,`active`,`userMail`,`adminMail`,`createdAt`,`modifiedAt`,`htmlOptions`,`data`) VALUES
	(@site,NULL,1,1,'Config Mail','config-mail','system','Mail configuration form.','All configurations saved successfully.',0,10,1,0,0,'2014-10-11 14:22:54','2014-10-11 14:22:54',NULL,NULL);

SELECT @form := `id` FROM cmg_core_form WHERE slug = 'config-mail';

INSERT INTO `cmg_core_form_field` (`formId`,`name`,`label`,`type`,`compress`,`validators`,`order`,`icon`,`htmlOptions`,`data`) VALUES 
	(@form,'smtp','SMTP',40,0,'required',0,NULL,'{\"title\":\"Check whether SMTP is required.\"}',NULL),
	(@form,'smtp_username','SMTP Username',0,0,NULL,0,NULL,'{\"title\":\"SMTP username.\",\"placeholder\":\"SMTP Username\"}',NULL),
	(@form,'smtp_password','SMTP Password',10,0,NULL,0,NULL,'{\"title\":\"SMTP password.\",\"placeholder\":\"SMTP Password\"}',NULL),
	(@form,'smtp_host','SMTP Host',0,0,NULL,0,NULL,'{\"title\":\"SMTP host.\",\"placeholder\":\"SMTP Host\"}',NULL),
	(@form,'smtp_port','SMTP Port',0,0,NULL,0,NULL,'{\"title\":\"SMTP port.\",\"placeholder\":\"SMTP Port\"}',NULL),
	(@form,'smtp_encryption','SMTP Encryption',0,0,NULL,0,NULL,'{\"title\":\"SMTP encryption.\",\"placeholder\":\"SMTP Encryption\"}',NULL),
	(@form,'debug','SMTP Debug',40,0,'required',0,NULL,'{\"title\":\"Check whether SMTP debug is required.\"}',NULL),
	(@form,'sender_name','Sender Name',0,0,'required',0,NULL,'{\"title\":\"Sender name.\",\"placeholder\":\"Sender Name\"}',NULL),
	(@form,'sender_email','Sender Email',0,0,'required',0,NULL,'{\"title\":\"Sender email.\",\"placeholder\":\"Sender Email\"}',NULL),
	(@form,'contact_name','Contact Name',0,0,'required',0,NULL,'{\"title\":\"Contact name.\",\"placeholder\":\"Contact Name\"}',NULL),
	(@form,'contact_email','Contact Email',0,0,'required',0,NULL,'{\"title\":\"Contact email.\",\"placeholder\":\"Contact Email\"}',NULL),
	(@form,'info_name','Info Name',0,0,'required',0,NULL,'{\"title\":\"Info name.\",\"placeholder\":\"Info Name\"}',NULL),
	(@form,'info_email','Info Email',0,0,'required',0,NULL,'{\"title\":\"Info email.\",\"placeholder\":\"Info Email\"}',NULL);

--
-- Backend Config Form
--

INSERT INTO `cmg_core_form` (`siteId`,`templateId`,`createdBy`,`modifiedBy`,`name`,`slug`,`type`,`description`,`successMessage`,`captcha`,`visibility`,`active`,`userMail`,`adminMail`,`createdAt`,`modifiedAt`,`htmlOptions`,`data`) VALUES
	(@site,NULL,1,1,'Config Backend','config-backend','system','Backend site configuration form.','All configurations saved successfully.',0,10,1,0,0,'2014-10-11 14:22:54','2014-10-11 14:22:54',NULL,NULL);

SELECT @form := `id` FROM cmg_core_form WHERE slug = 'config-backend';

-- INSERT INTO `cmg_core_form_field` (`formId`,`name`,`label`,`type`,`compress`,`validators`,`order`,`icon`,`htmlOptions`,`data`) VALUES 
--	(@form,NULL,NULL,0,0,'required',0,NULL,'{\"title\":\"Current theme.\",\"placeholder\":\"Theme\"}',NULL);

--
-- Frontend Config Form
--

INSERT INTO `cmg_core_form` (`siteId`,`templateId`,`createdBy`,`modifiedBy`,`name`,`slug`,`type`,`description`,`successMessage`,`captcha`,`visibility`,`active`,`userMail`,`adminMail`,`createdAt`,`modifiedAt`,`htmlOptions`,`data`) VALUES
	(@site,NULL,1,1,'Config Site','config-frontend','system','Frontend site configuration form.','All configurations saved successfully.',0,10,1,0,0,'2014-10-11 14:22:54','2014-10-11 14:22:54',NULL,NULL);

SELECT @form := `id` FROM cmg_core_form WHERE slug = 'config-frontend';

-- INSERT INTO `cmg_core_form_field` (`formId`,`name`,`label`,`type`,`compress`,`validators`,`order`,`icon`,`htmlOptions`,`data`) VALUES 
--	(@form,NULL,NULL,0,0,'required',0,NULL,'{\"title\":\"Current theme.\",\"placeholder\":\"Theme\"}',NULL);

--
-- Dumping data for table `cmg_core_model_attribute`
--

INSERT INTO `cmg_core_model_attribute` (`parentId`,`parentType`,`name`,`label`,`type`,`valueType`,`value`) VALUES
	(@site,'site','locale_message','Locale Message','core','flag','0'),
	(@site,'site','language','Language','core','text','en-US'),
	(@site,'site','locale','Locale','core','text','en_US'),
	(@site,'site','charset','Charset','core','text','UTF-8'),
	(@site,'site','site_title','Site Title','core','text','CMSGears Demo'),
	(@site,'site','site_name','Site Name','core','text','CMSGears'),
	(@site,'site','site_url','Site Url','core','text','http://demo.cmsgears.com/template/basic/'),
	(@site,'site','admin_url','Admin Url','core','text','http://demo.cmsgears.com/template/basic/admin/'),
	(@site,'site','registration','Registration','core','flag','1'),
	(@site,'site','change_email','Change Email','core','flag','1'),
	(@site,'site','change_username','Change Username','core','flag','1'),
	(@site,'site','date_format','Date Format','core','text','yyyy-MM-dd'),
	(@site,'site','time_format','Time Format','core','text','HH:mm:ss'),
	(@site,'site','timezone','Timezone','core','text','UTC+0'),
	(@site,'site','smtp','SMTP','mail','flag','0'),
	(@site,'site','smtp_username','SMTP Username','mail','text',''),
	(@site,'site','smtp_password','SMTP Password','mail','text',''),
	(@site,'site','smtp_host','SMTP Host','mail','text',''),
	(@site,'site','smtp_port','SMTP Port','mail','text','587'),
	(@site,'site','smtp_encryption','SMTP Encryption','mail','text','tls'),
	(@site,'site','debug','Debug','mail','flag','1'),
	(@site,'site','sender_name','Sender Name','mail','text','Admin'),
	(@site,'site','sender_email','Sender Email','mail','text','demoadmin@cmsgears.com'),
	(@site,'site','contact_name','Contact Name','mail','text','Contact Us'),
	(@site,'site','contact_email','Contact Email','mail','text','democontact@cmsgears.com'),
	(@site,'site','info_name','Info Name','mail','text','Info'),
	(@site,'site','info_email','Info Email','mail','text','demoinfo@cmsgears.com');

--
-- Default Locale
--

INSERT INTO `cmg_core_locale` (`code`,`name`) VALUES 
	('en_US','English US');

--
-- Default Categories and their options
--

INSERT INTO `cmg_core_category` (`siteId`,`parentId`,`rootId`,`name`,`slug`,`description`,`type`,`icon`,`featured`,`lValue`,`rValue`,`htmlOptions`,`data`) VALUES  
	(@site,NULL,NULL,'Gender','gender',NULL,'combo',NULL,0,1,2,NULL,NULL);

SELECT @category := `id` FROM cmg_core_category WHERE slug = 'gender';

INSERT INTO `cmg_core_option` (`categoryId`,`name`,`value`,`icon`,`htmlOptions`,`data`) VALUES 
	(@category,'Male','Male',NULL,NULL,NULL),
	(@category,'Female','Female',NULL,NULL,NULL),
	(@category,'Other','Other',NULL,NULL,NULL);

--
-- Core module roles and permissions
--

INSERT INTO `cmg_core_role` (`createdBy`,`modifiedBy`,`name`,`slug`,`homeUrl`,`type`,`icon`,`description`,`createdAt`,`modifiedAt`) VALUES
	(1,1,'Super Admin','super-admin','dashboard','system',NULL,'The Super Admin have all the permisisons to perform operations on the admin site and website.','2014-10-11 14:22:54','2014-10-11 14:22:54'),
	(1,1,'Admin','admin','dashboard','system',NULL,'The Admin have all the permisisons to perform operations on the admin site and website except RBAC module.','2014-10-11 14:22:54','2014-10-11 14:22:54'),
	(1,1,'User','user',NULL,'system',NULL,'The role User is limited to website users.','2014-10-11 14:22:54','2014-10-11 14:22:54'),
	(1,1,'User Manager','user-manager','dashboard','system',NULL,'The role User Manager is limited to manage site users from admin.','2014-10-11 14:22:54','2014-10-11 14:22:54');

SELECT @rolesadmin := `id` FROM cmg_core_role WHERE slug = 'super-admin';
SELECT @roleadmin := `id` FROM cmg_core_role WHERE slug = 'admin';
SELECT @roleuser := `id` FROM cmg_core_role WHERE slug = 'user';
SELECT @roleuserm := `id` FROM cmg_core_role WHERE slug = 'user-manager';

INSERT INTO `cmg_core_permission` (`createdBy`,`modifiedBy`,`name`,`slug`,`type`,`icon`,`description`,`createdAt`,`modifiedAt`) VALUES 
	(1,1,'Admin','admin','system',NULL,'The permission admin is to distinguish between admin and site user. It is a must have permission for admins.','2014-10-11 14:22:54','2014-10-11 14:22:54'),
	(1,1,'User','user','system',NULL,'The permission user is to distinguish between admin and site user. It is a must have permission for users.','2014-10-11 14:22:54','2014-10-11 14:22:54'),
	(1,1,'Core','core','system',NULL,'The permission core is to manage settings, drop downs, world countries, galleries and newsletters from admin.','2014-10-11 14:22:54','2014-10-11 14:22:54'),
	(1,1,'Identity','identity','system',NULL,'The permission identity is to manage users from admin.','2014-10-11 14:22:54','2014-10-11 14:22:54'),
	(1,1,'RBAC','rbac','system',NULL,'The permission rbac is to manage roles and permissions from admin.','2014-10-11 14:22:54','2014-10-11 14:22:54');

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

INSERT INTO `cmg_core_user` (`localeId`,`genderId`,`avatarId`,`status`,`email`,`username`,`passwordHash`,`firstName`,`lastName`,`dob`,`phone`,`verifyToken`,`resetToken`,`registeredAt`,`lastLoginAt`,`lastActivityAt`,`authKey`) VALUES 
	(NULL,NULL,NULL,750,'demomaster@cmsgears.com','demomaster','$2y$13$Ut5b2RskRpGA9Q0nKSO6Xe65eaBHdx/q8InO8Ln6Lt3HzOK4ECz8W','demo','master',NULL,NULL,NULL,NULL,'2014-10-11 14:22:54','2014-10-10 08:03:19',NULL,'JuL37UBqGpjnA7kaPiRnlsiWRwbRvXx7'),
	(NULL,NULL,NULL,750,'demoadmin@cmsgears.com','demoadmin','$2y$13$Ut5b2RskRpGA9Q0nKSO6Xe65eaBHdx/q8InO8Ln6Lt3HzOK4ECz8W','demo','admin',NULL,NULL,NULL,NULL,'2014-10-11 14:22:54','2014-10-10 08:03:19',NULL,'SQ1LLCWEPva4IKuQklILLGDpmUTGzq8E'),
	(NULL,NULL,NULL,750,'demouser@cmsgears.com','demouser','$2y$13$Ut5b2RskRpGA9Q0nKSO6Xe65eaBHdx/q8InO8Ln6Lt3HzOK4ECz8W','demo','user',NULL,NULL,NULL,NULL,'2014-10-11 14:22:54','2014-10-10 08:03:19',NULL,'-jG5ExHS0Y39ucSxHhl3PZ4xmPsfvQFC');

--
-- Default site members
--

INSERT INTO `cmg_core_site_member`(`siteId`,`userId`,`roleId`,`createdAt`,`modifiedAt`) VALUES
	(@site,1,@rolesadmin,'2014-10-11 14:22:54','2014-10-11 14:22:54'),
	(@site,2,@roleadmin,'2014-10-11 14:22:54','2014-10-11 14:22:54'),
	(@site,3,@roleuser,'2014-10-11 14:22:54','2014-10-11 14:22:54');

SET FOREIGN_KEY_CHECKS=1;