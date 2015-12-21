<?php
namespace cmsgears\core\admin\controllers;

// Yii Imports
use \Yii;

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

    public function behaviors() {

        $behaviours	= parent::behaviors();

		$behaviours[ 'rbac' ][ 'actions' ][ 'index' ]		= [ 'permission' => CoreGlobal::PERM_ADMIN ];
		$behaviours[ 'rbac' ][ 'actions' ][ 'dashboard' ]	= [ 'permission' => CoreGlobal::PERM_ADMIN ];

		$behaviours[ 'verbs' ][ 'actions' ][ 'index' ]		= [ 'get' ];
		$behaviours[ 'verbs' ][ 'actions' ][ 'dashboard' ]	= [ 'get' ];

		return $behaviours;
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