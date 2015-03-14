<?php
namespace cmsgears\core\admin\services;

// Yii Imports
use \Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\models\entities\Category;

class CategoryService extends \cmsgears\core\common\services\CategoryService {

	// Static Methods ----------------------------------------------

	// Pagination -------

	public static function getPagination( $type = null ) {

	    $sort = new Sort([
	        'attributes' => [
	            'name' => [
	                'asc' => [ 'category_name' => SORT_ASC ],
	                'desc' => ['category_name' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'name',
	            ]
	        ]
	    ]);

		if( isset( $type ) ) {

			return self::getPaginationDetails( new Category(), [ 'sort' => $sort, 'conditions' => [ "category_type" => $type ], 'search-col' => 'category_name' ] );
		}
		else {

			return self::getPaginationDetails( new Category(), [ 'sort' => $sort, 'search-col' => 'category_name' ] );
		}
	}

	public static function getPaginationByType( $type ) {

		return self::getPagination( $type );
	}

	// Create -----------

	public static function create( $category ) {

		$category->save();

		return true;
	}

	// Update -----------

	public static function update( $category ) {

		$categoryToUpdate	= self::findById( $category->getId() );

		$categoryToUpdate->setName( $category->getName() );
		$categoryToUpdate->setDesc( $category->getDesc() );
		$categoryToUpdate->setType( $category->getType() );

		$categoryToUpdate->update();

		return true;
	}

	// Delete -----------

	public static function delete( $category ) {

		$categoryId			= $category->getId();
		$categoryType		= $category->getType();
		$existingCategory	= self::findById( $categoryId );

		// Delete Category
		$existingCategory->delete();

		return true;
	}
}

?>