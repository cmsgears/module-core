<?php
namespace cmsgears\core\common\components;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * The Event Manager component in CMSGears must be capable of providing messages and counts for notifications, reminders and activities.
 *
 * It must also be capable to trigger notifications and reminders(for active events marked on calendar)
 *
 * The actual implementation of event manager is not done in core module and appropriate plugin or module must extend this component in order to use notifications, reminders and activities.
 */
class EventManager extends \yii\base\Component {

    // TODO: Add mechanism to cache stats results for specified duration

    // Variables ---------------------------------------------------

    // Global -----------------

    // Public -----------------

    // Protected --------------

    // Private ----------------

    // Constructor and Initialisation ------------------------------

    // Instance methods --------------------------------------------

    // Yii parent classes --------------------

    // CMG parent classes --------------------

    // EventManager --------------------------

    // Stats Collection -------

    public function getAdminStats() {

        return [
                    // Messages
                    'notifications' => [], 'reminders' => [], 'activities' => [],
                    // Counters - New
                    'notificationCount' => 0, 'reminderCount' => 0, 'activityCount' => 0
                ];
    }

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
