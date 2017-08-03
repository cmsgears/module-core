<?php
namespace cmsgears\core\common\actions\address;

// Yii Imports
use Yii;
use yii\db\Expression;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\resources\Address;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * Create Address
 *
 * The controller must provide appropriate model service.
 */
class Create extends \cmsgears\core\common\actions\base\ModelAction {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $scenario = 'location';

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

	// CreateMeta ----------------------------

	/**
	 * Create Meta for given parent slug and parent type.
	 */
	public function run() {

		if( isset( $this->model ) ) {

			$address = new Address();

			if( isset( $this->scenario ) ) {

				$address->setScenario( $this->scenario );

				if( $this->scenario == 'location' && $address->hasAttribute( 'geoPoint' ) && empty( $address->geoPoint ) ) {

					$address->geoPoint = new Expression( "ST_GeometryFromText( CONCAT( 'POINT(', 0, ' ', 0, ')' ) )" );
				}
			}

			$parent		= $this->model;
			$parentType	= $this->parentType;

			if( $address->load( Yii::$app->request->post(), 'Address' ) && $address->validate() ) {

				$type		= Yii::$app->request->post( 'addressType' );
				$type		= isset( $type ) ? $type : Address::TYPE_DEFAULT;

				// Address - Create
				$address	= $this->modelAddressService->create( $address, [ 'parentId' => $parent->id, 'parentType' => $parentType, 'type' => $type ] );
				$address	= $address->address;

				$data		= [ 'id' => $address->id, 'title' => $address->title, 'value' => $address->toString() ];

				// Trigger Ajax Success
				return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
			}

			// Generate Errors
			$errors = AjaxUtil::generateErrorMessage( $address );

			// Trigger Ajax Failure
			return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}
