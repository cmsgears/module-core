<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\CmgFile;

use cmsgears\core\common\utilities\DateUtil;

class FileService extends Service {

	// Static Methods ----------------------------------------------

	// Read ----------------

	public static function findById( $id ) {

		return CmgFile::findById( $id );
	}

	public static function findByAuthorId( $authorId ) {

		return CmgFile::findByAuthorId( $authorId );
	}

	// Create -----------

	public static function create( $file ) {
		
		// Create File
		$file->save();
		
		// Return File
		return $file;
	}

	// Update -----------

	public static function update( $file ) {

		// Find existing file
		$fileToUpdate	= self::findById( $file->id );

		if( isset( $fileToUpdate ) ) {

			// Copy and set Attributes
			$date 						= DateUtil::getMysqlDate();
			$fileToUpdate->modifiedAt	= $date;

			$fileToUpdate->copyForUpdateFrom( $file, [ 'description', 'altText' ] );

			// Update File
			$fileToUpdate->update();

			// Return updated File
			return $fileToUpdate;
		}
		
		return false;
	}

	public static function updateData( $file ) {

		// Find existing file
		$fileToUpdate	= self::findById( $file->id );

		if( isset( $fileToUpdate ) ) {

			// Copy and set Attributes
			$date 						= DateUtil::getMysqlDate();
			$fileToUpdate->modifiedAt	= $date;

			$fileToUpdate->copyForUpdateFrom( $file, [ 'name', 'description', 'altText', 'directory', 'authorId', 'type', 'url', 'thumb', 'createdAt' ] );

			// Update File
			$fileToUpdate->update();

			// Return updated File
			return $fileToUpdate;
		}

		return false;
	}
	
	/**
	 * Save pre-uploaded image to respective directory.
	 * @param CmgFile file
	 * @param User user
	 * @param CMGEntity model
	 * @param String attribute
	 * @param FileManager fileManager
	 * @param int width
	 * @param int height
	 */
	public static function saveImage( $file, $author, $options = [] ) {

		if( strlen( $file->name ) > 0 ) {

			$fileManager 	= Yii::$app->fileManager;
			$model			= null;
			$attribute		= null;
			$width 			= null;
			$height 		= null;

			if( isset( $options[ 'model'] ) ) 		$model 		= $options[ 'model'];
			if( isset( $options[ 'attribute'] ) ) 	$attribute 	= $options[ 'attribute'];
			if( isset( $options[ 'width'] ) ) 		$width 		= $options[ 'width'];
			if( isset( $options[ 'height'] ) ) 		$height 	= $options[ 'height'];

			// Update Banner
			$fileId 	= $file->id;
			$date 		= DateUtil::getMysqlDate();

			if( $file->changed ) {

				$fileManager->processImage( $date, $author, $file, $width, $height );
			}

			// New File
			if( !isset( $fileId ) || strlen( $fileId ) <= 0 ) {

				$file->id = null;

				self::create( $file );

				// Update model attribute
				if( isset( $model ) && isset( $attribute ) ) {
	
					$model->setAttribute( $attribute, $file->id );
				}
			}
			// Existing File - Image Changed
			else if( $file->changed ) {

				self::updateData( $file );
			}
			// Existing File - Info Changed
			else if( isset( $fileId ) && intval( $fileId ) > 0 ) {

				self::update( $file );
			}
		}
	}

	/**
	 * Save pre-uploaded file to respective directory.
	 * @param CmgFile file
	 * @param User user
	 * @param CMGEntity model
	 * @param String attribute
	 * @param FileManager fileManager
	 */
	public static function saveFile( $file, $author, $options = [] ) {

		if( strlen( $file->name ) > 0 ) {

			$fileManager 	= Yii::$app->fileManager;
			$model			= null;
			$attribute		= null;

			if( isset( $options[ 'model'] ) ) 		$model 		= $options[ 'model'];
			if( isset( $options[ 'attribute'] ) ) 	$attribute 	= $options[ 'attribute'];

			// Update File
			$fileId 	= $file->id;
			$date 		= DateUtil::getMysqlDate();

			if( $file->changed ) {

				$fileManager->processFile( $date, $author, $file );
			}

			// New File
			if( !isset( $fileId ) || strlen( $fileId ) <= 0 ) {

				$file->id = null;

				self::create( $file );

				// Update model attribute
				if( isset( $model ) && isset( $attribute ) ) {
	
					$model->setAttribute( $attribute, $file->id );
				}
			}
			// Existing File - File Changed
			else if( $file->changed ) {

				self::updateData( $file );
			}
			// Existing File - Info Changed
			else if( isset( $fileId ) && intval( $fileId ) > 0 ) {

				self::update( $file );
			}
		}
	}

	// Delete -----------

	public static function delete( $file ) {
		
		// Find existing File
		$existingFile	= self::findById( $file->id );

		// Delete File
		$existingFile->delete();

		return true;
	}
}

?>