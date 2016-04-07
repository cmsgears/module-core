<?php
namespace cmsgears\core\common\services\resources;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\resources\Category;
use cmsgears\core\common\models\mappers\ModelCategory;

/**
 * The class CategoryService is base class to perform database activities for Category Entity.
 */
class CategoryService extends \cmsgears\core\common\services\base\HierarchyService {

	// Static Methods ----------------------------------------------

	// Read ----------------

	// Read - Models ---

	public static function findById( $id ) {

		return Category::findById( $id );
	}

	public static function findByParentId( $id ) {

		return Category::findByParentId( $id );
	}

	public static function getFeaturedByType( $type ) {

		return Category::getFeaturedByType( $type );
	}

	public static function findByName( $name ) {

		return Category::findByName( $name );
	}

	public static function findByType( $type ) {

		return Category::findByType( $type );
    }

	public static function findBySlugType( $slug, $type ) {

		return Category::findBySlugType( $slug, $type );
	}

	// Read - Lists ----

	public static function getIdNameListByType( $type, $config = [] ) {

		$config[ 'conditions' ][ 'type' ] = $type;

		return self::findIdNameList( 'id', 'name', CoreTables::TABLE_CATEGORY, $config );
	}

	public static function getTopLevelIdNameListByType( $type, $config = [] ) {

		$config[ 'conditions' ][ 'parentId' ] = null;

		return self::getIdNameListByType( $type, $config );
	}

	public static function getTopLevelIdNameListById( $id, $config = [] ) {

		$category	= self::findById( $id );

		return self::getSubLevelList( $category->id, $category->rootId, [], 'depth = 1' );
	}

	public static function getLevelListByType( $type ) {

		return self::getLevelList( [ 'node.type' => $type ] );
	}

	// Read - Maps -----

	public static function getIdNameMapByType( $type, $config = [] ) {

		$config[ 'conditions' ][ 'type' ] 	= $type;

		return self::findMap( 'id', 'name', CoreTables::TABLE_CATEGORY, $config );
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

		$category	= self::createInHierarchy( CoreTables::TABLE_CATEGORY, $category );

		// Return Category
		return $category;
	}

	// Update -----------

	public static function update( $category ) {

		// Find existing Category
		$categoryToUpdate	= self::findById( $category->id );

		// Update Hierarchy
		$categoryToUpdate = self::updateInHierarchy( CoreTables::TABLE_CATEGORY, $category, $categoryToUpdate );

		// Copy Attributes
		$categoryToUpdate->copyForUpdateFrom( $category, [ 'name', 'description', 'type', 'icon', 'featured', 'htmlOptions' ] );

		// Update Category
		$categoryToUpdate->update();

		// Return updated Category
		return $categoryToUpdate;
	}

	// Delete -----------

	public static function delete( $category ) {

		// Find existing Category
		$categoryToDelete	= self::findById( $category->id );

		// Delete dependency
		ModelCategory::deleteByCategoryId( $category->id );

		// Update Hierarchy
		$categoryToDelete = self::deleteInHierarchy( CoreTables::TABLE_CATEGORY, $categoryToDelete );

		// Delete Category
		$categoryToDelete->delete();

		return true;
	}
}

?>