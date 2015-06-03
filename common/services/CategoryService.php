<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\CoreTables;
use cmsgears\core\common\models\entities\Category;

/**
 * The class CategoryService is base class to perform database activities for Category Entity.
 */
class CategoryService extends Service {

	// Static Methods ----------------------------------------------

	// Read ----------------

	/**
	 * @param integer $id
	 * @return Category
	 */
	public static function findById( $id ) {

		return Category::findById( $id );
	}

	/**
	 * @param string $name
	 * @return Category
	 */
	public static function findByName( $name ) {

		return Category::findByName( $name );
	}

	/**
	 * @param string $type
	 * @return Category
	 */
	public static function findByType( $type ) {

		return Category::findByType( $type );
    }

	/**
	 * @param string $id
	 * @return array - An array of associative array of category id and name for the specified category type
	 */
	public static function getIdNameListByType( $type ) {

		return self::findIdNameList( "id", "name", CoreTables::TABLE_CATEGORY, [ "type" => $type ] );
	}
}

?>