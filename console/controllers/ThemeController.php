<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\console\controllers;

class ThemeController extends \yii\console\controllers\MigrateController {

	// Make this theme as default theme.
	public $default = true;

	// Activate this theme for current site.
	public $activate = true;

	public function options( $actionID ) {

		$options = parent::options( $actionID );

		$options[]	= 'default';
		$options[]	= 'activate';

		return $options;
	}

}
