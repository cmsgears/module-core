<?php
namespace cmsgears\core\frontend\controllers\apix;

// Yii Imports
use Yii;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\forms\ResetPassword;
use cmsgears\core\common\models\entities\Address;

use cmsgears\core\frontend\services\UserService;
use cmsgears\core\common\services\ProvinceService;
use cmsgears\core\common\services\ModelAddressService;

use cmsgears\core\common\utilities\AjaxUtil;
use cmsgears\core\common\utilities\CodeGenUtil;

class UserController extends \cmsgears\core\common\controllers\apix\UserController {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );
	}

	// Instance Methods --------------------------------------------

	// yii\base\Component

    public function behaviors() {
		
		$behaviours	= parent::behaviors();
		
		$behaviours[ 'rbac' ][ 'actions' ][ 'profile' ]			= [ 'permission' => CoreGlobal::PERM_USER ];
		$behaviours[ 'rbac' ][ 'actions' ][ 'updateAddress' ]	= [ 'permission' => CoreGlobal::PERM_USER ];

		$behaviours[ 'verbs' ][ 'actions' ][ 'profile' ]		= [ 'post' ];
		$behaviours[ 'verbs' ][ 'actions' ][ 'updateAddress' ]	= [ 'post' ];

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
				UserService::update( $user );

				$data	= [
							'email' => $user->email, 'username' => $user->username, 'firstName' => $user->firstName, 
							'lastName' => $user->lastName, 'gender' => $user->getGenderStr(), 'phone' => $user->phone 
						];

				// Trigger Ajax Success
				return AjaxUtil::generateSuccess( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
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

	public function actionUpdateAddress( $type ) {

		$user	= Yii::$app->user->getIdentity();

		if( isset( $user ) ) {

			$address	= new Address();

			if( $address->load( Yii::$app->request->post(), 'Address' ) && $address->validate() ) {

			$modelAddress 	= ModelAddressService::createOrUpdateByType( $address, $user->id, CoreGlobal::TYPE_USER, $type );
			$address		= $modelAddress->address;

			$data	= [
						'line1' => $address->line1, 'line2' => $address->line2, 'city' => $address->city, 
						'country' => $address->country->name, 'province' => $address->province->name, 'phone' => $address->phone, 'zip' => $address->zip 
					];

				// Trigger Ajax Success
				return AjaxUtil::generateSuccess( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data ); 
			}

			// Generate Errors
			$errors = AjaxUtil::generateErrorMessage( $address );

			// Trigger Ajax Failure
	    	return AjaxUtil::generateFailure( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
		}

		// Trigger Ajax Failure
    	return AjaxUtil::generateFailure( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), [ 'session' => true ] );
    }
}

?>