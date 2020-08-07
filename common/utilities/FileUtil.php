<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\utilities;

class FileUtil {

	public static function createDirs( $path, $permissions = '0755' ) {

		if( !file_exists( $path ) ) {

			mkdir( $path, $permissions, true );
		}
	}

}
