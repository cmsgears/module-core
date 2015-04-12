<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\CmgFile;

class FileService extends Service {

	// Static Methods ----------------------------------------------

	// Read ----------------

	public static function findById( $id ) {

		return CmgFile::findById( $id );
	}

	public static function findByAuthorId( $authorId ) {

		return CmgFile::findByAuthorId( $authorId );
	}
}

?>