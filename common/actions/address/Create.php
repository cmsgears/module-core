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
 * The Create action creates model address, address and associate the model address to parent.
 */
class Create extends \cmsgears\core\common\actions\base\ModelAction {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $parent		= true;

	public $scenario	= 'location';

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

	// Create --------------------------------

	public function run() {

		if( isset( $this->model ) ) {

			$address = new Address();

			if( isset( $this->scenario ) ) {

				$address->setScenario( $this->scenario );

				if( $this->scenario == 'location' && $address->hasAttribute( 'geoPoint' ) && empty( $address->geoPoint ) ) {

					$address->geoPoint = new Expression( "ST_GeometryFromText( CONCAT( 'POINT(', 0, ' ', 0, ')' ) )" );
				}
			}

			if( $address->load( Yii::$app->request->post(), 'Address' ) && $address->validate() ) {

				$type	= isset( $this->modelType ) ? $this->modelType : Address::TYPE_DEFAULT;

				// Address - Create
				$modelAddress	= $this->modelAddressService->create( $address, [ 'parentId' => $this->model->id, 'parentType' => $this->parentType, 'type' => $type ] );
				$address		= $modelAddress->model;

				$data	= [ 'cid' => $modelAddress->id, 'title' => $address->title, 'value' => $address->toString() ];

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