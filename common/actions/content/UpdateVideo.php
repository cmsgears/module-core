<?php
namespace cmsgears\core\common\actions\content;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\resources\File;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * UpdateVideo can be used to update video for models.
 *
 * The controller must provide modelService variable using approprite service class.
 */
class UpdateVideo extends \cmsgears\core\common\actions\base\ModelAction {

    // Variables ---------------------------------------------------

    // Globals -------------------------------

    // Constants --------------

    // Public -----------------

    // Protected --------------

    // Variables -----------------------------

    // Public -----------------

    public $fileName	= 'Video';

    // Protected --------------

    // Private ----------------

    // Traits ------------------------------------------------------

    // Constructor and Initialisation ------------------------------

    // Instance methods --------------------------------------------

    // Yii interfaces ------------------------

    // Yii parent classes --------------------

    // CMG interfaces ------------------------

    // CMG parent classes --------------------

    // UpdateVideo ---------------------------

    public function run() {

        if( isset( $this->model ) ) {

            $video = $this->model->video;

            if( !isset( $video ) ) {

                $video	= new File();
            }

            if( $video->load( Yii::$app->request->post(), $this->fileName ) ) {

                $this->modelService->updateVideo( $this->model, $video );

                $video		= $this->model->video;
                $response	= [ 'fileUrl' => $video->getFileUrl() ];

                // Trigger Ajax Success
                return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $response );
            }

            // Trigger Ajax Failure
            return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ) );
        }

        // Trigger Ajax Failure
        return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
    }
}
