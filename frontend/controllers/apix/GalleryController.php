<?php
namespace cmsgears\core\frontend\controllers\apix;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\CmgFile;

use cmsgears\core\common\services\FileService;
use cmsgears\core\common\services\ModelFileService;
use cmsgears\core\frontend\services\GalleryService;

use cmsgears\core\common\utilities\AjaxUtil;

class GalleryController extends \cmsgears\core\admin\controllers\base\Controller {

	protected $ownerService;

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );
		
		$this->ownerService	= new GalleryService();
	}

	// Instance Methods --------------------------------------------

	// yii\base\Component

	public function behaviors() {

        return [
            'rbac' => [
                'class' => Yii::$app->cmgCore->getRbacFilterClass(),
                'actions' => [
	                'createItem' => [ 'permission' => CoreGlobal::PERM_USER, 'filters' => [ 'owner' => [ 'slug' => true, 'service' => $this->ownerService ] ] ],
	                'deleteItem' => [ 'permission' => CoreGlobal::PERM_USER, 'filters' => [ 'owner' => [ 'slug' => true, 'service' => $this->ownerService ] ] ],
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
	                'createItem' => [ 'post' ],
	                'deleteItem' => [ 'post' ]
                ]
            ]
        ];
    }

	// GalleryController

	public function actionCreateItem( $slug ) {

		$gallery = GalleryService::findBySlug( $slug );

		if( isset( $gallery ) ) {

			$item 	= new CmgFile();

			if( $item->load( Yii::$app->request->post(), 'File' ) && $item->validate() ) {

				$item	= GalleryService::createItem( $gallery, $item );
				$item	= FileService::findById( $item->id );
				$data	= [ 'id' => $item->id, 'thumbUrl' => $item->getThumbUrl() ];

				// Trigger Ajax Success
				return AjaxUtil::generateSuccess( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
			}

			// Generate Errors
			$errors = AjaxUtil::generateErrorMessage( $item );
	
			// Trigger Ajax Failure
	        return AjaxUtil::generateFailure( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
		}

		// Trigger Ajax Failure
        return AjaxUtil::generateFailure( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDeleteItem( $slug, $id ) {

		$gallery = GalleryService::findBySlug( $slug );

		if( isset( $gallery ) ) {

			$modelFile	= ModelFileService::findByFileId( $gallery->id, CoreGlobal::TYPE_GALLERY, $id );

			if( isset( $modelFile ) ) {

				if ( ModelFileService::delete( $modelFile ) ) {
					
					// Trigger Ajax Success
					return AjaxUtil::generateSuccess( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $id );
				}
			}
		}

		// Trigger Ajax Failure
        return AjaxUtil::generateFailure( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}

?>