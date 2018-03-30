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
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\services\interfaces\resources\IOptionService;

use cmsgears\core\common\services\base\ResourceService;

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

		$sort = new Sort([
			'attributes' => [
				'id' => [
					'asc' => [ "$modelTable.id" => SORT_ASC ],
					'desc' => [ "$modelTable.id" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Id'
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
				]
			]
		]);

		$config[ 'sort' ] = $sort;

		return parent::findPage( $config );
	}

	// Read ---------------

	// Read - Models ---

	public function getByCategoryId( $categoryId ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByCategoryId( $categoryId );
	}

	public function getByCategorySlug( $categorySlug, $categoryType = CoreGlobal::TYPE_OPTION_GROUP ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByCategorySlugType( $categorySlug, $categoryType );
	}

	public function getByNameCategoryId( $name, $categoryId ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByNameCategoryId( $name, $categoryId );
	}

	public function getByNameCategoryName( $name, $categoryName, $categoryType = CoreGlobal::TYPE_OPTION_GROUP ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByNameCategoryName( $name, $categoryName, $categoryType );
	}

	public function getByValueCategoryName( $value, $categoryName, $categoryType = CoreGlobal::TYPE_OPTION_GROUP ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByValueCategoryName( $value, $categoryName, $categoryType );
	}

	// Read - Lists ----

	public function getIdListByCategoryId( $categoryId, $config = [] ) {

		$config[ 'conditions' ][ 'categoryId' ] = $categoryId;

		return self::findIdList( $config );
	}

	// Read - Maps -----

	/**
	 * @param integer $categoryId - category id
	 * @return array - an array having id as key and name as value for given category id.
	 */
	public function getIdNameMapByCategoryId( $categoryId, $config = [] ) {

		$config[ 'conditions' ][ 'categoryId' ] = $categoryId;

		return self::findIdNameMap( $config );
	}

	/**
	 * @param integer $categoryName - category name
	 * @return array - an array having id as key and name as value for given category name.
	 */
	public function getIdNameMapByCategorySlug( $categorySlug, $config = [], $type = CoreGlobal::TYPE_OPTION_GROUP ) {

		$category	= Yii::$app->get( 'categoryService' )->getBySlugType( $categorySlug, $type );

		$config[ 'conditions' ][ 'categoryId' ] = $category->id;

		return self::findIdNameMap( $config );
	}

	/**
	 * @param integer $categoryId - category id
	 * @return array - an array having value as key and name as value for given category id.
	 */
	public function getValueNameMapByCategoryId( $categoryId ) {

		$category	= Yii::$app->get( 'categoryService' )->getById( $categoryId );
		$options	= $category->options;
		$optionsMap	= array();

		foreach ( $options as $option ) {

			$optionsMap[ $option->value ] = $option->name;
		}

		return $optionsMap;
	}

	/**
	 * @param integer $categoryName - category name
	 * @return array - an array having value as key and name as value for given category name.
	 */
	public function getValueNameMapByCategoryName( $categoryName, $type = CoreGlobal::TYPE_OPTION_GROUP ) {

		$category	= Yii::$app->get( 'categoryService' )->getByNameType( $categoryName, $type );
		$options	= $category->options;
		$optionsMap	= array();

		foreach( $options as $option ) {

			$optionsMap[ $option->value ] = $option->name;
		}

		return $optionsMap;
	}

	public function getValueNameMapByCategorySlug( $categorySlug, $type = CoreGlobal::TYPE_OPTION_GROUP ) {

		$category	= Yii::$app->get( 'categoryService' )->getBySlugType( $categorySlug, $type );
		$options	= $category->options;
		$optionsMap = array();

		foreach( $options as $option ) {

			$optionsMap[ $option->value ] = $option->name;
		}

		return $optionsMap;
	}

	// Read - Others ---

	// Create -------------

	public function create( $model, $config = [] ) {

		$model->value = $model->name;

		return parent::create( $model, $config );
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		$attributes = isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [ 'categoryId', 'name', 'value', 'icon', 'htmlOptions' ];

		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

	// Delete -------------

	public function delete( $model, $config = [] ) {

		// Delete mapping
		Yii::$app->get( 'modelOptionService' )->deleteByModelId( $model->id );

		// Delete model
		return parent::delete( $model, $config );
	}

	// Bulk ---------------

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
