<?php
namespace cmsgears\core\common\services\resources;

// Yii Imports
use \Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\resources\Category;
use cmsgears\core\common\models\resources\Option;
use cmsgears\core\common\models\mappers\ModelCategory;

use cmsgears\core\common\services\interfaces\resources\ICategoryService;

use cmsgears\core\common\services\traits\NameSlugTypeTrait;

/**
 * The class CategoryService is base class to perform database activities for Category Entity.
 */
class CategoryService extends \cmsgears\core\common\services\hierarchy\NestedSetService implements ICategoryService {

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

	use NameSlugTypeTrait;

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// CategoryService -----------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

	    $sort = new Sort([
	        'attributes' => [
	            'name' => [
	                'asc' => [ 'name' => SORT_ASC ],
	                'desc' => ['name' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'name',
	            ]
	        ]
	    ]);

		$config[ 'sort' ] = $sort;

		return parent::findPage( $config );
	}

	// Read ---------------

    // Read - Models ---

	public function getByParentId( $id ) {

		return self::findByParentId( $id );
	}

	public function getFeaturedByType( $type ) {

		return self::getFeaturedByType( $type );
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
		$modelToUpdate 	= self::updateInHierarchy( $model, $modelToUpdate );

		return parent::update( $model, [
			'attributes' => [ 'name', 'description', 'type', 'icon', 'featured', 'htmlOptions' ]
		]);
	}

	// Delete -------------

	public function delete( $model, $config = [] ) {

		// Delete dependencies
		ModelCategory::deleteByModelId( $model->id );
		Option::deleteByCategoryId( $model->id );

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

	public static function findFeaturedByType( $type ) {

		return Category::getFeaturedByType( $type );
	}

    // Read - Lists ----

    // Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------
}

?>