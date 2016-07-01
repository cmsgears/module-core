<?php
namespace cmsgears\core\common\services\base;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\base\Attribute;

use cmsgears\core\common\services\interfaces\base\IAttributeService;

abstract class AttributeService extends EntityService implements IAttributeService {

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

	// AttributeService ----------------------

	// Data Provider ------

	// Read ---------------

    // Read - Models ---

	public function getByType( $modelId, $type ) {

		return self::findByType( $modelId, $type );
	}

	public function getByName( $modelId, $name ) {

		return self::findByName( $modelId, $name );
	}

	public function getByTypeName( $modelId, $type, $name ) {

		return self::findByTypeName( $modelId, $type, $name );
	}

	public function getOrInitByTypeName( $modelId, $type, $name, $valueType = 'text' ) {

		$attribute	= $this->getByTypeName( $modelId, $type, $name );

		if( !isset( $attribute ) ) {

			$modelClass				= static::$modelClass;

			$attribute				= new $modelClass();
			$attribute->modelId		= $modelId;
			$attribute->name		= $name;
			$attribute->type		= $type;
			$attribute->valueType	= $valueType;
		}

		return $attribute;
	}

    // Read - Lists ----

    // Read - Maps -----

	public function getNameValueMapByType( $modelId, $type ) {

		$config[ 'conditions' ][ 'modelId' ] 	= $modelId;
		$config[ 'conditions' ][ 'type' ] 		= $type;

		return $this->getNameValueMap( $config );
	}

	public function getObjectMapByType( $modelId, $type ) {

		$config[ 'conditions' ][ 'modelId' ] 	= $modelId;
		$config[ 'conditions' ][ 'type' ] 		= $type;

		return $this->getObjectMap( $config );
	}

	// Read - Others ---

	// Create -------------

 	public function create( $model, $config = [] ) {

		if( !isset( $model->label ) || strlen( $model->label ) <= 0 ) {

			$model->label = $model->name;
		}

		$model->save();

		return $model;
 	}

	// Update -------------

	public function update( $model, $config = [] ) {

        $existingModel  = self::findByTypeName( $model->listingId, $model->type, $model->name );

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

		$parent	= $config[ 'listing' ];

		foreach( $models as $model ) {

			if( $model->listingId == $parent->id ) {

				self::update( $model );
			}
		}
	}

	public function updateMultipleByForm( $form, $config = [] ) {

		$attributes = $form->getArrayToStore();

		foreach ( $attributes as $attribute ) {

			if( !isset( $attribute[ 'valueType' ] ) ) {

				$attribute[ 'valueType' ]	= Attribute::VALUE_TYPE_TEXT;
			}

			$model			= self::findOrGetByTypeName( $config[ 'modelId' ], $config[ 'type' ], $attribute[ 'name' ], $attribute[ 'valueType' ] );

			$model->value	= $attribute[ 'value' ];
			$model->label	= $form->getAttributeLabel( $attribute[ 'name' ] );

			self::update( $model );
		}
 	}

	// Delete -------------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// AttributeService ----------------------

	// Data Provider ------

	// Read ---------------

    // Read - Models ---

	public static function findByType( $modelId, $type ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByType( $modelId, $type );
	}

	public static function findByName( $modelId, $name ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByName( $modelId, $name );
	}

	public static function findByTypeName( $modelId, $type, $name ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByTypeName( $modelId, $type, $name );
	}

    // Read - Lists ----

    // Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------
}

?>