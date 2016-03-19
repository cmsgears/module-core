<?php
namespace cmsgears\core\common\controllers\apix;

// Yii Imports
use Yii;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\ModelAttribute;
use cmsgears\core\common\models\entities\CmgFile;
use cmsgears\core\common\models\forms\ResetPassword;
use cmsgears\core\common\models\entities\Address;

use cmsgears\core\common\services\UserService;
use cmsgears\core\common\services\ProvinceService;
use cmsgears\core\common\services\ModelAddressService;

use cmsgears\core\common\utilities\AjaxUtil;
use cmsgears\core\common\utilities\CodeGenUtil;

class UserController extends \cmsgears\core\common\controllers\Controller {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );
	}

	// Instance Methods --------------------------------------------

	// yii\base\Component

    public function behaviors() {

        return [
            'rbac' => [
                'class' => Yii::$app->cmgCore->getRbacFilterClass(),
                'actions' => [
	                'avatar' => [ 'permission' => CoreGlobal::PERM_USER ],
	                'account' => [ 'permission' => CoreGlobal::PERM_USER ],
	                'settings' => [ 'permission' => CoreGlobal::PERM_USER ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'avatar' => [ 'post' ],
                    'account' => [ 'post' ],
                    'settings' => [ 'post' ]
                ]
            ]
        ];
    }

	// UserController

    public function actionAvatar() {

		$user	= Yii::$app->user->getIdentity();
		$avatar = new CmgFile();

		if( $avatar->load( Yii::$app->request->post(), 'Avatar' ) && UserService::updateAvatar( $user, $avatar ) ) {

			$response	= [ 'fileUrl' => $avatar->getThumbUrl() ];

			// Trigger Ajax Success
			return AjaxUtil::generateSuccess( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $response );
		}
		else {

			// Trigger Ajax Failure
        	return AjaxUtil::generateFailure( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_REQUEST ) );
		}
    }

    public function actionAccount() {

		// Find Model
		$user	= Yii::$app->user->getIdentity();

		// Update/Render if exist
		if( isset( $user ) ) {

			$model 	= new ResetPassword();

			if( $model->load( Yii::$app->request->post(), 'ResetPassword' ) && $model->validate() ) {

				// Update User and Site Member
				if( UserService::resetPassword( $user, $model, false ) ) {

					$data	= [ 'email' => $user->email, 'username' => $user->username ];

					// Trigger Ajax Success
					return AjaxUtil::generateSuccess( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
				}
			}
			else {

				// Generate Errors
				$errors = AjaxUtil::generateErrorMessage( $model );
	
				// Trigger Ajax Failure
	        	return AjaxUtil::generateFailure( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
			}
		}

		// Model not found
		return AjaxUtil::generateFailure( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), [ 'session' => true ] );
    }

	public function actionSettings() {
		
		$user			= Yii::$app->user->getIdentity();

		// Update/Render if exist
		if( isset( $user ) ) {

			$modelAttributes	= Yii::$app->request->post( 'ModelAttribute' );
			$count 				= count( $modelAttributes );
			$attributes			= [];

			for ( $i = 0; $i < $count; $i++ ) {

				$attribute		= new ModelAttribute( [ 'parentId' => $user->id, 'parentType' => CoreGlobal::TYPE_USER ] );
				$attributes[] 	= $attribute; 
			}

			// Load SchoolItem models
			if( ModelAttribute::loadMultiple( $attributes, Yii::$app->request->post(), 'ModelAttribute' ) && ModelAttribute::validateMultiple( $attributes ) ) {

				UserService::updateAttributes( $user, $attributes );

				$data	= [];

				foreach ( $attributes as $attribute ) {

					$data[]	= $attribute->getFieldInfo();
				}

				// Trigger Ajax Success
				return AjaxUtil::generateSuccess( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
			}

			// Trigger Ajax Failure
		    return AjaxUtil::generateFailure( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_REQUEST ) );
		}

		// Model not found
		return AjaxUtil::generateFailure( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), [ 'session' => true ] );
	}

    public function actionProfile() {

        // Find Model
        $user   = Yii::$app->user->getIdentity();

        // Update/Render if exist
        if( isset( $user ) ) {

            $user->setScenario( 'profile' );

            UserService::checkNewsletterMember( $user );

            if( $user->load( Yii::$app->request->post(), 'User' ) && $user->validate() ) {

                // Update User and Site Member
                UserService::update( $user );

                $data   = [
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

        $user   = Yii::$app->user->getIdentity();

        if( isset( $user ) ) {

            $address    = new Address();

            if( $address->load( Yii::$app->request->post(), 'Address' ) && $address->validate() ) {

            $modelAddress   = ModelAddressService::createOrUpdateByType( $address, $user->id, CoreGlobal::TYPE_USER, $type );
            $address        = $modelAddress->address;

            $data   = [
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