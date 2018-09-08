<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\resources;

// Yii Imports
use Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\resources\File;

use cmsgears\core\common\services\interfaces\resources\IFileService;

use cmsgears\core\common\services\base\ResourceService;

use cmsgears\core\common\services\traits\base\MultiSiteTrait;
use cmsgears\core\common\services\traits\base\VisibilityTrait;
use cmsgears\core\common\services\traits\resources\DataTrait;

/**
 * FileService provide service methods of file model.
 *
 * @since 1.0.0
 */
class FileService extends ResourceService implements IFileService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\core\common\models\resources\File';

	public static $parentType	= CoreGlobal::TYPE_FILE;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	use DataTrait;
	use MultiSiteTrait;
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

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		// Sorting ----------

		$sort = new Sort([
			'attributes' => [
				'id' => [
					'asc' => [ "$modelTable.id" => SORT_ASC ],
					'desc' => [ "$modelTable.id" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Id'
				],
				'name' => [
					'asc' => [ "$modelTable.name" => SORT_ASC ],
					'desc' => [ "$modelTable.name" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Name'
				],
				'title' => [
					'asc' => [ "$modelTable.title" => SORT_ASC ],
					'desc' => [ "$modelTable.title" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Title'
				],
				'extension' => [
					'asc' => [ "$modelTable.extension" => SORT_ASC ],
					'desc' => [ "$modelTable.extension" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Extension'
				],
				'directory' => [
					'asc' => [ "$modelTable.directory" => SORT_ASC ],
					'desc' => [ "$modelTable.directory" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Directory'
				],
				'size' => [
					'asc' => [ "$modelTable.size" => SORT_ASC ],
					'desc' => [ "$modelTable.size" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Size'
				],
				'visibility' => [
					'asc' => [ "$modelTable.visibility" => SORT_ASC ],
					'desc' => [ "$modelTable.visibility" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Visibility'
				],
				'type' => [
					'asc' => [ "$modelTable.type" => SORT_ASC ],
					'desc' => [ "$modelTable.type" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Type'
				],
				'storage' => [
					'asc' => [ "$modelTable.storage" => SORT_ASC ],
					'desc' => [ "$modelTable.storage" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Storage'
				],
				'url' => [
					'asc' => [ "$modelTable.url" => SORT_ASC ],
					'desc' => [ "$modelTable.url" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Path'
				],
				'shared' => [
					'asc' => [ "$modelTable.shared" => SORT_ASC ],
					'desc' => [ "$modelTable.shared" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Shared'
				],
	            'cdate' => [
	                'asc' => [ "$modelTable.createdAt" => SORT_ASC ],
	                'desc' => [ "$modelTable.createdAt" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Created At'
	            ],
	            'udate' => [
	                'asc' => [ "$modelTable.modifiedAt" => SORT_ASC ],
	                'desc' => [ "$modelTable.modifiedAt" => SORT_DESC ],
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

		// Params
		$type		= Yii::$app->request->getQueryParam( 'type' );
		$visibility	= Yii::$app->request->getQueryParam( 'visibility' );

		// Filter - Type
		if( isset( $type ) ) {

			$config[ 'conditions' ][ "$modelTable.type" ] = $type;
		}

		// Filter - Visibility
		if( isset( $visibility ) && isset( $modelClass::$urlRevVisibilityMap[ $visibility ] ) ) {

			$config[ 'conditions' ][ "$modelTable.visibility" ]	= $modelClass::$urlRevVisibilityMap[ $visibility ];
		}

		// Searching --------

		$searchCol = Yii::$app->request->getQueryParam( 'search' );

		if( isset( $searchCol ) ) {

			$search = [
				'title' => "$modelTable.title",
				'desc' => "$modelTable.description",
				'extension' => "$modelTable.extension",
				'directory' => "$modelTable.directory"
			];

			$config[ 'search-col' ] = $search[ $searchCol ];
		}

		// Reporting --------

		$config[ 'report-col' ]	= [
			'title' => "$modelTable.title",
			'desc' => "$modelTable.description",
			'extension' => "$modelTable.extension",
			'directory' => "$modelTable.directory",
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
		$modelClass = static::$modelClass;

		// Default visibility
		if( !isset( $model->visibility ) ) {

			$model->visibility = File::VISIBILITY_PUBLIC;
		}

		$model->siteId	= isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : Yii::$app->core->siteId;

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

		$attributes = isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [ 'title', 'description', 'altText', 'link', 'type', 'content' ];

		if( $model->changed ) {

			// Find existing file
			$existingFile = self::findById( $model->id );

			// Delete from disk
			$existingFile->clearDisk();

			$attributes[] = 'size';
		}

		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

	public function updateData( $model, $config = [] ) {

		$attributes = isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [ 'title', 'description', 'altText', 'link', 'type', 'content', 'name', 'directory', 'extension', 'url', 'medium', 'thumb' ];

		if( $model->changed ) {

			// Find existing file
			$existingFile = self::findById( $model->id );

			// Delete from disk
			if( isset( $existingFile ) ) {

				$existingFile->clearDisk();
			}

			$attributes[] = 'size';
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

		foreach( $files as $key => $value ) {

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

		$admin = isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : false;

		if( isset( $model ) ) {

			// Only admin is authorised to delete a shared file
			if( $admin || !$model->shared ) {

				// Delete mappings
				Yii::$app->factory->get( 'modelFileService' )->deleteByModelId( $model->id );

				// Delete from disk
				$model->clearDisk();

				// Delete model
				return parent::delete( $model, $config );
			}
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

	// Bulk ---------------

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
			case 'model': {

				switch( $action ) {

					case 'delete': {

						$this->delete( $model, $config );

						break;
					}
				}

				break;
			}
		}
	}

	// Notifications ------

	// Cache --------------

	// Additional ---------

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
