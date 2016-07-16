<?php
namespace cmsgears\core\common\services\resources;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\resources\ModelAttribute;

use cmsgears\core\common\services\interfaces\resources\IModelAttributeService;

/**
 * The class ModelAttributeService is base class to perform database activities for ModelAttribute Entity.
 */
class ModelAttributeService extends \cmsgears\core\common\services\base\EntityService implements IModelAttributeService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\core\common\models\resources\ModelAttribute';

	public static $modelTable	= CoreTables::TABLE_MODEL_ATTRIBUTE;

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

	// ModelAttributeService -----------------

	// Data Provider ------

	// Read ---------------

    // Read - Models ---

	public function getByType( $parentId, $parentType, $type ) {

		return ModelAttribute::findByType( $parentId, $parentType, $type );
	}

	public function getByNameType( $parentId, $parentType, $name, $type ) {

		return ModelAttribute::findByNameType( $parentId, $parentType, $name, $type );
	}

	public function initByNameType( $parentId, $parentType, $name, $type, $valueType = ModelAttribute::VALUE_TYPE_TEXT ) {

		$attribute	= ModelAttribute::findByNameType( $parentId, $parentType, $name, $type );

		if( !isset( $attribute ) ) {

			$attribute				= new ModelAttribute();
			$attribute->parentId	= $parentId;
			$attribute->parentType	= $parentType;
			$attribute->name		= $name;
			$attribute->type		= $type;
			$attribute->valueType	= $valueType;
		}

		return $attribute;
	}

    // Read - Lists ----

    // Read - Maps -----

	public function getNameValueMapByType( $parentId, $parentType, $type ) {

		$config[ 'conditions' ][ 'parentId' ] 	= $parentId;
		$config[ 'conditions' ][ 'parentType' ] = $parentType;
		$config[ 'conditions' ][ 'type' ] 		= $type;

		return $this->getNameValueMap( $config );
	}

	public function getIdObjectMapByType( $parentId, $parentType, $type ) {

		$config[ 'conditions' ][ 'parentId' ] 	= $parentId;
		$config[ 'conditions' ][ 'parentType' ] = $parentType;
		$config[ 'conditions' ][ 'type' ] 		= $type;

		return $this->getObjectMap( $config );
	}

	public function getNameObjectMapByType( $parentId, $parentType, $type ) {

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

			$model->valueType = ModelAttribute::VALUE_TYPE_TEXT;
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

		$attributes = $form->getArrayToStore();

		foreach ( $attributes as $attribute ) {

			if( !isset( $attribute[ 'valueType' ] ) ) {

				$attribute[ 'valueType' ]	= ModelAttribute::VALUE_TYPE_TEXT;
			}

			$model			= $this->initByNameType( $config[ 'parentId' ], $config[ 'parentType' ], $attribute[ 'name' ], $config[ 'type' ], $attribute[ 'valueType' ] );

			$model->value	= $attribute[ 'value' ];
			$model->label	= $form->getAttributeLabel( $attribute[ 'name' ] );

			$this->update( $model );
		}
 	}

	// Delete -------------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// ModelAttributeService -----------------

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
