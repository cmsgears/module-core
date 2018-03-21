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
use yii\db\Query;

// CMG Imports
use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\mappers\ModelOption;

use cmsgears\core\common\services\interfaces\resources\IOptionService;
use cmsgears\core\common\services\interfaces\mappers\IModelOptionService;

use cmsgears\core\common\services\base\ModelMapperService;

/**
 * ModelOptionService provide service methods of option mapper.
 *
 * @since 1.0.0
 */
class ModelOptionService extends  ModelMapperService implements IModelOptionService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\core\common\models\mappers\ModelOption';

	public static $modelTable	= CoreTables::TABLE_MODEL_OPTION;

	public static $parentType	= null;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	private $optionService;

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function __construct( IOptionService $optionService, $config = [] ) {

		$this->optionService	= $optionService;

		parent::__construct( $config );
	}

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ModelOptionService --------------------

	// Data Provider ------

	// Read ---------------

	public function getModelCounts( $parentType, $categorySlug, $active = false ) {

		$categoryTable	= CoreTables::TABLE_CATEGORY;
		$optionTable	= CoreTables::TABLE_OPTION;
		$mOptionTable	= CoreTables::TABLE_MODEL_OPTION;
		$query			= new Query();

		$query->select( [ "$optionTable.name", "count($optionTable.id) as total" ] )
				->from( $optionTable )
				->leftJoin( $mOptionTable, "$mOptionTable.modelId=$optionTable.id" )
				->leftJoin( $categoryTable, "$categoryTable.id=$optionTable.categoryId" )
				->where( "$mOptionTable.parentType='$parentType' AND $categoryTable.slug='$categorySlug'" )
				->groupBy( "$optionTable.id" );

		if( $active ) {

			$query->andWhere( "$mOptionTable.active=$active" );
		}

		$counts		= $query->all();
		$returnArr	= [];
		$counter	= 0;

		foreach ( $counts as $count ) {

			$returnArr[ $count[ 'name' ] ] = $count[ 'total' ];

			$counter	= $counter + $count[ 'total' ];
		}

		$returnArr[ 'all' ] = $counter;

		return $returnArr;
	}


	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function update( $model, $config = [] ) {

		$attributes = isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [ 'order', 'active' ];

		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

	public function toggle( $parentId, $parentType, $modelId ) {

		$toSave		= ModelOption::findByModelId( $parentId, $parentType, $modelId );

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

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// ModelOptionService --------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function bindOptions( $binder, $parentType ) {

		$parentId	= $binder->binderId;
		$allData	= $binder->all;
		$activeData	= $binder->binded;

		foreach ( $allData as $id ) {

			$toSave		= ModelOption::findByModelId( $parentId, $parentType, $id );

			// Existing mapping
			if( isset( $toSave ) ) {

				if( in_array( $id, $activeData ) ) {

					$toSave->active	= true;
				}
				else {

					$toSave->active	= false;
				}

				$toSave->update();
			}
			// Save only required data
			else if( in_array( $id, $activeData ) ) {

				$this->createByParams( [ 'modelId' => $id, 'parentId' => $parentId, 'parentType' => $parentType, 'active' => true ] );
			}
		}

		return true;
	}

	// Delete -------------

}
