<?php
namespace cmsgears\core\frontend\config;

class WebGlobalCore {

	// Layouts --------------------------------------------------------

	const LAYOUT_LANDING		= '//landing';
	const LAYOUT_PUBLIC			= '//public';
	const LAYOUT_PRIVATE		= '//private';

	// System Pages ---------------------------------------------------

	// Public
	const PAGE_INDEX			= 'index';
	const PAGE_REGISTER			= 'register';
	const PAGE_ACCOUNT_CONFIRM	= 'confirm-account';

	// Private
	const PAGE_PROFILE			= 'profile';
	const PAGE_SETTINGS			= 'settings';
	
	// Default Settings
	const SETTINGS_PRIVACY		= 'privacy';
	const SETTINGS_NOTIFICATION	= 'notification';
	const SETTINGS_REMINDER		= 'reminder';
}

?>