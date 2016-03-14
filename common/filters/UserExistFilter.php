<?php
namespace cmsgears\core\common\filters;

// Yii Imports
use \Yii;
use yii\web\Controller;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\AjaxUtil;

class UserExistFilter extends \yii\base\Behavior {

	public $actions	= [];

    public function events() {

        return [ Controller::EVENT_BEFORE_ACTION => 'validateUserExist' ];
    }

    public function validateUserExist( $event ) {

        $action = $event->action->id;
		$found	= false;

		foreach ( $this->actions as $value ) {

			if( strcmp( $action, $value ) == 0 ) {

				$found	= true;
			}
		}

		// Check whether action is permitted
        if ( $found ) {

			$user	= Yii::$app->user->identity;

			// Return appropriate error if user does not exist
			if( !isset( $user ) ) {

				if( Yii::$app->request->isAjax ) {

					// Remember URL for Login
					Url::remember( Yii::$app->request->post( CoreGlobal::REDIRECT_LOGIN ), CoreGlobal::REDIRECT_LOGIN );

					// Configure Errors
					$errors					= [];
					$errors[ 'userExist' ] 	= false;

					// Trigger Ajax Failure
			    	$response = AjaxUtil::generateFailure( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );

					echo json_encode( $response );

					$event->isValid = false;

					return $event->isValid;
				}
			}
		}

		return $event->isValid;
    }
}

?>