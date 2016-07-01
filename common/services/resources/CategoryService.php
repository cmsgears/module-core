<?php
namespace cmsgears\core\common\services\resources;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\resources\Category;
use cmsgears\core\common\models\mappers\ModelCategory;

use cmsgears\core\common\services\interfaces\resources\ICategoryService;

/**
 * The class CategoryService is base class to perform database activities for Category Entity.
 */
class CategoryService extends \cmsgears\core\common\services\base\HierarchyService implements ICategoryService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\core\common\models\resources\Category';

	public static $modelTable	= CoreTables::TABLE_CATEGORY;

	public static $parentType	= CoreGlobal::TYPE_CATEGORY;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// CategoryService -----------------------

	// Data Provider ------

	// Read ---------------

    // Read - Models ---

	public function getByParentId( $id ) {

		return self::findByParentId( $id );
	}

	public function getFeaturedByType( $type ) {

		return self::getFeaturedByType( $type );
	}

	public function getByName( $name, $first = false ) {

		return self::findByName( $name, $first );
	}

	public function getByType( $type ) {

		return self::findByType( $type );
    }

	public function getBySlugType( $slug, $type ) {

		return self::findBySlugType( $slug, $type );
	}

	public function searchByName( $name, $config = [] ) {

		$categoryTable				= CoreTables::TABLE_CATEGORY;
		$config[ 'query' ] 			= Category::queryWithSite();
		$config[ 'conditions' ][ "$categoryTable.siteId" ]	= Yii::$app->cmgCore->siteId;
		$config[ 'filters' ][]		= [ 'like', "$categoryTable.name", $name ];
		$config[ 'columns' ]		= [ '$categoryTable.id', '$categoryTable.name' ];
		$config[ 'array' ]			= true;

		return self::searchModels( $config );
	}

    // Read - Lists ----

	public function getIdNameListByType( $type, $config = [] ) {

		$config[ 'conditions' ][ 'type' ] = $type;

		return $this->getIdNameList( $config );
	}

	public function getTopLevelIdNameListByType( $type, $config = [] ) {

		$config[ 'conditions' ][ 'parentId' ] = null;

		return $this->getIdNameListByType( $type, $config );
	}

	public function getTopLevelIdNameListById( $id, $config = [] ) {

		$category	= self::findById( $id );

		return $this->getSubLevelList( $category->id, $category->rootId, [ 'having' => 'depth = 1' ] );
	}

	public function getLevelListByType( $type ) {

		return $this->getLevelList( [ 'node.type' => $type ] );
	}

    // Read - Maps -----

	public function getIdNameMapByType( $type, $config = [] ) {

		$config[ 'conditions' ][ 'type' ] 	= $type;

		return $this->getIdNameMap( $config );
	}

	// Read - Others ---

	// Create -------------

 	public function create( $model, $config = [] ) {

		$model	= $this->createInHierarchy( $model );

		return $model;
 	}

	// Update -------------

	public function update( $model, $config = [] ) {

		// Find existing model
		$modelToUpdate	= self::findById( $model->id );

		// Update Hierarchy
		$modelToUpdate 	= self::updateInHierarchy( CoreTables::TABLE_CATEGORY, $model, $modelToUpdate );

		return parent::update( $model, [
			'attributes' => [ 'name', 'description', 'type', 'icon', 'featured', 'htmlOptions' ]
		]);
	}

	// Delete -------------

	public function delete( $model, $config = [] ) {

		// Delete dependencies
		ModelCategory::deleteByCategoryId( $model->id );

		// Update Hierarchy
		$model = self::deleteInHierarchy( $model );

		// Delete model
		return parent::delete( $model, $config );
	}

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// CategoryService -----------------------

	// Data Provider ------

	// Read ---------------

    // Read - Models ---

	public static function findByParentId( $id ) {

		return Category::findByParentId( $id );
	}

	public static function getFeaturedByType( $type ) {

		return Category::getFeaturedByType( $type );
	}

	public static function findByName( $name, $first = false ) {

		return Category::findByName( $name, $first );
	}

	public static function findByType( $type ) {

		return Category::findByType( $type );
    }

	public static function findBySlugType( $slug, $type ) {

		return Category::findBySlugType( $slug, $type );
	}

    // Read - Lists ----

    // Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------
}

?>