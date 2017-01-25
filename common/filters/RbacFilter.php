<?php
namespace cmsgears\core\common\filters;

// Yii Imports
use \Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * The class RbacFilter use the roles and permissions defined for the project using the database tables.
 * It identify whether a user is assigned a permission. It trigger ForbiddenException in case a user does not have
 * required permission and try to execute the controller action by indirect means. It only works when
 * useRbac is set for the core Component within the application config file and action is configured within the
 * controller behaviours.
 */
class RbacFilter extends \yii\base\Behavior {

	//TODO Add code for caching the roles and permissions to avoid reloading them for each request

	/**
	 * @var maps the action to permission and permission filters.
	 */
	public $actions	= [];

	public function events() {

		return [ Controller::EVENT_BEFORE_ACTION => 'validateRbac' ];
	}

	public function validateRbac( $event ) {

		if( Yii::$app->core->isRbac() ) {

			$action = $event->action->id;

			// Check whether action is permitted
			if ( array_key_exists( $action, $this->actions ) ) {

				$actionConfig	= $this->actions[ $action ];
				$permission		= $actionConfig[ 'permission' ];

				// Allow if guest permission - It expects at least one filter
				if( strcmp( $permission, CoreGlobal::PERM_GUEST ) == 0 ) {

					$filterResult	= $this->executeFilters( $actionConfig );

					// Filter might throw exception to hault execution or return true/false on successful execution
					if( !$filterResult ) {

						// Unset event validity
						$event->isValid = false;

						return $event->isValid;
					}

					return true;
				}

				// Redirect to post logout page if user is guest
				if( Yii::$app->user->isGuest ) {

					// Redirect to post logout page
					Yii::$app->response->redirect( Url::toRoute( [ Yii::$app->core->getLogoutRedirectPage() ], true ) );

					// Unset event validity
					$event->isValid = false;

					// Move back and pass execution to controller
					return $event->isValid;
				}

				// find User and Action Permission
				$user	= Yii::$app->user->getIdentity();

				// Disallow action in case user is not permitted
				if( !isset( $user ) || !isset( $permission ) || !$user->isPermitted( $permission ) ) {

					throw new ForbiddenHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_ALLOWED ) );
				}

				if( ( $user->isConfirmed( true ) || $user->isSubmitted() ) && strcmp( $action, 'logout' ) != 0 ) {

					// Redirect to post logout page
					Yii::$app->response->redirect( Url::toRoute( [ Yii::$app->core->getConfirmRedirectPage() ], true ) );

					// Unset event validity
					$event->isValid = false;

					// Move back and pass execution to controller
					return $event->isValid;
				}

				// Execute filters in the order defined in controller behaviours for rbac behaviour. The filters must be configured in appropriate application config file.
				if( isset( $actionConfig[ 'filters' ] ) ) {

					$filterResult	= $this->executeFilters( $actionConfig );

					// Filter might throw exception to hault execution or return true/false on successful execution
					if( !$filterResult ) {

						// Unset event validity
						$event->isValid = false;

						return $event->isValid;
					}
				}
			}
		}

		return $event->isValid;
	}

	private function executeFilters( $actionConfig ) {

		$filters		= $actionConfig[ 'filters' ];
		$filterKeys		= array_keys( $actionConfig[ 'filters' ] );
		$filterResult	= true;

		foreach ( $filterKeys as $key ) {

			$filterResult	= true;

			// Permission Filter with filter config params
			if( is_array( $filters[ $key ] ) ) {

				$filter	= Yii::createObject( Yii::$app->core->rbacFilters[ $key ] );

				// Pass filter config while performing filter
				$filterResult = $filter->doFilter( $filters[ $key ] );
			}
			// Permission Filter without filter config params
			else {

				$filter	= Yii::createObject( Yii::$app->core->rbacFilters[ $filters[ $key ] ] );

				// Do filter without any config
				$filterResult = $filter->doFilter();
			}

			// Break the loop in case filter failed and filter didn't throw any exception
			if( !$filterResult ) {

				break;
			}
		}

		return $filterResult;
	}
}
