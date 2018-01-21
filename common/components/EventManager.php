<?php
/**
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 * @license https://www.cmsgears.org/license/
 * @package module
 * @subpackage core
 */
namespace cmsgears\core\common\components;

/**
 * The Event Manager component provides messages and counts for notifications, reminders
 * and activities.
 *
 * It can also be used to trigger notifications and reminders(for active events marked
 * on calendar) and log activities.
 *
 * The actual implementation of event manager is not done in core module and appropriate
 * plugin or module must extend this component in order to use notifications, reminders
 * and activities.
 *
 * @author Bhagwat Singh Chouhan <bhagwat.chouhan@gmail.com>
 * @since 1.0.0
 */
class EventManager extends \cmsgears\core\common\base\Component {

	// TODO: Add mechanism to cache stats results for specified duration

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// EventManager --------------------------

	// Stats Collection -------

	/**
	 * It query appropriate services and collection messages and counts of notifications,
	 * reminders and activities for admin site.
	 *
	 * @return array having recent notifications, reminders, activities and counts.
	 */
	public function getAdminStats() {

		return [
					// Messages
					'notifications' => [], 'reminders' => [], 'activities' => [],
					// Counters - New
					'notificationCount' => 0, 'reminderCount' => 0, 'activityCount' => 0
				];
	}

	/**
	 * It query appropriate services and collection messages and counts of notifications,
	 * reminders and activities for frontend site or application.
	 *
	 * @return array having recent notifications, reminders, activities and counts.
	 */
	public function getUserStats() {

		return [
					// Messages
					'notifications' => [], 'reminders' => [], 'activities' => [],
					// Counters - New
					'notificationCount' => 0, 'reminderCount' => 0, 'activityCount' => 0
				];
	}

	// Notification Trigger ---

	public function triggerNotification( $template, $message, $config = [] ) {

		// Trigger notifications using given template, message and config
	}

	// Reminder Trigger -------

	public function triggerReminder( $template, $message, $config = [] ) {

		// Trigger notifications using given template, message and config
	}

	// Activity Logger --------

	public function logActivity( $template, $message, $config = [] ) {

		// Trigger notifications using given template, message and config
	}
}
