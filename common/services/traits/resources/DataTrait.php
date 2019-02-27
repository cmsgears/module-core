<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\traits\resources;

// CMG Imports
use cmsgears\core\common\models\forms\Meta;

/**
 * Used by services with base model having data trait.
 *
 * @since 1.0.0
 */
trait DataTrait {

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// DataTrait -----------------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	/**
	 * Returns the Meta object using given key from data object.
	 *
	 * @param \cmsgears\core\common\models\base\ActiveRecord $model
	 * @param string $key
	 * @param boolean $assoc
	 * @return boolean|\cmsgears\core\common\models\forms\Meta
	 */
	public function getDataMeta( $model, $key, $assoc = false ) {

		$object	= $model->generateDataObjectFromJson( $assoc );
		$meta	= new Meta();

		if( isset( $object->$key ) ) {

			$meta->key		= $key;
			$meta->value	= $object->$key;

			return $meta;
		}

		return false;
	}

	/**
	 * Returns the Meta object using given key from data object using data key.
	 *
	 * @param \cmsgears\core\common\models\base\ActiveRecord $model
	 * @param string $key
	 * @param boolean $assoc
	 * @return boolean|\cmsgears\core\common\models\forms\Meta
	 */
	public function getDataKeyMeta( $model, $key, $assoc = false ) {

		$object	= $model->generateDataObjectFromJson( $assoc );
		$config	= 'data';
		$meta	= new Meta();

		if( isset( $object->$config ) && isset( $object->$config->$key ) ) {

			$meta->key		= $key;
			$meta->value	= $object->$config->$key;

			return $meta;
		}

		return false;
	}

	/**
	 * Returns the Meta object using given key from data object using attributes key.
	 *
	 * @param \cmsgears\core\common\models\base\ActiveRecord $model
	 * @param string $key
	 * @param boolean $assoc
	 * @return boolean|\cmsgears\core\common\models\forms\Meta
	 */
	public function getDataAttributeMeta( $model, $key, $assoc = false ) {

		$object	= $model->generateDataObjectFromJson( $assoc );
		$config	= 'attributes';
		$meta	= new Meta();

		if( isset( $object->$config ) && isset( $object->$config->$key ) ) {

			$meta->key		= $key;
			$meta->value	= $object->$config->$key;

			return $meta;
		}

		return false;
	}

	/**
	 * Returns the Meta object using given key from data object using config key.
	 *
	 * @param \cmsgears\core\common\models\base\ActiveRecord $model
	 * @param string $key
	 * @param boolean $assoc
	 * @return boolean|\cmsgears\core\common\models\forms\Meta
	 */
	public function getDataConfigMeta( $model, $key, $assoc = false ) {

		$object	= $model->generateDataObjectFromJson( $assoc );
		$config	= 'config';
		$meta	= new Meta();

		if( isset( $object->$config ) && isset( $object->$config->$key ) ) {

			$meta->key		= $key;
			$meta->value	= $object->$config->$key;

			return $meta;
		}

		return false;
	}

	/**
	 * Returns the Meta object using given key from data object using settings key.
	 *
	 * @param \cmsgears\core\common\models\base\ActiveRecord $model
	 * @param string $key
	 * @param boolean $assoc
	 * @return boolean|\cmsgears\core\common\models\forms\Meta
	 */
	public function getDataSettingMeta( $model, $key, $assoc = false ) {

		$object	= $model->generateDataObjectFromJson( $assoc );
		$config	= 'settings';
		$meta	= new Meta();

		if( isset( $object->$config ) && isset( $object->$config->$key ) ) {

			$meta->key		= $key;
			$meta->value	= $object->$config->$key;

			return $meta;
		}

		return false;
	}

	/**
	 * Returns the Meta object using given type and key from data object.
	 *
	 * @param \cmsgears\core\common\models\base\ActiveRecord $model
	 * @param string $type
	 * @param string $key
	 * @param boolean $assoc
	 * @return boolean|\cmsgears\core\common\models\forms\Meta
	 */
	public function getDataCustomMeta( $model, $type, $key, $assoc = false ) {

		$object	= $model->generateDataObjectFromJson( $assoc );
		$meta	= new Meta();

		if( isset( $object->$type ) && isset( $object->$type->$key ) ) {

			$meta->key		= $key;
			$meta->value	= $object->$type->$key;

			return $meta;
		}

		return false;
	}

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	/**
	 * Update the data object using given key and value.
	 *
	 * @param \cmsgears\core\common\models\base\ActiveRecord $model
	 * @param string $key
	 * @param mixed $value
	 * @return \cmsgears\core\common\models\base\ActiveRecord
	 */
	public function updateDataMeta( $model, $key, $value ) {

		$model->updateDataMeta( $key, $value );

		$model->refresh();

		return $model;
	}

