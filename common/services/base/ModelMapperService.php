<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\base;

// CMG Imports
use cmsgears\core\common\services\interfaces\base\IModelMapperService;

/**
 * ModelMapperService defines commonly used methods specific to model mapper. It provide several
 * useful and commonly used methods by mapper.
 *
 * The model mapper must have modelId, parentId and parentType attributes where
 * modelId is the id of model mapped to parent for given parentId and parentType.
 *
 * The mapper might also provide few more common attributes including active, order and type.
 *
 * @since 1.0.0
 */
abstract class ModelMapperService extends ActiveRecordService implements IModelMapperService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

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

	// ModelMapperService --------------------

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

			return $this->activate( $model );
		}
		else {

			return $this->createByParams( [ 'parentId' => $parentId, 'parentType' => $parentType, 'modelId' => $modelId, 'active' => true ] );
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

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// ModelMapperService --------------------

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