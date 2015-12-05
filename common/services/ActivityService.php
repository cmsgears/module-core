<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\Activity;

class ActivityService extends \cmsgears\core\common\services\Service {

	// Static Methods ----------------------------------------------

	// Read ----------------

	/**
	 * @param integer $id
	 * @return Activity
	 */
	public static function findById( $id ) {

		return Activity::findById( $id );
	}

	// Data Provider ----

	/**
	 * @param array $config to generate query
	 * @return ActiveDataProvider
	 */
	public static function getPagination( $config = [] ) {

		return self::getDataProvider( new Activity(), $config );
	}

	// Create -----------

	public static function create( $activity ) {

		if( isset( $activity->templateId ) && $activity->templateId <= 0 ) {

			unset( $activity->templateId );
		}

		// Create Activity
		$activity->save();

		return $activity;
	}

	// Update -----------

	public static function update( $activity ) {

		$activityToUpdate	= self::findById( $activity->id );

		$activityToUpdate->copyForUpdateFrom( $activity, [ 'templateId', 'type', 'consumed', 'data' ] );

		$activityToUpdate->update();

		return $activityToUpdate;
	}

	// Delete -----------

	public static function delete( $activity ) {

		$existingActivity	= self::findById( $activity->id );

		// Delete Activity
		$existingActivity->delete();

		return true;
	}
}

?>