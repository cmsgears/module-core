<?php
namespace cmsgears\core\common\models\traits;

use cmsgears\core\common\models\entities\CoreTables;
use cmsgears\core\common\models\entities\ModelFile;
use cmsgears\core\common\models\entities\CmgFile;

/**
 * FileTrait can be used to add files feature to relevant models. It allows to have a list of files for a Model.
 * The model must define the member variable $fileType which is unique for the model.
 */
trait FileTrait {

	/**
	 * @return array - ModelFile associated with parent
	 */
	public function getModelFiles() {

		$parentType	= $this->fileType;

    	return $this->hasMany( ModelFile::className(), [ 'parentId' => 'id' ] )
					->where( "parentType='$parentType'" );
	}

	/**
	 * @return array - ModelFile associated with parent
	 */
	public function getModelFilesByType( $type ) {

		$parentType	= $this->fileType;

    	return $this->hasMany( ModelFile::className(), [ 'parentId' => 'id' ] )
					->where( "parentType=:ptype AND type=:type", [ ':ptype' => $parentType, ':type' => $type ] )->all();
	}

	/**
	 * @return Address - associated with parent having type set to residential
	 */
	public function getFiles() {

    	return $this->hasMany( CmgFile::className(), [ 'id' => 'fileId' ] )
					->viaTable( CoreTables::TABLE_MODEL_FILE, [ 'parentId' => 'id' ], function( $query ) {

						$modelFile	= CoreTables::TABLE_MODEL_FILE;

                      	$query->onCondition( "$modelFile.parentType=:type", [ ':type' => $this->fileType ] );
					});
	}

	public function getFileByTitle( $title ) {

		$fileTable	= CoreTables::TABLE_FILE;

    	$file 		= $this->hasOne( CmgFile::className(), [ 'id' => 'fileId' ] )
							->where( "$fileTable.title=:title", [ ':title' => $title ] )
							->viaTable( CoreTables::TABLE_MODEL_FILE, [ 'parentId' => 'id' ], function( $query ) {

								$modelFileTable	= CoreTables::TABLE_MODEL_FILE;

                      			$query->onCondition( "$modelFileTable.parentType=:type", [ ':type' => $this->fileType ] );
							})->one();

		return $file;
	}
}

?>