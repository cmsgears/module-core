<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\resources;

// CMG Imports
use cmsgears\core\common\models\resources\ModelMeta;

use cmsgears\core\common\services\interfaces\resources\IModelMetaService;

use cmsgears\core\common\services\base\ModelResourceService;

/**
 * ModelMetaService provide service methods of model meta.
 *
 * @since 1.0.0
 */
class ModelMetaService extends ModelResourceService implements IModelMetaService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass = '\cmsgears\core\common\models\resources\ModelMeta';

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

	// ModelMetaService ----------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getByType( $parentId, $parentType, $type ) {

		$modelClass = static::$modelClass;

		return $modelClass::findByType( $parentId, $parentType, $type );
	}

	public function getByNameType( $parentId, $parentType, $name, $type ) {

		$modelClass = static::$modelClass;

		return $modelClass::findByNameType( $parentId, $parentType, $name, $type );
	}

	public function initByNameType( $parentId, $parentType, $name, $type, $valueType = ModelMeta::VALUE_TYPE_TEXT, $label = null ) {

		$modelClass = static::$modelClass;

		$meta = $modelClass::findByNameType( $parentId, $parentType, $name, $type );

		if( !isset( $meta ) ) {

			$meta = new $modelClass();

			$meta->parentId		= $parentId;
			$meta->parentType	= $parentType;
			$meta->name			= $name;
			$meta->label		= !empty( $label ) ? $label : $name;
			$meta->type			= $type;
			$meta->active		= true;
			$meta->valueType	= $valueType;

			switch( $valueType ) {

				case ModelMeta::VALUE_TYPE_FLAG: {

					$meta->value = 0;
				}
				default: {

					$meta->value = null;
				}
			}

			// Create & Refresh
			$meta->save();

			$meta->refresh();
		}

		return $meta;
	}

	// Read - Lists ----

	// Read - Maps -----

	public function getNameValueMapByType( $parentId, $parentType, $type ) {

		$config = [];

		$config[ 'conditions' ][ 'parentId' ]	= $parentId;
		$config[ 'conditions' ][ 'parentType' ] = $parentType;
		$config[ 'conditions' ][ 'type' ]		= $type;

		return $this->getNameValueMap( $config );
	}

	public function getIdMetaMapByType( $parentId, $parentType, $type ) {

		$config = [];

		$config[ 'conditions' ][ 'parentId' ]	= $parentId;
		$config[ 'conditions' ][ 'parentType' ] = $parentType;
		$config[ 'conditions' ][ 'type' ]		= $type;

		return $this->getObjectMap( $config );
	}

	public function getNameMetaMapByType( $parentId, $parentType, $type ) {

		$config = [];

		$config[ 'key' ] = 'name';

		$config[ 'conditions' ][ 'parentId' ]	= $parentId;
		$config[ 'conditions' ][ 'parentType' ] = $parentType;
		$config[ 'conditions' ][ 'type' ]		= $type;

		return $this->getObjectMap( $config );
	}

	// Read - Others ---

	// Create -------------

	public function create( $model, $config = [] ) {

		if( !isset( $model->label ) || strlen( $model->label ) <= 0 ) {

			$model->label = $model->name;
		}

		if( !isset( $model->valueType ) ) {

			$model->valueType = ModelMeta::VALUE_TYPE_TEXT;
		}

		$model->save();

		return $model;
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		$existingModel	= $this->getByNameType( $model->parentId, $model->parentType, $model->name, $model->type );

		// Create if it does not exist
		if( !isset( $existingModel ) ) {

			return $this->create( $model );
		}

		if( isset( $model->valueType ) ) {

			$existingModel->copyForUpdateFrom( $model, [ 'valueType', 'value' ] );
		}
		else {

			$existingModel->copyForUpdateFrom( $model, [ 'value' ] );
		}

		$existingModel->update();

		return $existingModel;
	}

	public function updateMultiple( $models, $config = [] ) {

		$parent	= $config[ 'parent' ];

		foreach( $models as $model ) {

			if( $model->parentId == $parent->id ) {

				$this->update( $model );
			}
		}
	}

	public function updateMultipleByForm( $form, $config = [] ) {

		$metas = $form->getArrayToStore();

		foreach( $metas as $meta ) {

			if( !isset( $meta[ 'valueType' ] ) ) {

				$meta[ 'valueType' ] = ModelMeta::VALUE_TYPE_TEXT;
			}

			if( !isset( $meta[ 'label' ] ) ) {

				$meta[ 'label' ] = $form->getAttributeLabel( $meta[ 'name' ] );
			}

			$model = $this->initByNameType( $config[ 'parentId' ], $config[ 'parentType' ], $meta[ 'name' ], $config[ 'type' ], $meta[ 'valueType' ], $meta[ 'label' ] );

			$model->value = $meta[ 'value' ];

			$this->update( $model );
		}
	}

	public function toggle( $model ) {

		if( $model->value ) {

			$model->value = false;
		}
		else {

			$model->value = true;
		}

		$model->update();
	}

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// ModelMetaService ----------------------

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
