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
use cmsgears\core\common\services\interfaces\entities\IObjectDataService;
use cmsgears\core\common\services\interfaces\mappers\IModelObjectService;

use cmsgears\core\common\services\traits\base\FeaturedTrait;

/**
 * ModelObjectService provide service methods of object mapper.
 *
 * @since 1.0.0
 */
class ModelObjectService extends \cmsgears\core\common\services\base\ModelMapperService implements IModelObjectService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass = '\cmsgears\core\common\models\mappers\ModelObject';

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	use FeaturedTrait;

	// Constructor and Initialisation ------------------------------

	public function __construct( IObjectDataService $objectService, $config = [] ) {

		$this->parentService = $objectService;

		parent::__construct( $config );
	}

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ModelObjectService --------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$searchParam	= $config[ 'search-param' ] ?? 'keywords';
		$searchColParam	= $config[ 'search-col-param' ] ?? 'search';

		$defaultSort = isset( $config[ 'defaultSort' ] ) ? $config[ 'defaultSort' ] : [ 'id' => SORT_DESC ];

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$objectDataClass = $this->parentService->getModelClass();
		$objectDataTable = $this->parentService->getModelTable();

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
					'asc' => [ "$objectDataTable.name" => SORT_ASC ],
					'desc' => [ "$objectDataTable.name" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Name',
				],
				'title' => [
					'asc' => [ "$objectDataTable.title" => SORT_ASC ],
					'desc' => [ "$objectDataTable.title" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Title',
				],
				'status' => [
					'asc' => [ "$objectDataTable.status" => SORT_ASC ],
					'desc' => [ "$objectDataTable.status" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Status',
				],
				'visibility' => [
					'asc' => [ "$objectDataTable.visibility" => SORT_ASC ],
					'desc' => [ "$objectDataTable.visibility" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Visibility'
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
		$type	= Yii::$app->request->getQueryParam( 'type' );
		$status	= Yii::$app->request->getQueryParam( 'status' );
		$filter	= Yii::$app->request->getQueryParam( 'model' );

		// Filter - Type
		if( isset( $type ) && empty( $config[ 'conditions' ][ "$modelTable.type" ] ) ) {

			$config[ 'conditions' ][ "$modelTable.type" ] = $type;
		}

		// Filter - Status
		if( isset( $status ) && empty( $config[ 'conditions' ][ "$modelTable.status" ] ) && isset( $objectDataClass::$urlRevStatusMap[ $status ] ) ) {

			$config[ 'conditions' ][ "$objectDataTable.status" ] = $objectDataClass::$urlRevStatusMap[ $status ];
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
			}
		}

		// Searching --------

		$searchCol		= Yii::$app->request->getQueryParam( $searchColParam );
		$keywordsCol	= Yii::$app->request->getQueryParam( $searchParam );

		$search = [
			'name' => "$objectDataTable.name",
			'title' => "$objectDataTable.title",
			'desc' => "$objectDataTable.description",
			'content' => "$objectDataTable.content"
		];

		if( isset( $searchCol ) ) {

			$config[ 'search-col' ] = $config[ 'search-col' ] ?? $search[ $searchCol ];
		}
		else if( isset( $keywordsCol ) ) {

			$config[ 'search-col' ] = $config[ 'search-col' ] ?? $search;
		}

		// Reporting --------

		$config[ 'report-col' ]	= $config[ 'report-col' ] ?? [
			'name' => "$objectDataTable.name",
			'title' => "$objectDataTable.title",
			'desc' => "$objectDataTable.description",
			'content' => "$objectDataTable.content",
			'status' => "$objectDataTable.status",
			'visibility' => "$objectDataTable.visibility",
			'order' => "$modelTable.order",
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

	// Update -------------

	// Delete -------------

	public function deleteWithParent( $model, $config = [] ) {

		$parent = $this->parentService->getById( $model->modelId );

		$this->parentService->delete( $parent, $config );
	}

	// Bulk ---------------

	protected function applyBulk( $model, $column, $action, $target, $config = [] ) {

		$direct = isset( $config[ 'direct' ] ) ? $config[ 'direct' ] : false; // Trigger direct notifications
		$users	= isset( $config[ 'users' ] ) ? $config[ 'users' ] : []; // Trigger user notifications

		switch( $column ) {

			case 'status': {

				switch( $action ) {

					case 'activate': {

						$this->parentService->activate( $model->parent, [ 'direct' => $direct, 'users' => $users ] );

						break;
					}
					case 'freeze': {

						$this->parentService->freeze( $model->parent, [ 'direct' => $direct, 'users' => $users ] );

						break;
					}
					case 'block': {

						$this->parentService->block( $model->parent, [ 'direct' => $direct, 'users' => $users ] );

						break;
					}
					case 'terminate': {

						$this->parentService->terminate( $model->parent, [ 'direct' => $direct, 'users' => $users ] );

						break;
					}
				}

				break;
			}
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

	// ModelObjectService --------------------

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
