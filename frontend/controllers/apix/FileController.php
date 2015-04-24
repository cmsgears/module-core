<?php
namespace cmsgears\core\frontend\controllers\apix;

// Yii Imports
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\Permission;

use cmsgears\core\common\utilities\AjaxUtil;

class FileController extends Controller {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );

		$this->enableCsrfValidation = false;
	}

	// Instance Methods --------------------------------------------

	// yii\base\Component

    public function behaviors() {

        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['fileHandler'],
                'rules' => [
                    [
                        'actions' => ['fileHandler'],
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'fileHandler' => ['post']
                ]
            ]
        ];
    }

	// UserController

	public function actionFileHandler( $selector ) {

		$data	= Yii::$app->fileManager->handleFileUpload( $selector );

		if( $data ) {

			// Trigger Ajax Success
			AjaxUtil::generateSuccess( Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
		}
		else {

			// Trigger Ajax Failure
	        AjaxUtil::generateFailure( Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::ERROR_REQUEST ) );
		}
	}
}

?>