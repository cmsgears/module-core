<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\traits\resources;

/**
 * DataTrait provides methods specific to managing data object.
 */
trait DataTrait {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii classes ---------------------------

	// CMG interfaces ------------------------

	// CMG classes ---------------------------

	// Validators ----------------------------

	// DataTrait ------------------------------

	/**
	 * @inheritdoc
	 */
	public function generateJsonFromDataObject( $dataObject ) {

		$data = json_encode( $dataObject );

		$this->data	= $data;
	}

	/**
	 * @inheritdoc
	 */
	public function generateDataObjectFromJson( $assoc = false ) {

		$obj = json_decode( $this->data, $assoc );

		return (object)$obj;
	}

	/**
	 * @inheritdoc
	 */
	public function getDataMeta( $name, $assoc = false ) {

		$object	= $this->generateDataObjectFromJson( $assoc );

		if( isset( $object->$name ) ) {

			return $object->$name;
		}

		return null;
	}

	public function getDataKeyMeta( $name, $assoc = false ) {

		$object	= $this->generateDataObjectFromJson( $assoc );
		$config	= 'data';

		if( isset( $object->$config ) && isset( $object->$config->$name ) ) {

			return $object->$config->$name;
		}

		return null;
	}

	public function getDataAttributeMeta( $name, $assoc = false ) {

		$object	= $this->generateDataObjectFromJson( $assoc );
		$config	= 'attributes';

		if( isset( $object->$config ) && isset( $object->$config->$name ) ) {

			return $object->$config->$name;
		}

		return null;
	}

	public function getDataConfigMeta( $name, $assoc = false ) {

		$object	= $this->generateDataObjectFromJson( $assoc );
		$config	= 'config';

		if( isset( $object->$config ) && isset( $object->$config->$name ) ) {

			return $object->$config->$name;
		}

		return null;
	}

	public function getDataSettingMeta( $name, $assoc = false ) {

		$object	= $this->generateDataObjectFromJson( $assoc );
		$config	= 'settings';

		if( isset( $object->$config ) && isset( $object->$config->$name ) ) {

			return $object->$config->$name;
		}

		return null;
	}

	public function getDataPluginMeta( $name, $assoc = false ) {

		$object	= $this->generateDataObjectFromJson( $assoc );
		$config	= 'plugins';

		if( isset( $object->$config ) && isset( $object->$config->$name ) ) {

			return $object->$config->$name;
		}

		return null;
	}

	public function getDataCustomMeta( $type, $name, $assoc = false ) {

		$object	= $this->generateDataObjectFromJson( $assoc );

		if( isset( $object->$type ) && isset( $object->$type->$name ) ) {

			return $object->$type->$name;
		}

		return null;
	}

	/**
	 * @inheritdoc
	 */
	public function setDataMeta( $name, $value, $assoc = false ) {

		// Convert data to object
		$object	= $this->generateDataObjectFromJson( $assoc );

		// Add/Update meta
		$object->$name = $value;

		// Convert object back to data
		$this->generateJsonFromDataObject( $object );
	}

	/**
	 * @inheritdoc
	 */
	public function updateDataMeta( $name, $value, $assoc = false ) {

		// set object property
		$this->setDataMeta( $name, $value, $assoc );

		// Save model meta state
		$this->update();
	}

	/**
	 * @inheritdoc
	 */
	public function unsetDataMeta( $name, $assoc = false ) {

		// Convert data to object
		$object	= $this->generateDataObjectFromJson( $assoc );

		// Remove meta
		unset( $object->$name );

		// Convert object back to data
		$this->generateJsonFromDataObject( $object );
	}

	/**
	 * @inheritdoc
	 */
	public function removeDataMeta( $name, $assoc = false ) {

		// Convert data to object
		$object	= $this->generateDataObjectFromJson( $assoc );

		// Remove meta
		unset( $object->$name );

		// Convert object back to data
		$this->generateJsonFromDataObject( $object );

		// Save model meta state
		$this->update();
	}

	// Static Methods ----------------------------------------------

	// Yii classes ---------------------------

	// CMG classes ---------------------------

	// DataTrait -----------------------------

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
