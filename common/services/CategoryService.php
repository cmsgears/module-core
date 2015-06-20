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

	// Data Provider ----

	/**
	 * @param array $config to generate query
	 * @return ActiveDataProvider
	 */
	public static function getPagination( $config = [] ) {

		return self::getDataProvider( new Category(), $config );
	}

	// Create -----------

	public static function create( $category ) {

		// Create Category
		$category->save();
		
		// Return Category
		return $category;
	}

	// Update -----------

	public static function update( $category ) {

		// Find existing Category
		$categoryToUpdate	= self::findById( $category->id );

		// Copy Attributes
		$categoryToUpdate->copyForUpdateFrom( $category, [ 'parentId', 'name', 'description', 'type', 'icon' ] );

		// Update Category
		$categoryToUpdate->update();

		// Return updated Category
		return $categoryToUpdate;
	}

	// Delete -----------

	public static function delete( $category ) {

		// Find existing Category
		$categoryToDelete	= self::findById( $category->id );

		// Delete Category
		$categoryToDelete->delete();

		return true;
	}
}

?>