<?php
namespace cmsgears\core\frontend\controllers\apix;

// Yii Imports
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\CmgFile;

use cmsgears\core\frontend\services\UserService;

use cmsgears\core\frontend\controllers\BaseController;

use cmsgears\core\common\utilities\AjaxUtil;

class UserController extends BaseController {

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
	                'avatar' => [ 'permission' => CoreGlobal::PERM_USER ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'avatar' => ['post']
                ]
            ]
        ];
    }

	// UserController

    public function actionAvatar() {

		$user	= Yii::$app->user->getIdentity();
		$avatar = new CmgFile();

		$avatar->load( Yii::$app->request->post(), "Avatar" );

		if( UserService::updateAvatar( $user, $avatar ) ) {

			$user	= UserService::findById( $user->id );
			$avatar	= $user->avatar;

			// Trigger Ajax Success
			AjaxUtil::generateSuccess( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), [ 'fileUrl' => $avatar->getFileUrl() ] );
		}
		else {

			// Trigger Ajax Failure
        	AjaxUtil::generateFailure( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_REQUEST ) );
		}
    }
}

?>