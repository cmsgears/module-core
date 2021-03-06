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
use cmsgears\core\common\models\entities\ObjectData;
use cmsgears\core\common\models\mappers\ModelObject;

/**
 * ObjectTrait can be used to associate objects to relevant models.
 */
trait ObjectTrait {

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

	// ObjectTrait ---------------------------

	/**
	 * @inheritdoc
	 */
	public function getModelObjects() {

		$modelObjectTable = ModelObject::tableName();

		return $this->hasMany( ModelObject::class, [ 'parentId' => 'id' ] )
			->where( "$modelObjectTable.parentType='$this->modelType'" )
			->orderBy( [ "$modelObjectTable.order" => SORT_DESC, "$modelObjectTable.id" => SORT_DESC ] );
	}

	/**
	 * @inheritdoc
	 */
	public function getActiveModelObjects() {

		$modelObjectTable = ModelObject::tableName();

		return $this->hasMany( ModelObject::class, [ 'parentId' => 'id' ] )
			->where( "$modelObjectTable.parentType='$this->modelType' AND $modelObjectTable.active=1" )
			->orderBy( [ "$modelObjectTable.order" => SORT_DESC, "$modelObjectTable.id" => SORT_DESC ] );
	}

	/**
	 * @inheritdoc
	 */
	public function getModelObjectsByType( $type, $active = true ) {

		$modelObjectTable = ModelObject::tableName();

		if( $active ) {

			return $this->hasMany( ModelObject::class, [ 'parentId' => 'id' ] )
				->where( "$modelObjectTable.parentType=:ptype AND $modelObjectTable.type=:type AND $modelObjectTable.active=:active", [ ':ptype' => $this->modelType, ':type' => $type, ':active' => $active ] )
				->orderBy( [ "$modelObjectTable.order" => SORT_DESC, "$modelObjectTable.id" => SORT_DESC ] );
		}

		return $this->hasMany( ModelObject::class, [ 'parentId' => 'id' ] )
			->where( "$modelObjectTable.parentType=:ptype AND $modelObjectTable.type=:type", [ ':ptype' => $this->modelType, ':type' => $type ] )
			->orderBy( [ "$modelObjectTable.order" => SORT_DESC, "$modelObjectTable.id" => SORT_DESC ] );
	}

	/**
	 * @inheritdoc
	 */
	public function getObjects() {

		$objectTable		= ObjectData::tableName();
		$modelObjectTable	= ModelObject::tableName();

		return ObjectData::find()
			->leftJoin( $modelObjectTable, "$modelObjectTable.modelId=$objectTable.id" )
			->where( "$modelObjectTable.parentId=:pid AND $modelObjectTable.parentType=:ptype", [ ':pid' => $this->id, ':ptype' => $this->modelType ] )
			->orderBy( [ "$modelObjectTable.order" => SORT_DESC, "$modelObjectTable.id" => SORT_DESC ] )
			->all();
	}

	/**
	 * @inheritdoc
	 */
	public function getActiveObjects() {

		$objectTable		= ObjectData::tableName();
		$modelObjectTable	= ModelObject::tableName();

		return ObjectData::find()
			->leftJoin( $modelObjectTable, "$modelObjectTable.modelId=$objectTable.id" )
			->where( "$modelObjectTable.parentId=:pid AND $modelObjectTable.parentType=:ptype AND $modelObjectTable.active=1", [ ':pid' => $this->id, ':ptype' => $this->modelType ] )
			->orderBy( [ "$modelObjectTable.order" => SORT_DESC, "$modelObjectTable.id" => SORT_DESC ] )
			->all();
	}

	/**
	 * @inheritdoc
	 */
	public function getObjectsByType( $type, $active = true ) {

		$objectTable		= ObjectData::tableName();
		$modelObjectTable	= ModelObject::tableName();

		if( $active ) {

			return ObjectData::find()
				->leftJoin( $modelObjectTable, "$modelObjectTable.modelId=$objectTable.id" )
				->where( "$modelObjectTable.parentId=:pid AND $modelObjectTable.parentType=:ptype AND $modelObjectTable.type=:type AND $modelObjectTable.active=:active", [ ':pid' => $this->id, ':ptype' => $this->modelType, ':type' => $type, ':active' => $active ] )
				->orderBy( [ "$modelObjectTable.order" => SORT_DESC, "$modelObjectTable.id" => SORT_DESC ] )
				->all();
		}

		return ObjectData::find()
			->leftJoin( $modelObjectTable, "$modelObjectTable.modelId=$objectTable.id" )
			->where( "$modelObjectTable.parentId=:pid AND $modelObjectTable.parentType=:ptype AND $modelObjectTable.type=:type", [ ':pid' => $this->id, ':ptype' => $this->modelType, ':type' => $type ] )
			->orderBy( [ "$modelObjectTable.order" => SORT_DESC, "$modelObjectTable.id" => SORT_DESC ] )
			->all();
	}

	// Static Methods ----------------------------------------------

	// Yii classes ---------------------------

	// CMG classes ---------------------------

	// ObjectTrait ---------------------------

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
