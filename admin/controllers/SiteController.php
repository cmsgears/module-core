<?php
namespace cmsgears\core\admin\controllers;

// Yii Imports
use \Yii;
use yii\filters\AccessControl;

// CMG Imports
use cmsgears\core\admin\config\AdminGlobalCore;

use cmsgears\core\common\models\forms\LoginForm;

class SiteController extends BaseController {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );

		// Send user to home if already logged in
        if ( Yii::$app->user->isGuest ) {

			$this->layout	= AdminGlobalCore::LAYOUT_PUBLIC;
        }
	}

	// Instance Methods --------------------------------------------

	// yii\base\Component ----------------

    public function behaviors() {

        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => [ 'index', 'dashboard' ],
                'rules' => [
                    [
                        'actions' => [ 'index', 'dashboard' ],
                        'allow' => true,
                        'roles' => ['@']
                    ]
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

    public function actionIndex() {

        return $this->render( 'index' );
    }

    public function actionDashboard() {

        return $this->render( 'index' );
    }

	public function actionLogin() {

		// Send user to home if already logged in
        if ( !\Yii::$app->user->isGuest ) {

        	$this->redirect( [ 'site/index' ] );
        }

		// Create Form Model
        $model 	= new LoginForm();

		// Load and Validate Form Model
		if( $model->load( Yii::$app->request->post() )  && $model->login() ) {

			// Redirect user to home set by admin
			if( isset( $role ) && isset( $role->homeUrl ) ) {

				$this->redirect( [ $role->homeUrl ] );
			}
			// Redirect user to home set by app config
			else {

				$this->redirect( [ Yii::$app->cmgCore->getLoginRedirectPage() ] );
			}
		}
		else {

        	return $this->render( 'login', [
        		'model' => $model
        	]);
		}
    }
}

?>