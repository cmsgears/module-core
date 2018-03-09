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
use cmsgears\core\common\models\base\CoreTables;
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

		$modelFileTable = CoreTables::getTableName( CoreTables::TABLE_MODEL_FILE );

		return $this->hasMany( ModelFile::class, [ 'parentId' => 'id' ] )
			->where( "$modelFileTable.parentType='$this->modelType'" );
	}

	/**
	 * @inheritdoc
	 */
	public function getActiveModelFiles() {

		$modelFileTable = CoreTables::getTableName( CoreTables::TABLE_MODEL_FILE );

		return $this->hasMany( ModelFile::class, [ 'parentId' => 'id' ] )
			->where( "$modelFileTable.parentType='$this->modelType' AND $modelFileTable.active=1" );
	}

	/**
	 * @inheritdoc
	 */
	public function getModelFilesByType( $type, $active = true ) {

		$modelFileTable = CoreTables::getTableName( CoreTables::TABLE_MODEL_FILE );

		return $this->hasOne( ModelFile::class, [ 'parentId' => 'id' ] )
			->where( "$modelFileTable.parentType=:ptype AND $modelFileTable.type=:type AND $modelFileTable.active=:active", [ ':ptype' => $this->modelType, ':type' => $type, ':active' => $active ] )->all();
	}

	/**
	 * @inheritdoc
	 */
	public function getFiles() {

		$modelFileTable = CoreTables::getTableName( CoreTables::TABLE_MODEL_FILE );

		return $this->hasMany( File::class, [ 'id' => 'modelId' ] )
			->viaTable( $modelFileTable, [ 'parentId' => 'id' ],
				function( $query ) use( &$modelFileTable ) {

					$query->onCondition( [ "$modelFileTable.parentType" => $this->modelType ] );
				}
			);
	}

	/**
	 * @inheritdoc
	 */
	public function getActiveFiles() {

		$modelFileTable = CoreTables::getTableName( CoreTables::TABLE_MODEL_FILE );

		return $this->hasMany( File::class, [ 'id' => 'modelId' ] )
			->viaTable( $modelFileTable, [ 'parentId' => 'id' ],
				function( $query ) use( &$modelFileTable ) {

					$query->onCondition( [ "$modelFileTable.parentType" => $this->modelType, "$modelFileTable.active" => true ] );
				}
			);
	}

	/**
	 * @inheritdoc
	 */
	public function getFilesByType( $type, $active = true ) {

		$modelFileTable = CoreTables::getTableName( CoreTables::TABLE_MODEL_FILE );

		return $this->hasMany( File::class, [ 'id' => 'modelId' ] )
			->viaTable( $modelFileTable, [ 'parentId' => 'id' ],
				function( $query ) use( &$type, &$active, &$modelFileTable ) {

					$query->onCondition( [ "$modelFileTable.parentType" => $this->modelType, "$modelFileTable.type" => $type, "$modelFileTable.active" => $active ] );
				}
			)->all();
	}

	/**
	 * @inheritdoc
	 */
	public function getFileByTitle( $title ) {

		$fileTable		= CoreTables::getTableName( CoreTables::TABLE_FILE );
		$modelFileTable = CoreTables::getTableName( CoreTables::TABLE_MODEL_FILE );

		return $this->hasOne( File::class, [ 'id' => 'modelId' ] )
			->viaTable( $modelFileTable, [ 'parentId' => 'id' ],
				function( $query ) use( &$modelFileTable ) {

					$query->onCondition( "$modelFileTable.parentType=:type", [ ':type' => $this->modelType ] );
				}
			)->where( "$fileTable.title=:title", [ ':title' => $title ] )->one();
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
