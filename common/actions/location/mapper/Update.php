<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\actions\location\mapper;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * The Update action find model location for the given id and update the corresponding location.
 *
 * @since 1.0.0
 */
class Update extends \cmsgears\core\common\actions\base\ModelAction {

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

	protected $locationService;

	protected $modelLocationService;

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->locationService = Yii::$app->factory->get( 'locationService' );

		$this->modelLocationService	= Yii::$app->factory->get( 'modelLocationService' );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Update --------------------------------

	public function run( $cid ) {

		if( isset( $this->model ) ) {

			$modelLocation = $this->modelLocationService->getById( $cid );

			if( isset( $modelLocation ) && $modelLocation->isParentValid( $this->model->id, $this->parentType ) ) {

				$location = $modelLocation->model;

				if( isset( $this->scenario ) ) {

					$location->setScenario( $this->scenario );
				}

				if( $location->load( Yii::$app->request->post(), $location->getClassName() ) && $location->validate() ) {

					$modelLocation->type = $this->modelType;

					$this->locationService->update( $location );

					$this->modelLocationService->update( $modelLocation );

					$location->refresh();

					$data = [
						'cid' => $modelLocation->id, 'ctype' => $modelLocation->type,
						'title' => $location->title,
						'country' => $location->countryName, 'province' => $location->provinceName,
						'region' => $location->regionName, 'city' => $location->cityName,
						'latitude' => $location->latitutde, 'longitude' => $location->longitude, 'zoomLevel' => $location->zoomLevel,
						'zip' => $location->zip, 'value' => $location->toString()
					];

					// Trigger Ajax Success
					return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
				}

				// Generate Errors
				$errors = AjaxUtil::generateErrorMessage( $location );

				// Trigger Ajax Failure
				return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
			}
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
