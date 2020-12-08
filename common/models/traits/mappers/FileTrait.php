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
use cmsgears\core\common\models\resources\File;
use cmsgears\core\common\models\mappers\ModelFile;

/**
 * FileTrait can be used to add files feature to relevant models. It allows to have a
 * list of files for a Model.
 */
trait FileTrait {

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

	// FileTrait -----------------------------

	/**
	 * @inheritdoc
	 */
	public function getModelFiles() {

		$modelFileTable = ModelFile::tableName();

		return $this->hasMany( ModelFile::class, [ 'parentId' => 'id' ] )
			->where( "$modelFileTable.parentType=:ptype", [ ':ptype' => $this->modelType ] )
			->orderBy( [ "$modelFileTable.order" => SORT_DESC, "$modelFileTable.id" => SORT_DESC ] );
	}

	/**
	 * @inheritdoc
	 */
	public function getActiveModelFiles() {

		$modelFileTable = ModelFile::tableName();

		return $this->hasMany( ModelFile::class, [ 'parentId' => 'id' ] )
			->where( "$modelFileTable.parentType=:ptype AND $modelFileTable.active=:active", [ ':ptype' => $this->modelType, ':active' => true ] )
			->orderBy( [ "$modelFileTable.order" => SORT_DESC, "$modelFileTable.id" => SORT_DESC ] );
	}

	/**
	 * @inheritdoc
	 */
	public function getModelFilesByType( $type, $active = true ) {

		$modelFileTable = ModelGallery::tableName();

		if( $active ) {

			return $this->hasMany( ModelFile::class, [ 'parentId' => 'id' ] )
				->where( "$modelFileTable.parentType=:ptype AND $modelFileTable.type=:type AND $modelFileTable.active=:active", [ ':ptype' => $this->modelType, ':type' => $type, ':active' => $active ] )
				->orderBy( [ "$modelFileTable.order" => SORT_DESC, "$modelFileTable.id" => SORT_DESC ] );
		}

		return $this->hasMany( ModelFile::class, [ 'parentId' => 'id' ] )
			->where( "$modelFileTable.parentType=:ptype AND $modelFileTable.type=:type", [ ':ptype' => $this->modelType, ':type' => $type ] )
			->orderBy( [ "$modelFileTable.order" => SORT_DESC, "$modelFileTable.id" => SORT_DESC ] );
	}

	/**
	 * @inheritdoc
	 */
	public function getFiles() {

		$fileTable		= File::tableName();
		$modelFileTable	= ModelFile::tableName();

		return File::find()
			->leftJoin( $modelFileTable, "$modelFileTable.modelId=$fileTable.id" )
			->where( "$modelFileTable.parentId=:pid AND $modelFileTable.parentType=:ptype", [ ':pid' => $this->id, ':ptype' => $this->modelType ] )
			->orderBy( [ "$modelFileTable.order" => SORT_DESC, "$modelFileTable.id" => SORT_DESC ] )
			->all();
	}

	/**
	 * @inheritdoc
	 */
	public function getActiveFiles() {

		$fileTable		= File::tableName();
		$modelFileTable	= ModelFile::tableName();

		return File::find()
			->leftJoin( $modelFileTable, "$modelFileTable.modelId=$fileTable.id" )
			->where( "$modelFileTable.parentId=:pid AND $modelFileTable.parentType=:ptype AND $modelFileTable.active=:active", [ ':pid' => $this->id, ':ptype' => $this->modelType, ':active' => true ] )
			->orderBy( [ "$modelFileTable.order" => SORT_DESC, "$modelFileTable.id" => SORT_DESC ] )
			->all();
	}

	/**
	 * @inheritdoc
	 */
	public function getFilesByType( $type, $active = true ) {

		$fileTable		= File::tableName();
		$modelFileTable	= ModelFile::tableName();

		if( $active ) {

			return File::find()
				->leftJoin( $modelFileTable, "$modelFileTable.modelId=$fileTable.id" )
				->where( "$modelFileTable.parentId=:pid AND $modelFileTable.parentType=:ptype AND $modelFileTable.type=:type AND $modelFileTable.active=:active", [ ':pid' => $this->id, ':ptype' => $this->modelType, ':type' => $type, ':active' => $active ] )
				->orderBy( [ "$modelFileTable.order" => SORT_DESC, "$modelFileTable.id" => SORT_DESC ] )
				->all();
		}

		return File::find()
			->leftJoin( $modelFileTable, "$modelFileTable.modelId=$fileTable.id" )
			->where( "$modelFileTable.parentId=:pid AND $modelFileTable.parentType=:ptype AND $modelFileTable.type=:type", [ ':pid' => $this->id, ':ptype' => $this->modelType, ':type' => $type ] )
			->orderBy( [ "$modelFileTable.order" => SORT_DESC, "$modelFileTable.id" => SORT_DESC ] )
			->all();
	}

	/**
	 * @inheritdoc
	 */
	public function getFilesByTypes( $types, $active = true ) {

		$fileTable		= File::tableName();
		$modelFileTable	= ModelFile::tableName();

		if( $active ) {

			return File::find()
				->leftJoin( $modelFileTable, "$modelFileTable.modelId=$fileTable.id" )
				->where( "$modelFileTable.parentId=:pid AND $modelFileTable.parentType=:ptype AND $modelFileTable.active=:active", [ ':pid' => $this->id, ':ptype' => $this->modelType, ':active' => $active ] )
				->filterWhere( "$modelFileTable.type", 'in', $types )
				->orderBy( [ "$modelFileTable.order" => SORT_DESC, "$modelFileTable.id" => SORT_DESC ] )
				->all();
		}

		return File::find()
			->leftJoin( $modelFileTable, "$modelFileTable.modelId=$fileTable.id" )
			->where( "$modelFileTable.parentId=:pid AND $modelFileTable.parentType=:ptype", [ ':pid' => $this->id, ':ptype' => $this->modelType ] )
			->filterWhere( "$modelFileTable.type", 'in', $types )
			->orderBy( [ "$modelFileTable.order" => SORT_DESC, "$modelFileTable.id" => SORT_DESC ] )
			->all();
	}

	/**
	 * @inheritdoc
	 */
	public function getFileByCode( $code ) {

		$fileTable		= File::tableName();
		$modelFileTable	= ModelFile::tableName();

		return File::find()
			->leftJoin( $modelFileTable, "$modelFileTable.modelId=$fileTable.id" )
			->where( "$modelFileTable.parentId=:pid AND $modelFileTable.parentType=:ptype AND $modelFileTable.code=:code", [ ':pid' => $this->id, ':ptype' => $this->modelType, ':code' => $code ] )
			->one();
	}

	// Static Methods ----------------------------------------------

	// Yii classes ---------------------------

	// CMG classes ---------------------------

	// FileTrait -----------------------------

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
