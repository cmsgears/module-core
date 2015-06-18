<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\CmgFile;

// TODO: Delete existing file while replacing the file.

/**
 * The class FileService is base class to perform database activities for CmgFile Entity.
 */
class FileService extends Service {

	// Static Methods ----------------------------------------------

	// Read ----------------

	/**
	 * @param integer $id
	 * @return CmgFile
	 */
	public static function findById( $id ) {

		return CmgFile::findById( $id );
	}

	// Data Provider ----

	/**
	 * @param array - yii conditions for where query
	 * @param array - custom query instead of model
	 * @return ActiveDataProvider
	 */
	public static function getPagination( $conditions = [], $query = null ) {

		return self::getDataProvider( new CmgFile(), [ 'conditions' => $conditions, 'query' => $query ] );
	}

	// Create -----------

	/**
	 * @param CmgFile $file
	 * @return CmgFile
	 */
	public static function create( $file ) {

		$user				= Yii::$app->user->getIdentity();
		$file->createdBy	= $user->id;

		// File Type
		if( !isset( $file->type ) ) {

			$file->type		= CmgFile::TYPE_PUBLIC;
		}

		// Create File
		$file->save();

		// Return File
		return $file;
	}

	// Update -----------

	/**
	 * The method updates the file information in cases when actual file is not changed.
	 * @param CmgFile $file
	 * @return CmgFile
	 */
	public static function update( $file ) {

		// Find existing file
		$fileToUpdate	= self::findById( $file->id );

		if( isset( $fileToUpdate ) ) {

			// Copy and set Attributes
			$user						= Yii::$app->user->getIdentity();
			$fileToUpdate->modifiedBy	= $user->id;

			$fileToUpdate->copyForUpdateFrom( $file, [ 'title', 'description', 'altText', 'link', 'type' ] );

			// Update File
			$fileToUpdate->update();

			// Return updated File
			return $fileToUpdate;
		}
		
		return false;
	}

	/**
	 * The method updates the file information when actual file is also changed.
	 * @param CmgFile $file
	 * @return CmgFile
	 */
	public static function updateData( $file ) {

		// Find existing file
		$fileToUpdate	= self::findById( $file->id );

		if( isset( $fileToUpdate ) ) {

			// Copy and set Attributes
			$user						= Yii::$app->user->getIdentity();
			$fileToUpdate->modifiedBy	= $user->id;

			$fileToUpdate->copyForUpdateFrom( $file, [ 'title', 'description', 'altText', 'link', 'type', 'name', 'directory', 'extension', 'url', 'thumb' ] );

			// Update File
			$fileToUpdate->update();

			// Return updated File
			return $fileToUpdate;
		}

		return false;
	}

	/**
	 * Save pre-uploaded image to respective directory. The file manager does the file uploading task and use file service method to persist file data.
	 * @param CmgFile $file
	 * @param array $args
	 */
	public static function saveImage( $file, $args = [] ) {

		// Save only when filename is provided
		if( strlen( $file->name ) > 0 ) {

			$fileManager 	= Yii::$app->fileManager;
			$model			= null;
			$attribute		= null;
			$width 			= null;
			$height 		= null;
			$twidth 		= null;
			$theight 		= null;

			// The model and it's attribute used to refer to image
			if( isset( $args[ 'model' ] ) ) 	$model 		= $args[ 'model' ];
			if( isset( $args[ 'attribute' ] ) ) $attribute 	= $args[ 'attribute' ];

			// Update Image
			$fileId 	= $file->id;

			if( $file->changed ) {

				// Image dimensions to crop actual image uploaded by users
				if( isset( $args[ 'width' ] ) ) 	$width 		= $args[ 'width' ];
				if( isset( $args[ 'height' ] ) ) 	$height 	= $args[ 'height' ];
				if( isset( $args[ 'twidth' ] ) ) 	$twidth 	= $args[ 'twidth' ];
				if( isset( $args[ 'theight' ] ) ) 	$theight 	= $args[ 'theight' ];

				// override controller args
				if( isset( $file->width ) && isset( $file->height ) ) {

					$width		= $file->width;
					$height		= $file->height;
				}

				if( isset( $file->twidth ) && isset( $file->theight ) ) {

					$twidth		= $file->twidth;
					$theight	= $file->theight;
				}

				// Process Image
				$fileManager->processImage( $file, $width, $height, $twidth, $theight );
			}

			// New File
			if( !isset( $fileId ) || strlen( $fileId ) <= 0 ) {

				// unset id
				$file->id = null;

				// create
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
	 * @param CmgFile $file
	 * @param array $args
	 */
	public static function saveFile( $file, $args = [] ) {

		// Save only when filename is provided
		if( strlen( $file->name ) > 0 ) {

			$fileManager 	= Yii::$app->fileManager;
			$model			= null;
			$attribute		= null;

			// The model and it's attribute used to refer to image
			if( isset( $args[ 'model' ] ) ) 	$model 		= $args[ 'model' ];
			if( isset( $args[ 'attribute' ] ) ) $attribute 	= $args[ 'attribute' ];

			// Update File
			$fileId 	= $file->id;

			if( $file->changed ) {

				$fileManager->processFile( $file );
			}

			// New File
			if( !isset( $fileId ) || strlen( $fileId ) <= 0 ) {

				// unset id
				$file->id = null;

				// create
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