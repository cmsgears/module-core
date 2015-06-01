<?php
namespace cmsgears\core\common\models\entities;

class CoreTables {

	// Variables ---------------------------------------------------

	// Static Variables --------------------

	// Basic
	const TABLE_LOCALE				= "cmg_core_locale";
	const TABLE_TAG					= "cmg_core_tag";
	const TABLE_CATEGORY			= "cmg_core_category";
	const TABLE_OPTION				= "cmg_core_option";
	const TABLE_FILE				= "cmg_core_file";

	// Address
	const TABLE_COUNTRY				= "cmg_core_country";
	const TABLE_PROVINCE			= "cmg_core_province";
	const TABLE_ADDRESS				= "cmg_core_address";

	// RBAC
	const TABLE_ROLE				= "cmg_core_role";
	const TABLE_PERMISSION			= "cmg_core_permission";
	const TABLE_ROLE_PERMISSION		= "cmg_core_role_permission";

	// User
	const TABLE_USER				= "cmg_core_user";

	// Marketing
	const TABLE_NEWSLETTER			= "cmg_core_newsletter";

	// User Messages
	const TABLE_NOTIFICATION		= "cmg_core_notification";
	const TABLE_REMINDER			= "cmg_core_reminder";
	const TABLE_ACTIVITY			= "cmg_core_activity";

	// Image Gallery
	const TABLE_GALLERY				= "cmg_core_gallery";

	// Site
	const TABLE_SITE				= "cmg_core_site";
	const TABLE_SITE_MEMBER			= "cmg_core_site_member";

	// Traits
	const TABLE_MODEL_MESSAGE		= "cmg_core_model_message";
	const TABLE_MODEL_CATEGORY		= "cmg_core_model_category";
	const TABLE_MODEL_META			= "cmg_core_model_meta";
	const TABLE_MODEL_FILE			= "cmg_core_model_file";
	const TABLE_MODEL_TAG			= "cmg_core_model_tag";
	const TABLE_MODEL_ADDRESS		= "cmg_core_model_address";
}

?>