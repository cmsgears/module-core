<?php
namespace cmsgears\core\common\services\resources;

// Yii Imports
use Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\resources\File;
use cmsgears\core\common\models\mappers\ModelGallery;
use cmsgears\core\common\models\mappers\ModelFile;

use cmsgears\core\common\services\interfaces\resources\IGalleryService;
use cmsgears\core\common\services\interfaces\resources\IFileService;

use cmsgears\core\common\services\traits\NameTypeTrait;
use cmsgears\core\common\services\traits\SlugTypeTrait;

/**
 * The class GalleryService is base class to perform database activities for Gallery Entity.
 */
class GalleryService extends \cmsgears\core\common\services\base\EntityService implements IGalleryService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\core\common\models\resources\Gallery';

	public static $modelTable	= CoreTables::TABLE_GALLERY;

	public static $parentType	= CoreGlobal::TYPE_GALLERY;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $fileService;

	// Private ----------------

	// Traits ------------------------------------------------------

	use NameTypeTrait;
	use SlugTypeTrait;

	// Constructor and Initialisation ------------------------------

	public function __construct( IFileService $fileService, $config = [] ) {

		$this->fileService	= $fileService;

		parent::__construct( $config );
	}

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// GalleryService ------------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$modelClass		= static::$modelClass;
		$modelTable		= static::$modelTable;
		$templateTable	= CoreTables::TABLE_TEMPLATE;

		// Sorting ----------

		$sort = new Sort([
			'attributes' => [
	            'template' => [
	                'asc' => [ "`$templateTable`.`name`" => SORT_ASC ],
	                'desc' => [ "`$templateTable`.`name`" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Template'
	            ],
				'name' => [
					'asc' => [ 'name' => SORT_ASC ],
					'desc' => ['name' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'name'
				],
				'slug' => [
					'asc' => [ 'slug' => SORT_ASC ],
					'desc' => ['slug' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'slug'
				],
	            'type' => [
	                'asc' => [ 'type' => SORT_ASC ],
	                'desc' => ['type' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Type'
	            ],
				'title' => [
					'asc' => [ 'title' => SORT_ASC ],
					'desc' => ['title' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'title'
				],
	            'active' => [
	                'asc' => [ 'active' => SORT_ASC ],
	                'desc' => ['active' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Active'
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

		// Filter - Status
		$status	= Yii::$app->request->getQueryParam( 'status' );

		if( isset( $status ) ) {

			switch( $status ) {

				case 'active': {

					$config[ 'conditions' ][ "$modelTable.active" ]	= true;

					break;
				}
			}
		}

		// Searching --------

		$searchCol	= Yii::$app->request->getQueryParam( 'search' );

		if( isset( $searchCol ) ) {

			$search = [ 'name' => "$modelTable.name", 'slug' => "$modelTable.slug", 'title' => "$modelTable.title", 'desc' => "$modelTable.description" ];

			$config[ 'search-col' ] = $search[ $searchCol ];
		}

		// Reporting --------

		$config[ 'report-col' ]	= [
			'name' => "$modelTable.name", 'slug' => "$modelTable.slug", 'title' => "$modelTable.title", 'desc' => "$modelTable.description",
			'content' => "$modelTable.content", 'active' => "$modelTable.active"
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

	public function createByParams( $params = [], $config = [] ) {

		$autoName	= isset( $config[ 'autoName' ] ) ? $config[ 'autoName' ] : false;

		if( $autoName ) {

			$gallery	= $this->getByNameType( $params[ 'name' ], $params[ 'type' ] );

			// Rare scenario
			if( isset( $gallery ) ) {

				$params[ 'name' ]	= Yii::$app->security->generateRandomString( 16 );
			}
		}

		return parent::createByParams( $params, $config );
	}

	public function createItem( $gallery, $item ) {

		$modelFile	= new ModelFile();

		// Save Gallery Image
		$this->fileService->saveImage( $item, [ 'model' => $modelFile, 'attribute' => 'modelId' ] );

		// Save Gallery Item
		if( $item->id > 0 ) {

			$modelFile->parentType	= CoreGlobal::TYPE_GALLERY;
			$modelFile->parentId	= $gallery->id;

			$modelFile->save();
		}

		return $item;
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		$attributes = isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [ 'templateId', 'name', 'title', 'description' ];

		// Provide activate/deactivate capability to admin
		if( isset( $config[ 'admin' ] ) && $config[ 'admin' ] ) {

			$attributes[] = 'active';
		}

		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

	public function updateItem( $item ) {

		// Save Gallery Item
		$this->fileService->saveImage( $item );

		return true;
	}

	public function refreshItems( $gallery, $config = [] ) {

		$name	= isset( $config[ 'name' ] ) ? $config[ 'name' ] : 'File';
		$items	= isset( $config[ 'items' ] ) ? $config[ 'items' ] : [];

		if( count( $items ) == 0 && isset( $name ) ) {

			$items	= File::loadFiles( $name );
		}

		foreach( $items as $item ) {

			if( isset( $item->id ) && $item->id > 0 ) {

				$this->updateItem( $item );
			}
			else {

				$this->createItem( $gallery, $item );
			}
		}
	}

	public function switchActive( $model, $config = [] ) {

		$global			= $model->global ? false : true;
		$model->global	= $global;

		return parent::updateSelective( $model, [
			'attributes' => [ 'global' ]
		]);
 	}

	protected function applyBulk( $model, $column, $action, $target, $config = [] ) {

		switch( $column ) {

			case 'status': {

				switch( $action ) {

					case 'active': {

						$model->active = true;

						$model->update();

						break;
					}
					case 'block': {

						$model->active = false;

						$model->update();

						break;
					}
				}

				break;
			}
			case 'model': {

				switch( $action ) {

					case 'delete': {

						$this->delete( $model );

						break;
					}
				}

				break;
			}
		}
	}

	// Delete -------------

	public function delete( $model, $config = [] ) {

		// Delete items
		$items	= $model->files;

		// Delete Items
		foreach ( $items as $item ) {

			$this->fileService->delete( $item );
		}

		// Delete mappings
		ModelGallery::deleteByModelId( $model->id );

		// Delete model
		return parent::delete( $model, $config );
	}

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// GalleryService ------------------------

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
