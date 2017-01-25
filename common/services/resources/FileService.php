<?php
namespace cmsgears\core\common\services\resources;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\resources\File;
use cmsgears\core\common\models\mappers\ModelFile;

use cmsgears\core\common\services\interfaces\resources\IFileService;

/**
 * The class FileService is base class to perform database activities for CmgFile Entity.
 */
class FileService extends \cmsgears\core\common\services\base\EntityService implements IFileService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\core\common\models\resources\File';

	public static $modelTable	= CoreTables::TABLE_FILE;

	public static $parentType	= CoreGlobal::TYPE_FILE;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// FileService ---------------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	/**
	 * It create the file with visibility set to public by default. It also disallow the file to be shared among multiple models.
	 * If file sharing is set to false, it will be deleted with model and can't be browsed using file browser.
	 */
	public function create( $model, $config = [] ) {

		// Default visibility
		if( !isset( $model->visibility ) ) {

			$model->visibility = File::VISIBILITY_PUBLIC;
		}

		// Default sharing
		if( !isset( $model->shared ) ) {

			$model->shared = false;
		}

		// Create File
		$model->save();

		// Return File
		return $model;
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		$attributes = isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [ 'title', 'description', 'altText', 'link', 'visibility', 'type' ];

		if( $model->changed ) {

			// Find existing file
			$existingFile	= self::findById( $model->id );

			// Delete from disk
			$existingFile->clearDisk();
		}

		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

	public function updateData( $model, $config = [] ) {

		$attributes = isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [ 'title', 'description', 'altText', 'link', 'visibility', 'type', 'name', 'directory', 'extension', 'url', 'medium', 'thumb' ];

		if( $model->changed ) {

			// Find existing file
			$existingFile	= self::findById( $model->id );

			// Delete from disk
			$existingFile->clearDisk();
		}

		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

	/**
	 * Save pre-uploaded image to respective directory. The file manager does the file uploading task and use file service method to persist file data.
	 * @param CmgFile $file
	 * @param array $args
	 */
	public function saveImage( $file, $args = [] ) {

		// Save only when filename is provided
		if( strlen( $file->name ) > 0 ) {

			$fileManager	= Yii::$app->fileManager;
			$model			= null;
			$attribute		= null;
			$width			= null;
			$height			= null;
			$mwidth			= null;
			$mheight		= null;
			$twidth			= null;
			$theight		= null;

			// The model and it's attribute used to refer to image
			if( isset( $args[ 'model' ] ) )		$model		= $args[ 'model' ];
			if( isset( $args[ 'attribute' ] ) ) $attribute	= $args[ 'attribute' ];

			// Update Image
			$fileId		= $file->id;

			if( $file->changed ) {

				// Image dimensions to crop actual image uploaded by users
				if( isset( $args[ 'width' ] ) )		$width		= $args[ 'width' ];
				if( isset( $args[ 'height' ] ) )	$height		= $args[ 'height' ];
				if( isset( $args[ 'mwidth' ] ) )	$twidth		= $args[ 'mwidth' ];
				if( isset( $args[ 'mheight' ] ) )	$theight	= $args[ 'mheight' ];
				if( isset( $args[ 'twidth' ] ) )	$twidth		= $args[ 'twidth' ];
				if( isset( $args[ 'theight' ] ) )	$theight	= $args[ 'theight' ];

				// override controller args
				if( isset( $file->width ) && isset( $file->height ) ) {

					$width		= $file->width;
					$height		= $file->height;
				}

				if( isset( $file->mwidth ) && isset( $file->mheight ) ) {

					$mwidth		= $file->mwidth;
					$mheight	= $file->mheight;
				}

				if( isset( $file->twidth ) && isset( $file->theight ) ) {

					$twidth		= $file->twidth;
					$theight	= $file->theight;
				}

				// Process Image
				$fileManager->processImage( $file, $width, $height, $mwidth, $mheight, $twidth, $theight );
			}

			// New File
			if( !isset( $fileId ) || strlen( $fileId ) <= 0 || intval( $fileId ) <= 0 ) {

				// unset id
				$file->id		= null;

				// create
				$this->create( $file );

				// Update model attribute
				if( isset( $model ) && isset( $attribute ) ) {

					$model->setAttribute( $attribute, $file->id );
				}
			}
			// Existing File - Image Changed
			else if( $file->changed ) {

				$this->updateData( $file );
			}
			// Existing File - Info Changed
			else if( isset( $fileId ) && intval( $fileId ) > 0 ) {

				$this->update( $file );
			}

			$file->changed	= false;
		}

		return $file;
	}

	/**
	 * Save pre-uploaded file to respective directory.
	 * @param CmgFile $file
	 * @param array $args
	 */
	public function saveFile( $file, $args = [] ) {

		// Save only when filename is provided
		if( strlen( $file->name ) > 0 ) {

			$fileManager	= Yii::$app->fileManager;
			$model			= null;
			$attribute		= null;

			// The model and it's attribute used to refer to image
			if( isset( $args[ 'model' ] ) )		$model		= $args[ 'model' ];
			if( isset( $args[ 'attribute' ] ) ) $attribute	= $args[ 'attribute' ];

			// Update File
			$fileId		= $file->id;

			if( $file->changed ) {

				$fileManager->processFile( $file );
			}

			// New File
			if( !isset( $fileId ) || strlen( $fileId ) <= 0 ) {

				// unset id
				$file->id = null;

				// create
				$this->create( $file );

				// Update model attribute
				if( isset( $model ) && isset( $attribute ) ) {

					$model->setAttribute( $attribute, $file->id );
				}
			}
			// Existing File - File Changed
			else if( $file->changed ) {

				$this->updateData( $file );
			}
			// Existing File - Info Changed
			else if( isset( $fileId ) && intval( $fileId ) > 0 ) {

				$this->update( $file );
			}

			$file->changed	= false;
		}

		return $file;
	}

	public function saveFiles( $model, $files = [] ) {

		foreach ( $files as $key => $value ) {

			if( isset( $value ) ) {

				if( $value->type == 'image' ) {

					$this->saveImage( $value, [ 'model' => $model, 'attribute' => $key ] );
				}
				else {

					$this->saveFile( $value, [ 'model' => $model, 'attribute' => $key ] );
				}
			}
		}
	}

	// Delete -------------

	public function delete( $model, $config = [] ) {

		if( isset( $model ) ) {

			// Delete from disk
			$model->clearDisk();

			// Delete mapping
			ModelFile::deleteByModelId( $model->id );
		}

		// Delete model
		return parent::delete( $model, $config );
	}

	public function deleteFiles( $files = [] ) {

		foreach ( $files as $file ) {

			if( isset( $file ) ) {

				$this->delete( $file );
			}
		}
	}

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// FileService ---------------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------
}
