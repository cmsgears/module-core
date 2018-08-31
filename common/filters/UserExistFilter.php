<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\filters;

// Yii Imports
use Yii;
use yii\base\Behavior;
use yii\web\Controller;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * Check whether user exists before redirecting user to the given url.
 *
 * @since 1.0.0
 */
class UserExistFilter extends Behavior {

	public $actions	= [];

	public function events() {

		return [ Controller::EVENT_BEFORE_ACTION => 'validateUserExist' ];
	}

	public function validateUserExist( $event ) {

		$action = $event->action->id;
		$found	= false;

		foreach( $this->actions as $value ) {

			if( strcmp( $action, $value ) == 0 ) {

				$found = true;
			}
		}

		// Proceed if requested action exists
		if ( $found ) {

			$user = Yii::$app->user->identity;

			// Return appropriate error if user does not exist
			if( !isset( $user ) ) {

				if( Yii::$app->request->isAjax ) {

					// Remember URL for Login
					$redirectUrl = Yii::$app->request->get( 'redirect' );

					if( empty( $redirectUrl ) ) {

						$redirectUrl = Yii::$app->request->post( CoreGlobal::REDIRECT_LOGIN );
					}

					Url::remember( $redirectUrl, CoreGlobal::REDIRECT_LOGIN );

					// Configure Errors
					$errors = [];

					$errors[ 'userExist' ] = false;

					// Trigger Ajax Failure
					$response = AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );

					echo json_encode( $response );

					$event->isValid = false;

					return $event->isValid;
				}
			}
		}

		return $event->isValid;
	}

}
