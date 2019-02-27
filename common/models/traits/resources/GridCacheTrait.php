<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\traits\resources;

trait GridCacheTrait {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	private $localGridCache;

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii classes ---------------------------

	// CMG interfaces ------------------------

	// CMG classes ---------------------------

	// Validators ----------------------------

	// GridCacheTrait ------------------------

	/**
	 * @inheritdoc
	 */
	public function isGridCacheValid() {

		return $this->gridCacheValid;
	}

	/**
	 * @inheritdoc
	 */
	public function generateJsonFromGridObject( $dataObject ) {

		$data				= json_encode( $dataObject );
		$this->gridCache	= $data;
	}

	/**
	 * @inheritdoc
	 */
	public function generateGridObjectFromJson( $assoc = false ) {

		$obj = json_decode( $this->gridCache, $assoc );

		return (object)$obj;
	}

	/**
	 * @inheritdoc
	 */
	public function getGridCacheAttribute( $name, $assoc = false ) {

		$object	= $this->generateGridObjectFromJson( $assoc );

		if( isset( $object->$name ) ) {

			return $object->$name;
		}

		return null;
	}

	/**
	 * @inheritdoc
	 */
	public function setGridCacheAttribute( $name, $value, $assoc = false ) {

		// Convert data to object
		$object	= $this->generateGridObjectFromJson( $assoc );

		// Add/Update meta
		$object->$name	= $value;

		// Convert object back to data
		$this->generateJsonFromGridObject( $object );
	}

	/**
	 * @inheritdoc
	 */
	public function updateGridCacheAttribute( $name, $value, $assoc = false ) {

		// set object property
		$this->setGridCacheAttribute( $name, $value, $assoc );

		// Save model meta state
		$this->update();
	}

	/**
	 * @inheritdoc
	 */
	public function removeGridCacheAttribute( $name, $assoc = false ) {

		// Convert data to object
		$object	= $this->generateGridObjectFromJson( $assoc );

		// Remove meta
		unset( $object->$name );

		// Convert object back to data
		$this->generateJsonFromGridObject( $object );

		// Save model meta state
		$this->update();
	}

	// Static Methods ----------------------------------------------

	// Yii classes ---------------------------

	// CMG classes ---------------------------

	// GridCacheTrait ------------------------

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
