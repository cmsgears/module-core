<?php
namespace cmsgears\core\common\services\entities;

use yii;
use yii\data\Sort;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\entities\Province;

class ProvinceService extends \cmsgears\core\common\services\base\Service {

	// Static Methods ----------------------------------------------

	// Create --------------

	public static function create( $model ) {

		$model->save();

		return $model;
	}

	// Read ----------------

	public static function findById( $id ) {

		return Province::findById( $id );
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

		return self::getDataProvider( new Province(), $config );
	}

	public static function getListByCountryId( $countryId ) {

		return self::findIdNameList( 'id', 'name', CoreTables::TABLE_PROVINCE, [ 'conditions' => [ 'countryId' => $countryId ] ] );
	}

	public static function getMapByCountryId( $countryId ) {

		return self::findMap( 'id', 'name', CoreTables::TABLE_PROVINCE, [ 'conditions' => [ 'countryId' => $countryId ] ] );
	}

	// Update ---------

	public static function update( $model ) {

		$modelToUpdate	= self::findById( $model->id );

		// Copy Attributes
		$modelToUpdate->copyForUpdateFrom( $model, [ 'countryId', 'code', 'name' ] );

		// Update Province
		$modelToUpdate->update();

		// Return updated province
		return $modelToUpdate;
	}

	// Delete ---------

	public static function delete( $model ) {

		$model->delete();
	}
}

?>