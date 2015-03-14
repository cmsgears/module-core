<?php
namespace cmsgears\core\admin\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\CmgFile;

use cmsgears\core\common\utilities\DateUtil;

class FileService extends \cmsgears\core\common\services\FileService {

	// Static Methods ----------------------------------------------

	// Create -----------

	public static function create( $file ) {

		$file->save();

		return true;
	}

	// Update -----------

	public static function update( $file ) {
		
		$date 			= DateUtil::getMysqlDate();
		$fileToUpdate	= self::findById( $file->getId() );

		if( isset( $fileToUpdate ) ) {

			$fileToUpdate->setDesc( $file->getDesc() );
			$fileToUpdate->setAltText( $file->getAltText() );
			$fileToUpdate->setUpdatedOn( $date );
	
			$fileToUpdate->update();
		}

		return true;
	}

	public static function updateData( $file ) {
		
		$date 			= DateUtil::getMysqlDate();
		$fileToUpdate	= self::findById( $file->getId() );
		
		if( isset( $fileToUpdate ) ) {

			$fileToUpdate->setDesc( $file->getDesc() );
			$fileToUpdate->setAltText( $file->getAltText() );
	
			// File Data
			$fileToUpdate->setDirectory( $file->getDirectory() );
			$fileToUpdate->setCreatedOn( $file->getCreatedOn() );
			$fileToUpdate->setAuthorId( $file->getAuthorId() );
			$fileToUpdate->setType( $file->getType() );
			$fileToUpdate->setUrl( $file->getUrl() );
			$fileToUpdate->setThumb( $file->getThumb() );
			$fileToUpdate->setUpdatedOn( $date );
	
			$fileToUpdate->update();
		}

		return true;
	}

	public static function saveImage( $file, $author, $fileManager, $width = null, $height = null ) {

		if( strlen( $file->getName() ) > 0 ) {

			// Update Banner
			$fileId 	= $file->getId();
			$date 		= DateUtil::getMysqlDate();

			if( $file->changed ) {

				$fileManager->processImage( $date, $author, $file, $width, $height );
			}

			// New File
			if( !isset( $fileId ) || strlen( $fileId ) <= 0 ) {

				$file->unsetId();

				self::create( $file );
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

	public static function saveFile( $file, $author, $fileManager ) {

		if( strlen( $file->getName() ) > 0 ) {

			// Update File
			$fileId 	= $file->getId();
			$date 		= DateUtil::getMysqlDate();

			if( $file->changed ) {

				$fileManager->processFile( $date, $author, $file );
			}

			// New File
			if( !isset( $fileId ) || strlen( $fileId ) <= 0 ) {

				$file->unsetId();

				self::create( $file );
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

		$fileId			= $file->getId();
		$existingFile	= self::findById( $fileId );

		// Delete File
		$existingFile->delete();

		return true;
	}
}

?>