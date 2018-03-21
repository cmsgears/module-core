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

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\resources\Category;
use cmsgears\core\common\models\resources\Option;
use cmsgears\core\common\models\mappers\ModelCategory;

use cmsgears\core\common\services\interfaces\resources\ICategoryService;

use cmsgears\core\common\services\base\ResourceService;

use cmsgears\core\common\services\traits\base\NameTypeTrait;
use cmsgears\core\common\services\traits\base\SlugTypeTrait;
use cmsgears\core\common\services\traits\resources\HierarchyTrait;
use cmsgears\core\common\services\traits\resources\NestedSetTrait;
use cmsgears\core\common\services\traits\resources\DataTrait;

/**
 * CategoryService provide service methods of category model.
 *
 * @since 1.0.0
 */
class CategoryService extends ResourceService implements ICategoryService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\core\common\models\resources\Category';

	public static $modelTable	= CoreTables::TABLE_CATEGORY;

	public static $typed		= true;

	public static $parentType	= CoreGlobal::TYPE_CATEGORY;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	use DataTrait;
	use HierarchyTrait;
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

		$modelTable = static::$modelTable;
		$modelClass	= static::$modelClass;

		// Sorting ----------

		$sort = new Sort([
			'attributes' => [
				'id' => [
					'asc' => [ 'id' => SORT_ASC ],
					'desc' => [ 'id' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Id'
				],
				'parent' => [
					'asc' => [ 'parent.name' => SORT_ASC ],
					'desc' => [ 'parent.name' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Parent',
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
	            'featured' => [
	                'asc' => [ "$modelTable.featured" => SORT_ASC ],
	                'desc' => [ "$modelTable.featured" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Featured'
	            ],
	            'order' => [
	                'asc' => [ "$modelTable.`order`" => SORT_ASC ],
	                'desc' => [ "$modelTable.`order`" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Order'
	            ]
			],
			'defaultOrder' => [
				'id' => SORT_DESC
			]
		]);

		$config[ 'sort' ] 	= $sort;

		// Query ------------

		$query 	= $modelClass::find()->joinWith( 'parent' );

		// Filters ----------

		// Filter - Status
		$status	= Yii::$app->request->getQueryParam( 'status' );

		if( isset( $status ) ) {

			switch( $status ) {

				case 'featured': {

					$config[ 'conditions' ][ "$modelTable.featured" ]	= true;

					break;
				}
			}
		}

		// Filter - Level
		$parent	= Yii::$app->request->getQueryParam( 'parent' );

		if( isset( $parent ) ) {

			if( $parent === 'top' ) {

				$query->andWhere( "$modelTable.parentId IS NULL" );
			}
		}

		// Searching --------

		$searchCol	= Yii::$app->request->getQueryParam( 'search' );

		if( isset( $searchCol ) ) {

			$search = [ 'name' => "$modelTable.name", 'desc' => "$modelTable.description" ];

			$config[ 'search-col' ] = $search[ $searchCol ];
		}

		// Reporting --------

		$config[ 'report-col' ]	= [
			'name' => "$modelTable.name", 'slug' => "$modelTable.slug", 'desc' => "$modelTable.description",
			'pname' => 'parent.name', 'pdesc' => 'parent.description'
		];

		// Result -----------

		$config[ 'query' ]	= $query;

		return parent::getPage( $config );
	}

	// Read ---------------

	// Read - Models ---

	public function getByParentId( $id ) {

		return Category::findByParentId( $id );
	}

	public function getFeaturedByType( $type ) {

		return Category::getFeaturedByType( $type );
	}

	public function getL0ByType( $type ) {

		$modelClass	= static::$modelClass;

		return $modelClass::find()->where( [ 'type' => $type, 'lValue' => 1 ] )->all();
	}

	// Read - Lists ----

	public function getTopLevelIdNameListByType( $type, $config = [] ) {

		$config[ 'conditions' ][ 'parentId' ] = null;

		return $this->getIdNameListByType( $type, $config );
	}

	public function getTopLevelIdNameListById( $id, $config = [] ) {

		$category	= self::findById( $id );

		return $this->getSubLevelList( $category->id, $category->rootId, [ 'having' => 'depth = 1' ] );
	}

	public function getLevelListByType( $type ) {

		return $this->getLevelList( [ 'conditions' => [ 'node.type' => $type ] ] );
	}

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function create( $model, $config = [] ) {

		$model	= $this->createInHierarchy( $model );

		return $model;
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		$attributes = isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [ 'name', 'description', 'type', 'icon', 'featured', 'htmlOptions' ];

		// Find existing model
		$modelToUpdate	= $this->getById( $model->id );

		// Update Hierarchy
		$modelToUpdate	= $this->updateInHierarchy( $model, $modelToUpdate );

		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

	public function markFeatured( $model ) {

		$model->featured = true;

		return parent::update( $model, [
			'attributes' => [ 'featured' ]
		]);
	}

	public function markRegular( $model ) {

		$model->featured = false;

		return parent::update( $model, [
			'attributes' => [ 'featured' ]
		]);
	}

	protected function applyBulk( $model, $column, $action, $target, $config = [] ) {

		switch( $column ) {

			case 'status': {

				switch( $action ) {

					case 'featured': {

						$this->markFeatured( $model );

						break;
					}
					case 'regular': {

						$this->markRegular( $model );

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

		// Delete mapping
		ModelCategory::deleteByModelId( $model->id );

		// Delete options and mappings - mappings will be deleted by cascade effect
		Option::deleteByCategoryId( $model->id );

		// Update Hierarchy
		$model = $this->deleteInHierarchy( $model );

		// Delete model
		return parent::delete( $model, $config );
	}

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// CategoryService -----------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public static function findByParentId( $id ) {

		return Category::findByParentId( $id );
	}

	public static function findFeaturedByType( $type ) {

		return Category::getFeaturedByType( $type );
	}

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

}
