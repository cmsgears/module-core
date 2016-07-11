<?php
namespace cmsgears\core\common\services\traits;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * MapperTrait is generic trait for model mappers and provide several useful and
 * commonly used methods by mappers.
 *
 * The model mapper must have modelId, parentId and parentType attributes where
 * modelId is the id of model mapped to parent for given parentId and parentType.
 *
 * The mapper might also provide few more common attributes including active, order and type.
 */
trait MapperTrait {

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// MapperTrait ---------------------------

	// Data Provider ------

	// Read ---------------

    // Read - Models ---

	/**
	 * @param long $modelId of mapped model.
	 * @return array of model mappings having matching $modelId.
	 */
    public function getAllByModelId( $modelId ) {

		$modelClass	= self::$modelClass;

		return $modelClass::findAllByModelId( $modelId );
    }

	/**
	 * @param long $parentId of parent model.
	 * @param long $parentType assigned to parent model.
	 * @param long $modelId of mapped model.
	 * @return Object having matching $parentId, $parentType and $modelId.
	 */
	public function getByModelId( $parentId, $parentType, $modelId ) {

		$modelClass	= self::$modelClass;

		return $modelClass::findByModelId( $parentId, $parentType, $modelId );
	}

	/**
	 * @param long $parentId of parent model.
	 * @param long $parentType assigned to parent model.
	 * @return array of model mappings having matching $parentId and $parentType.
	 */
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

	public function activateByModelId( $parentId, $parentType, $modelId ) {

		$model = $this->getByModelId( $parentId, $parentType, $modelId );

		if( isset( $model ) ) {

			$this->activate( $model );
		}
	}

	public function disable( $model ) {

		$model->active	= false;

		$model->update();

		return $model;
	}

	public function disableByModelId( $parentId, $parentType, $modelId, $delete = false ) {

		$model = $this->getByModelId( $parentId, $parentType, $modelId );

		if( isset( $model ) ) {

			// Hard delete
			if( $delete ) {

				$model->delete();
			}
			// Soft delete
			else {

				$this->disable( $model );
			}
		}
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