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
use yii\db\Expression;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * The Create action creates model location, location and associate the model location to parent.
 *
 * @since 1.0.0
 */
class Create extends \cmsgears\core\common\actions\base\ModelAction {

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

	// Create --------------------------------

	public function run() {

		if( isset( $this->model ) ) {

			$location = $this->locationService->getModelObject();

			if( isset( $this->scenario ) ) {

				$location->setScenario( $this->scenario );

				if( $this->scenario == 'location' && $location->hasAttribute( 'geoPoint' ) && empty( $location->geoPoint ) ) {

					$location->geoPoint = new Expression( "ST_GeometryFromText( CONCAT( 'POINT(', 0, ' ', 0, ')' ) )" );
				}
			}

			if( $location->load( Yii::$app->request->post(), $location->getClassName() ) && $location->validate() ) {

				// Create Location
				$location = $this->locationService->create( $location );

				// Create Mapping
				$modelLocation = $this->modelLocationService->activateByParentModelId( $this->model->id, $this->parentType, $location->id, $this->modelType );

				$data = [
					'cid' => $modelLocation->id, 'ctype' => $modelLocation->type,
					'title' => $location->title,
					'country' => $location->countryName, 'province' => $location->provinceName,
					'region' => $location->regionName, 'city' => $location->cityName,
					'latitude' => $location->latitude, 'longitude' => $location->longitude, 'zoomLevel' => $location->zoomLevel,
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

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
