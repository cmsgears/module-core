<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\traits\mappers;

// CMG Imports
use cmsgears\core\common\models\resources\Location;
use cmsgears\core\common\models\mappers\ModelLocation;

/**
 * It provide methods specific to managing location mappings of respective models.
 */
trait LocationTrait {

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

	// LocationTrait -------------------------

	/**
	 * @inheritdoc
	 */
	public function getModelLocations() {

		$modelLocationTable = ModelLocation::tableName();

		return $this->hasMany( ModelLocation::class, [ 'parentId' => 'id' ] )
			->where( "$modelLocationTable.parentType='$this->modelType'" );
	}

	/**
	 * @inheritdoc
	 */
	public function getActiveModelLocations() {

		$modelLocationTable = ModelLocation::tableName();

		return $this->hasMany( ModelLocation::class, [ 'parentId' => 'id' ] )
			->where( "$modelLocationTable.parentType='$this->modelType' AND $modelLocationTable.active=1" );
	}

	/**
	 * @inheritdoc
	 */
	public function getModelLocationsByType( $type, $active = true ) {

		$modelLocationTable = ModelLocation::tableName();

		return $this->hasOne( ModelLocation::class, [ 'parentId' => 'id' ] )
			->where( "$modelLocationTable.parentType=:ptype AND $modelLocationTable.type=:type AND $modelLocationTable.active=:active", [ ':ptype' => $this->modelType, ':type' => $type, ':active' => $active ] )->all();
	}

	/**
	 * @inheritdoc
	 */
	public function getLocations() {

		$modelLocationTable = ModelLocation::tableName();

		return $this->hasMany( Location::class, [ 'id' => 'modelId' ] )
			->viaTable( $modelLocationTable, [ 'parentId' => 'id' ],
				function( $query ) use( &$modelLocationTable ) {
					$query->onCondition( [ "$modelLocationTable.parentType" => $this->modelType ] );
				}
			);
	}

	/**
	 * @inheritdoc
	 */
	public function getActiveLocations() {

		$modelLocationTable = ModelLocation::tableName();

		return $this->hasMany( Location::class, [ 'id' => 'modelId' ] )
			->viaTable( $modelLocationTable, [ 'parentId' => 'id' ],
				function( $query ) use( &$modelLocationTable ) {
					$query->onCondition( [ "$modelLocationTable.parentType" => $this->modelType, "$modelLocationTable.active" => true ] );
				}
			);
	}

	/**
	 * @inheritdoc
	 */
	public function getLocationsByType( $type, $active = true ) {

		$modelLocationTable = ModelLocation::tableName();

		return $this->hasMany( Location::class, [ 'id' => 'modelId' ] )
			->viaTable( $modelLocationTable, [ 'parentId' => 'id' ],
				function( $query ) use( &$type, &$active, &$modelLocationTable ) {
					$query->onCondition( [ "$modelLocationTable.parentType" => $this->modelType, "$modelLocationTable.type" => $type, "$modelLocationTable.active" => $active ] );
				}
			)->all();
	}

	// Static Methods ----------------------------------------------

	// Yii classes ---------------------------

	// CMG classes ---------------------------

	// LocationTrait -------------------------

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
