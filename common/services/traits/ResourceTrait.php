<?php
namespace cmsgears\core\common\services\traits;

trait ResourceTrait {

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// MapperTrait ---------------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

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

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

	public function deleteByParent( $parentId, $parentType ) {

		//$modelClass	= self::$modelClass;

		//return $modelClass::deleteByParent( $parentId, $parentType );

		$models	= $this->getByParent( $parentId, $parentType );

		foreach ( $models as $model ) {

			$this->delete( $model );
		}
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
