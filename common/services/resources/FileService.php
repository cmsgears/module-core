<?php
namespace cmsgears\core\common\services\resources;

// Yii Imports
use Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\resources\File;
use cmsgears\core\common\models\mappers\ModelFile;

use cmsgears\core\common\services\interfaces\resources\IFileService;

use cmsgears\core\common\services\traits\VisibilityTrait;

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

	use VisibilityTrait;

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// FileService ---------------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$modelClass		= static::$modelClass;
		$modelTable		= static::$modelTable;

		// Sorting ----------

		$sort = new Sort([
			'attributes' => [
				'id' => [
					'asc' => [ 'id' => SORT_ASC ],
					'desc' => [ 'id' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Id'
				],
				'name' => [
					'asc' => [ 'name' => SORT_ASC ],
					'desc' => ['name' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Name'
				],
				'title' => [
					'asc' => [ 'title' => SORT_ASC ],
					'desc' => [ 'title' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Title'
				],
				'extension' => [
					'asc' => [ 'extension' => SORT_ASC ],
					'desc' => [ 'extension' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Extension'
				],
				'directory' => [
					'asc' => [ 'directory' => SORT_ASC ],
					'desc' => [ 'directory' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Directory'
				],
				'size' => [
					'asc' => [ 'size' => SORT_ASC ],
					'desc' => [ 'size' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Size'
				],
				'url' => [
					'asc' => [ 'url' => SORT_ASC ],
					'desc' => [ 'url' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Path'
				],
				'visibility' => [
					'asc' => [ 'visibility' => SORT_ASC ],
					'desc' => [ 'visibility' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Visibility'
				],
				'shared' => [
					'asc' => [ 'shared' => SORT_ASC ],
					'desc' => [ 'shared' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Shared'
				],
	            'cdate' => [
	                'asc' => [ 'createdAt' => SORT_ASC ],
	                'desc' => ['createdAt' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Created At'
	            ],
	            'udate' => [
	                'asc' => [ 'modifiedAt' => SORT_ASC ],
	                'desc' => ['modifiedAt' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Updated At'
	            ]
			],
			'defaultOrder' => [
				'id' => SORT_DESC
			]
		]);

		if( !isset( $config[ 'sort' ] ) ) {

			$config[ 'sort' ] = $sort;
		}

		// Query ------------

		if( !isset( $config[ 'query' ] ) ) {

			$config[ 'hasOne' ] = true;
		}

		// Filters ----------

		// Filter - Visibility
		$visibility	= Yii::$app->request->getQueryParam( 'visibility' );

		if( isset( $visibility ) && isset( $modelClass::$urlRevVisibilityMap[ $visibility ] ) ) {

			$config[ 'conditions' ][ "$modelTable.visibility" ]	= $modelClass::$urlRevVisibilityMap[ $visibility ];
		}

		// Searching --------

		$searchCol	= Yii::$app->request->getQueryParam( 'search' );

		if( isset( $searchCol ) ) {

			$search = [ 'title' => "$modelTable.title", 'extension' => "$modelTable.extension", 'directory' => "$modelTable.directory" ];

			$config[ 'search-col' ] = $search[ $searchCol ];
		}

		// Reporting --------

		$config[ 'report-col' ]	= [
			'title' => "$modelTable.title", 'extension' => "$modelTable.extension", 'directory' => "$modelTable.directory",
			'visibility' => "$modelTable.visibility"
		];

		// Result -----------

		return parent::getPage( $config );
	}

	public function getSharedPage( $config = [] ) {

		$config[ 'conditions' ][ 'shared' ] = true;

		return $this->getPage( $config );
	}

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

		// model class
		$modelClass		= static::$modelClass;
		
		// Default visibility
		if( !isset( $model->visibility ) ) {

			$model->visibility = File::VISIBILITY_PUBLIC;
		}
		
		$siteId	= isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : ( $modelClass::isMultiSite() ? Yii::$app->core->siteId : null );

		if( $siteId ) {

			$model->siteId = $siteId;

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

		$attributes = isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [ 'title', 'description', 'altText', 'link', 'type' ];

		if( $model->changed ) {

			// Find existing file
			$existingFile	= self::findById( $model->id );

			// Delete from disk
			$existingFile->clearDisk();

			$attributes[]	= 'size';
		}

		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

	public function updateData( $model, $config = [] ) {

		$attributes = isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [ 'title', 'description', 'altText', 'link', 'type', 'name', 'directory', 'extension', 'url', 'medium', 'thumb' ];

		if( $model->changed ) {

			// Find existing file
			$existingFile	= self::findById( $model->id );

			// Delete from disk
			$existingFile->clearDisk();

			$attributes[]	= 'size';
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
			$fileId = $file->id;

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

				// Unset Id
				$file->id = null;

				// Update File Size
				$file->resetSize();

				// Create
				$this->create( $file );

				// Update model attribute
				if( isset( $model ) && isset( $attribute ) ) {

					$model->setAttribute( $attribute, $file->id );
				}
			}
			// Existing File - Image Changed
			else if( $file->changed ) {

				// Update File Size
				$file->resetSize();

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

				// Unset Id
				$file->id = null;

				// Update File Size
				$file->resetSize();

				// Create
				$this->create( $file );

				// Update model attribute
				if( isset( $model ) && isset( $attribute ) ) {

					$model->setAttribute( $attribute, $file->id );
				}
			}
			// Existing File - File Changed
			else if( $file->changed ) {

				// Update File Size
				$file->resetSize();

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

	protected function applyBulk( $model, $column, $action, $target, $config = [] ) {

		switch( $column ) {

			case 'visibility': {

				switch( $action ) {

					case 'public': {

						$model->visibility = File::VISIBILITY_PUBLIC;

						$model->update();

						break;
					}
					case 'protected': {

						$model->visibility = File::VISIBILITY_PROTECTED;

						$model->update();

						break;
					}
					case 'private': {

						$model->visibility = File::VISIBILITY_PRIVATE;

						$model->update();

						break;
					}
				}

				break;
			}
		}
	}

	// Delete -------------

	public function delete( $model, $config = [] ) {

		$admin	= isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : false;

		if( isset( $model ) ) {

			// Only admin is authorised to delete a shared file
			if( $admin || $model->type !== 'shared' ) {

				// Delete mapping
				ModelFile::deleteByModelId( $model->id );

				// Delete from disk
				$model->clearDisk();

				// Delete model
				return parent::delete( $model, $config );
			}

			return true;
		}

		return false;
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
