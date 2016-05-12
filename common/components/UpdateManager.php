<?php
namespace cmsgears\core\common\components;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\notify\common\models\mappers\ModelNotification;

use cmsgears\notify\common\services\mappers\ModelNotificationService;

class UpdateManager extends \yii\base\Component {

	public $admin;

	public $notifications;
	public $notificationCount	= 0;

	public $reminders;
	public $reminderCount		= 0;

	public $activities;
	public $activityCount		= 0;

	/**
	 * Initialise the Counter Component.
	 */
    public function init() {

        parent::init();

		$this->initNotifications();
    }

	public function initNotifications() {

		if( $this->admin ) {

			$counts					= ModelNotificationService::getStatusCounts( true );

			if( $counts[ 0 ] > 0 ) {

				$this->notificationCount 	= $counts[ ModelNotification::STATUS_NEW ];
			}

			$this->notifications	= ModelNotificationService::getRecent( 5, true );
		}
	}

	public function getCounts() {

		$counts		= [];

		$counts[ 'notificationCount' ]	= $this->notificationCount;
		$counts[ 'reminderCount' ]		= $this->reminderCount;
		$counts[ 'activityCount' ]		= $this->activityCount;

		return $counts;
	}
}

?>