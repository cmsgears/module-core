<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\CoreTables;
use cmsgears\core\common\models\entities\Tag;
use cmsgears\core\common\models\entities\ModelTag;

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

	public static function findBySlug( $slug ) {

		return Tag::findBySlug( $slug );
	}

	public static function findByTypeName( $type, $name ) {

		return Tag::findByTypeName( $type, $name );
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
	
	// Create ---------------- 

	public static function create( $tag ) {

		$tag->save();

		return $tag;
	}

	// Delete -----------

	public static function delete( $tag ) {

		// Find existing Tag
		$tagToDelete	= self::findById( $tag->id );

		// Delete dependency
		ModelTag::deleteByTagId( $tag->id );

		// Delete Tag
		$tagToDelete->delete();

		return true;
	}
}

?>