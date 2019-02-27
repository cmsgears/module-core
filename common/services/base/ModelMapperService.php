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
use cmsgears\core\common\config\CoreGlobal;

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

	public function getPageByParent( $parentId, $parentType ) {

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		return $this->getPage( [ 'conditions' => [ "$modelTable.parentId" => $parentId, "$modelTable.parentType" => $parentType ] ] );
	}

	// Read ---------------

	// Read - Models ---

	/**
	 * @inheritdoc
	 */
	public function getByParentId( $parentId ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByParentId( $parentId );
	}

	/**
	 * @inheritdoc
	 */
	public function getByParentType( $parentType ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByParentType( $parentType );
	}

	/**
	 * @inheritdoc
	 */
	public function getByParent( $parentId, $parentType ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByParent( $parentId, $parentType );
	}

	/**
	 * @inheritdoc
	 */
	public function getByModelId( $modelId ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByModelId( $modelId );
	}

	/**
	 * @inheritdoc
	 */
	public function getByParentModelId( $parentId, $parentType, $modelId ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByParentModelId( $parentId, $parentType, $modelId );
	}

	/**
	 * @inheritdoc
	 */
	public function getFirstByParentModelId( $parentId, $parentType, $modelId ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findFirstByParentModelId( $parentId, $parentType, $modelId );
	}

	/**
	 * @inheritdoc
	 */
	public function getByModelIdParentType( $modelId, $parentType ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByModelIdParentType( $modelId, $parentType );
	}

	/**
	 * @inheritdoc
	 */
	public function getByParentModelIdType( $parentId, $parentType, $modelId, $type ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByParentModelIdType( $parentId, $parentType, $modelId, $type );
	}

	/**
	 * @inheritdoc
	 */
	public function getFirstByParentModelIdType( $parentId, $parentType, $modelId, $type ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findFirstByParentModelIdType( $parentId, $parentType, $modelId, $type );
	}

	/**
	 * @inheritdoc
	 */
	public function getActiveByParentId( $parentId ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findActiveByParentId( $parentId );
	}

	/**
	 * @inheritdoc
	 */
	public function getActiveByParentType( $parentType ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findActiveByParentType( $parentType );
	}

	/**
	 * @inheritdoc
	 */
	public function getActiveByParent( $parentId, $parentType ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findActiveByParent( $parentId, $parentType );
	}

	/**
	 * @inheritdoc
	 */
	public function getActiveByModelId( $modelId ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findActiveByModelId( $modelId );
	}

	/**
	 * @inheritdoc
	 */
	public function getActiveByParentModelId( $parentId, $parentType, $modelId ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findActiveByParentModelId( $parentId, $parentType, $modelId );
	}

	/**
	 * @inheritdoc
	 */
	public function getFirstActiveByParentModelId( $parentId, $parentType, $modelId ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findFirstActiveByParentModelId( $parentId, $parentType, $modelId );
	}

	/**
	 * @inheritdoc
	 */
	public function getActiveByModelIdParentType( $modelId, $parentType ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findActiveByModelIdParentType( $modelId, $parentType );
	}

	/**
	 * @inheritdoc
	 */
	public function getActiveByParentModelIdType( $parentId, $parentType, $modelId, $type ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findActiveByParentModelIdType( $parentId, $parentType, $modelId, $type );
	}

	/**
	 * @inheritdoc
	 */
	public function getFirstActiveByParentModelIdType( $parentId, $parentType, $modelId, $type ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findFirstActiveByParentModelIdType( $parentId, $parentType, $modelId, $type );
	}

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function create( $model, $config = [] ) {

		if( empty( $model->type ) ) {

			$model->type = CoreGlobal::TYPE_DEFAULT;
		}

		return parent::create( $model, $config );
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		$attributes = isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [ 'type', 'order', 'active' ];

        $config[ 'attributes' ] = $attributes;

		return parent::update( $model, $config );
	}

	// Models having active column

	public function activate( $model ) {

		$model->active = true;

		$model->update();

		return $model;
	}

	public function activateByModelId( $parentId, $parentType, $modelId, $type = null ) {

		$model = $this->getFirstByParentModelId( $parentId, $parentType, $modelId );

		if( isset( $model ) ) {

			return $this->activate( $model );
		}
		else {

			if( empty( $type ) ) {

				$type = CoreGlobal::TYPE_DEFAULT;
			}

			return $this->createByParams( [ 'parentId' => $parentId, 'parentType' => $parentType, 'modelId' => $modelId, 'type' => $type, 'active' => true ] );
		}
	}

	public function disable( $model ) {

		$model->active = false;

		$model->update();

		return $model;
	}

	public function disableByParent( $parentId, $parentType, $delete = false ) {

		$models = $this->getByParent( $parentId, $parentType );

		foreach( $models as $model ) {

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

		$modelClass	= static::$modelClass;

		return $modelClass::deleteByParent( $parentId, $parentType );
	}

	public function deleteByModelId( $modelId ) {

		$modelClass	= static::$modelClass;

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
