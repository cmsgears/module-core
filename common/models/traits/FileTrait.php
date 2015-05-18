<?php
namespace cmsgears\core\common\models\traits;

use cmsgears\core\common\models\entities\CoreTables;
use cmsgears\core\common\models\entities\ModelFile;

trait FileTrait {

	public function getFiles() {
		
		$parentType	= $this->fileType;

    	return $this->hasMany( ModelFile::className(), [ 'parentId' => 'id' ] )
					->where( "parentType='$parentType'" );
	}
}

?>