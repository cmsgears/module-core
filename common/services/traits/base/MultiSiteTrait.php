<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\traits\base;

// Yii Imports
use Yii;
use yii\db\Query;

/**
 * MultiSiteTrait provide methods specific to multi-site models.
 *
 * @since 1.0.0
 */
trait MultiSiteTrait {

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// MultiSiteTrait ------------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	/**
	 * Returns model count of active models.
	 *
	 * @param type $config
	 * @return type
	 */
	public function getSiteStats( $config = [] ) {

		$status		= isset( $config[ 'status' ] ) ? $config[ 'status' ] : null;
		$type		= isset( $config[ 'type' ] ) ? $config[ 'type' ] : null;
		$limit		= isset( $config[ 'limit' ] ) ? $config[ 'limit' ] : 0;
		$conditions	= isset( $config[ 'conditions' ] ) ? $config[ 'conditions' ] : [];

		$modelClass	= static::$modelClass;
        $modelTable	= $modelClass::tableName();
		$siteTable	= Yii::$app->factory->get( 'siteService' )->getModelTable();

        $query = new Query();

        $query->select( [ "$modelTable.siteId", "$siteTable.name", "$siteTable.title", "count($modelTable.id) as total" ] )
			->from( $modelTable )
			->leftJoin( $siteTable, "$siteTable.id=$modelTable.siteId" );

		// Filter Status
		if( isset( $status ) ) {

			$query->where( "$modelTable.status=:status", [ ':status' => $status ] );
		}

		// Filter Type
		if( isset( $type ) ) {

			$query->andWhere( "$modelTable.type=:type", [ ':type' => $type ] );
		}

		// Conditions ----------

		if( isset( $conditions ) ) {

			foreach ( $conditions as $ckey => $condition ) {

				if( is_numeric( $ckey ) ) {

					$query->andWhere( $condition );

					unset( $conditions[ $ckey ] );
				}
			}

			$query->andWhere( $conditions );
		}

		// Limit
		if( $limit > 0 ) {

			$query->limit( $limit );
		}

		// Group and Order
		$query->groupBy( 'siteId' )->orderBy( 'total DESC' );

        return $query->all();
    }

	// Create -------------

	// Update -------------

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// MultiSiteTrait ------------------------

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
