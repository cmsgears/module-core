<?php
namespace cmsgears\core\common\actions\address;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * Delete Address
 *
 * The controller must provide appropriate model service.
 */
class Delete extends \cmsgears\core\common\actions\base\ModelAction {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $addressService;

	protected $modelAddressService;

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->addressService		= Yii::$app->factory->get( 'addressService' );

		$this->modelAddressService	= Yii::$app->factory->get( 'modelAddressService' );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// CreateMeta ----------------------------

	/**
	 * Create Meta for given parent slug and parent type.
	 */
	public function run( $id, $cid ) {

		if( isset( $this->model ) ) {

			$parent			= $this->model;
			$parentType		= $this->parentType;
			$modelAddress	= $this->modelAddressService->getByModelId( $parent->id, $parentType, $cid );

			if( isset( $modelAddress ) ) {

				$address = $modelAddress->address;

				$this->addressService->delete( $address );

				// Trigger Ajax Success
				return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ) );
			}
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}
