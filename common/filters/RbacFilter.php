<?php
namespace cmsgears\core\common\filters;

// Yii Imports
use \Yii;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * The class RbacFilter use the roles and permissions defined for the project using the database tables.
 * It identify whether a user is assigned a permission. It trigger ForbiddenException in case a user does not have
 * required permission and try to execute the controller action by indirect means. It only works when 
 * useRbac is set for the cmgCore Component within the application config file and action is configured within the 
 * controller behaviours.
 */
class RbacFilter extends \yii\base\Behavior {

	//TODO Add code for caching the roles and permissions

	/**
	 * @var maps the action to permission and permission filters.
	 */
	public $actions	= [];

    public function events() {

        return [ Controller::EVENT_BEFORE_ACTION => 'beforeAction' ];
    }

    public function beforeAction( $event ) {

		if( Yii::$app->cmgCore->isRbac() ) {

	        $action = $event->action->id;

			// Check whether action is permitted
	        if ( array_key_exists( $action, $this->actions ) ) {

				// Redirect to post logout page if guest
				if( Yii::$app->user->isGuest ) {

					Yii::$app->response->redirect( Url::toRoute( [ Yii::$app->cmgCore->getLogoutRedirectPage() ], true ) );

					$event->isValid = false;

					return $event->isValid;
				}

				// find User and Action Permission
				$user		= Yii::$app->user->getIdentity();
				$action 	= $this->actions[ $action ];
				$permission	= $action[ 'permission' ];

				// Check whether user is permitted

				if( !isset( $user ) || !$user->isPermitted( $permission ) ) {

					throw new ForbiddenHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_ALLOWED ) );
				}

				// Check permission filters to filter the permission for the current action
				if( isset( $action[ 'filters' ] ) ) {

					$filters	= $action[ 'filters' ];
					$filterKeys	= array_keys( $action[ 'filters' ] );

					foreach ( $filterKeys as $key ) {

						// Permission Filter with filter config params
						if( is_array( $filters[ $key ] ) ) {

							$filter	= Yii::createObject( Yii::$app->cmgCore->rbacFilters[ $key ] );

							// Pass filter config while performing filter
							$filter->doFilter( $filters[ $key ] );
						}
						// Permission Filter without filter config params
						else {

							$filter	= Yii::createObject( Yii::$app->cmgCore->rbacFilters[ $filters[ $key ] ] );

							// Do filter without any config
							$filter->doFilter();
						}
					}
				}
	        }
		}

        return $event->isValid;
    }
}

?>