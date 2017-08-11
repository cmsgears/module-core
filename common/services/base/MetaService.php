<?php
namespace cmsgears\core\common\services\base;

// CMG Imports
use cmsgears\core\common\models\base\Meta;

use cmsgears\core\common\services\interfaces\base\IMetaService;

abstract class MetaService extends EntityService implements IMetaService {

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

	// MetaService ---------------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getByName( $modelId, $name, $first = false ) {

		return self::findByName( $modelId, $name, $first );
	}

	public function getByModelId( $modelId ) {

		return self::findByModelId( $modelId );
	}

	public function getByType( $modelId, $type ) {

		return self::findByType( $modelId, $type );
	}

	public function getByNameType( $modelId, $name, $type ) {

		return self::findByNameType( $modelId, $name, $type );
	}

	public function initByNameType( $modelId, $name, $type, $valueType = 'text' ) {

		$meta	= $this->getByNameType( $modelId, $name, $type );

		if( !isset( $meta ) ) {

			$modelClass			= static::$modelClass;

			// Initialise
			$meta				= new $modelClass();
			$meta->modelId		= $modelId;
			$meta->name			= $name;
			$meta->label		= $name;
			$meta->type			= $type;
			$meta->valueType	= $valueType;

			switch( $valueType ) {

				case Meta::VALUE_TYPE_FLAG: {

					$meta->value = false;
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

	public function getNameValueMapByModelId( $modelId ) {

		$config[ 'conditions' ][ 'modelId' ]	= $modelId;

		return $this->getNameValueMap( $config );
	}

	public function getNameValueMapByType( $modelId, $type ) {

		$config[ 'conditions' ][ 'modelId' ]	= $modelId;
		$config[ 'conditions' ][ 'type' ]		= $type;

		return $this->getNameValueMap( $config );
	}

	public function getIdMetaMapByModelId( $modelId ) {

		$config[ 'conditions' ][ 'modelId' ]	= $modelId;

		return $this->getObjectMap( $config );
	}

	public function getIdMetaMapByType( $modelId, $type ) {

		$config[ 'conditions' ][ 'modelId' ]	= $modelId;
		$config[ 'conditions' ][ 'type' ]		= $type;

		return $this->getObjectMap( $config );
	}

	public function getNameMetaMapByType( $modelId, $type ) {

		$config[ 'key' ]						= 'name';
		$config[ 'conditions' ][ 'modelId' ]	= $modelId;
		$config[ 'conditions' ][ 'type' ]		= $type;

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

		$existingModel	= $this->getByNameType( $model->modelId, $model->name, $model->type );

		// Create if it does not exist
		if( !isset( $existingModel ) ) {

			return $this->create( $model );
		}

		if( isset( $model->valueType ) ) {

			$attributes	= [ 'valueType', 'value' ];
		}
		else {

			$attributes	= [ 'value' ];
		}

		if( !isset( $config[ 'attributes' ] ) ) {

			$config[ 'attributes' ]	= $attributes;
		}

		return parent::update( $model, $config );
	}

	public function updateByParams( $params = [], $config = [] ) {

		$modelId	= $params[ 'modelId' ];
		$name		= $params[ 'name' ];
		$type		= $params[ 'type' ];

		$model		= $this->getByNameType( $modelId, $name, $type );

		if( isset( $model ) ) {

			$model->value	= $params[ 'value' ];

			return parent::update( $model, [
				'selective' => false,
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

		$metas = $form->getArrayToStore();

		foreach ( $metas as $meta ) {

			if( !isset( $meta[ 'valueType' ] ) ) {

				$meta[ 'valueType' ] = Meta::VALUE_TYPE_TEXT;
			}

			$model = $this->initByNameType( $config[ 'modelId' ], $meta[ 'name' ], $config[ 'type' ], $meta[ 'valueType' ] );

			$model->value	= $meta[ 'value' ];
			$model->label	= $form->getAttributeLabel( $meta[ 'name' ] );

			$this->update( $model );
		}
	}

	public function toggle( $modelId, $type, $name, $label ) {

		$model	= $this->getByNameType( $modelId, $name, $type );

		// Create if it does not exist
		if( !isset( $model ) ) {

			$this->createByParams([
				'modelId' => $modelId, 'name' => $name, 'label' => $label, 'type' => $type,
				'valueType' => Meta::VALUE_TYPE_FLAG, 'value' => true
			]);
		}
		else {

			if( $model->value ) {

				$model->value = false;
			}
			else {

				$model->value = true;
			}

			$model->update();
		}
	}

	// Delete -------------

	public function deleteByModelId( $modelId ) {

		$modelClass	= static::$modelClass;

		$modelClass::deleteAll( 'modelId=:id', [ ':id' => $modelId ] );
	}

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// MetaService ---------------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public static function findByName( $modelId, $name, $first = false ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByName( $modelId, $name, $first );
	}

	public static function findByModelId( $modelId ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByModelId( $modelId );
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
