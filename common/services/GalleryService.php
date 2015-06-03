<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\CoreTables;
use cmsgears\core\common\models\entities\Gallery;

/**
 * The class GalleryService is base class to perform database activities for Gallery Entity.
 */
class GalleryService extends Service {

	// Static Methods ----------------------------------------------
	
	// Read ----------------

	/**
	 * @param integer $id
	 * @return Gallery
	 */
	public static function findById( $id ) {

		return Gallery::findById( $id );
	}

	/**
	 * @param string $name
	 * @return Gallery
	 */
	public static function findByName( $name ) {

		return Gallery::findByName( $name );
	}

	/**
	 * @param string $id
	 * @return array - An array of associative array of gallery id and name
	 */
	public static function getIdNameList() {

		return self::findIdNameList( 'id', 'name', CoreTables::TABLE_GALLERY );
	}
}

?>