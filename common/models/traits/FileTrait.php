<?php
namespace cmsgears\core\common\models\traits;

use cmsgears\core\common\models\entities\CoreTables;
use cmsgears\core\common\models\entities\ModelFile;

/**
 * FileTrait can be used to add files feature to relevant models. It allows to have a list of files for a Model.
 * The model must define the member variable $fileType which is unique for the model.
 */
trait FileTrait {

	/**
	 * @return array - ModelFile associated with parent
	 */
	public function getFiles() {
		
		$parentType	= $this->fileType;

    	return $this->hasMany( ModelFile::className(), [ 'parentId' => 'id' ] )
					->where( "parentType='$parentType'" );
	}
}

?>