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
use yii\db\Query;

// CMG Imports
use cmsgears\core\common\services\interfaces\resources\IOptionService;
use cmsgears\core\common\services\interfaces\mappers\IModelOptionService;

/**
 * ModelOptionService provide service methods of option mapper.
 *
 * @since 1.0.0
 */
class ModelOptionService extends \cmsgears\core\common\services\base\ModelMapperService implements IModelOptionService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass = '\cmsgears\core\common\models\mappers\ModelOption';

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function __construct( IOptionService $optionService, $config = [] ) {

		$this->parentService = $optionService;

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

	// Read - Models ---

	// Read - Lists ----

	public function getValueListByCategorySlug( $parentId, $parentType, $categorySlug, $active = true ) {

		$categoryTable	= Yii::$app->factory->get( 'categoryService' )->getModelTable();
		$optionTable	= Yii::$app->factory->get( 'parentService' )->getModelTable();
		$mOptionTable	= Yii::$app->factory->get( 'modelOptionService' )->getModelTable();

		$query = new Query();

		$query->select( [ "$optionTable.value" ] )
				->from( $optionTable )
				->leftJoin( $mOptionTable, "$mOptionTable.modelId=$optionTable.id" )
				->leftJoin( $categoryTable, "$categoryTable.id=$optionTable.categoryId" )
				->where( "$mOptionTable.parentId=:pid AND $mOptionTable.parentType=:ptype AND $mOptionTable.active=:active AND $categoryTable.slug=:cslug", [ ':pid' => $parentId, ':ptype' => $parentType, ':active' => $active, ':cslug' => $categorySlug ] );

		$values = $query->all();

		$data = [];

		foreach( $values as $value ) {

			$data[] = $value[ 'value' ];
		}

		return $data;
	}

	// Read - Maps -----

	public function getIdValueMapByCategorySlug( $parentId, $parentType, $categorySlug, $active = true ) {

		$categoryTable	= Yii::$app->factory->get( 'categoryService' )->getModelTable();
		$optionTable	= Yii::$app->factory->get( 'parentService' )->getModelTable();
		$mOptionTable	= Yii::$app->factory->get( 'modelOptionService' )->getModelTable();

		$query = new Query();

		$query->select( [ "$optionTable.id", "$optionTable.value" ] )
				->from( $optionTable )
				->leftJoin( $mOptionTable, "$mOptionTable.modelId=$optionTable.id" )
				->leftJoin( $categoryTable, "$categoryTable.id=$optionTable.categoryId" )
				->where( "$mOptionTable.parentId=:pid AND $mOptionTable.parentType=:ptype AND $mOptionTable.active=:active AND $categoryTable.slug=:cslug", [ ':pid' => $parentId, ':ptype' => $parentType, ':active' => $active, ':cslug' => $categorySlug ] );

		$values = $query->all();

		$data = [];

		foreach( $values as $value ) {

			$data[ $value[ 'id' ] ] = $value[ 'value' ];
		}

		return $data;
	}

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function update( $model, $config = [] ) {

		$attributes = isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [
			'order', 'active', 'value'
		];

		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

	public function bindOptions( $binder, $parentType ) {

		$parentId	= $binder->binderId;
		$allData	= $binder->all;
		$activeData	= $binder->binded;

		foreach( $allData as $id ) {

			$modelClass	= static::$modelClass;

			$toSave = $modelClass::findFirstByParentModelId( $parentId, $parentType, $id );

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

				$this->createByParams([
					'modelId' => $id, 'parentId' => $parentId, 'parentType' => $parentType,
					'type' => $parentType, 'active' => true
				]);
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

	// ModelOptionService --------------------

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
