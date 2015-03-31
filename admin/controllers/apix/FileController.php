<?php
namespace cmsgears\core\admin\controllers\apix;

// Yii Imports
use \Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\core\common\models\entities\Permission;

use cmsgears\core\common\components\MessageDbCore;
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
	                'fileHandler'  => [ 'permission' => Permission::PERM_ADMIN ]
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

		$data	= Yii::$app->cmgFileManager->handleFileUpload( $selector );

		if( $data ) {

			// Trigger Ajax Success
			AjaxUtil::generateSuccess( Yii::$app->cmgCoreMessage->getMessage( MessageDbCore::MESSAGE_REQUEST ), $data );
		}
		else {

			// Trigger Ajax Failure
	        AjaxUtil::generateFailure( Yii::$app->cmgCoreMessage->getMessage( MessageDbCore::ERROR_REQUEST ) );
		}
	}
}

?>