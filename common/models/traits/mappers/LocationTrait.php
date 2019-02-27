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
			->where( "$modelLocationTable.parentType=:ptype", [ ':ptype' => $this->modelType ] )
			->orderBy( "$modelLocationTable.id DESC" );
	}

	/**
	 * @inheritdoc
	 */
	public function getActiveModelLocations() {

		$modelLocationTable = ModelLocation::tableName();

		return $this->hasMany( ModelLocation::class, [ 'parentId' => 'id' ] )
			->where( "$modelLocationTable.parentType=:ptype AND $modelLocationTable.active=:active", [ ':ptype' => $this->modelType, ':active' => true ] )
			->orderBy( "$modelLocationTable.id DESC" );
	}

	/**
	 * @inheritdoc
	 */
	public function getModelLocationsByType( $type, $active = true ) {

		$modelLocationTable = ModelLocation::tableName();

		return $this->hasMany( ModelLocation::class, [ 'parentId' => 'id' ] )
			->where( "$modelLocationTable.parentType=:ptype AND $modelLocationTable.type=:type AND $modelLocationTable.active=:active", [ ':ptype' => $this->modelType, ':type' => $type, ':active' => $active ] )
			->orderBy( "$modelLocationTable.id DESC" );
	}

	/**
	 * @inheritdoc
	 */
	public function getLocations() {

		$locationTable		= Location::tableName();
		$modelLocationTable	= ModelLocation::tableName();

		return Location::find()
			->leftJoin( $modelLocationTable, "$modelLocationTable.modelId=$locationTable.id" )
			->where( "$modelLocationTable.parentId=:pid AND $modelLocationTable.parentType=:ptype", [ ':pid' => $this->id, ':ptype' => $this->modelType ] )
			->orderBy( [ "$modelLocationTable.order" => SORT_DESC, "$modelLocationTable.id" => SORT_DESC ] )
			->all();
	}

	/**
	 * @inheritdoc
	 */
	public function getActiveLocations() {

		$locationTable		= Location::tableName();
		$modelLocationTable	= ModelLocation::tableName();

		return Location::find()
			->leftJoin( $modelLocationTable, "$modelLocationTable.modelId=$locationTable.id" )
			->where( "$modelLocationTable.parentId=:pid AND $modelLocationTable.parentType=:ptype AND $modelLocationTable.active=:active", [ ':pid' => $this->id, ':ptype' => $this->modelType, ':active' => true ] )
			->orderBy( [ "$modelLocationTable.order" => SORT_DESC, "$modelLocationTable.id" => SORT_DESC ] )
			->all();
	}

	/**
	 * @inheritdoc
	 */
	public function getLocationsByType( $type, $active = true ) {

		$locationTable		= Location::tableName();
		$modelLocationTable	= ModelLocation::tableName();

		return Location::find()
			->leftJoin( $modelLocationTable, "$modelLocationTable.modelId=$locationTable.id" )
			->where( "$modelLocationTable.parentId=:pid AND $modelLocationTable.parentType=:ptype AND $modelLocationTable.type=:type AND $modelLocationTable.active=:active", [ ':pid' => $this->id, ':ptype' => $this->modelType, ':type' => $type, ':active' => $active ] )
			->orderBy( [ "$modelLocationTable.order" => SORT_DESC, "$modelLocationTable.id" => SORT_DESC ] )
			->all();
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
