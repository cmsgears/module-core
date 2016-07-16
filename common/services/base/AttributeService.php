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

	public function getByName( $modelId, $name ) {

		return self::findByName( $modelId, $name );
	}

	public function getByType( $modelId, $type ) {

		return self::findByType( $modelId, $type );
	}

	public function getByNameType( $modelId, $name, $type ) {

		return self::findByNameType( $modelId, $name, $type );
	}

	public function initByNameType( $modelId, $name, $type, $valueType = 'text' ) {

		$attribute	= $this->getByNameType( $modelId, $name, $type );

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

	public function getIdObjectMapByType( $modelId, $type ) {

		$config[ 'conditions' ][ 'modelId' ] 	= $modelId;
		$config[ 'conditions' ][ 'type' ] 		= $type;

		return $this->getObjectMap( $config );
	}

	public function getNameObjectMapByType( $modelId, $type ) {

		$config[ 'key' ]						= 'name';
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

        $existingModel  = $this->getByNameType( $model->modelId, $model->name, $model->type );

		// Create if it does not exist
		if( !isset( $existingModel ) ) {

			return $this->create( $model );
		}

		if( isset( $model->valueType ) ) {

			return parent::update( $model, [
				'attributes' => [ 'valueType', 'value' ]
			]);
		}
		else {

			return parent::update( $model, [
				'attributes' => [ 'value' ]
			]);
		}
 	}

	public function updateMultiple( $models, $config = [] ) {

		$parent	= $config[ 'parent' ];

		foreach( $models as $model ) {

			if( $model->modelId == $parent->id ) {

				$this->update( $model );
			}
		}
	}

	public function updateMultipleByForm( $form, $config = [] ) {

		$attributes = $form->getArrayToStore();

		foreach ( $attributes as $attribute ) {

			if( !isset( $attribute[ 'valueType' ] ) ) {

				$attribute[ 'valueType' ]	= Attribute::VALUE_TYPE_TEXT;
			}

			$model			= $this->initByNameType( $config[ 'modelId' ], $attribute[ 'name' ], $config[ 'type' ], $attribute[ 'valueType' ] );

			$model->value	= $attribute[ 'value' ];
			$model->label	= $form->getAttributeLabel( $attribute[ 'name' ] );

			$this->update( $model );
		}
 	}

	// Delete -------------

	public function deleteByModelId( $modelId ) {

		$modelClass	= static::$modelClass;

		$modelClass::deleteAll( 'modelId=:id', [ ':id' => $modelId ] );
	}

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// AttributeService ----------------------

	// Data Provider ------

	// Read ---------------

    // Read - Models ---

	public static function findByName( $modelId, $name ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByName( $modelId, $name );
	}

	public static function findByType( $modelId, $type ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByType( $modelId, $type );
	}

	public static function findByNameType( $modelId, $name, $type ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByNameType( $modelId, $name, $type );
	}

    // Read - Lists ----

    // Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------
}
