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
 * The Read action find model location for the given id and return the location.
 *
 * @since 1.0.0
 */
class Read extends \cmsgears\core\common\actions\base\ModelAction {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $parent = true;

	// Attributes having UTF-8 Encoding
	public $returnAttributes = [
		'id', 'countryId', 'provinceId', 'regionId', 'cityId', 'title',
		'countryName', 'provinceName', 'cityName', 'zip', 'subZip',
		'latitude', 'longitude', 'zoomLevel'
	];

	// Protected --------------

	protected $modelLocationService;

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->modelLocationService = Yii::$app->factory->get( 'modelLocationService' );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Read ----------------------------------

	public function run( $cid ) {

		if( isset( $this->model ) ) {

			$modelLocation = $this->modelLocationService->getById( $cid );

			if( isset( $modelLocation ) && $modelLocation->isParentValid( $this->model->id, $this->parentType ) ) {

				$location = $modelLocation->model;

				$ldata = $location->getAttributeArray( $this->returnAttributes );

				$data = [ 'cid' => $cid, 'location' => $ldata, 'type' => $modelLocation->type ];

				// Trigger Ajax Success
				return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
			}
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
