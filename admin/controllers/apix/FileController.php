<?php
namespace cmsgears\modules\core\admin\controllers\apix;

// Yii Imports
use \Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\modules\core\common\config\CoreGlobal;

use cmsgears\modules\core\common\utilities\MessageUtil;
use cmsgears\modules\core\common\utilities\AjaxUtil;

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
			AjaxUtil::generateSuccess( MessageUtil::getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
		}
		else {

			// Trigger Ajax Failure
	        AjaxUtil::generateFailure( MessageUtil::getMessage( CoreGlobal::ERROR_REQUEST ) );
		}
	}
}

?>