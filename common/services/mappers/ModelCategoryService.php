<?php
namespace cmsgears\core\common\services\mappers;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\models\forms\Binder;
use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\mappers\ModelCategory;

use cmsgears\core\common\services\interfaces\resources\ICategoryService;
use cmsgears\core\common\services\interfaces\mappers\IModelCategoryService;

use cmsgears\core\common\services\traits\MapperTrait;

/**
 * The class ModelCategoryService is base class to perform database activities for ModelCategory Entity.
 */
class ModelCategoryService extends \cmsgears\core\common\services\base\EntityService implements IModelCategoryService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\core\common\models\mappers\ModelCategory';

	public static $modelTable	= CoreTables::TABLE_MODEL_CATEGORY;

	public static $parentType	= null;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	private $categoryService;

	// Traits ------------------------------------------------------

	use MapperTrait;

	// Constructor and Initialisation ------------------------------

	public function __construct( ICategoryService $categoryService, $config = [] ) {

		$this->categoryService	= $categoryService;

		parent::__construct( $config );
	}

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ModelCategoryService ------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getModelCounts( $parentType, $categoryType ) {

		$categoryTable	= CoreTables::TABLE_CATEGORY;
		$mcategoryTable	= CoreTables::TABLE_MODEL_CATEGORY;
		$query			= new Query();

		$query->select( [ 'slug', "count($categoryTable.id) as total" ] )
				->from( $categoryTable )
				->leftJoin( $mcategoryTable, "$mcategoryTable.categoryId=$categoryTable.id" )
				->where( "$mcategoryTable.parentType='$parentType' AND $categoryTable.type='$categoryType'" )
				->groupBy( "$categoryTable.id" );

		$counts		= $query->all();
		$returnArr	= [];
		$counter	= 0;

		foreach ( $counts as $count ) {

			$returnArr[ $count[ 'slug' ] ] = $count[ 'total' ];

			$counter	= $counter + $count[ 'total' ];
		}

		$returnArr[ 'all' ] = $counter;

		return $returnArr;
	}

	// Read - Lists ----

	public function getActiveCategoryIdList( $categoryId, $parentType ) {

		$models = ModelCategory::findActiveByCategoryIdParentType( $categoryId, $parentType );
		$ids	= [];

		foreach ( $models as $model ) {

			$category	= $model->category;

			$ids[]		= $category->id;
		}

		return $ids;
	}

	public function getActiveCategoryIdListByParent( $parentId, $parentType ) {

		$models = ModelCategory::findActiveByParent( $parentId, $parentType );
		$ids	= [];

		foreach ( $models as $model ) {

			$category	= $model->category;

			$ids[]		= $category->id;
		}

		return $ids;
	}

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function bindCategories( $parentId, $parentType, $config = [] ) {

		$binderName	= isset( $config[ 'binder' ] ) ? $config[ 'binder' ] : 'Binder';
		$binder		= new Binder();

		$binder->load( Yii::$app->request->post(), $binderName );

		$all		= $binder->all;
		$binded		= $binder->binded;
		$process	= [];

		// Check for All
		if( count( $all ) > 0 ) {

			$process = $all;
		}
		// Check for Active
		else {

			$process = $binded;

			ModelCategory::disableByParent( $parentId, $parentType );
		}

		// Process the List
		foreach ( $process as $id ) {

			$existingMapping	= ModelCategory::findByModelId( $parentId, $parentType, $id );

			// Existing mapping
			if( isset( $existingMapping ) ) {

				if( in_array( $id, $binded ) ) {

					$existingMapping->active	= true;
				}
				else {

					$existingMapping->active	= false;
				}

				$existingMapping->update();
			}
			// Create Mapping
			else if( in_array( $id, $binded ) ) {

				$this->createByParams( [ 'modelId' => $id, 'parentId' => $parentId, 'parentType' => $parentType, 'order' => 0, 'active' => true ] );
			}
		}

		return true;
	}

	// Delete -------------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// ModelCategoryService ------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

}
