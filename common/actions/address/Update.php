<?php
namespace cmsgears\core\common\actions\address;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * Update Address
 *
 * The controller must provide appropriate model service.
 */
class Update extends \cmsgears\core\common\actions\base\ModelAction {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $scenario = 'location';

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

				if( isset( $this->scenario ) ) {

					$address->setScenario( $this->scenario );
				}

				if( $address->load( Yii::$app->request->post(), 'Address' ) && $address->validate() ) {

					$this->addressService->update( $address );

					$address->refresh();

					$data	= [ 'id' => $address->id, 'title' => $address->title, 'value' => $address->toString() ];

					// Trigger Ajax Success
					return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
				}

				// Generate Errors
				$errors = AjaxUtil::generateErrorMessage( $address );

				// Trigger Ajax Failure
				return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
			}
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}
