<?php
namespace cmsgears\core\frontend\controllers;

// Yii Imports
use Yii;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\core\frontend\config\WebGlobalCore;

use cmsgears\core\common\models\forms\Login;
use cmsgears\core\frontend\models\forms\Register;

use cmsgears\core\common\services\SiteMemberService;
use cmsgears\core\frontend\services\UserService;

class SiteController extends \cmsgears\core\common\controllers\SiteController {

	// Constructor and Initialisation ------------------------------

    /**
     * @inheritdoc
     */
 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );

		$this->layout	= WebGlobalCore::LAYOUT_PUBLIC;
	}

	// Instance Methods --------------------------------------------

	// yii\base\Component ----------------

    public function behaviors() {

        return [
            'rbac' => [
                'class' => Yii::$app->cmgCore->getRbacFilterClass(),
                'actions' => [
	                'logout' => [ 'permission' => CoreGlobal::PERM_USER ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['get','post']
                ]
            ]
        ];
    }

	// yii\base\Controller ---------------

    public function actions() {

		if ( !Yii::$app->user->isGuest ) {

			$this->layout	= WebGlobalCore::LAYOUT_PRIVATE;
		}

        return [
            'error' => [
                'class' => 'yii\web\ErrorAction'
            ]
        ];
    }

	// SiteController --------------------

    public function actionIndex() {

		$this->checkHome();

		$this->layout	= WebGlobalCore::LAYOUT_LANDING;

		// Render Page
        return $this->render( WebGlobalCore::PAGE_INDEX );
    }
	
	public function actionLogin() {
		
		return parent::actionLogin( false, true );
	}
	
    public function actionRegister() {

		$coreProperties = $this->getCoreProperties();

		// Send user to home if already logged in
		$this->checkHome();

		// Create Form Model
		$model = new Register();

		// Load and Validate Form Model
		if( $coreProperties->isPublicRegister() && $model->load( Yii::$app->request->post() ) && $model->validate() ) {

			// Register User
			$user 	= UserService::register( $model );

			if( isset( $user ) ) {

				// Add User to current Site 
				SiteMemberService::create( $user );

				// Send Register Mail
				Yii::$app->cmgCoreMailer->sendRegisterMail( $user );

				// Set Flash Message
				Yii::$app->session->setFlash( 'message', Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::MESSAGE_REGISTER ) );
	
				// Refresh the Page
				return $this->refresh();
			}
		}

        return $this->render( WebGlobalCore::PAGE_REGISTER, [
        	'model' => $model
        ]);
    }

    public function actionConfirmAccount( $token, $email ) {

		// Unset Flash Message
		Yii::$app->session->setFlash( 'message', null );

		// Send user to home if already logged in
		$this->checkHome();

		if( isset( $token ) && isset( $email ) ) {

			$user	= UserService::findByEmail( $email );

			if( isset( $user ) && UserService::verify( $user, $token ) ) {

				// Send Register Mail
				Yii::$app->cmgCoreMailer->sendVerifyUserMail( $user );

				// Set Success Message
				Yii::$app->session->setFlash( 'message', Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::MESSAGE_ACCOUNT_CONFIRM ) );
			}
			else {

				// Set Failure Message
				Yii::$app->session->setFlash( 'message', Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_ACCOUNT_CONFIRM ) );
			}
		}
		else {

			// Set Failure Message
			Yii::$app->session->setFlash( 'message', MYii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_ACCOUNT_CONFIRM ) );
		}

        return $this->render( WebGlobalCore::PAGE_ACCOUNT_CONFIRM );
    }
}

?>