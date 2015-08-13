<?php
namespace cmsgears\core\admin\controllers;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\core\admin\config\AdminGlobalCore;

use cmsgears\core\common\models\forms\Login;

class SiteController extends BaseController {

	// Constructor and Initialisation ------------------------------

    /**
     * @inheritdoc
     */
 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );

		// Send user to home if already logged in
        if ( Yii::$app->user->isGuest ) {

			$this->layout	= AdminGlobalCore::LAYOUT_PUBLIC;
        }
	}

	// Instance Methods --------------------------------------------

	// yii\base\Component ----------------

    /**
     * @inheritdoc
     */
    public function behaviors() {

        return [
            'rbac' => [
                'class' => Yii::$app->cmgCore->getRbacFilterClass(),
                'actions' => [
	                'index'  => [ 'permission' => CoreGlobal::PERM_ADMIN ],
	                'dashboard' => [ 'permission' => CoreGlobal::PERM_ADMIN ],
	                'logout' => [ 'permission' => CoreGlobal::PERM_ADMIN ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
	                'index'  => [ 'get' ],
	                'dashboard'   => [ 'get' ],
	                'logout' => [ 'get', 'post' ]
                ]
            ]
        ];
    }

	// yii\base\Controller ---------------

    public function actions() {

        return [
            'error' => [
                'class' => 'yii\web\ErrorAction'
            ]
        ];
    }

	// SiteController --------------------

	/**
	 * The method redirect user to dashboard page.
	 */
    public function actionIndex() {

        $this->redirect( [ '/dashboard' ] );
    }

	/**
	 * The method shows the dashboard page based on user role.
	 */
    public function actionDashboard() {

		$this->layout	= AdminGlobalCore::LAYOUT_DASHBOARD;

        return $this->render( 'index' );
    }

	/**
	 * The method check whether user is logged in and send to home.
	 */
	public function actionLogin() {

		// Send user to home if already logged in
        $this->checkHome();

		// Create Form Model
        $model 			= new Login();
		$model->admin 	= true;

		// Load and Validate Form Model
		if( $model->load( Yii::$app->request->post(), "Login" )  && $model->login() ) {

			// Redirect user to home
			$this->checkHome();
		}

    	return $this->render( 'login', [
    		'model' => $model
    	]);
    }

	/**
	 * The method clears user session and cookies and redirect user to login.
	 */
    public function actionLogout() {

		// Logout User
        Yii::$app->user->logout();

		// Destroy Session
		Yii::$app->session->destroy();

    	$this->redirect( [ Yii::$app->cmgCore->getLogoutRedirectPage() ] );
    }

	/**
	 * The method check whether user is logged in and send to respective home page.
	 */
	private function checkHome() {

		// Send user to home if already logged in
	    if ( !Yii::$app->user->isGuest ) {

			$user	= Yii::$app->user->getIdentity();
			$role	= $user->role;

			// Redirect user to home
			if( isset( $role ) && isset( $role->homeUrl ) ) {
				
				$this->redirect( [ "/$role->homeUrl" ] );
			}
			// Redirect user to home set by app config
			else {

				$this->redirect( [ Yii::$app->cmgCore->getLoginRedirectPage() ] );
			}
	    }
	}
}

?>