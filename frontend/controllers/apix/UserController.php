<?php
namespace cmsgears\core\frontend\controllers\apix;

// Yii Imports
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\core\frontend\config\WebGlobalCore;

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
            'access' => [
                'class' => AccessControl::className(),
                'only' => [ 'home' ],
                'rules' => [
                    [
                        'actions' => [ 'home' ],
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'home' => ['get']
                ]
            ]
        ];
    }

	// UserController

    public function actionAvatar() {

		$user	= Yii::$app->user->getIdentity();
		$avatar = new CmgFile();
		
		$avatar->load( Yii::$app->request->post( "Avatar" ), "" );

		if( UserService::actionUpdateAvatar( $user, $avatar ) ) {

			$user	= UserService::findById( $user->id );
			$avatar	= $user->avatar;

			// Trigger Ajax Success
			AjaxUtil::generateSuccess( Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::MESSAGE_REQUEST ), [ 'fileUrl' => $avatar->getFileUrl() ] );
		}
		else {

			// Trigger Ajax Failure
        	AjaxUtil::generateFailure( Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::ERROR_REQUEST ) );
		}
    }
}

?>