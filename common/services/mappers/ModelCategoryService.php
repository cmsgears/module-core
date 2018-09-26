<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\mappers;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\models\forms\Binder;

use cmsgears\core\common\services\interfaces\resources\ICategoryService;
use cmsgears\core\common\services\interfaces\mappers\IModelCategoryService;

use cmsgears\core\common\services\base\ModelMapperService;

/**
 * ModelCategoryService provide service methods of category mapper.
 *
 * @since 1.0.0
 */
class ModelCategoryService extends ModelMapperService implements IModelCategoryService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass = '\cmsgears\core\common\models\mappers\ModelCategory';

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	private $categoryService;

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function __construct( ICategoryService $categoryService, $config = [] ) {

		$this->categoryService = $categoryService;

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

		$categoryTable	= Yii::$app->get( 'categoryService' )->getModelTable();
		$mcategoryTable	= Yii::$app->get( 'modelCategoryService' )->getModelTable();

		$query = new Query();

		$query->select( [ 'slug', "count($categoryTable.id) as total" ] )
				->from( $categoryTable )
				->leftJoin( $mcategoryTable, "$mcategoryTable.categoryId=$categoryTable.id" )
				->where( "$mcategoryTable.parentType='$parentType' AND $categoryTable.type='$categoryType'" )
				->groupBy( "$categoryTable.id" );

		$counts = $query->all();

		$returnArr	= [];
		$counter	= 0;

		foreach ( $counts as $count ) {

			$returnArr[ $count[ 'slug' ] ] = $count[ 'total' ];

			$counter = $counter + $count[ 'total' ];
		}

		$returnArr[ 'all' ] = $counter;

		return $returnArr;
	}

	// Read - Lists ----

	public function getActiveCategoryIdList( $categoryId, $parentType ) {

		$modelClass	= static::$modelClass;

		$models = $modelClass::findActiveByCategoryIdParentType( $categoryId, $parentType );
		$ids	= [];

		foreach ( $models as $model ) {

			$category = $model->category;

			$ids[] = $category->id;
		}

		return $ids;
	}

	public function getActiveCategoryIdListByParent( $parentId, $parentType ) {

		$modelClass	= static::$modelClass;

		$models = $modelClass::findActiveByParent( $parentId, $parentType );
		$ids	= [];

		foreach ( $models as $model ) {

			$category = $model->category;

			$ids[] = $category->id;
		}

		return $ids;
	}

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function toggle( $parentId, $parentType, $modelId ) {

		$modelClass	= static::$modelClass;

		$toSave	= $modelClass::findFirstByParentModelId( $parentId, $parentType, $modelId );

		// Existing mapping
		if( isset( $toSave ) ) {

			if( $toSave->active ) {

				$toSave->active	= false;
			}
			else {

				$toSave->active	= true;
			}

			$toSave->update();
		}
		// New Mapping
		else {

			$this->createByParams( [ 'modelId' => $modelId, 'parentId' => $parentId, 'parentType' => $parentType, 'active' => true ] );
		}
	}

	public function bindCategories( $parentId, $parentType, $config = [] ) {

		$modelClass	= static::$modelClass;
		$binderName	= isset( $config[ 'binder' ] ) ? $config[ 'binder' ] : 'Binder';
		$binder		= $config[ 'categoryBinder' ] ?? null;
		
		if( empty( $binder ) ) {

			$binder		= new Binder();
			$binder->load( Yii::$app->request->post(), $binderName );
		}

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

			$modelClass::disableByParent( $parentId, $parentType );
		}

		// Process the List
		foreach( $process as $id ) {

			$existingMapping = $modelClass::findByModelId( $parentId, $parentType, $id );

			// Existing mapping
			if( !empty( $existingMapping ) ) {

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

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

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
