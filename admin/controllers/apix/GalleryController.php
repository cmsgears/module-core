<?php
namespace cmsgears\core\admin\controllers\apix;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\resources\CmgFile;

use cmsgears\core\admin\services\resources\FileService;
use cmsgears\core\admin\services\resources\GalleryService;

use cmsgears\core\common\utilities\AjaxUtil;

class GalleryController extends \cmsgears\core\admin\controllers\base\Controller {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );
	}

	// Instance Methods --------------------------------------------

	// yii\base\Component

    public function behaviors() {

        return [
            'rbac' => [
                'class' => Yii::$app->cmgCore->getRbacFilterClass(),
                'actions' => [
	                'createItem' => [ 'permission' => CoreGlobal::PERM_CORE ],
	                'updateItem' => [ 'permission' => CoreGlobal::PERM_CORE ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
	                'createItem' => ['post'],
	                'updateItem' => ['post']
                ]
            ]
        ];
    }

	// GalleryController

	public function actionCreateItem( $id ) {

		$gallery = GalleryService::findById( $id );

		if( isset( $gallery ) ) {

			$item 	= new CmgFile();

			if( $item->load( Yii::$app->request->post(), "File" ) && GalleryService::createItem( $gallery, $item ) ) {

				// Trigger Ajax Success
				return AjaxUtil::generateSuccess( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ) );
			}

			// Generate Errors
			$errors = AjaxUtil::generateErrorMessage( $item );

			// Trigger Ajax Success
	        return AjaxUtil::generateFailure( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
		}
	}

	public function actionUpdateItem( $id ) {

		// Find Model
		$item 	= FileService::findById( $id );

		// Update/Render if exist
		if( isset( $item ) ) {

			if( $item->load( Yii::$app->request->post(), "File" ) && GalleryService::updateItem( $item ) ) {

				// Trigger Ajax Success
				return AjaxUtil::generateSuccess( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ) );
			}
			else {

				// Generate Errors
				$errors = AjaxUtil::generateErrorMessage( $item );

				// Trigger Ajax Success
		        return AjaxUtil::generateFailure( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
			}
		}
	}
}

?>