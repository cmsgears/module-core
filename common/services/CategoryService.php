<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\CoreTables;
use cmsgears\core\common\models\entities\Category;
use cmsgears\core\common\models\entities\ModelCategory;

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
	
	public static function findByParentId( $id ) {
		
		return Category::findByParentId( $id );
	}

	/**
	 * @param string $name
	 * @return Category
	 */
	public static function findByName( $name ) {

		return Category::findByName( $name );
	}

	public static function findBySlug( $slug ) {

		return Category::findBySlug( $slug );
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
	public static function getIdNameListByType( $type, $prepend = [], $append = [] ) {

		return self::findIdNameList( 'id', 'name', CoreTables::TABLE_CATEGORY, [ 'conditions' => [ 'type' => $type ], 'asArray' => false, 'prepend' => $prepend, 'append' => $append ] );
	}

	public static function getIdNameMapByType( $type, $config ) {

		$config[ 'conditions' ][ 'type' ] 	= $type;
		$config[ 'asArray' ] 				= false;

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

	public static function create( $category, $avatar = null ) {

		if( $category->parentId <= 0 ) {

			unset( $category->parentId );
		}

		if( isset( $avatar ) ) {

			FileService::saveImage( $avatar, [ 'model' => $category, 'attribute' => 'avatarId' ] );
		}

		// Create Category
		$category->save();

		// Return Category
		return $category;
	}

	// Update -----------

	public static function update( $category, $avatar = null ) {

		// Find existing Category
		$categoryToUpdate	= self::findById( $category->id );

		// Copy Attributes
		$categoryToUpdate->copyForUpdateFrom( $category, [ 'avatarId', 'parentId', 'name', 'description', 'type', 'icon', 'featured', 'htmlOptions' ] );

		if( $categoryToUpdate->parentId <= 0 ) {

			unset( $categoryToUpdate->parentId );
		}

		if( isset( $avatar ) ) {

			FileService::saveImage( $avatar, [ 'model' => $categoryToUpdate, 'attribute' => 'avatarId' ] );
		}

		// Update Category
		$categoryToUpdate->update();

		// Return updated Category
		return $categoryToUpdate;
	}

	// Delete -----------

	public static function delete( $category, $avatar = null ) {

		// Find existing Category
		$categoryToDelete	= self::findById( $category->id );

		// Delete dependency
		ModelCategory::deleteByCategoryId( $category->id );

		// Delete Category
		$categoryToDelete->delete();

		// Delete Avatar
		if( isset( $avatar ) ) {

			FileService::delete( $avatar, true );
		}

		return true;
	}
}

?>