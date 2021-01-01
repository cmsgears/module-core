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

use cmsgears\core\common\services\interfaces\resources\IOptionService;

use cmsgears\core\common\services\traits\base\NameTrait;
use cmsgears\core\common\services\traits\resources\DataTrait;

/**
 * OptionService provide service methods of option model.
 *
 * @since 1.0.0
 */
class OptionService extends \cmsgears\core\common\services\base\ResourceService implements IOptionService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass = '\cmsgears\core\common\models\resources\Option';

	public static $parentType = CoreGlobal::TYPE_OPTION;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	use DataTrait;
	use NameTrait;

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// OptionService -------------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$searchParam	= $config[ 'search-param' ] ?? 'keywords';
		$searchColParam	= $config[ 'search-col-param' ] ?? 'search';

		$defaultSort = isset( $config[ 'defaultSort' ] ) ? $config[ 'defaultSort' ] : [ 'id' => SORT_DESC ];

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$categoryTable = Yii::$app->factory->get( 'categoryService' )->getModelTable();

		$sort = new Sort([
			'attributes' => [
				'id' => [
					'asc' => [ "$modelTable.id" => SORT_ASC ],
					'desc' => [ "$modelTable.id" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Id'
				],
				'category' => [
					'asc' => [ "$categoryTable.name" => SORT_ASC ],
					'desc' => [ "$categoryTable.name" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Category'
				],
				'name' => [
					'asc' => [ "$modelTable.name" => SORT_ASC ],
					'desc' => [ "$modelTable.name" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Name'
				],
	            'icon' => [
	                'asc' => [ "$modelTable.icon" => SORT_ASC ],
	                'desc' => [ "$modelTable.icon" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Icon'
	            ],
	            'active' => [
	                'asc' => [ "$modelTable.active" => SORT_ASC ],
	                'desc' => [ "$modelTable.active" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Active'
	            ],
				'order' => [
					'asc' => [ "$modelTable.order" => SORT_ASC ],
					'desc' => [ "$modelTable.order" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Order'
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
		$filter	= Yii::$app->request->getQueryParam( 'model' );

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
			'name' => "$modelTable.name",
			'value' => "$modelTable.value"
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
			'value' => "$modelTable.value",
			'active' => "$modelTable.active",
			'order' => "$modelTable.order"
		];

		// Result -----------

		return parent::getPage( $config );
	}

	public function getPageByCategoryId( $categoryId, $config = [] ) {

		$config[ 'conditions' ][ 'categoryId' ] = $categoryId;

		return $this->getPage( $config );
	}

	// Read ---------------

	// Read - Models ---

	public function getByCategoryId( $categoryId ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByCategoryId( $categoryId );
	}

	public function getByNameCategoryId( $name, $categoryId ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByNameCategoryId( $name, $categoryId );
	}

	public function isExistByNameCategoryId( $name, $categoryId ) {

		$modelClass	= static::$modelClass;

		return $modelClass::isExistByNameCategoryId( $name, $categoryId );
	}

	// Read - Lists ----

	public function getIdListByCategoryId( $categoryId, $config = [] ) {

		$config[ 'conditions' ][ 'categoryId' ] = $categoryId;

		return $this->getIdList( $config );
	}

	public function searchByNameCategoryId( $name, $categoryId, $config = [] ) {

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$config[ 'conditions' ][ "$modelTable.categoryId" ] = $categoryId;

		return $this->searchByName( $name, $config );
	}

	// Read - Maps -----

	/**
	 * @param integer $categoryId - category id
	 * @return array - an array having id as key and name as value for given category id.
	 */
	public function getIdNameMapByCategoryId( $categoryId, $config = [] ) {

		$modelTable	= $this->getModelTable();

		$config[ 'order' ] = isset( $config[ 'order' ] ) ? $config[ 'order' ] : "$modelTable.order ASC, $modelTable.value ASC";

		$config[ 'conditions' ][ 'categoryId' ] = $categoryId;

		return $this->getIdNameMap( $config );
	}

	public function getActiveIdNameMapByCategoryId( $categoryId, $config = [] ) {

		$config[ 'conditions' ][ 'active' ] = true;

		return $this->getIdNameMapByCategoryId( $categoryId, $config );
	}

	/**
	 * @param integer $categoryId - category id
	 * @return array - an array having value as key and name as value for given category id.
	 */
	public function getValueNameMapByCategoryId( $categoryId, $config = [] ) {

		$modelTable	= $this->getModelTable();

		$config[ 'nameColumn' ]		= "$modelTable.value";
		$config[ 'valueColumn' ]	= "$modelTable.name";

		$config[ 'conditions' ][ 'categoryId' ] = $categoryId;

		return $this->getIdNameMap( $config );
	}

	public function getActiveValueNameMapByCategoryId( $categoryId, $config = [] ) {

		$config[ 'conditions' ][ 'active' ] = true;

		return $this->getValueNameMapByCategoryId( $categoryId, $config );
	}

	public function getIdNameMapByCategorySlug( $slug, $config = [] ) {

		$type = isset( $config[ 'type' ] ) ? $config[ 'type' ] : CoreGlobal::TYPE_OPTION_GROUP;

		$category = Yii::$app->factory->get( 'categoryService' )->getBySlugType( $slug, $type, $config );

		if( isset( $category ) ) {

			return $this->getIdNameMapByCategoryId( $category->id, $config );
		}

		return [];
	}

	public function getActiveIdNameMapByCategorySlug( $slug, $config = [] ) {

		$type = isset( $config[ 'type' ] ) ? $config[ 'type' ] : CoreGlobal::TYPE_OPTION_GROUP;

		$category = Yii::$app->factory->get( 'categoryService' )->getBySlugType( $slug, $type, $config );

		if( isset( $category ) ) {

			return $this->getActiveIdNameMapByCategoryId( $category->id, $config );
		}

		return [];
	}

	public function getValueNameMapByCategorySlug( $slug, $config = [] ) {

		$type = isset( $config[ 'type' ] ) ? $config[ 'type' ] : CoreGlobal::TYPE_OPTION_GROUP;

		$category = Yii::$app->factory->get( 'categoryService' )->getBySlugType( $slug, $type, $config );

		if( isset( $category ) ) {

			$config[ 'defaultValue' ] = $category->name;

			return $this->getValueNameMapByCategoryId( $category->id, $config );
		}

		return [];
	}

	public function getActiveValueNameMapByCategorySlug( $slug, $config = [] ) {

		$config[ 'conditions' ][ 'active' ] = true;

		return $this->getValueNameMapByCategorySlug( $slug, $config );
	}

	// Read - Others ---

	// Create -------------

	public function create( $model, $config = [] ) {

		if( empty( $model->value ) ) {

			$model->value = $model->name;
		}

		return parent::create( $model, $config );
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		$admin = isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : false;

		$attributes = isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [
			'name', 'value', 'icon', 'input', 'order', 'htmlOptions'
		];

		if( $admin ) {

			$attributes	= ArrayHelper::merge( $attributes, [
				'active'
			]);
		}

		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

	// Delete -------------

	public function delete( $model, $config = [] ) {

		// Delete mappings
		Yii::$app->factory->get( 'modelOptionService' )->deleteByModelId( $model->id );

		// Delete model
		return parent::delete( $model, $config );
	}

	public function deleteByCategoryId( $categoryId ) {

		$options = $this->getByCategoryId( $categoryId );

		foreach( $options as $option ) {

			$this->delete( $option );
		}
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

	// OptionService -------------------------

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
