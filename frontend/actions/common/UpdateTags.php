<?php
namespace cmsgears\core\frontend\actions\common;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\services\mappers\ModelTagService;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * UpdateTags can be used to create or update tags for models.
 *
 * The controller must provide modelService variable using approprite service class.
 */
class UpdateTags extends ModelAction {

	// Variables ---------------------------------------------------

	// Constants/Statics --

	// Public -------------

	// Private/Protected --

	// Constructor and Initialisation ------------------------------

	// Instance Methods --------------------------------------------

	// CreateTags ------------------------

	public function run() {

		$post	= yii::$app->request->post();

		if( isset( $this->model ) && isset( $post[ 'tags' ] ) ) {

			$tags 		= $post[ 'tags' ];

			ModelTagService::createFromCsv( $this->model->id, $this->modelType, $tags );

			$tags		= $this->model->activeTags;
			$data		= [];

			foreach ( $tags as $tag ) {

				$data[]	= [ 'name' => $tag->name, 'slug' => $tag->slug ];
			}

			// Trigger Ajax Success
			return AjaxUtil::generateSuccess( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
		}

		// Trigger Ajax Failure
        return AjaxUtil::generateFailure( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}

?>
