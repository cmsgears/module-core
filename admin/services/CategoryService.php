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

	public static function getPagination( $conditions = [] ) {

	    $sort = new Sort([
	        'attributes' => [
	            'name' => [
	                'asc' => [ 'name' => SORT_ASC ],
	                'desc' => ['name' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'name',
	            ]
	        ]
	    ]);

		return self::getPaginationDetails( new Category(), [ 'sort' => $sort, 'conditions' => $conditions, 'search-col' => 'name' ] );
	}

	public static function getPaginationByType( $type ) {

		return self::getPagination( [ "type" => $type ] );
	}

	// Create -----------

	public static function create( $category ) {
		
		// Create Category
		$category->save();
		
		// Return Category
		return $category;
	}

	// Update -----------

	public static function update( $category ) {
		
		// Find existing Category
		$categoryToUpdate	= self::findById( $category->id );
		
		// Copy Attributes
		$categoryToUpdate->copyForUpdateFrom( $category, [ 'name', 'description', 'type' ] );
		
		// Update Category
		$categoryToUpdate->update();
		
		// Return updated Category
		return $categoryToUpdate;
	}

	// Delete -----------

	public static function delete( $category ) {

		// Find existing Category
		$categoryToDelete	= self::findById( $category->id );

		// Delete Category
		$categoryToDelete->delete();

		return true;
	}
}

?>