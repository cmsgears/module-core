<?php
namespace cmsgears\core\frontend\controllers\apix;

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
	                'fileHandler'  => [ 'permission' => CoreGlobal::PERM_USER ]
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

	public function actionFileHandler( $directory, $type ) {

		$data	= Yii::$app->fileManager->handleFileUpload( $directory, $type );
		$keys	= array_keys( $data );

		if( !in_array( 'error', array_keys( $data ) ) ) {

			// Trigger Ajax Success
			return AjaxUtil::generateSuccess( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
		}

		// Trigger Ajax Failure
        return AjaxUtil::generateFailure( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $data );
	}
}

?>