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

use cmsgears\core\common\services\interfaces\resources\ICategoryService;

use cmsgears\core\common\services\traits\base\MultisiteTrait;
use cmsgears\core\common\services\traits\base\NameTypeTrait;
use cmsgears\core\common\services\traits\base\SlugTypeTrait;
use cmsgears\core\common\services\traits\hierarchy\HierarchyTrait;
use cmsgears\core\common\services\traits\hierarchy\NestedSetTrait;
use cmsgears\core\common\services\traits\resources\DataTrait;

/**
 * CategoryService provide service methods of category model.
 *
 * @since 1.0.0
 */
class CategoryService extends \cmsgears\core\common\services\base\ResourceService implements ICategoryService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass = '\cmsgears\core\common\models\resources\Category';

	public static $typed = true;

	public static $parentType = CoreGlobal::TYPE_CATEGORY;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	use DataTrait;
	use HierarchyTrait;
	use MultisiteTrait;
	use NameTypeTrait;
	use NestedSetTrait;
	use SlugTypeTrait;

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// CategoryService -----------------------

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
				'parent' => [
					'asc' => [ 'parent.name' => SORT_ASC ],
					'desc' => [ 'parent.name' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Parent'
				],
				'root' => [
					'asc' => [ 'root.name' => SORT_ASC ],
					'desc' => [ 'root.name' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Root'
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
	            'order' => [
	                'asc' => [ "$modelTable.`order`" => SORT_ASC ],
	                'desc' => [ "$modelTable.`order`" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Order'
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
		$type	= Yii::$app->request->getQueryParam( 'type' );
		$filter	= Yii::$app->request->getQueryParam( 'model' );

		// Filter - Type
		if( isset( $type ) && empty( $config[ 'conditions' ][ "$modelTable.type" ] ) ) {

			$config[ 'conditions' ][ "$modelTable.type" ] = $type;
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
				case 'popular': {

					$config[ 'conditions' ][ "$modelTable.popular" ] = true;

					break;
				}
				case 'top': {

					$config[ 'conditions' ][ "$modelTable.parentId" ] = null;

					break;
				}
			}
		}

		// Searching --------

		$searchCol		= Yii::$app->request->getQueryParam( $searchColParam );
		$keywordsCol	= Yii::$app->request->getQueryParam( $searchParam );

		$search = [
			'name' => "$modelTable.name",
			'title' => "$modelTable.title",
			'desc' => "$modelTable.description",
			'content' => "$modelTable.content"
		];

		if( isset( $searchCol ) ) {

			$config[ 'search-col' ] = $config[ 'search-col' ] ?? $search[ $searchCol ];
		}
		else if( isset( $keywordsCol ) ) {

			$config[ 'search-col' ] = $config[ 'search-col' ] ?? $search;
		}

		// Reporting --------

		$config[ 'report-col' ]	= $config[ 'report-col' ] ?? [
			'name' => "$modelTable.name",
			'title' => "$modelTable.title",
			'desc' => "$modelTable.description",
			'content' => "$modelTable.content",
			'pinned' => "$modelTable.pinned",
			'featured' => "$modelTable.featured",
			'popular' => "$modelTable.popular",
			'pname' => 'parent.name',
			'pdesc' => 'parent.description',
			'rname' => 'root.name',
			'rdesc' => 'root.description'
		];

		// Result -----------

		return parent::getPage( $config );
	}

	// Read ---------------

	// Read - Models ---

	public function getByParentId( $id, $config = [] ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByParentId( $id, $config );
	}

	public function getFeaturedByType( $type, $config = [] ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findFeaturedByType( $type, $config );
	}

	public function getL0ByType( $type, $config = [] ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findL0ByType( $type, $config );
	}

	// Read - Lists ----

	public function getL0IdNameListByType( $type, $config = [] ) {

		$config[ 'conditions' ][ 'parentId' ] = null;

		return $this->getIdNameListByType( $type, $config );
	}

	public function getL0IdNameListById( $id, $config = [] ) {

		$category = self::findById( $id );

		return $this->getSubLevelList( $category->id, $category->rootId, [ 'having' => 'depth = 1' ] );
	}

	public function getLevelListByType( $type ) {

		return $this->getLevelList( [ 'conditions' => [ 'node.type' => $type ], 'slug' => true ] );
	}

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function create( $model, $config = [] ) {

		if( empty( $model->siteId ) ) {

			$model->siteId = Yii::$app->core->site->id;
		}

		if( empty( $model->order ) ) {

			$model->order = 0;
		}

		return $this->createInHierarchy( $model );
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		$admin = isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : false;

		$attributes = isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [
			'name', 'slug', 'icon', 'texture', 'title',
			'description', 'htmlOptions', 'content'
		];

		// Update Hierarchy
		$this->updateInHierarchy( $model );

		if( $admin ) {

			$attributes	= ArrayHelper::merge( $attributes, [ 'order', 'pinned', 'featured' ] );
		}

		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

	// Delete -------------

	public function delete( $model, $config = [] ) {

		// Delete mapping
		Yii::$app->factory->get( 'modelCategoryService' )->deleteByModelId( $model->id );

		// Delete options
		Yii::$app->factory->get( 'optionService' )->deleteByCategoryId( $model->id );

		// Update Hierarchy
		$model = $this->deleteInHierarchy( $model );

		// Delete model
		return parent::delete( $model, $config );
	}

	// Bulk ---------------

	protected function applyBulk( $model, $column, $action, $target, $config = [] ) {

		switch( $column ) {

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
					case 'popular': {

						$model->popular = true;

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

	// CategoryService -----------------------

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
