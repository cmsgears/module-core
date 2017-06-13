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

use cmsgears\core\common\services\traits\NameTypeTrait;
use cmsgears\core\common\services\traits\SlugTypeTrait;

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

	use NameTypeTrait;
	use SlugTypeTrait;

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// CategoryService -----------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$modelTable = static::$modelTable;
		$modelClass	= static::$modelClass;

		// Sorting ----------

		$sort = new Sort([
			'attributes' => [
				'name' => [
					'asc' => [ "$modelTable.name" => SORT_ASC ],
					'desc' => [ "$modelTable.name" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Name',
				],
				'parent' => [
					'asc' => [ 'parent.name' => SORT_ASC ],
					'desc' => [ 'parent.name' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Parent',
				]
			]
		]);

		$config[ 'sort' ] 	= $sort;

		// Query ------------

		$query 	= $modelClass::find()->joinWith( 'parent' );

		// Filters ----------

		// Filter - Level
		$parent	= Yii::$app->request->getQueryParam( 'parent' );

		if( isset( $parent ) ) {

			if( $parent === 'top' ) {

				$query->andWhere( "$modelTable.parentId IS NULL" );
			}
		}

		// Searching --------

		$searchCol	= Yii::$app->request->getQueryParam( 'search' );

		if( isset( $searchCol ) ) {

			$search = [ 'name' => "$modelTable.name", 'desc' => "$modelTable.description" ];

			$config[ 'search-col' ] = $search[ $searchCol ];
		}

		// Reporting --------

		$config[ 'report-col' ]	= [ 'name' => "$modelTable.name", 'desc' => "$modelTable.description", 'pname' => 'parent.name', 'pdesc' => 'parent.description' ];

		// Result -----------

		$config[ 'query' ]	= $query;

		return parent::getPage( $config );
	}

	// Read ---------------

	// Read - Models ---

	public function getByParentId( $id ) {

		return Category::findByParentId( $id );
	}

	public function getFeaturedByType( $type ) {

		return Category::getFeaturedByType( $type );
	}

	public function getL1ByType( $type ) {

		$modelClass	= static::$modelClass;

		return $modelClass::find()->where( [ 'type' => $type, 'lValue' => 1 ] )->all();
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

		return $this->getLevelList( [ 'conditions' => [ 'node.type' => $type ] ] );
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

		$attributes = isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [ 'name', 'description', 'type', 'icon', 'featured', 'htmlOptions' ];

		// Find existing model
		$modelToUpdate	= $this->getById( $model->id );

		// Update Hierarchy
		$modelToUpdate	= $this->updateInHierarchy( $model, $modelToUpdate );

		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

	// Delete -------------

	public function delete( $model, $config = [] ) {

		// Delete mapping
		ModelCategory::deleteByModelId( $model->id );

		// Delete options and mappings - mappings will be deleted by cascade effect
		Option::deleteByCategoryId( $model->id );

		// Update Hierarchy
		$model = $this->deleteInHierarchy( $model );

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
