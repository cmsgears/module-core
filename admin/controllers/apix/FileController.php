<?php
namespace cmsgears\core\admin\controllers\apix;

// Yii Imports
use \Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

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
            'rbac' => [
                'class' => Yii::$app->cmgCore->getRbacFilterClass(),
                'actions' => [
	                'fileHandler'  => [ 'permission' => CoreGlobal::PERM_ADMIN ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
	                'fileHandler'  => ['post']
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