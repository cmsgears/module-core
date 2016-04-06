<?php
namespace cmsgears\core\common\models\traits;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\resources\CmgFile;
use cmsgears\core\common\models\mappers\ModelFile;

/**
 * FileTrait can be used to add files feature to relevant models. It allows to have a list of files for a Model.
 * The model must define the member variable $fileType which is unique for the model.
 */
trait FileTrait {

	/**
	 * @return array - ModelFile associated with parent
	 */
	public function getModelFiles() {

    	return $this->hasMany( ModelFile::className(), [ 'parentId' => 'id' ] )
					->where( "parentType='$this->parentType'" );
	}

	/**
	 * @return array - ModelFile associated with parent
	 */
	public function getModelFilesByType( $type ) {

    	return $this->hasMany( ModelFile::className(), [ 'parentId' => 'id' ] )
					->where( "parentType=:ptype AND type=:type", [ ':ptype' => $this->parentType, ':type' => $type ] )->all();
	}

	/**
	 * @return Address - associated with parent having type set to residential
	 */
	public function getFiles() {

    	return $this->hasMany( CmgFile::className(), [ 'id' => 'fileId' ] )
					->viaTable( CoreTables::TABLE_MODEL_FILE, [ 'parentId' => 'id' ], function( $query ) {

						$modelFile	= CoreTables::TABLE_MODEL_FILE;

                      	$query->onCondition( "$modelFile.parentType=:type", [ ':type' => $this->parentType ] );
					});
	}

	public function getFileByTitle( $title ) {

		$fileTable	= CoreTables::TABLE_FILE;

    	$file 		= $this->hasOne( CmgFile::className(), [ 'id' => 'fileId' ] )
							->where( "$fileTable.title=:title", [ ':title' => $title ] )
							->viaTable( CoreTables::TABLE_MODEL_FILE, [ 'parentId' => 'id' ], function( $query ) {

								$modelFileTable	= CoreTables::TABLE_MODEL_FILE;

                      			$query->onCondition( "$modelFileTable.parentType=:type", [ ':type' => $this->parentType ] );
							})->one();

		return $file;
	}
}

?>