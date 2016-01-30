<?php
namespace cmsgears\core\common\models\entities;

class CoreTables {

	// Variables ---------------------------------------------------

	// Static Variables --------------------

	// Basic
	const TABLE_LOCALE				= 'cmg_core_locale';
	const TABLE_OBJECT_DATA			= 'cmg_core_object';
	const TABLE_THEME				= 'cmg_core_theme';
	const TABLE_TEMPLATE			= 'cmg_core_template';
	const TABLE_TAG					= 'cmg_core_tag';
	const TABLE_CATEGORY			= 'cmg_core_category';
	const TABLE_OPTION				= 'cmg_core_option';
	const TABLE_FILE				= 'cmg_core_file';

	// Address
	const TABLE_COUNTRY				= 'cmg_core_country';
	const TABLE_PROVINCE			= 'cmg_core_province';
	const TABLE_ADDRESS				= 'cmg_core_address';

	// RBAC
	const TABLE_ROLE				= 'cmg_core_role';
	const TABLE_PERMISSION			= 'cmg_core_permission';
	const TABLE_ROLE_PERMISSION		= 'cmg_core_role_permission';

	// User
	const TABLE_USER				= 'cmg_core_user';

	// Marketing
	const TABLE_NEWSLETTER			= 'cmg_core_newsletter';
	const TABLE_NEWSLETTER_MEMBER	= 'cmg_core_newsletter_member';
	const TABLE_NEWSLETTER_LIST		= 'cmg_core_newsletter_list';

	// User - Notification, Reminder and Actions
	const TABLE_ACTIVITY			= 'cmg_core_activity';

	// Image Gallery, Portfolio
	const TABLE_GALLERY				= 'cmg_core_gallery';

	// Site, Multi Site
	const TABLE_SITE				= 'cmg_core_site';
	const TABLE_SITE_MEMBER			= 'cmg_core_site_member';

	//Forms
	const TABLE_FORM				= 'cmg_core_form';
	const TABLE_FORM_FIELD			= 'cmg_core_form_field';

	// Model Traits
	const TABLE_MODEL_MESSAGE		= 'cmg_core_model_message';
	const TABLE_MODEL_CATEGORY		= 'cmg_core_model_category';
	const TABLE_MODEL_ATTRIBUTE		= 'cmg_core_model_attribute';
	const TABLE_MODEL_FILE			= 'cmg_core_model_file';
	const TABLE_MODEL_TAG			= 'cmg_core_model_tag';
	const TABLE_MODEL_ADDRESS		= 'cmg_core_model_address';
	const TABLE_MODEL_COMMENT		= 'cmg_core_model_comment';
	const TABLE_MODEL_FORM			= 'cmg_core_model_form';
}

?>