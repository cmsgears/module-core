<?php
namespace cmsgears\core\common\actions\social;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\forms\SocialLink;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * DeleteLink deletes social link for given parent model.
 *
 * The controller must provide appropriate model service.
 */
class DeleteLink extends \cmsgears\core\common\base\Action {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $modelService;

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->modelService	= $this->controller->modelService;
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// UpdateLink ----------------------------

	/**
	 * Create Link for given parent slug and parent type.
	 */
	public function run( $id = null, $slug = null, $type = null ) {

		// Find parent
		$parent	= null;

		if( isset( $id ) ) {

			$parent	= $this->modelService->getById( $id );
		}
		else if( isset( $type ) ) {

			$parent	= $this->modelService->getBySlugType( $slug, $type );
		}
		else {

			$parent	= $this->modelService->getBySlug( $slug );
		}

		// Create Link
		if( isset( $parent ) ) {

			$link	= new SocialLink();

			if( $link->load( Yii::$app->request->post(), 'SocialLink' ) && $link->validate() ) {

				$this->modelService->deleteSocialLink( $parent, $link );

				// Trigger Ajax Success
				return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ) );
			}

			// Generate Errors
			$errors = AjaxUtil::generateErrorMessage( $link );

			// Trigger Ajax Failure
			return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}
