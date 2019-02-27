<?php
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
