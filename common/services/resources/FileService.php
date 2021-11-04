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

use cmsgears\core\common\services\traits\base\MultiSiteTrait;
use cmsgears\core\common\services\traits\base\SharedTrait;
use cmsgears\core\common\services\traits\base\VisibilityTrait;
use cmsgears\core\common\services\traits\cache\GridCacheTrait;
use cmsgears\core\common\services\traits\resources\DataTrait;

/**
 * FileService provide service methods of file model.
 *
 * @since 1.0.0
 */
class FileService extends \cmsgears\core\common\services\base\ResourceService implements IFileService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass = '\cmsgears\core\common\models\resources\File';

	public static $parentType = CoreGlobal::TYPE_FILE;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	use DataTrait;
	use GridCacheTrait;
	use MultiSiteTrait;
	use SharedTrait;
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

		$searchParam	= $config[ 'search-param' ] ?? 'keywords';
		$searchColParam	= $config[ 'search-col-param' ] ?? 'search';

		$defaultSort = isset( $config[ 'defaultSort' ] ) ? $config[ 'defaultSort' ] : [ 'id' => SORT_DESC ];

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
			'defaultOrder' => $defaultSort
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
		$filter		= Yii::$app->request->getQueryParam( 'model' );

		// Filter - Type
		if( isset( $type ) && empty( $config[ 'conditions' ][ "$modelTable.type" ] ) ) {

			$config[ 'conditions' ][ "$modelTable.type" ] = $type;
		}

		// Filter - Visibility
		if( isset( $visibility ) && empty( $config[ 'conditions' ][ "$modelTable.visibility" ] ) && isset( $modelClass::$urlRevVisibilityMap[ $visibility ] ) ) {

			$config[ 'conditions' ][ "$modelTable.visibility" ]	= $modelClass::$urlRevVisibilityMap[ $visibility ];
		}

		// Filter - Model
		if( isset( $filter ) ) {

			switch( $filter ) {

				case 'shared': {

					$config[ 'conditions' ][ "$modelTable.shared" ] = true;

					break;
				}
			}
		}

		// Searching --------

		$searchCol		= Yii::$app->request->getQueryParam( $searchColParam );
		$keywordsCol	= Yii::$app->request->getQueryParam( $searchParam );

		$search = [
			'title' => "$modelTable.title",
			'desc' => "$modelTable.description",
			'caption' => "$modelTable.caption",
			'extension' => "$modelTable.extension",
			'directory' => "$modelTable.directory"
		];

		if( isset( $searchCol ) ) {

			$config[ 'search-col' ] = $config[ 'search-col' ] ?? $search[ $searchCol ];
		}
		else if( isset( $keywordsCol ) ) {

			$config[ 'search-col' ] = $config[ 'search-col' ] ?? $search;
		}

		// Reporting --------

		$config[ 'report-col' ]	= $config[ 'report-col' ] ?? [
			'title' => "$modelTable.title",
			'desc' => "$modelTable.description",
			'caption' => "$modelTable.caption",
			'extension' => "$modelTable.extension",
			'directory' => "$modelTable.directory",
			'type' => "$modelTable.type",
			'visibility' => "$modelTable.visibility"
		];

		// Result -----------

		return parent::getPage( $config );
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

		// Model Class
		$modelClass = static::$modelClass;

		// Default Visibility
		if( !isset( $model->visibility ) ) {

			$model->visibility = File::VISIBILITY_PUBLIC;
		}

		return parent::create( $model );
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		//$admin = isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : false;

		$attributes = isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [
			'code', 'title', 'description', 'caption', 'altText', 'link', 'type', 'content'
		];

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

		$attributes = isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [
			'title', 'description', 'caption', 'altText', 'link', 'type', 'content',
			'name', 'directory', 'extension', 'url', 'medium', 'small', 'thumb',
			'placeholder', 'smallPlaceholder', 'srcset', 'sizes'
		];

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

			$fileManager = Yii::$app->fileManager;

			$args[ 'width' ]	= isset( $args[ 'width' ] ) ? isset( $args[ 'width' ] ) : null;
			$args[ 'height' ]	= isset( $args[ 'height' ] ) ? isset( $args[ 'height' ] ) : null;
			$args[ 'mwidth' ]	= isset( $args[ 'mwidth' ] ) ? isset( $args[ 'mwidth' ] ) : null;
			$args[ 'mheight' ]	= isset( $args[ 'mheight' ] ) ? isset( $args[ 'mheight' ] ) : null;
			$args[ 'swidth' ]	= isset( $args[ 'swidth' ] ) ? isset( $args[ 'swidth' ] ) : null;
			$args[ 'sheight' ]	= isset( $args[ 'sheight' ] ) ? isset( $args[ 'sheight' ] ) : null;
			$args[ 'twidth' ]	= isset( $args[ 'twidth' ] ) ? isset( $args[ 'twidth' ] ) : null;
			$args[ 'theight' ]	= isset( $args[ 'theight' ] ) ? isset( $args[ 'theight' ] ) : null;

			// The model and it's attribute
			$model		= isset( $args[ 'model' ] ) ? $args[ 'model' ] : null;
			$attribute	= isset( $args[ 'attribute' ] ) ? $args[ 'attribute' ] : null;

			// Update Image
			$fileId = $file->id;

			if( $file->changed ) {

				// Override controller args
				if( isset( $file->width ) && isset( $file->height ) ) {

					$args[ 'width' ]	= $file->width;
					$args[ 'height' ]	= $file->height;
				}

				if( isset( $file->mwidth ) && isset( $file->mheight ) ) {

					$args[ 'mwidth' ]	= $file->mwidth;
					$args[ 'mheight' ]	= $file->mheight;
				}

				if( isset( $file->swidth ) && isset( $file->sheight ) ) {

					$args[ 'swidth' ]	= $file->swidth;
					$args[ 'sheight' ]	= $file->sheight;
				}

				if( isset( $file->twidth ) && isset( $file->theight ) ) {

					$args[ 'twidth' ]	= $file->twidth;
					$args[ 'theight' ]	= $file->theight;
				}

				// Process Image
				$fileManager->processImage( $file, $args );
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

			$file->changed = false;
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

			$fileManager = Yii::$app->fileManager;

			// The model and it's attribute
			$model		= isset( $args[ 'model' ] ) ? $args[ 'model' ] : null;
			$attribute	= isset( $args[ 'attribute' ] ) ? $args[ 'attribute' ] : null;

			// Update File
			$fileId = $file->id;

			if( $file->changed ) {

				if( $file->isImage() ) {

					$fileManager->processImage( $file );
				}
				else {

					$fileManager->processFile( $file );
				}
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

			// Ignores empty files
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

	/**
	 * Delete the file and corresponding mappings.
	 *
	 * @param \cmsgears\core\common\models\resources\File $model
	 * @param array $config
	 * @return boolean
	 */
	public function delete( $model, $config = [] ) {

		$backend	= isset( $config[ 'backend' ] ) ? $config[ 'backend' ] : false;
		$frontend	= isset( $config[ 'frontend' ] ) ? $config[ 'frontend' ] : false;

		if( isset( $model ) ) {

			// Admin can delete all the files
			// Users can delete frontend and shared files
			// Non-Shared files can be deleted with the model
			if( $backend || ( $frontend && isset( $model->userId ) && $model->shared ) || !$model->shared ) {

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

	// Bulk ---------------

	protected function applyBulk( $model, $column, $action, $target, $config = [] ) {

		switch( $column ) {

			case 'visibility': {

				switch( $action ) {

					case File::VISIBILITY_PUBLIC: {

						$model->visibility = File::VISIBILITY_PUBLIC;

						$model->update();

						break;
					}
					case File::VISIBILITY_PROTECTED: {

						$model->visibility = File::VISIBILITY_PROTECTED;

						$model->update();

						break;
					}
					case File::VISIBILITY_SECURED: {

						$model->visibility = File::VISIBILITY_SECURED;

						$model->update();

						break;
					}
					case File::VISIBILITY_PRIVATE: {

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
