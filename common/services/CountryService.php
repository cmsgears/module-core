<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\models\entities\CoreTables; 
use cmsgears\core\common\models\entities\Country;
use cmsgears\core\common\services\Service;


/**
 * The class OptionService is base class to perform database activities for Option Entity.
 */
class CountryService extends Service {
	
	// Static Methods ---------------------------------------------- 
 
	// Read ----------------

	/**
	 * @param integer $id
	 * @return Country
	 */
	public static function findById( $id ) {

		return Country::findById( $id );
	}
	 
	// Data Provider ----

	/**
	 * @param array $config to generate query
	 * @return ActiveDataProvider
	 */ 
	 
	public static function getPagination( $config = [] ) {
	
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
	
		if( !isset( $config[ 'sort' ] ) ) {
	
			$config[ 'sort' ] = $sort;
		}
	
		if( !isset( $config[ 'search-col' ] ) ) {
	
			$config[ 'search-col' ] = 'name';
		}
	
		return self::getDataProvider( new Country(), $config );
	}
	
	// Create ----
	
	public static function create( $model ) {
		
		$model->save();
		
		return $model;
	}
	
	// Update ----
	
	public static function update( $model ) {
		
		$modelToUpdate	= self::findById( $model->id );

		// Copy Attributes
		$modelToUpdate->copyForUpdateFrom( $model, [ 'code', 'name' ] );

		// Update Country
		$modelToUpdate->update();

		// Return updated country
		return $modelToUpdate;
	}
	
	// Delete ----
	
	public static function delete( $model ) {
		
		$model->delete(); 
	}
}

?>