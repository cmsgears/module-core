<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\mappers;

// Yii Imports
use Yii;
use yii\data\Sort;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\services\interfaces\resources\IFileService;
use cmsgears\core\common\services\interfaces\mappers\IModelFileService;

/**
 * ModelFileService provide service methods of file mapper.
 *
 * @since 1.0.0
 */
class ModelFileService extends \cmsgears\core\common\services\base\ModelMapperService implements IModelFileService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass = '\cmsgears\core\common\models\mappers\ModelFile';

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function __construct( IFileService $fileService, $config = [] ) {

		$this->parentService = $fileService;

		parent::__construct( $config );
	}

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ModelFileService ----------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$searchParam	= $config[ 'search-param' ] ?? 'keywords';
		$searchColParam	= $config[ 'search-col-param' ] ?? 'search';

		$defaultSort = isset( $config[ 'defaultSort' ] ) ? $config[ 'defaultSort' ] : [ 'id' => SORT_DESC ];

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$fileClass	= $this->parentService->getModelClass();
		$fileTable	= $fileClass::tableName();

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
					'asc' => [ "$fileTable.name" => SORT_ASC ],
					'desc' => [ "$fileTable.name" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Name'
				],
				'code' => [
					'asc' => [ "$fileTable.code" => SORT_ASC ],
					'desc' => [ "$fileTable.code" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Code'
				],
				'title' => [
					'asc' => [ "$fileTable.title" => SORT_ASC ],
					'desc' => [ "$fileTable.title" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Title'
				],
				'extension' => [
					'asc' => [ "$fileTable.extension" => SORT_ASC ],
					'desc' => [ "$fileTable.extension" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Extension'
				],
				'directory' => [
					'asc' => [ "$fileTable.directory" => SORT_ASC ],
					'desc' => [ "$fileTable.directory" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Directory'
				],
				'size' => [
					'asc' => [ "$fileTable.size" => SORT_ASC ],
					'desc' => [ "$fileTable.size" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Size'
				],
				'visibility' => [
					'asc' => [ "$fileTable.visibility" => SORT_ASC ],
					'desc' => [ "$fileTable.visibility" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Visibility'
				],
				'ftype' => [
					'asc' => [ "$fileTable.type" => SORT_ASC ],
					'desc' => [ "$fileTable.type" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'File Type'
				],
				'storage' => [
					'asc' => [ "$fileTable.storage" => SORT_ASC ],
					'desc' => [ "$fileTable.storage" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Storage'
				],
				'url' => [
					'asc' => [ "$fileTable.url" => SORT_ASC ],
					'desc' => [ "$fileTable.url" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Path'
				],
				'shared' => [
					'asc' => [ "$fileTable.shared" => SORT_ASC ],
					'desc' => [ "$fileTable.shared" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Shared'
				],
	            'cdate' => [
	                'asc' => [ "$fileTable.createdAt" => SORT_ASC ],
	                'desc' => [ "$fileTable.createdAt" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Created At'
	            ],
	            'udate' => [
	                'asc' => [ "$fileTable.modifiedAt" => SORT_ASC ],
	                'desc' => [ "$fileTable.modifiedAt" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Updated At'
	            ],
	            'type' => [
	                'asc' => [ "$modelTable.type" => SORT_ASC ],
	                'desc' => [ "$modelTable.type" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Type'
	            ],
				'order' => [
					'asc' => [ "$modelTable.order" => SORT_ASC ],
					'desc' => [ "$modelTable.order" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Order'
				],
				'active' => [
					'asc' => [ "$modelTable.active" => SORT_ASC ],
					'desc' => [ "$modelTable.active" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Active'
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
				'popular' => [
					'asc' => [ "$modelTable.popular" => SORT_ASC ],
					'desc' => [ "$modelTable.popular" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Popular'
				],
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
		$ftype		= Yii::$app->request->getQueryParam( 'ftype' );
		$visibility	= Yii::$app->request->getQueryParam( 'visibility' );
		$filter		= Yii::$app->request->getQueryParam( 'model' );

		// Filter - Type
		if( isset( $type ) && empty( $config[ 'conditions' ][ "$modelTable.type" ] ) ) {

			$config[ 'conditions' ][ "$modelTable.type" ] = $type;
		}

		// Filter - File Type
		if( isset( $ftype ) && empty( $config[ 'conditions' ][ "$fileTable.type" ] ) ) {

			$config[ 'conditions' ][ "$fileTable.type" ] = $ftype;
		}

		// Filter - Visibility
		if( isset( $visibility ) && isset( $fileClass::$urlRevVisibilityMap[ $visibility ] ) ) {

			$config[ 'conditions' ][ "$fileTable.visibility" ] = $fileClass::$urlRevVisibilityMap[ $visibility ];
		}

		// Filter - Model
		if( isset( $filter ) ) {

			switch( $filter ) {

				case 'active': {

					$config[ 'conditions' ][ "$modelTable.active" ] = true;

					break;
				}
				case 'disabled': {

					$config[ 'conditions' ][ "$modelTable.active" ] = false;

					break;
				}
				case 'pinned': {

					$config[ 'conditions' ][ "$modelTable.pinned" ] = true;

					break;
				}
				case 'featured': {

					$config[ 'conditions' ][ "$modelTable.featured" ] = true;

					break;
				}
				case 'popular': {

					$config[ 'conditions' ][ "$modelTable.popular" ] = true;

					break;
				}
			}
		}

		// Searching --------

		$searchCol		= Yii::$app->request->getQueryParam( $searchColParam );
		$keywordsCol	= Yii::$app->request->getQueryParam( $searchParam );

		$search = [
			'title' => "$fileTable.title",
			'desc' => "$fileTable.description",
			'caption' => "$fileTable.caption",
			'extension' => "$fileTable.extension",
			'directory' => "$fileTable.directory"
		];

		if( isset( $searchCol ) ) {

			$config[ 'search-col' ] = $config[ 'search-col' ] ?? $search[ $searchCol ];
		}
		else if( isset( $keywordsCol ) ) {

			$config[ 'search-col' ] = $config[ 'search-col' ] ?? $search;
		}

		// Reporting --------

		$config[ 'report-col' ]	= $config[ 'report-col' ] ?? [
			'title' => "$fileTable.title",
			'desc' => "$fileTable.description",
			'caption' => "$fileTable.caption",
			'extension' => "$fileTable.extension",
			'directory' => "$fileTable.directory",
			'visibility' => "$fileTable.visibility",
			'ftype' => "$fileTable.type",
			'type' => "$modelTable.type",
			'order' => "$modelTable.order",
			'active' => "$modelTable.active",
			'pinned' => "$modelTable.pinned",
			'featured' => "$modelTable.featured",
			'popular' => "$modelTable.popular"
		];

		// Result -----------

		return parent::getPage( $config );
	}

	// Read ---------------

	// Read - Models ---

	/**
	 * @inheritdoc
	 */
	public function getByFileCode( $parentId, $parentType, $fileCode, $type = null ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByFileCode( $parentId, $parentType, $fileCode, $type );
	}

	/**
	 * @inheritdoc
	 */
	public function getByFileTitle( $parentId, $parentType, $fileTitle ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByFileTitle( $parentId, $parentType, $fileTitle );
	}

	/**
	 * @inheritdoc
	 */
	public function getByFileType( $parentId, $parentType, $fileType ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByFileType( $parentId, $parentType, $fileType );
	}

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function createWithParent( $parent, $config = [] ) {

		$modelClass	= static::$modelClass;

		$parentId	= $config[ 'parentId' ];
		$parentType	= $config[ 'parentType' ];
		$type		= isset( $config[ 'type' ] ) ? $config[ 'type' ] : CoreGlobal::TYPE_DEFAULT;
		$order		= isset( $config[ 'order' ] ) ? $config[ 'order' ] : 0;

		$file = null;

		switch( $parent->type ) {

			case 'image': {

				$file = $this->parentService->saveImage( $parent );
			}
			// TODO: Add case to save cross-browser compatible videos
			default: {

				$file = $this->parentService->saveFile( $parent );
			}
		}

		$model = isset( $config[ 'model' ] ) ? $config[ 'model' ] : new $modelClass;

		$model->modelId		= $file->id;
		$model->parentId	= $parentId;
		$model->parentType	= $parentType;

		$model->type	= $file->type;
		$model->order	= $order;
		$model->active	= isset( $model->active ) ? $model->active : true;

		return parent::create( $model );
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		$admin = isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : false;

		$attributes = isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [
			'type', 'order', 'active'
		];

		if( $admin ) {

			$attributes	= ArrayHelper::merge( $attributes, [
				'pinned', 'featured', 'popular'
			]);
		}

		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

	// Delete -------------

	public function deleteWithParent( $model, $config = [] ) {

		$parent = $this->parentService->getById( $model->modelId );

		$this->parentService->delete( $parent, $config );
	}

	// Bulk ---------------

	protected function applyBulk( $model, $column, $action, $target, $config = [] ) {

		switch( $column ) {

			case 'model': {

				switch( $action ) {

					case 'activate': {

						$model->active = true;

						$model->update();

						break;
					}
					case 'disable': {

						$model->active = false;

						$model->update();

						break;
					}
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
					case 'popular': {

						$model->popular = true;

						$model->update();

						break;
					}
					case 'delete': {

						$this->deleteWithParent( $model, $config );

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

	// ModelFileService ----------------------

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
