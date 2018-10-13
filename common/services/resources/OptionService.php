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

use cmsgears\core\common\services\interfaces\resources\IOptionService;

use cmsgears\core\common\services\base\ResourceService;

use cmsgears\core\common\services\traits\base\NameTrait;
use cmsgears\core\common\services\traits\resources\DataTrait;

/**
 * OptionService provide service methods of option model.
 *
 * @since 1.0.0
 */
class OptionService extends ResourceService implements IOptionService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\core\common\models\resources\Option';

	public static $parentType	= CoreGlobal::TYPE_OPTION;

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
	            ]
			]
		]);

		if( !isset( $config[ 'sort' ] ) ) {

			$config[ 'sort' ] = $sort;
		}

		// Query ------------

		// Filters ----------

		// Searching --------

		$searchCol = Yii::$app->request->getQueryParam( 'search' );

		if( isset( $searchCol ) ) {

			$search = [
				'name' => "$modelTable.name",
				'value' => "$modelTable.value"
			];

			$config[ 'search-col' ] = $search[ $searchCol ];
		}

		// Reporting --------

		$config[ 'report-col' ]	= [
			'name' => "$modelTable.name",
			'value' => "$modelTable.value"
		];

		// Result -----------

		return parent::getPage( $config );
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

		$config[ 'conditions' ][ 'categoryId' ] = $categoryId;

		return $this->getIdNameMap( $config );
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

	public function getIdNameMapByCategorySlug( $slug, $config = [] ) {

		$type = isset( $config[ 'type' ] ) ? $config[ 'type' ] : CoreGlobal::TYPE_OPTION_GROUP;

		$category = Yii::$app->factory->get( 'categoryService' )->getBySlugType( $slug, $type, $config );

		return $this->getIdNameMapByCategoryId( $category->id, $config );
	}

	public function getValueNameMapByCategorySlug( $slug, $config = [] ) {

		$type = isset( $config[ 'type' ] ) ? $config[ 'type' ] : CoreGlobal::TYPE_OPTION_GROUP;

		$category = Yii::$app->factory->get( 'categoryService' )->getBySlugType( $slug, $type, $config );

		return $this->getValueNameMapByCategoryId( $category->id, $config );
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

		$attributes = isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [ 'name', 'value', 'icon', 'input', 'htmlOptions' ];

		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

	// Delete -------------

	public function delete( $model, $config = [] ) {

		// Delete mapping
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
