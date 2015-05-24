<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\CoreTables;
use cmsgears\core\common\models\entities\Tag;

class TagService extends Service {

	// Static Methods ----------------------------------------------
	
	// Read ----------------

	public static function findById( $id ) {

		return Tag::findById( $id );
	}

	public static function findByName( $name ) {

		return Tag::findByName( $name );
	}

	public static function getIdNameList() {

		return self::findIdNameList( 'id', 'name', CoreTables::TABLE_MODEL_TAG );
	}

	public static function getIdNameMap() {

		return self::findMap( 'id', 'name', CoreTables::TABLE_MODEL_TAG );
	}
}

?>