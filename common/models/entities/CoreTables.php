<?php
namespace cmsgears\core\common\models\entities;

class CoreTables {

	// Variables ---------------------------------------------------

	// Static Variables --------------------

	// Basic
	const TABLE_CONFIG				= "cmg_config";
	const TABLE_FILE				= "cmg_file";
	const TABLE_LOCALE				= "cmg_locale";
	const TABLE_LOCALE_MESSAGE		= "cmg_locale_message";
	const TABLE_CATEGORY			= "cmg_category";
	const TABLE_OPTION				= "cmg_option";

	// Address
	const TABLE_COUNTRY				= "cmg_country";
	const TABLE_PROVINCE			= "cmg_province";
	const TABLE_ADDRESS				= "cmg_address";

	// RBAC
	const TABLE_ROLE				= "cmg_role";
	const TABLE_PERMISSION			= "cmg_permission";
	const TABLE_ROLE_PERMISSION		= "cmg_role_permission";

	// User
	const TABLE_USER				= "cmg_user";

	// Marketing
	const TABLE_NEWSLETTER			= "cmg_newsletter";

	// Messages
	const TABLE_NOTIFICATION		= "cmg_notification";
	const TABLE_REMINDER			= "cmg_reminder";

	// Traits
	const TABLE_MODEL_CATEGORY		= "cmg_model_category";
	const TABLE_MODEL_META			= "cmg_model_meta";
	const TABLE_MODEL_FILE			= "cmg_model_file";
	const TABLE_MODEL_TAG			= "cmg_model_tag";
}

?>