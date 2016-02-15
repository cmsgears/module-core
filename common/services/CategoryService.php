<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\CoreTables;
use cmsgears\core\common\models\entities\Category;
use cmsgears\core\common\models\entities\ModelCategory;

/**
 * The class CategoryService is base class to perform database activities for Category Entity.
 */
class CategoryService extends HierarchyService {

	// Static Methods ----------------------------------------------

	// Read ----------------

	// Read - Models ---

	public static function findById( $id ) {

		return Category::findById( $id );
	}

	public static function findByParentId( $id ) {

		return Category::findByParentId( $id );
	}

	public static function getFeatured() {

		return Category::findAll( [ 'featured' => 1 ] );
	}

	public static function findByName( $name ) {

		return Category::findByName( $name );
	}

	public static function findBySlug( $slug ) {

		return Category::findBySlug( $slug );
	}

	public static function findByType( $type ) {

		return Category::findByType( $type );
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

	public static function create( $category, $banner = null, $video = null ) {

		FileService::saveFiles( $category, [ 'bannerId' => $banner, 'videoId' => $video ] );

		$category	= self::createInHierarchy( CoreTables::TABLE_CATEGORY, $category );

		// Return Category
		return $category;
	}

	// Update -----------

	public static function update( $category, $banner = null, $video = null ) {

		// Find existing Category
		$categoryToUpdate	= self::findById( $category->id );

		FileService::saveFiles( $categoryToUpdate, [ 'bannerId' => $banner, 'videoId' => $video ] );

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

	public static function delete( $category, $banner = null, $video = null ) {

		// Find existing Category
		$categoryToDelete	= self::findById( $category->id );

		// Delete dependency
		ModelCategory::deleteByCategoryId( $category->id );

		// Update Hierarchy
		$categoryToDelete = self::deleteInHierarchy( CoreTables::TABLE_CATEGORY, $categoryToDelete );

		// Delete Category
		$categoryToDelete->delete();

		// Delete Files
		FileService::deleteFiles( [ $banner, $video ] );

		return true;
	}
}

?>