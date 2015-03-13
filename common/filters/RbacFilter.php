<?php
namespace cmsgears\core\common\filters;

// Yii Imports
use \Yii;
use yii\web\Controller;
use yii\base\Behavior;
use yii\web\ForbiddenHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\MessageUtil;

/**
 * The class RbacFilter use the roles and permissions defined for the project using the database tables.
 * It identify whether a user is assigned a permission. It trigger ForbiddenException in case a user does not have
 * required permission and try to execute the controller action by indirect means. It only works when 
 * useRbac is set for the cmgCore Component within the application config file and action is configured within the 
 * controller behaviours.
 */
class RbacFilter extends Behavior {

	//TODO Add code for caching the roles and permissions

	/**
	 * @var maps the action to permission.
	 */
	public $permissions = [];

    public function events() {

        return [ Controller::EVENT_BEFORE_ACTION => 'beforeAction' ];
    }

    public function beforeAction( $event ) {

		if( Yii::$app->cmgCore->isRbac() ) {

	        $action = $event->action->id;

			// Redirect to login page if guest
			if( Yii::$app->user->isGuest ) {

				Yii::$app->controller->redirect( Yii::$app->urlManager->createUrl( Yii::$app->cmgCore->getLogoutRedirectPage() ) );
			}

			// Check whether action is permitted
	        if ( array_key_exists( $action, $this->permissions ) ) {
	
				$user		= Yii::$app->user->getIdentity();
				$permission = $this->permissions[ $action ];	
	
				if( !$user->isPermitted( $permission ) ) {
	
					throw new ForbiddenHttpException( MessageUtil::getMessage( CoreGlobal::ERROR_NOT_ALLOWED ) );
				}
	        }
		}

        return $event->isValid;
    }
}

?>