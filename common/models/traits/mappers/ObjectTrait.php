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
			->orderBy( [ "$modelObjectTable.order" => SORT_DESC, "$modelObjectTable.id" => SORT_ASC ] );
	}

	/**
	 * @inheritdoc
	 */
	public function getActiveModelObjects() {

		$modelObjectTable = ModelObject::tableName();

		return $this->hasMany( ModelObject::class, [ 'parentId' => 'id' ] )
			->where( "$modelObjectTable.parentType='$this->modelType' AND $modelObjectTable.active=1" )
			->orderBy( [ "$modelObjectTable.order" => SORT_DESC, "$modelObjectTable.id" => SORT_ASC ] );
	}

	/**
	 * @inheritdoc
	 */
	public function getModelObjectsByType( $type, $active = true ) {

		$modelObjectTable = ModelObject::tableName();

		return $this->hasOne( ModelObject::class, [ 'parentId' => 'id' ] )
			->where( "$modelObjectTable.parentType=:ptype AND $modelObjectTable.type=:type AND $modelObjectTable.active=:active", [ ':ptype' => $this->modelType, ':type' => $type, ':active' => $active ] )
			->orderBy( [ 'order' => SORT_DESC, 'id' => SORT_ASC ] )->all();
	}

	/**
	 * @inheritdoc
	 */
	public function getObjects() {

		$modelObjectTable = ModelObject::tableName();

		return $this->hasMany( ObjectData::class, [ 'id' => 'modelId' ] )
			->viaTable( $modelObjectTable, [ 'parentId' => 'id' ],
				function( $query ) use( &$modelObjectTable ) {

					$query->onCondition( [ "$modelObjectTable.parentType" => $this->modelType ] );
				}
			);
	}

	/**
	 * @inheritdoc
	 */
	public function getActiveObjects() {

		$modelObjectTable = ModelObject::tableName();

		return $this->hasMany( ObjectData::class, [ 'id' => 'modelId' ] )
			->viaTable( $modelObjectTable, [ 'parentId' => 'id' ],
				function( $query ) use( &$modelObjectTable ) {

					$query->onCondition( [ "$modelObjectTable.parentType" => $this->modelType, "$modelObjectTable.active" => true ] );
				}
			);
	}

	/**
	 * @inheritdoc
	 */
	public function getObjectsByType( $type, $active = true ) {

		$modelObjectTable = ModelObject::tableName();

		return $this->hasMany( ObjectData::class, [ 'id' => 'modelId' ] )
			->viaTable( $modelObjectTable, [ 'parentId' => 'id' ],
				function( $query ) use( &$type, &$active, &$modelObjectTable ) {

					$query->onCondition( [ "$modelObjectTable.parentType" => $this->modelType, "$modelObjectTable.type" => $type, "$modelObjectTable.active" => $active ] );
				}
			)->all();
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
