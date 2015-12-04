<?php
namespace cmsgears\core\common\controllers\apix;

// Yii Imports
use Yii;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\ModelMeta;
use cmsgears\core\common\models\entities\CmgFile;
use cmsgears\core\common\models\forms\ResetPassword;

use cmsgears\core\frontend\services\UserService;

use cmsgears\core\common\utilities\AjaxUtil;

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

		if( $avatar->load( Yii::$app->request->post(), 'File' ) && UserService::updateAvatar( $user, $avatar ) ) {

			$user		= UserService::findById( $user->id );
			$avatar		= $user->avatar;
			$response	= [ 'fileUrl' => $avatar->getFileUrl() ];

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

			$modelMetas		= Yii::$app->request->post( 'ModelMeta' );
			$count 			= count( $modelMetas );
			$metas			= [];

			for ( $i = 0; $i < $count; $i++ ) {

				$meta		= new ModelMeta( [ 'parentId' => $user->id, 'parentType' => CoreGlobal::TYPE_USER ] );
				$metas[] 	= $meta; 
			}

			// Load SchoolItem models
			if( ModelMeta::loadMultiple( $metas, Yii::$app->request->post(), 'ModelMeta' ) && ModelMeta::validateMultiple( $metas ) ) {

				UserService::updateMetas( $user, $metas );

				$data	= [];

				foreach ( $metas as $meta ) {

					$data[]	= [ 'name' => $meta->name, 'value' => $meta->getStrValue() ];
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
}

?>