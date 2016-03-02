<?php
namespace cmsgears\core\common\models\traits;

use cmsgears\core\common\models\entities\CoreTables;
use cmsgears\core\common\models\entities\Activity;
use cmsgears\core\common\models\entities\ModelActivity;

/**
 * ActivityTrait can be used to store model activities. These can be notifications, reminders and actions.
 */
trait ActivityTrait {

	/**
	 * @return array - ActivityTrait associated with parent
	 */
	public function getModelActivities() {

    	return $this->hasMany( ModelActivity::className(), [ 'parentId' => 'id' ] )
					->where( "parentType='$this->parentType'" );
	}

	/**
	 * @return array - ActivityTrait associated with parent
	 */
	public function getActivities( $options = [] ) {

    	$query = $this->hasMany( Activity::className(), [ 'id' => 'activityId' ] )
					->viaTable( CoreTables::TABLE_MODEL_ACTIVITY, [ 'activityId' => 'id' ], function( $query ) {

						$modelActivity	= CoreTables::TABLE_MODEL_ACTIVITY;
	
                      	$query->onCondition( [ "$modelActivity.parentType" => $this->parentType ] );
						$query->where( "$modelActivity.admin=0" );
					});

		if( isset( $options[ 'limit' ] ) ) {

			$query = $query->limit( $options[ 'limit' ] );
		}

		return $query; 
	}

	public function getAdminActivities( $options = [] ) {

    	$query = $this->hasMany( Activity::className(), [ 'id' => 'activityId' ] )
					->viaTable( CoreTables::TABLE_MODEL_ACTIVITY, [ 'activityId' => 'id' ], function( $query ) {

						$modelActivity	= CoreTables::TABLE_MODEL_ACTIVITY;
	
                      	$query->onCondition( [ "$modelActivity.parentType" => $this->parentType ] );
						$query->where( "$modelActivity.admin=1" );
					});

		if( isset( $options[ 'limit' ] ) ) {

			$query = $query->limit( $options[ 'limit' ] );
		}

		return $query; 
	}

	public function getActivityCsv( $limit = 0 ) {

    	$activities 			= $this->activities( [ 'limit' => $limit ] )->all();
		$activitiesCsv			= [];

		foreach ( $activities as $activity ) {

			$activitiesCsv[] = $activity;
		}

		return implode( ", ", $categoriesCsv );
	}
}

?>