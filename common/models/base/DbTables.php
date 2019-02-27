<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\base;

/**
 * It provide methods to access table name constants of db tables.
 *
 * @since 1.0.0
 */
abstract class DbTables {

	// TODO: Update to add cmg prefix after removing it from all the constants.
	public static function getTableName( $name ) {

		return $name;
	}
}
