<?php
namespace cmsgears\core\common\models\base;

/**
 * Tables provided by Core Module.
 */
class CoreTables {

	// Entities -------------

	// Basic
	const TABLE_LOCALE				= 'cmg_core_locale';
	const TABLE_THEME				= 'cmg_core_theme';
	const TABLE_TEMPLATE			= 'cmg_core_template';
	const TABLE_OBJECT_DATA			= 'cmg_core_object';

	// Address
	const TABLE_COUNTRY				= 'cmg_core_country';
	const TABLE_PROVINCE			= 'cmg_core_province';
	const TABLE_CITY				= 'cmg_core_city';

	// RBAC
	const TABLE_ROLE				= 'cmg_core_role';
	const TABLE_PERMISSION			= 'cmg_core_permission';

	// User
	const TABLE_USER				= 'cmg_core_user';

	// Site, Multi Site
	const TABLE_SITE				= 'cmg_core_site';

	// Resources ------------

	// Basic
	const TABLE_FILE				= 'cmg_core_file';
	const TABLE_TAG					= 'cmg_core_tag';
	const TABLE_CATEGORY			= 'cmg_core_category';
	const TABLE_OPTION				= 'cmg_core_option';

	// Address
	const TABLE_ADDRESS				= 'cmg_core_address';

	// Image Gallery, Portfolio
	const TABLE_GALLERY				= 'cmg_core_gallery';

	//Forms
	const TABLE_FORM				= 'cmg_core_form';
	const TABLE_FORM_FIELD			= 'cmg_core_form_field';

	// Mappers & Traits -----

	// Direct Mappers
	const TABLE_ROLE_PERMISSION		= 'cmg_core_role_permission';
	const TABLE_SITE_ATTRIBUTE		= 'cmg_core_site_attribute';
	const TABLE_SITE_MEMBER			= 'cmg_core_site_member';

	// Model Resources
	const TABLE_MODEL_MESSAGE		= 'cmg_core_model_message';
	const TABLE_MODEL_HIERARCHY		= 'cmg_core_model_hierarchy';
	const TABLE_MODEL_COMMENT		= 'cmg_core_model_comment';
	const TABLE_MODEL_ATTRIBUTE		= 'cmg_core_model_attribute';

	// Model Mappers
	const TABLE_MODEL_OBJECT		= 'cmg_core_model_object';
	const TABLE_MODEL_ADDRESS		= 'cmg_core_model_address';
	const TABLE_MODEL_FILE			= 'cmg_core_model_file';
	const TABLE_MODEL_GALLERY		= 'cmg_core_model_gallery';
	const TABLE_MODEL_TAG			= 'cmg_core_model_tag';
	const TABLE_MODEL_CATEGORY		= 'cmg_core_model_category';
	const TABLE_MODEL_OPTION		= 'cmg_core_model_option';
	const TABLE_MODEL_FORM			= 'cmg_core_model_form';
}
