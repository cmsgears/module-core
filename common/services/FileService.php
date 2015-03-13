<?php
namespace cmsgears\modules\core\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\modules\core\common\models\entities\CmgFile;

class FileService extends Service {

	// Static Methods ----------------------------------------------

	// Read ----------------

	public static function findById( $id ) {

		return CmgFile::findOne( $id );
	}
}

?>