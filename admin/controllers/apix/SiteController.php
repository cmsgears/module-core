<?php
namespace cmsgears\modules\core\admin\controllers\apix;

// Yii Imports
use \Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\modules\core\common\utilities\AjaxUtil;

class SiteController extends Controller {

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
                'only' => [ 'logout' ],
                'rules' => [
                    [
                        'actions' => [ 'logout' ],
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post']
                ]
            ]
        ];
    }

	// SiteController

    public function actionLogout() {
		
		// Logout User
        Yii::$app->user->logout();
		
		// Trigger Ajax Success
		AjaxUtil::generateSuccess( "success", null );
    }
}

?>