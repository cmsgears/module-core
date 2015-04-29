<?php
namespace cmsgears\core\common\models\traits;

use cmsgears\core\common\models\entities\CoreTables;
use cmsgears\core\common\models\entities\CmgFile;

trait FileTrait {

	public function getFiles() {
		
		$parentType	= $this->fileType;

    	return $this->hasMany( CmgFile::className(), [ 'id' => 'fileId' ] )
					->viaTable( CoreTables::TABLE_MODEL_FILE, [ 'parentId' => 'id' ] )
					->where( "parentType=$parentType" );
	}
}

?>