<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
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
	public function getAdminStats( $type = null ) {

		return [
			// Messages
			'notifications' => [], 'reminders' => [], 'activities' => [], 'announcements' => [],
			// Counters - New
			'notificationCount' => 0, 'reminderCount' => 0, 'activityCount' => 0, 'announcementCount' => 0
		];
	}

	/**
	 * It query appropriate services and collection messages and counts of notifications,
	 * reminders and activities for frontend site or application.
	 *
	 * @return array having recent notifications, reminders, activities and counts.
	 */
	public function getUserStats( $type = null ) {

		return [
			// Messages
			'notifications' => [], 'reminders' => [], 'activities' => [], 'announcements' => [],
			// Counters - New
			'notificationCount' => 0, 'reminderCount' => 0, 'activityCount' => 0, 'announcementCount' => 0
		];
	}

	/**
	 * It query appropriate services and collection messages and counts of notifications,
	 * reminders and activities for frontend site or application.
	 *
	 * @return array having recent notifications, reminders, activities and counts.
	 */
	public function getModelStats( $parentId, $parentType, $type = null ) {

		return [
			// Messages
			'notifications' => [], 'reminders' => [], 'activities' => [], 'announcements' => [],
			// Counters - New
			'notificationCount' => 0, 'reminderCount' => 0, 'activityCount' => 0, 'announcementCount' => 0
		];
	}

	public function getMostRecentAnnouncement() {

		return null;
	}

	// Notification Trigger ---

	public function triggerNotification( $slug, $data, $config = [] ) {

		// Trigger notifications using given template, message and config
	}

	public function deleteNotificationsByUserId( $userId, $config = [] ) {

		// Delete notifications triggered for a user
	}

	public function deleteNotificationsByParent( $parentId, $parentType, $config = [] ) {

		// Delete notifications triggered for a model
	}

	// Reminder Trigger -------

	public function triggerReminder( $slug, $data, $config = [] ) {

		// Trigger notifications using given template, message and config
	}

	// Activity Logger --------

	/**
	 * Trigger activity log on model creation.
	 *
	 * @param type $model
	 */
	public function logCreate( $model, $service, $config = [] ) {

		// Trigger create activity
	}

	/**
	 * Trigger activity log on model update.
	 *
	 * @param type $model
	 */
	public function logUpdate( $model, $service, $config = [] ) {

		// Trigger update activity
	}

	/**
	 * Trigger activity log on model delete.
	 *
	 * @param type $model
	 */
	public function logDelete( $model, $service, $config = [] ) {

		// Trigger delete activity
	}

	public function triggerActivity( $slug, $data, $config = [] ) {

		// Trigger notifications using given template, message and config
	}

}
