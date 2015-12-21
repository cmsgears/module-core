<?php
namespace cmsgears\core\frontend\controllers\apix;

// Yii Imports
use Yii;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\forms\ResetPassword;

use cmsgears\core\frontend\services\UserService;

use cmsgears\core\common\utilities\AjaxUtil;

class UserController extends \cmsgears\core\common\controllers\apix\UserController {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );
	}

	// Instance Methods --------------------------------------------

	// yii\base\Component

    public function behaviors() {
		
		$behaviours	= parent::behaviors();
		
		$behaviours[ 'rbac' ][ 'actions' ][ 'profile' ]		= [ 'permission' => CoreGlobal::PERM_USER ];
		$behaviours[ 'verbs' ][ 'actions' ][ 'profile' ]	= [ 'post' ];
		
		return $behaviours;
    }

	// UserController

    public function actionProfile() {

		// Find Model
		$user	= Yii::$app->user->getIdentity();

		// Update/Render if exist
		if( isset( $user ) ) {

			$user->setScenario( 'profile' );

			UserService::checkNewsletterMember( $user );

			if( $user->load( Yii::$app->request->post(), 'User' ) && $user->validate() ) {

				// Update User and Site Member
				if( UserService::update( $user ) ) {

					$data	= [ 
								'email' => $user->email, 'username' => $user->username, 'firstName' => $user->firstName, 
								'lastName' => $user->lastName, 'gender' => $user->getGenderStr(), 'phone' => $user->phone ];

					// Trigger Ajax Success
					return AjaxUtil::generateSuccess( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
				}
			}
			else {

				// Generate Errors
				$errors = AjaxUtil::generateErrorMessage( $user );
	
				// Trigger Ajax Failure
	        	return AjaxUtil::generateFailure( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
			}
		}

		// Model not found
		return AjaxUtil::generateFailure( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), [ 'session' => true ] );
    }
}

?>