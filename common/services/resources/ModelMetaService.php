<?php
namespace cmsgears\core\common\services\resources;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\resources\ModelMeta;

use cmsgears\core\common\services\interfaces\resources\IModelMetaService;

/**
 * The class ModelMetaService is base class to perform database activities for ModelMeta Entity.
 */
class ModelMetaService extends \cmsgears\core\common\services\base\EntityService implements IModelMetaService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\core\common\models\resources\ModelMeta';

	public static $modelTable	= CoreTables::TABLE_MODEL_META;

	public static $parentType	= null;

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

		return ModelMeta::findByType( $parentId, $parentType, $type );
	}

	public function getByNameType( $parentId, $parentType, $name, $type ) {

		return ModelMeta::findByNameType( $parentId, $parentType, $name, $type );
	}

	public function initByNameType( $parentId, $parentType, $name, $type, $valueType = ModelMeta::VALUE_TYPE_TEXT ) {

		$meta	= ModelMeta::findByNameType( $parentId, $parentType, $name, $type );

		if( !isset( $meta ) ) {

			$meta				= new ModelMeta();
			$meta->parentId		= $parentId;
			$meta->parentType	= $parentType;
			$meta->name			= $name;
			$meta->type			= $type;
			$meta->valueType	= $valueType;
		}

		return $meta;
	}

    // Read - Lists ----

    // Read - Maps -----

	public function getNameValueMapByType( $parentId, $parentType, $type ) {

		$config[ 'conditions' ][ 'parentId' ] 	= $parentId;
		$config[ 'conditions' ][ 'parentType' ] = $parentType;
		$config[ 'conditions' ][ 'type' ] 		= $type;

		return $this->getNameValueMap( $config );
	}

	public function getIdMetaMapByType( $parentId, $parentType, $type ) {

		$config[ 'conditions' ][ 'parentId' ] 	= $parentId;
		$config[ 'conditions' ][ 'parentType' ] = $parentType;
		$config[ 'conditions' ][ 'type' ] 		= $type;

		return $this->getObjectMap( $config );
	}

	public function getNameMetaMapByType( $parentId, $parentType, $type ) {

		$config[ 'key' ] = 'name';

		$config[ 'conditions' ][ 'parentId' ] 	= $parentId;
		$config[ 'conditions' ][ 'parentType' ] = $parentType;
		$config[ 'conditions' ][ 'type' ] 		= $type;

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

        $existingModel  = $this->getByNameType( $model->parentId, $model->parentType, $model->name, $model->type );

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

		foreach ( $metas as $meta ) {

			if( !isset( $meta[ 'valueType' ] ) ) {

				$meta[ 'valueType' ]	= ModelMeta::VALUE_TYPE_TEXT;
			}

			$model			= $this->initByNameType( $config[ 'parentId' ], $config[ 'parentType' ], $meta[ 'name' ], $config[ 'type' ], $meta[ 'valueType' ] );

			$model->value	= $meta[ 'value' ];
			$model->label	= $form->getMetaLabel( $meta[ 'name' ] );

			$this->update( $model );
		}
 	}

	// Delete -------------

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
