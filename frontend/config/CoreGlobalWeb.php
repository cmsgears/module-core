<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\frontend\config;

/**
 * CoreGlobalWeb defines the global constants and variables available for core and dependent modules.
 *
 * @since 1.0.0
 */
class CoreGlobalWeb {

	// System Sites ---------------------------------------------------

	// System Pages ---------------------------------------------------

	// Public
	const PAGE_INDEX			= 'index';
	const PAGE_MAINTENANCE		= 'maintenance';
	const PAGE_REGISTER			= 'register';
	const PAGE_SIGNUP			= 'signup';
	const PAGE_ACCOUNT_CONFIRM	= 'confirm-account';
	const PAGE_LOGIN			= 'login';
	const PAGE_FEEDBACK			= 'feedback';
	const PAGE_TESTIMONIAL		= 'testimonial';

	// Private
	const PAGE_PROFILE	= 'profile';
	const PAGE_ACCOUNT	= 'account';
	const PAGE_ADDRESS	= 'address';
	const PAGE_SETTINGS	= 'settings';

	// Grouping by type ------------------------------------------------

	// Templates -------------------------------------------------------

	const LAYOUT_LANDING	= '//landing';

	const LAYOUT_PUBLIC		= '//public';
	const LAYOUT_PRIVATE	= '//private';

	const LAYOUT_SEARCH		= '//search';
	const LAYOUT_CATEGORY	= '//category';
	const LAYOUT_TAG		= '//tag';

	// Config ----------------------------------------------------------

	// Roles -----------------------------------------------------------

	// Permissions -----------------------------------------------------

	// Model Attributes ------------------------------------------------

	// Default Maps ----------------------------------------------------

	// Messages --------------------------------------------------------

	// Errors ----------------------------------------------------------

	// Model Fields ----------------------------------------------------

}
