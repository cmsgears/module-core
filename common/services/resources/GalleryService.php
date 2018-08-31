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
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\services\interfaces\resources\IGalleryService;
use cmsgears\core\common\services\interfaces\resources\IFileService;

use cmsgears\core\common\services\base\ResourceService;

use cmsgears\core\common\services\traits\base\ApprovalTrait;
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

	use ApprovalTrait;
	use DataTrait;
	use NameTypeTrait;
	use SlugTypeTrait;

	// Constructor and Initialisation ------------------------------

	public function __construct( IFileService $fileService, $config = [] ) {

		$this->fileService = $fileService;

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

		$templateTable = Yii::$app->factory->get( 'templateService' )->getModelTable();

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
	                'asc' => [ "$templateTable.name" => SORT_ASC ],
	                'desc' => [ "$templateTable.name" => SORT_DESC ],
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
	            'icon' => [
	                'asc' => [ "$modelTable.icon" => SORT_ASC ],
	                'desc' => [ "$modelTable.icon" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Icon'
	            ],
				'title' => [
					'asc' => [ "$modelTable.title" => SORT_ASC ],
					'desc' => [ "$modelTable.title" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Title'
				],
				'status' => [
					'asc' => [ "$modelTable.status" => SORT_ASC ],
					'desc' => [ "$modelTable.status" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Status'
				],
				'visibility' => [
					'asc' => [ "$modelTable.visibility" => SORT_ASC ],
					'desc' => [ "$modelTable.visibility" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Visibility'
				],
				'order' => [
					'asc' => [ "$modelTable.order" => SORT_ASC ],
					'desc' => [ "$modelTable.order" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Order'
				],
				'pinned' => [
					'asc' => [ "$modelTable.pinned" => SORT_ASC ],
					'desc' => [ "$modelTable.pinned" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Pinned'
				],
				'featured' => [
					'asc' => [ "$modelTable.featured" => SORT_ASC ],
					'desc' => [ "$modelTable.featured" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Featured'
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
		$type	= Yii::$app->request->getQueryParam( 'type' );
		$status	= Yii::$app->request->getQueryParam( 'status' );
		$filter	= Yii::$app->request->getQueryParam( 'model' );

		// Filter - Type
		if( isset( $type ) ) {

			$config[ 'conditions' ][ "$modelTable.type" ] = $type;
		}

		// Filter - Status
		if( isset( $status ) && isset( $modelClass::$urlRevStatusMap[ $status ] ) ) {

			$config[ 'conditions' ][ "$modelTable.status" ]	= $modelClass::$urlRevStatusMap[ $status ];
		}

		// Filter - Model
		if( isset( $filter ) ) {

			switch( $filter ) {

				case 'pinned': {

					$config[ 'conditions' ][ "$modelTable.pinned" ] = true;

					break;
				}
				case 'featured': {

					$config[ 'conditions' ][ "$modelTable.featured" ] = true;

					break;
				}
			}
		}

		// Searching --------

		$searchCol	= Yii::$app->request->getQueryParam( 'search' );

		if( isset( $searchCol ) ) {

			$search = [
				'name' => "$modelTable.name",
				'title' => "$modelTable.title",
				'desc' => "$modelTable.description",
				'content' => "$modelTable.content"
			];

			$config[ 'search-col' ] = $search[ $searchCol ];
		}

		// Reporting --------

		$config[ 'report-col' ]	= [
			'name' => "$modelTable.name",
			'title' => "$modelTable.title",
			'desc' => "$modelTable.description",
			'content' => "$modelTable.content",
			'status' => "$modelTable.status",
			'visibility' => "$modelTable.visibility",
			'order' => "$modelTable.order",
			'pinned' => "$modelTable.pinned",
			'featured' => "$modelTable.featured"
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

	public function createItem( $gallery, $item ) {

		$modelFile = Yii::$app->factory->get( 'modelFileService' )->getModelObject();

		// Save Gallery Image
		$this->fileService->saveImage( $item, [ 'model' => $modelFile, 'attribute' => 'modelId' ] );

		// Save Gallery Item
		if( $item->id > 0 ) {

			$modelFile->parentType	= static::$parentType;
			$modelFile->parentId	= $gallery->id;

			$modelFile->save();
		}

		return $modelFile;
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		$admin 		= isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : false;

		$attributes = isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [
			'templateId', 'name', 'slug', 'icon', 'title',
			'description', 'visibility', 'content'
		];

		if( $admin ) {

			$attributes	= ArrayHelper::merge( $attributes, [ 'status', 'order', 'pinned', 'featured' ] );
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

	// Delete -------------

	public function delete( $model, $config = [] ) {

		if( empty( $model ) ) {

			return false;
		}

		// Delete Items
		$this->fileService->deleteFiles( $model->files );

		// Delete mappings
		Yii::$app->factory->get( 'modelGalleryService' )->deleteByModelId( $model->id );

		// Delete model
		return parent::delete( $model, $config );
	}

	// Bulk ---------------

	protected function applyBulk( $model, $column, $action, $target, $config = [] ) {

		switch( $column ) {

			case 'status': {

				switch( $action ) {

					case 'confirmed': {

						$this->confirm( $model );

						break;
					}
					case 'rejected': {

						$this->reject( $model );

						break;
					}
					case 'active': {

						$this->approve( $model );

						break;
					}
					case 'frozen': {

						$this->freeze( $model );

						break;
					}
					case 'blocked': {

						$this->block( $model );

						break;
					}
				}

				break;
			}
			case 'model': {

				switch( $action ) {

					case 'pinned': {

						$model->pinned = true;

						$model->update();

						break;
					}
					case 'featured': {

						$model->featured = true;

						$model->update();

						break;
					}
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
