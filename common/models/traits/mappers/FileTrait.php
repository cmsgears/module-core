<?php
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

	/**
	 * @return array - ModelFile associated with parent
	 */
	public function getModelFiles() {

		return $this->hasMany( ModelFile::className(), [ 'parentId' => 'id' ] )
					->where( "parentType='$this->modelType'" );
	}

	/**
	 * @return array - ModelFile associated with parent
	 */
	public function getModelFilesByType( $type ) {

		return $this->hasMany( ModelFile::className(), [ 'parentId' => 'id' ] )
					->where( "parentType=:ptype AND type=:type", [ ':ptype' => $this->modelType, ':type' => $type ] )->all();
	}

	/**
	 * @return array - files associated with parent
	 */
	public function getFiles() {

		return $this->hasMany( File::className(), [ 'id' => 'modelId' ] )
					->viaTable( CoreTables::TABLE_MODEL_FILE, [ 'parentId' => 'id' ], function( $query ) {

						$modelFileTable	= CoreTables::TABLE_MODEL_FILE;

						$query->onCondition( "$modelFileTable.parentType=:type", [ ':type' => $this->modelType ] );
					});
	}

	// Useful only in cases where unique title is allowed
	public function getFileByTitle( $title ) {

		$fileTable	= CoreTables::TABLE_FILE;

		$file		= $this->hasOne( File::className(), [ 'id' => 'modelId' ] )
							->where( "$fileTable.title=:title", [ ':title' => $title ] )
							->viaTable( CoreTables::TABLE_MODEL_FILE, [ 'parentId' => 'id' ], function( $query ) {

								$modelFileTable	= CoreTables::TABLE_MODEL_FILE;

								$query->onCondition( "$modelFileTable.parentType=:type", [ ':type' => $this->modelType ] );
							})->one();

		return $file;
	}
}
