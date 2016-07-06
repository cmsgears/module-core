<?php
namespace cmsgears\core\common\services\traits;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

trait MapperTrait {

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// MapperTrait ---------------------------

	// Data Provider ------

	// Read ---------------

    // Read - Models ---

    public function getAllByModelId( $modelId ) {

		$modelClass	= self::$modelClass;

		return $modelClass::findAllByModelId( $modelId );
    }

	public function getByModelId( $parentId, $parentType, $modelId ) {

		$modelClass	= self::$modelClass;

		return $modelClass::findByModelId( $parentId, $parentType, $modelId );
	}

	public function getByParent( $parentId, $parentType ) {

		$modelClass	= self::$modelClass;

		return $modelClass::findByParent( $parentId, $parentType );
	}

	public function getByParentId( $parentId ) {

		$modelClass	= self::$modelClass;

		return $modelClass::findByParentId( $parentId );
	}

	public function getByParentType( $parentType ) {

		$modelClass	= self::$modelClass;

		return $modelClass::findByParentType( $parentType );
	}

	// Models having active column

	public function getActiveByParent( $parentId, $parentType ) {

		$modelClass	= self::$modelClass;

		return $modelClass::findActiveByParent( $parentId, $parentType );
	}

	public function getActiveByParentId( $parentId ) {

		$modelClass	= self::$modelClass;

		return $modelClass::findActiveByParentId( $parentId );
	}

	public function getActiveByParentType( $parentType ) {

		$modelClass	= self::$modelClass;

		return $modelClass::findActiveByParentType( $parentType );
	}

    public function getActiveByModelIdParentType( $modelId, $parentType ) {

		$modelClass	= self::$modelClass;

		return $modelClass::findActiveByModelIdParentType( $modelId, $parentType );
    }

    // Read - Lists ----

    // Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Models having active column

	public function activate( $model ) {

		$model->active	= true;

		$model->update();

		return $model;
	}

	public function deActivate( $model ) {

		$model->active	= false;

		$model->update();

		return $model;
	}

	// Delete -------------

	public function deleteByParent( $parentId, $parentType ) {

		$modelClass	= self::$modelClass;

		return $modelClass::deleteByParent( $parentId, $parentType );
	}

	public function deleteByModelId( $modelId ) {

		$modelClass	= self::$modelClass;

		return $modelClass::deleteByModelId( $modelId );
	}

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// MapperTrait ---------------------------

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
