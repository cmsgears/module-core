<?php
namespace cmsgears\core\common\models\entities;

trait FileTrait {

	public function getFiles() {
		
		$parentType	= $this->fileType;

    	return $this->hasMany( CmgFile::className(), [ 'id' => 'fileId' ] )
					->viaTable( CoreTables::TABLE_MODEL_FILE, [ 'parentId' => 'id' ] )
					->where( "parentType=$parentType" );
	}
}

?>