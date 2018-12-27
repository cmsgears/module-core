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

// CMG Imports
use cmsgears\core\common\services\interfaces\resources\IFileService;
use cmsgears\core\common\services\interfaces\mappers\IModelFileService;

use cmsgears\core\common\services\base\ModelMapperService;

/**
 * ModelFileService provide service methods of file mapper.
 *
 * @since 1.0.0
 */
class ModelFileService extends ModelMapperService implements IModelFileService {

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

	private $fileService;

	// Traits ------------------------------------------------------

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

	// ModelFileService ----------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$fileClass	= Yii::$app->factory->get( 'fileService' )->getModelClass();
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
		$ftype		= Yii::$app->request->getQueryParam( 'ftype' );
		$filter		= Yii::$app->request->getQueryParam( 'model' );
		$visibility	= Yii::$app->request->getQueryParam( 'visibility' );

		// Filter - Type
		if( isset( $type ) ) {

			$config[ 'conditions' ][ "$modelTable.type" ] = $type;
		}

		// Filter - File Type
		if( isset( $ftype ) ) {

			$config[ 'conditions' ][ "$fileTable.type" ] = $ftype;
		}

		// Filter - Model
		if( isset( $filter ) ) {

			switch( $filter ) {

				case 'active': {

					$config[ 'conditions' ][ "$modelTable.active" ] = true;

					break;
				}
			}
		}

		// Filter - Visibility
		if( isset( $visibility ) && isset( $fileClass::$urlRevVisibilityMap[ $visibility ] ) ) {

			$config[ 'conditions' ][ "$fileTable.visibility" ]	= $fileClass::$urlRevVisibilityMap[ $visibility ];
		}

		// Searching --------

		$searchCol = Yii::$app->request->getQueryParam( 'search' );

		if( isset( $searchCol ) ) {

			$search = [
				'title' => "$fileTable.title",
				'desc' => "$fileTable.description",
				'caption' => "$fileTable.caption",
				'extension' => "$fileTable.extension",
				'directory' => "$fileTable.directory"
			];

			$config[ 'search-col' ] = $search[ $searchCol ];
		}

		// Reporting --------

		$config[ 'report-col' ]	= [
			'title' => "$fileTable.title",
			'desc' => "$fileTable.description",
			'caption' => "$fileTable.caption",
			'extension' => "$fileTable.extension",
			'directory' => "$fileTable.directory",
			'visibility' => "$fileTable.visibility",
			'order' => "$modelTable.order",
			'active' => "$modelTable.active"
		];

		// Result -----------

		return parent::getPage( $config );
	}

	// Read ---------------

	// Read - Models ---

	/**
	 * @inheritdoc
	 */
	public function getByFileTag( $parentId, $parentType, $fileTag, $type = null ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByFileTag( $parentId, $parentType, $fileTag, $type );
	}

	/**
	 * @inheritdoc
	 */
	public function searchByFileTitle( $parentId, $parentType, $fileTitle ) {

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
		$type		= isset( $config[ 'type' ] ) ? $config[ 'type' ] : $parent->type;
		$order		= isset( $config[ 'order' ] ) ? $config[ 'order' ] : 0;

		$file = null;

		switch( $parent->type ) {

			case 'image': {

				$file = $this->fileService->saveImage( $parent );
			}
			// TODO: Add case to save cross-browser compatible videos
			default: {

				$file = $this->fileService->saveFile( $parent );
			}
		}

		$model = new $modelClass;

		$model->modelId		= $file->id;
		$model->parentId	= $parentId;
		$model->parentType	= $parentType;

		$model->type	= $type;
		$model->order	= $order;
		$model->active	= true;

		return parent::create( $model );
	}

	// Update -------------

	// Delete -------------

	// Bulk ---------------

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
