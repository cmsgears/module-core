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
use cmsgears\core\common\services\interfaces\resources\IGalleryService;
use cmsgears\core\common\services\interfaces\mappers\IModelGalleryService;

/**
 * ModelGalleryService provide service methods of gallery mapper.
 *
 * @since 1.0.0
 */
class ModelGalleryService extends \cmsgears\core\common\services\base\ModelMapperService implements IModelGalleryService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass = '\cmsgears\core\common\models\mappers\ModelGallery';

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function __construct( IGalleryService $galleryService, $config = [] ) {

		$this->parentService = $galleryService;

		parent::__construct( $config );
	}

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ModelGalleryService -------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$searchParam	= $config[ 'search-param' ] ?? 'keywords';
		$searchColParam	= $config[ 'search-col-param' ] ?? 'search';

		$defaultSort = isset( $config[ 'defaultSort' ] ) ? $config[ 'defaultSort' ] : [ 'id' => SORT_DESC ];

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$galleryClass	= $this->parentService->getModelClass();
		$galleryTable	= $galleryClass::tableName();

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
					'asc' => [ "$galleryTable.name" => SORT_ASC ],
					'desc' => [ "$galleryTable.name" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Name'
				],
				'title' => [
					'asc' => [ "$galleryTable.title" => SORT_ASC ],
					'desc' => [ "$galleryTable.title" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Title'
				],
	            'cdate' => [
	                'asc' => [ "$galleryTable.createdAt" => SORT_ASC ],
	                'desc' => [ "$galleryTable.createdAt" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Created At'
	            ],
	            'udate' => [
	                'asc' => [ "$galleryTable.modifiedAt" => SORT_ASC ],
	                'desc' => [ "$galleryTable.modifiedAt" => SORT_DESC ],
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
		$type	= Yii::$app->request->getQueryParam( 'type' );
		$filter	= Yii::$app->request->getQueryParam( 'model' );

		// Filter - Type
		if( isset( $type ) ) {

			$config[ 'conditions' ][ "$modelTable.type" ] = $type;
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
			'title' => "$galleryTable.title",
			'desc' => "$galleryTable.description"
		];

		if( isset( $searchCol ) ) {

			$config[ 'search-col' ] = $config[ 'search-col' ] ?? $search[ $searchCol ];
		}
		else if( isset( $keywordsCol ) ) {

			$config[ 'search-col' ] = $config[ 'search-col' ] ?? $search;
		}

		// Reporting --------

		$config[ 'report-col' ]	= $config[ 'report-col' ] ?? [
			'title' => "$galleryTable.title",
			'desc' => "$galleryTable.description",
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

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

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

						$this->deleteWithParent( $model );

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

	// ModelGalleryService -------------------

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
