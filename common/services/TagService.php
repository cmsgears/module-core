<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\CoreTables;
use cmsgears\core\common\models\entities\Tag;

/**
 * The class TagService is base class to perform database activities for Tag Entity.
 */
class TagService extends Service {

	// Static Methods ----------------------------------------------
	
	// Read ----------------

	/**
	 * @param integer $id
	 * @return Tag
	 */
	public static function findById( $id ) {

		return Tag::findById( $id );
	}

	/**
	 * @param string $name
	 * @return Tag
	 */
	public static function findByName( $name ) {

		return Tag::findByName( $name );
	}

	/**
	 * @return array - an array having id as key and name as value.
	 */
	public static function getIdNameMap() {

		return self::findMap( 'id', 'name', CoreTables::TABLE_TAG );
	}

	/**
	 * @param string $id
	 * @return array - An array of associative array of tag id and name.
	 */
	public static function getIdNameList() {

		return self::findIdNameList( 'id', 'name', CoreTables::TABLE_TAG );
	}

}

?>