	/**
	 * Update the data object using multiple key, value pairs from the params.
	 *
	 * @param \cmsgears\core\common\models\base\ActiveRecord $model
	 * @param array $params
	 * @param array $config
	 * @return \cmsgears\core\common\models\base\ActiveRecord
	 */
	public function updateDataByParams( $model, $params = [], $config = [] ) {

		foreach( $params as $key => $value ) {

			$model->setDataMeta( $key, $value );
		}

		$model->update();

		return $model;
    }

	/**
	 * Update the data object using meta model.
	 *
	 * @param \cmsgears\core\common\models\base\ActiveRecord $model
	 * @param \cmsgears\core\common\models\forms\Meta $meta
	 * @return \cmsgears\core\common\models\base\ActiveRecord
	 */
	public function updateDataMetaObj( $model, $meta ) {

		$model->updateDataMeta( $meta->key, $meta->value );

		$model->refresh();

		return $model;
	}

	/**
	 * Update the data object using data key and given meta object.
	 *
	 * @param \cmsgears\core\common\models\base\ActiveRecord $model
	 * @param \cmsgears\core\common\models\forms\Meta $meta
	 * @return \cmsgears\core\common\models\base\ActiveRecord
	 */
	public function updateDataKeyObj( $model, $meta ) {

		$data	= $model->getDataMeta( 'data' );
		$key	= $meta->key;
		$data	= !empty( $data ) ? $data : new \StdClass();

		$data->$key = $meta->value;

		$model->updateDataMeta( 'data', $data );

		$model->refresh();

		return $model;
	}

	/**
	 * Update the data object using attributes key and given meta object.
	 *
	 * @param \cmsgears\core\common\models\base\ActiveRecord $model
	 * @param \cmsgears\core\common\models\forms\Meta $meta
	 * @return \cmsgears\core\common\models\base\ActiveRecord
	 */
	public function updateDataAttributeObj( $model, $meta ) {

		$attributes = $model->getDataMeta( 'attributes' );
		$key		= $meta->key;
		$attributes	= !empty( $attributes ) ? $attributes : new \StdClass();

		$attributes->$key = $meta->value;

		$model->updateDataMeta( 'attributes', $attributes );

		$model->refresh();

		return $model;
	}

	/**
	 * Update the data object using config key and given meta object.
	 *
	 * @param \cmsgears\core\common\models\base\ActiveRecord $model
	 * @param \cmsgears\core\common\models\forms\Meta $meta
	 * @return \cmsgears\core\common\models\base\ActiveRecord
	 */
	public function updateDataConfigObj( $model, $meta ) {

		$config = $model->getDataMeta( 'config' );
		$key	= $meta->key;
		$config	= !empty( $config ) ? $config : new \StdClass();

		$config->$key = $meta->value;

		$model->updateDataMeta( 'config', $config );

		$model->refresh();

		return $model;
	}

	/**
	 * Update the data object using settings key and given meta object.
	 *
	 * @param \cmsgears\core\common\models\base\ActiveRecord $model
	 * @param \cmsgears\core\common\models\forms\Meta $meta
	 * @return \cmsgears\core\common\models\base\ActiveRecord
	 */
	public function updateDataSettingObj( $model, $meta ) {

		$settings	= $model->getDataMeta( 'settings' );
		$key		= $meta->key;
		$settings	= !empty( $settings ) ? $settings : new \StdClass();

		$settings->$key = $meta->value;

		$model->updateDataMeta( 'settings', $settings );

		$model->refresh();

		return $model;
	}

