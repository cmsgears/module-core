<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\models\entities\CoreTables;
 
use cmsgears\core\common\models\entities\Country;

/**
 * The class CountryService is base class to perform database activities for Country Entity.
 */
class CountryService extends \cmsgears\core\common\services\Service {
	
	// Static Methods ---------------------------------------------- 
 
	// Read ----------------

	/**
	 * @param integer $id
	 * @return Country
	 */
	public static function findById( $id ) {

		return Country::findById( $id );
	}
	
	public static function getIdNameList() {
		
		return self::findMap( 'id', 'name', CoreTables::TABLE_COUNTRY );
	}
	 
	// Data Provider ----

	/**
	 * @param array $config to generate query
	 * @return ActiveDataProvider
	 */
	public static function getPagination( $config = [] ) {

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