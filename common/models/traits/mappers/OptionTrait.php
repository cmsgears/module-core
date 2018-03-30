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
use cmsgears\core\common\models\resources\Option;
use cmsgears\core\common\models\mappers\ModelOption;

/**
 * OptionTrait can be used to map options to relevant models.
 */
trait OptionTrait {

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

	// OptionTrait ---------------------------

	/**
	 * @inheritdoc
	 */
	public function getModelOptions() {

		$modelOptionTable = ModelOption::tableName();

		return $this->hasMany( ModelOption::class, [ 'parentId' => 'id' ] )
			->where( "$modelOptionTable.parentType='$this->modelType'" );
	}

	/**
	 * @inheritdoc
	 */
	public function getActiveModelOptions() {

		$modelOptionTable = ModelOption::tableName();

		return $this->hasMany( ModelOption::class, [ 'parentId' => 'id' ] )
			->where( "$modelOptionTable.parentType='$this->modelType' AND $modelOptionTable.active=1" );
	}

	/**
	 * @inheritdoc
	 */
	public function getModelOptionsByType( $type, $active = true ) {

		$modelOptionTable = ModelOption::tableName();

		return $this->hasOne( ModelOption::class, [ 'parentId' => 'id' ] )
			->where( "$modelOptionTable.parentType=:ptype AND $modelOptionTable.type=:type AND $modelOptionTable.active=:active", [ ':ptype' => $this->modelType, ':type' => $type, ':active' => $active ] )->all();
	}

	/**
	 * @inheritdoc
	 */
	public function getOptions() {

		$modelOptionTable = ModelOption::tableName();

		return $this->hasMany( Option::class, [ 'id' => 'modelId' ] )
			->viaTable( $modelOptionTable, [ 'parentId' => 'id' ],
				function( $query ) use( &$modelOptionTable ) {

					$query->onCondition( [ "$modelOptionTable.parentType" => $this->modelType ] );
				}
			);
	}

	/**
	 * @inheritdoc
	 */
	public function getActiveOptions() {

		$modelOptionTable = ModelOption::tableName();

		return $this->hasMany( Option::class, [ 'id' => 'modelId' ] )
			->viaTable( $modelOptionTable, [ 'parentId' => 'id' ],
				function( $query ) use( &$modelOptionTable ) {

					$query->onCondition( [ "$modelOptionTable.parentType" => $this->modelType, "$modelOptionTable.active" => true ] );
				}
			);
	}

	/**
	 * @inheritdoc
	 */
	public function getOptionsByType( $type, $active = true ) {

		$modelOptionTable = ModelOption::tableName();

		return $this->hasMany( Option::class, [ 'id' => 'modelId' ] )
			->viaTable( $modelOptionTable, [ 'parentId' => 'id' ],
				function( $query ) use( &$type, &$active, &$modelOptionTable ) {

					$query->onCondition( [ "$modelOptionTable.parentType" => $this->modelType, "$modelOptionTable.type" => $type, "$modelOptionTable.active" => $active ] );
				}
			)->all();
	}

	// Category specific methods

	/**
	 * @inheritdoc
	 */
	public function getOptionIdListByCategoryId( $categoryId, $active = true ) {

		$optionTable	= Option::tableName();

		$options		= null;
		$optionsList	= [];

		if( $active ) {

			$options = $this->getActiveOptions()->where( [ "$optionTable.categoryId" => $categoryId ] )->all();
		}
		else {

			$options = $this->getOptions()->where( [ "$optionTable.categoryId" => $categoryId ] )->all();
		}

		foreach( $options as $option ) {

			array_push( $optionsList, $option->id );
		}

		return $optionsList;
	}

	/**
	 * @inheritdoc
	 */
	public function getOptionsCsvByCategoryId( $categoryId, $active = true ) {

		$optionTable	= Option::tableName();

		$options		= null;
		$optionsCsv		= [];

		if( $active ) {

			$options = $this->getActiveOptions()->where( [ "$optionTable.categoryId" => $categoryId ] )->all();
		}
		else {

			$options = $this->getOptions()->where( [ "$optionTable.categoryId" => $categoryId ] )->all();
		}

		foreach ( $options as $option ) {

			$optionsCsv[] = $option->name;
		}

		return implode( ", ", $optionsCsv );
	}

	/**
	 * @inheritdoc
	 */
	public function getOptionsByCategoryId( $categoryId, $active = true ) {

		$optionTable	= Option::tableName();

		$options		= null;

		if( $active ) {

			$options = $this->getActiveOptions()->where( [ "$optionTable.categoryId" => $categoryId ] )->all();
		}
		else {

			$options = $this->getOptions()->where( [ "$optionTable.categoryId" => $categoryId ] )->all();
		}

		return $options;
	}

	// Static Methods ----------------------------------------------

	// Yii classes ---------------------------

	// CMG classes ---------------------------

	// OptionTrait ---------------------------

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
