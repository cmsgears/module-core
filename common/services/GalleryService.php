<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\CoreTables;
use cmsgears\core\common\models\entities\Gallery;

class GalleryService extends Service {

	// Static Methods ----------------------------------------------
	
	// Read ----------------

	public static function findById( $id ) {

		return Gallery::findById( $id );
	}

	public static function findByName( $name ) {

		return Gallery::findByName( $name );
	}

	public static function getIdNameList() {

		return self::findIdNameList( 'id', 'name', CoreTables::TABLE_GALLERY );
	}
}

?>