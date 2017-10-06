<?php
namespace cmsgears\core\common\actions\address;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * The Read action find model address for the given id and return the address.
 */
class Read extends \cmsgears\core\common\actions\base\ModelAction {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $parent 	= true;

	// Attributes having UTF-8 Encoding
	public $returnAttributes = [
		'id', 'countryId', 'provinceId', 'cityId', 'title', 'line1', 'line2', 'line3',
		'countryName', 'provinceName', 'cityName', 'zip', 'subZip',
		'firstName', 'lastName', 'phone', 'email', 'fax', 'website',
		'latitude', 'longitude', 'zoomLevel' ];

	// Protected --------------

	protected $modelAddressService;

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->modelAddressService	= Yii::$app->factory->get( 'modelAddressService' );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Read ----------------------------------

	public function run( $cid ) {

		if( isset( $this->model ) ) {

			$modelAddress	= $this->modelAddressService->getById( $cid );

			if( isset( $modelAddress ) && $modelAddress->checkParent( $this->model->id, $this->parentType ) ) {

				$address	= $modelAddress->model;
				$data		= $address->getAttributeArray( $this->returnAttributes );

				// Trigger Ajax Success
				return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
			}
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}
