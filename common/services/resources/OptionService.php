<?php
namespace cmsgears\core\common\services\resources;

// Yii Imports
use \Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\resources\Category;
use cmsgears\core\common\models\resources\Option;
use cmsgears\core\common\models\mappers\ModelOption;

use cmsgears\core\common\services\interfaces\resources\IOptionService;

/**
 * The class OptionService is base class to perform database activities for Option Entity.
 */
class OptionService extends \cmsgears\core\common\services\base\EntityService implements IOptionService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\core\common\models\resources\Option';

	public static $modelTable	= CoreTables::TABLE_OPTION;

	public static $parentType	= CoreGlobal::TYPE_OPTION;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// OptionService -------------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$sort = new Sort([
	        'attributes' => [
	            'name' => [
	                'asc' => [ 'name' => SORT_ASC ],
	                'desc' => ['name' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'name'
	            ],
	            'slug' => [
	                'asc' => [ 'slug' => SORT_ASC ],
	                'desc' => ['slug' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'slug'
	            ]
	        ]
	    ]);

		$config[ 'sort' ] = $sort;

		return parent::findPage( $config );
	}

	// Read ---------------

    // Read - Models ---

	public function getByCategoryId( $categoryId ) {

		return self::findByCategoryId( $categoryId );
	}

	public function getByCategoryName( $categoryName, $categoryType = CoreGlobal::TYPE_OPTION_GROUP ) {

		return self::findByCategoryName( $categoryName, $categoryType );
	}

	public function getByNameCategoryId( $name, $categoryId ) {

		return self::findByNameCategoryId( $name, $categoryId );
	}

	public function getByNameCategoryName( $name, $categoryName, $categoryType = CoreGlobal::TYPE_OPTION_GROUP ) {

		return self::findByNameCategoryName( $name, $categoryName, $categoryType );
	}

	public function getByValueCategoryName( $value, $categoryName, $categoryType = CoreGlobal::TYPE_OPTION_GROUP ) {

		return self::findByValueCategoryName( $value, $categoryName, $categoryType );
	}

    // Read - Lists ----

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

		$category	= Category::findBySlugType( $categorySlug, $type );

		$config[ 'conditions' ][ 'categoryId' ] = $category->id;

		return self::findIdNameMap( $config );
	}

	/**
	 * @param integer $categoryId - category id
	 * @return array - an array having value as key and name as value for given category id.
	 */
	public function getValueNameMapByCategoryId( $categoryId ) {

		$category	= Category::findById( $categoryId );
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

		$category	= Category::findByNameType( $categoryName, $type );
		$options	= $category->options;
		$optionsMap	= array();

		foreach ( $options as $option ) {

			$optionsMap[ $option->value ] = $option->name;
		}

		return $optionsMap;
	}

	public function getValueNameMapByCategorySlug( $categorySlug, $type = CoreGlobal::TYPE_OPTION_GROUP ) {

        $category   = Category::findBySlugType( $categorySlug, $type );
        $options    = $category->options;
        $optionsMap = array();

        foreach ( $options as $option ) {

            $optionsMap[ $option->value ] = $option->name;
        }

        return $optionsMap;
    }

	// Read - Others ---

	// Create -------------

 	public function create( $model, $config = [] ) {

		$model->value	= $model->name;

		return parent::create( $model, $config );
 	}

	// Update -------------

	public function update( $model, $config = [] ) {

		return parent::update( $model, [
			'attributes' => [ 'categoryId', 'name', 'value', 'icon', 'htmlOptions' ]
		]);
 	}

	// Delete -------------

	public function delete( $model, $config = [] ) {

		// Delete mapping
		ModelOption::deleteByModelId( $model->id );

		// Delete model
		return parent::delete( $model, $config );
	}

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// OptionService -------------------------

	// Data Provider ------

	// Read ---------------

    // Read - Models ---

	public static function findByCategoryId( $categoryId ) {

		return Option::findByCategoryId( $categoryId );
	}

	public static function findByCategoryName( $categoryName, $categoryType = CoreGlobal::TYPE_OPTION_GROUP ) {

		return Option::findByCategoryName( $categoryName, $categoryType );
	}

	public static function findByNameCategoryId( $name, $categoryId ) {

		return Option::findByNameCategoryId( $name, $categoryId );
	}

	public static function findByNameCategoryName( $name, $categoryName, $categoryType = CoreGlobal::TYPE_OPTION_GROUP ) {

		return Option::findByNameCategoryName( $name, $categoryName, $categoryType );
	}

	public static function findByValueCategoryName( $value, $categoryName, $categoryType = CoreGlobal::TYPE_OPTION_GROUP ) {

		return Option::findByValueCategoryName( $value, $categoryName, $categoryType );
	}

    // Read - Lists ----

    // Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------
}