	/**
	 * Update the data object using custom key and given meta object.
	 *
	 * @param \cmsgears\core\common\models\base\ActiveRecord $model
	 * @param string $type
	 * @param \cmsgears\core\common\models\forms\Meta $meta
	 * @return \cmsgears\core\common\models\base\ActiveRecord
	 */
	public function updateDataCustomObj( $model, $type, $meta ) {

		$custom = $model->getDataMeta( $type );
		$key	= $meta->key;
		$custom	= !empty( $custom ) ? $custom : new \StdClass();

		$custom->$key = $meta->value;

		$model->updateDataMeta( $type, $custom );

		$model->refresh();

		return $model;
	}

	// Delete -------------

	/**
	 * Update the data object by removing the given key.
	 *
	 * @param \cmsgears\core\common\models\base\ActiveRecord $model
	 * @param string $key
	 * @return \cmsgears\core\common\models\base\ActiveRecord
	 */
	public function removeDataMeta( $model, $key ) {

		$model->removeDataMeta( $key );

		$model->refresh();

		return $model;
	}

	/**
	 * Update the data object by removing the given key using meta object.
	 *
	 * @param \cmsgears\core\common\models\base\ActiveRecord $model
	 * @param \cmsgears\core\common\models\forms\Meta $meta
	 * @return \cmsgears\core\common\models\base\ActiveRecord
	 */
	public function removeDataMetaObj( $model, $meta ) {

		$model->removeDataMeta( $meta->key );

		$model->refresh();

		return $model;
	}

	/**
	 * Update the data object by removing the given key from data.
	 *
	 * @param \cmsgears\core\common\models\base\ActiveRecord $model
	 * @param \cmsgears\core\common\models\forms\Meta $meta
	 * @return \cmsgears\core\common\models\base\ActiveRecord
	 */
	public function removeDataKeyObj( $model, $meta ) {

		$config = $model->getDataMeta( 'data' );
		$rkey	= $meta->key;

		unset( $config->$rkey );

		$model->updateDataMeta( 'data', $config );

		$model->refresh();

		return $model;
	}

	/**
	 * Update the data object by removing the given key from attributes.
	 *
	 * @param \cmsgears\core\common\models\base\ActiveRecord $model
	 * @param \cmsgears\core\common\models\forms\Meta $meta
	 * @return \cmsgears\core\common\models\base\ActiveRecord
	 */
	public function removeDataAttributeObj( $model, $meta ) {

		$config = $model->getDataMeta( 'attributes' );
		$rkey	= $meta->key;

		unset( $config->$rkey );

		$model->updateDataMeta( 'attributes', $config );

		$model->refresh();

		return $model;
	}

	/**
	 * Update the data object by removing the given key from config.
	 *
	 * @param \cmsgears\core\common\models\base\ActiveRecord $model
	 * @param \cmsgears\core\common\models\forms\Meta $meta
	 * @return \cmsgears\core\common\models\base\ActiveRecord
	 */
	public function removeDataConfigObj( $model, $meta ) {

		$config = $model->getDataMeta( 'config' );
		$rkey	= $meta->key;

		unset( $config->$rkey );

		$model->updateDataMeta( 'config', $config );

		$model->refresh();

		return $model;
	}

	/**
	 * Update the data object by removing the given key from settings.
	 *
	 * @param \cmsgears\core\common\models\base\ActiveRecord $model
	 * @param \cmsgears\core\common\models\forms\Meta $meta
	 * @return \cmsgears\core\common\models\base\ActiveRecord
	 */
	public function removeDataSettingObj( $model, $meta ) {

		$config = $model->getDataMeta( 'settings' );
		$rkey	= $meta->key;

		unset( $config->$rkey );

		$model->updateDataMeta( 'settings', $config );

		$model->refresh();

		return $model;
	}

	/**
	 * Update the data object by removing the given key from settings.
	 *
	 * @param \cmsgears\core\common\models\base\ActiveRecord $model
	 * @param string $type
	 * @param \cmsgears\core\common\models\forms\Meta $meta
	 * @return \cmsgears\core\common\models\base\ActiveRecord
	 */
	public function removeDataCustomObj( $model, $key, $meta ) {

		$config = $model->getDataMeta( $key );
		$rkey	= $meta->key;

		unset( $config->$rkey );

		$model->updateDataMeta( $key, $config );

		$model->refresh();

		return $model;
	}

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// DataTrait -----------------------------

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
