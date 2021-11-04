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
			->where( "$modelOptionTable.parentType='$this->modelType'" )
			->orderBy( [ "$modelOptionTable.order" => SORT_DESC, "$modelOptionTable.id" => SORT_DESC ] );
	}

	/**
	 * @inheritdoc
	 */
	public function getActiveModelOptions() {

		$modelOptionTable = ModelOption::tableName();

		return $this->hasMany( ModelOption::class, [ 'parentId' => 'id' ] )
			->where( "$modelOptionTable.parentType='$this->modelType' AND $modelOptionTable.active=1" )
			->orderBy( [ "$modelOptionTable.order" => SORT_DESC, "$modelOptionTable.id" => SORT_DESC ] );
	}

	/**
	 * @inheritdoc
	 */
	public function getModelOptionsByType( $type, $active = true ) {

		$modelOptionTable = ModelOption::tableName();

		if( $active ) {

			return $this->hasMany( ModelOption::class, [ 'parentId' => 'id' ] )
				->where( "$modelOptionTable.parentType=:ptype AND $modelOptionTable.type=:type AND $modelOptionTable.active=:active", [ ':ptype' => $this->modelType, ':type' => $type, ':active' => $active ] )
				->orderBy( [ "$modelOptionTable.order" => SORT_DESC, "$modelOptionTable.id" => SORT_DESC ] )
				->all();
		}

		return $this->hasMany( ModelOption::class, [ 'parentId' => 'id' ] )
			->where( "$modelOptionTable.parentType=:ptype AND $modelOptionTable.type=:type", [ ':ptype' => $this->modelType, ':type' => $type ] )
			->orderBy( [ "$modelOptionTable.order" => SORT_DESC, "$modelOptionTable.id" => SORT_DESC ] )
			->all();
	}

	/**
	 * @inheritdoc
	 */
	public function getOptions() {

		$optionTable		= Option::tableName();
		$modelOptionTable	= ModelOption::tableName();

		return Option::find()
			->leftJoin( $modelOptionTable, "$modelOptionTable.modelId=$optionTable.id" )
			->where( "$modelOptionTable.parentId=:pid AND $modelOptionTable.parentType=:ptype", [ ':pid' => $this->id, ':ptype' => $this->modelType ] )
			->orderBy( [ "$modelOptionTable.order" => SORT_DESC, "$optionTable.name" => SORT_ASC, "$modelOptionTable.id" => SORT_DESC ] )
			->all();
	}

	/**
	 * @inheritdoc
	 */
	public function getActiveOptions() {

		$optionTable		= Option::tableName();
		$modelOptionTable	= ModelOption::tableName();

		return Option::find()
			->leftJoin( $modelOptionTable, "$modelOptionTable.modelId=$optionTable.id" )
			->where( "$modelOptionTable.parentId=:pid AND $modelOptionTable.parentType=:ptype AND $modelOptionTable.active=1", [ ':pid' => $this->id, ':ptype' => $this->modelType ] )
			->orderBy( [ "$modelOptionTable.order" => SORT_DESC, "$optionTable.name" => SORT_ASC, "$modelOptionTable.id" => SORT_DESC ] )
			->all();
	}

	/**
	 * @inheritdoc
	 */
	public function getOptionsByType( $type, $active = true ) {

		$optionTable		= Option::tableName();
		$modelOptionTable	= ModelOption::tableName();

		if( $active ) {

			return Option::find()
				->leftJoin( $modelOptionTable, "$modelOptionTable.modelId=$optionTable.id" )
				->where( "$modelOptionTable.parentId=:pid AND $modelOptionTable.parentType=:ptype AND $modelOptionTable.type=:type AND $modelOptionTable.active=:active", [ ':pid' => $this->id, ':ptype' => $this->modelType, ':type' => $type, ':active' => $active ] )
				->orderBy( [ "$modelOptionTable.order" => SORT_DESC, "$optionTable.name" => SORT_ASC, "$modelOptionTable.id" => SORT_DESC ] )
				->all();
		}

		return Option::find()
			->leftJoin( $modelOptionTable, "$modelOptionTable.modelId=$optionTable.id" )
			->where( "$modelOptionTable.parentId=:pid AND $modelOptionTable.parentType=:ptype AND $modelOptionTable.type=:type", [ ':pid' => $this->id, ':ptype' => $this->modelType, ':type' => $type ] )
			->orderBy( [ "$modelOptionTable.order" => SORT_DESC, "$optionTable.name" => SORT_ASC, "$modelOptionTable.id" => SORT_DESC ] )
			->all();
	}

	// Category specific methods

	/**
	 * @inheritdoc
	 */
	public function getOptionIdListByCategoryId( $categoryId, $active = true ) {

		$optionTable		= Option::tableName();
		$modelOptionTable	= ModelOption::tableName();

		if( $active ) {

			$options = Option::find()
				->leftJoin( $modelOptionTable, "$modelOptionTable.modelId=$optionTable.id" )
				->where( "$modelOptionTable.parentId=:pid AND $modelOptionTable.parentType=:ptype AND $optionTable.categoryId=:cid AND $modelOptionTable.active=:active", [ ':pid' => $this->id, ':ptype' => $this->modelType, ':cid' => $categoryId, ':active' => $active ] )
				->orderBy( [ "$modelOptionTable.order" => SORT_DESC, "$optionTable.name" => SORT_ASC, "$modelOptionTable.id" => SORT_DESC ] )
				->all();
		}
		else {

			$options = Option::find()
				->leftJoin( $modelOptionTable, "$modelOptionTable.modelId=$optionTable.id" )
				->where( "$modelOptionTable.parentId=:pid AND $modelOptionTable.parentType=:ptype AND $optionTable.categoryId=:cid", [ ':pid' => $this->id, ':ptype' => $this->modelType, ':cid' => $categoryId ] )
				->orderBy( [ "$modelOptionTable.order" => SORT_DESC, "$optionTable.name" => SORT_ASC, "$modelOptionTable.id" => SORT_DESC ] )
				->all();
		}

		$optionsList = [];

		foreach( $options as $option ) {

			array_push( $optionsList, $option->id );
		}

		return $optionsList;
	}

	/**
	 * @inheritdoc
	 */
	public function getOptionsCsvByCategoryId( $categoryId, $active = true ) {

		$optionTable		= Option::tableName();
		$modelOptionTable	= ModelOption::tableName();

		if( $active ) {

			$options = Option::find()
				->leftJoin( $modelOptionTable, "$modelOptionTable.modelId=$optionTable.id" )
				->where( "$modelOptionTable.parentId=:pid AND $modelOptionTable.parentType=:ptype AND $optionTable.categoryId=:cid AND $modelOptionTable.active=:active", [ ':pid' => $this->id, ':ptype' => $this->modelType, ':cid' => $categoryId, ':active' => $active ] )
				->orderBy( [ "$modelOptionTable.order" => SORT_DESC, "$optionTable.name" => SORT_ASC, "$modelOptionTable.id" => SORT_DESC ] )
				->all();
		}
		else {

			$options = Option::find()
				->leftJoin( $modelOptionTable, "$modelOptionTable.modelId=$optionTable.id" )
				->where( "$modelOptionTable.parentId=:pid AND $modelOptionTable.parentType=:ptype AND $optionTable.categoryId=:cid", [ ':pid' => $this->id, ':ptype' => $this->modelType, ':cid' => $categoryId ] )
				->orderBy( [ "$modelOptionTable.order" => SORT_DESC, "$optionTable.name" => SORT_ASC, "$modelOptionTable.id" => SORT_DESC ] )
				->all();
		}

		$optionsCsv = [];

		foreach( $options as $option ) {

			$optionsCsv[] = $option->name;
		}

		return implode( ", ", $optionsCsv );
	}

	/**
	 * @inheritdoc
	 */
	public function getModelOptionsByCategoryId( $categoryId, $active = true ) {

		$optionTable		= Option::tableName();
		$modelOptionTable	= ModelOption::tableName();

		if( $active ) {

			$query = ModelOption::find()
				->leftJoin( $optionTable, "$modelOptionTable.modelId=$optionTable.id" )
				->where( "$modelOptionTable.parentId=:pid AND $modelOptionTable.parentType=:ptype AND $optionTable.categoryId=:cid AND $modelOptionTable.active=:active", [ ':pid' => $this->id, ':ptype' => $this->modelType, ':cid' => $categoryId, ':active' => $active ] )
				->orderBy( [ "$modelOptionTable.order" => SORT_DESC, "$optionTable.name" => SORT_ASC, "$modelOptionTable.id" => SORT_DESC ] );
		}
		else {

			$query = ModelOption::find()
				->leftJoin( $optionTable, "$modelOptionTable.modelId=$optionTable.id" )
				->where( "$modelOptionTable.parentId=:pid AND $modelOptionTable.parentType=:ptype AND $optionTable.categoryId=:cid", [ ':pid' => $this->id, ':ptype' => $this->modelType, ':cid' => $categoryId ] )
				->orderBy( [ "$modelOptionTable.order" => SORT_DESC, "$optionTable.name" => SORT_ASC, "$modelOptionTable.id" => SORT_DESC ] );
		}

		return $query->all();
	}

	/**
	 * @inheritdoc
	 */
	public function getOptionsByCategoryId( $categoryId, $active = true ) {

		$optionTable		= Option::tableName();
		$modelOptionTable	= ModelOption::tableName();

		if( $active ) {

			return Option::find()
				->leftJoin( $modelOptionTable, "$modelOptionTable.modelId=$optionTable.id" )
				->where( "$modelOptionTable.parentId=:pid AND $modelOptionTable.parentType=:ptype AND $optionTable.categoryId=:cid AND $modelOptionTable.active=:active", [ ':pid' => $this->id, ':ptype' => $this->modelType, ':cid' => $categoryId, ':active' => $active ] )
				->orderBy( [ "$modelOptionTable.order" => SORT_DESC, "$optionTable.name" => SORT_ASC, "$modelOptionTable.id" => SORT_DESC ] )
				->all();
		}

		return Option::find()
			->leftJoin( $modelOptionTable, "$modelOptionTable.modelId=$optionTable.id" )
			->where( "$modelOptionTable.parentId=:pid AND $modelOptionTable.parentType=:ptype AND $optionTable.categoryId=:cid", [ ':pid' => $this->id, ':ptype' => $this->modelType, ':cid' => $categoryId ] )
			->orderBy( [ "$modelOptionTable.order" => SORT_DESC, "$optionTable.name" => SORT_ASC, "$modelOptionTable.id" => SORT_DESC ] )
			->all();
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
