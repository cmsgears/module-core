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

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function updateDataMeta( $model, $key, $value ) {

		$model->updateDataMeta( $key, $value );

		$model->refresh();

		return $model;
	}

	public function updateDataByParams( $model, $params = [], $config = [] ) {

		foreach( $params as $key => $value ) {

			$model->setDataMeta( $key, $value );
		}

		$model->update();
    }

	public function updateDataMetaObj( $model, $meta ) {

		$model->updateDataMeta( $meta->key, $meta->value );

		$model->refresh();

		return $model;
	}

	public function updateDataConfigObj( $model, $meta ) {

		$config = $model->getDataMeta( 'config' );
		$key	= $meta->key;
		$config	= !empty( $config ) ? $config : new \StdClass();

		$config->$key = $meta->value;

		$model->updateDataMeta( 'config', $config );

		$model->refresh();

		return $model;
	}

	public function updateDataSettingObj( $model, $meta ) {

		$config = $model->getDataMeta( 'settings' );
		$key	= $meta->key;
		$config	= !empty( $config ) ? $config : new \StdClass();

		$config->$key = $meta->value;

		$model->updateDataMeta( 'settings', $config );

		$model->refresh();

		return $model;
	}

	// Delete -------------

	public function removeDataMeta( $model, $key ) {

		$model->removeDataMeta( $key );

		$model->refresh();

		return $model;
	}

	public function removeDataMetaObj( $model, $meta ) {

		$model->removeDataMeta( $meta->key );

		$model->refresh();

		return $model;
	}

	public function removeDataConfigObj( $model, $meta ) {

		$config = $model->getDataMeta( 'config' );

		unset( $meta->key );

		$model->updateDataMeta( 'config', $config );

		$model->refresh();

		return $model;
	}

	public function removeDataSettingObj( $model, $meta ) {

		$config = $model->getDataMeta( 'settings' );

		unset( $meta->key );

		$model->updateDataMeta( 'settings', $config );

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
