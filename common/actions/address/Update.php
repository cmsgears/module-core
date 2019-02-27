<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\actions\address;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\actions\base\ModelAction;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * The Update action find model address for the given id and update the corresponding address.
 *
 * @since 1.0.0
 */
class Update extends ModelAction {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $parent = true;

	public $scenario = 'location';

	// Protected --------------

	protected $addressService;

	protected $modelAddressService;

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->addressService = Yii::$app->factory->get( 'addressService' );

		$this->modelAddressService = Yii::$app->factory->get( 'modelAddressService' );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Update --------------------------------

	public function run( $cid ) {

		if( isset( $this->model ) ) {

			$modelAddress = $this->modelAddressService->getById( $cid );

			if( isset( $modelAddress ) && $modelAddress->isParentValid( $this->model->id, $this->parentType ) ) {

				$address = $modelAddress->model;

				if( isset( $this->scenario ) ) {

					$address->setScenario( $this->scenario );
				}

				if( $address->load( Yii::$app->request->post(), $address->getClassName() ) && $address->validate() ) {

					$modelAddress->type = $this->modelType;

					$this->addressService->update( $address );

					$this->modelAddressService->update( $modelAddress );

					$address->refresh();

					$data = [
						'cid' => $modelAddress->id, 'ctype' => $modelAddress->type,
						'title' => $address->title, 'line1' => $address->line1, 'line2' => $address->line2,
						'country' => $address->countryName, 'province' => $address->provinceName,
						'region' => $address->regionName, 'city' => $address->cityName,
						'zip' => $address->zip, 'value' => $address->toString()
					];

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
