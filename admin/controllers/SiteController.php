<?php
namespace cmsgears\core\admin\controllers;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\core\admin\config\AdminGlobalCore;

class SiteController extends \cmsgears\core\common\controllers\SiteController {

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

		if ( !Yii::$app->user->isGuest ) {

			$this->layout	= AdminGlobalCore::LAYOUT_PRIVATE;
		}

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

	public function actionLogin() {
		
		return parent::actionLogin( true );
	}
}

?>