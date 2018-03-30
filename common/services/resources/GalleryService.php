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

use cmsgears\core\common\services\interfaces\resources\IGalleryService;
use cmsgears\core\common\services\interfaces\resources\IFileService;

use cmsgears\core\common\services\base\ResourceService;

use cmsgears\core\common\services\traits\base\NameTypeTrait;
use cmsgears\core\common\services\traits\base\SlugTypeTrait;
use cmsgears\core\common\services\traits\resources\DataTrait;

/**
 * GalleryService provide service methods of gallery model.
 *
 * @since 1.0.0
 */
class GalleryService extends ResourceService implements IGalleryService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\core\common\models\resources\Gallery';

	public static $typed		= true;

	public static $parentType	= CoreGlobal::TYPE_GALLERY;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $fileService;

	// Private ----------------

	// Traits ------------------------------------------------------

	use DataTrait;
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

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$templateTable	= Yii::$app->get( 'templateService' )->getModelTable();

		// Sorting ----------

		$sort = new Sort([
			'attributes' => [
				'id' => [
					'asc' => [ "$modelTable.id" => SORT_ASC ],
					'desc' => [ "$modelTable.id" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Id'
				],
	            'template' => [
	                'asc' => [ "`$templateTable`.`name`" => SORT_ASC ],
	                'desc' => [ "`$templateTable`.`name`" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Template'
	            ],
				'name' => [
					'asc' => [ "$modelTable.name" => SORT_ASC ],
					'desc' => [ "$modelTable.name" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Name'
				],
				'slug' => [
					'asc' => [ "$modelTable.slug" => SORT_ASC ],
					'desc' => [ "$modelTable.slug" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Slug'
				],
	            'type' => [
	                'asc' => [ "$modelTable.type" => SORT_ASC ],
	                'desc' => [ "$modelTable.type" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Type'
	            ],
				'title' => [
					'asc' => [ "$modelTable.title" => SORT_ASC ],
					'desc' => [ "$modelTable.title" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Title'
				],
	            'active' => [
	                'asc' => [ "$modelTable.active" => SORT_ASC ],
	                'desc' => [ "$modelTable.active" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Active'
	            ],
				'cdate' => [
					'asc' => [ "$modelTable.createdAt" => SORT_ASC ],
					'desc' => [ "$modelTable.createdAt" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Created At'
				],
				'udate' => [
					'asc' => [ "$modelTable.updatedAt" => SORT_ASC ],
					'desc' => [ "$modelTable.updatedAt" => SORT_DESC ],
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

			$search = [
				'name' => "$modelTable.name",
				'slug' => "$modelTable.slug",
				'title' => "$modelTable.title",
				'desc' => "$modelTable.description"
			];

			$config[ 'search-col' ] = $search[ $searchCol ];
		}

		// Reporting --------

		$config[ 'report-col' ]	= [
			'name' => "$modelTable.name",
			'slug' => "$modelTable.slug",
			'title' => "$modelTable.title",
			'desc' => "$modelTable.description",
			'content' => "$modelTable.content",
			'active' => "$modelTable.active"
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

		$autoName = isset( $config[ 'autoName' ] ) ? $config[ 'autoName' ] : false;

		if( $autoName ) {

			$gallery = $this->getByNameType( $params[ 'name' ], $params[ 'type' ] );

			// Rare scenario
			if( isset( $gallery ) ) {

				$params[ 'name' ] = Yii::$app->security->generateRandomString( 16 );
			}
		}

		return parent::createByParams( $params, $config );
	}

	public function createItem( $gallery, $item ) {

		$modelFile = Yii::$app->get( 'modelFileService' )->getModelObject();

		// Save Gallery Image
		$this->fileService->saveImage( $item, [ 'model' => $modelFile, 'attribute' => 'modelId' ] );

		// Save Gallery Item
		if( $item->id > 0 ) {

			$modelFile->parentType	= static::$parentType;
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

			$fileClass = Yii::$app->get( 'fileService' )->getModelClass();

			$items = $fileClass::loadFiles( $name );
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

		$global	= $model->global ? false : true;

		$model->global	= $global;

		return parent::updateSelective( $model, [
			'attributes' => [ 'global' ]
		]);
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
		Yii::$app->get( 'modelGalleryService' )->deleteByModelId( $model->id );

		// Delete model
		return parent::delete( $model, $config );
	}

	// Bulk ---------------

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

	// Notifications ------

	// Cache --------------

	// Additional ---------

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
