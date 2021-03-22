<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\base;

class Migration extends \yii\db\Migration {

	/**
	 * Creates a medium text column.
	 *
	 * @return ColumnSchemaBuilder
	 */
	public function mediumText() {

		return $this->getDb()->getSchema()->createColumnSchemaBuilder( 'mediumtext' );
	}

	/**
	 * Creates a long text column.
	 *
	 * @return ColumnSchemaBuilder
	 */
	public function longText() {

		return $this->getDb()->getSchema()->createColumnSchemaBuilder( 'longtext' );
	}

}
