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

	public function getByTypeName( $parentId, $parentType, $type, $name ) {

		return ModelAttribute::findByTypeName( $parentId, $parentType, $type, $name );
	}

	public function getOrInitByTypeName( $parentId, $parentType, $type, $name, $valueType = 'text' ) {

		$attribute	= ModelAttribute::findByTypeName( $parentId, $parentType, $type, $name );

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

	public function getObjectMapByType( $parentId, $parentType, $type ) {

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

        $existingModel  = self::findByTypeName( $model->parentId, $model->parentType, $model->type, $model->name );

		// Create if it does not exist
		if( !isset( $existingModel ) ) {

			return self::create( $model );
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

				self::update( $model );
			}
		}
	}

	public function updateMultipleByForm( $form, $config = [] ) {

		$attributes = $form->getArrayToStore();

		foreach ( $attributes as $attribute ) {

			if( !isset( $attribute[ 'valueType' ] ) ) {

				$attribute[ 'valueType' ]	= ModelAttribute::VALUE_TYPE_TEXT;
			}

			$model			= self::findOrGetByTypeName( $config[ 'parentId' ], $config[ 'parentType' ], $config[ 'type' ], $attribute[ 'name' ], $attribute[ 'valueType' ] );

			$model->value	= $attribute[ 'value' ];
			$model->label	= $form->getAttributeLabel( $attribute[ 'name' ] );

			self::update( $model );
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
