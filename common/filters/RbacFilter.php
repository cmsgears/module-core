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
	 * @var maps the action to permission and permissions filters.
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

					Yii::$app->controller->redirect( Yii::$app->urlManager->createUrl( Yii::$app->cmgCore->getLogoutRedirectPage() ) );
				}

				$user		= Yii::$app->user->getIdentity();
				$action 	= $this->actions[ $action ];
				$permission	= $action[ 'permission' ];

				// Check whether user is permitted	
				if( !$user->isPermitted( $permission ) ) {

					throw new ForbiddenHttpException( MessageUtil::ERROR_NOT_ALLOWED );
				}

				// Check permission filters
				if( isset( $action[ 'filters' ] ) ) {

					$filters	= $action[ 'filters' ];
					$filterKeys	= array_keys( $action[ 'filters' ] );

					foreach ( $filterKeys as $key ) {

						if( is_array( $filters[ $key ] ) ) {

							$filter	= Yii::createObject( Yii::$app->cmgCore->rbacFilters[ $key ] );

							$filter->doFilter( $filters[ $key ] );
						}
						else {

							$filter	= Yii::createObject( Yii::$app->cmgCore->rbacFilters[ $filters[ $key ] ] );

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