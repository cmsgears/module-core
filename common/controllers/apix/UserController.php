<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\controllers\apix;

// Yii Imports
use Yii;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\forms\ResetPassword;
use cmsgears\core\common\models\resources\Address;
use cmsgears\core\common\models\resources\File;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * UserController handles the ajax requests specific to User model.
 *
 * @since 1.0.0
 */
class UserController extends \cmsgears\core\common\controllers\apix\base\Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	public $metaService;

	// Protected --------------

	protected $addressService;
	protected $modelAddressService;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->crudPermission = CoreGlobal::PERM_USER;

		$this->modelService			= Yii::$app->factory->get( 'userService' );
		$this->addressService		= Yii::$app->factory->get( 'addressService' );
		$this->modelAddressService	= Yii::$app->factory->get( 'modelAddressService' );
		$this->metaService			= Yii::$app->factory->get( 'userMetaService' );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	public function behaviors() {

		return [
			'rbac' => [
				'class' => Yii::$app->core->getRbacFilterClass(),
				'actions' => [
					// Avatar
					'assign-avatar' => [ 'permission' => $this->crudPermission ],
					'clear-avatar' => [ 'permission' => $this->crudPermission ],
					// Banner
					'assign-banner' => [ 'permission' => $this->crudPermission ],
					'clear-banner' => [ 'permission' => $this->crudPermission ],
					// Video
					'assign-video' => [ 'permission' => $this->crudPermission ],
					'clear-video' => [ 'permission' => $this->crudPermission ],
					// Gallery
					'update-gallery' => [ 'permission' => $this->crudPermission ],
					'get-gallery-item' => [ 'permission' => $this->crudPermission ],
					'add-gallery-item' => [ 'permission' => $this->crudPermission ],
					'update-gallery-item' => [ 'permission' => $this->crudPermission ],
					'delete-gallery-item' => [ 'permission' => $this->crudPermission ],
					// Categories
					'assign-category' => [ 'permission' => $this->crudPermission ],
					'remove-category' => [ 'permission' => $this->crudPermission ],
					'toggle-category' => [ 'permission' => $this->crudPermission ],
					// Options
					'assign-option' => [ 'permission' => $this->crudPermission ],
					'remove-option' => [ 'permission' => $this->crudPermission ],
					'delete-option' => [ 'permission' => $this->crudPermission ],
					'toggle-option' => [ 'permission' => $this->crudPermission ],
					// Metas
					'add-meta' => [ 'permission' => $this->crudPermission ],
					'update-meta' => [ 'permission' => $this->crudPermission ],
					'toggle-meta' => [ 'permission' => $this->crudPermission ],
					'delete-meta' => [ 'permission' => $this->crudPermission ],
					'settings' => [ 'permission' => $this->crudPermission ],
					// Address
					'get-address' => [ 'permission' => $this->crudPermission ],
					'add-address' => [ 'permission' => $this->crudPermission ],
					'update-address' => [ 'permission' => $this->crudPermission ],
					'delete-address' => [ 'permission' => $this->crudPermission ],
					// Data Object
					'set-data' => [ 'permission' => $this->crudPermission ],
					'remove-data' => [ 'permission' => $this->crudPermission ],
					'set-attribute' => [ 'permission' => $this->crudPermission ],
					'remove-attribute' => [ 'permission' => $this->crudPermission ],
					'set-config' => [ 'permission' => $this->crudPermission ],
					'remove-config' => [ 'permission' => $this->crudPermission ],
					'set-setting' => [ 'permission' => $this->crudPermission ],
					'remove-setting' => [ 'permission' => $this->crudPermission ],
					// Controller
					'profile' => [ 'permission' => $this->crudPermission ],
					'account' => [ 'permission' => $this->crudPermission ],
					'address' => [ 'permission' => $this->crudPermission ]
				]
			],
			'verbs' => [
				'class' => VerbFilter::class,
				'actions' => [
					// Avatar
					'assign-avatar' => [ 'post' ],
					'clear-avatar' => [ 'post' ],
					// Banner
					'assign-banner' => [ 'post' ],
					'clear-banner' => [ 'post' ],
					// Video
					'assign-video' => [ 'post' ],
					'clear-video' => [ 'post' ],
					// Gallery
					'update-gallery' => [ 'post' ],
					'get-gallery-item' => [ 'post' ],
					'add-gallery-item' => [ 'post' ],
					'update-gallery-item' => [ 'post' ],
					'delete-gallery-item' => [ 'post' ],
					// Categories
					'assign-category' => [ 'post' ],
					'remove-category' => [ 'post' ],
					'toggle-category' => [ 'post' ],
					// Options
					'assign-option' => [ 'post' ],
					'remove-option' => [ 'post' ],
					'delete-option' => [ 'post' ],
					'toggle-option' => [ 'post' ],
					// Metas
					'add-meta' => [ 'post' ],
					'update-meta' => [ 'post' ],
					'toggle-meta' => [ 'post' ],
					'delete-meta' => [ 'post' ],
					'settings' => [ 'post' ],
					// Address
					'get-address' => [ 'post' ],
					'add-address' => [ 'post' ],
					'update-address' => [ 'post' ],
					'delete-address' => [ 'post' ],
					// Data Object
					'set-data' => [ 'post' ],
					'remove-data' => [ 'post' ],
					'set-attribute' => [ 'post' ],
					'remove-attribute' => [ 'post' ],
					'set-config' => [ 'post' ],
					'remove-config' => [ 'post' ],
					'set-setting' => [ 'post' ],
					'remove-setting' => [ 'post' ],
					// Controller
					'profile' => [ 'post' ],
					'account' => [ 'post' ],
					'address' => [ 'post' ]
				]
			]
		];
	}

	// yii\base\Controller ----

	public function actions() {

		$user = Yii::$app->core->getUser();

		return [
			// Avatar
			'assign-avatar' => [ 'class' => 'cmsgears\core\common\actions\content\avatar\Assign', 'model' => $user ],
			'clear-avatar' => [ 'class' => 'cmsgears\core\common\actions\content\avatar\Clear', 'model' => $user ],
			// Banner
			'assign-banner' => [ 'class' => 'cmsgears\core\common\actions\content\banner\Assign', 'model' => $user ],
			'clear-banner' => [ 'class' => 'cmsgears\core\common\actions\content\banner\Clear', 'model' => $user ],
			// Video
			'assign-video' => [ 'class' => 'cmsgears\core\common\actions\content\video\Assign', 'model' => $user ],
			'clear-video' => [ 'class' => 'cmsgears\core\common\actions\content\video\Clear', 'model' => $user ],
			// Gallery
			'update-gallery' => [ 'class' => 'cmsgears\core\common\actions\gallery\Update', 'model' => $user, 'user' => true ],
			'get-gallery-item' => [ 'class' => 'cmsgears\core\common\actions\gallery\item\Read', 'model' => $user, 'user' => true ],
			'add-gallery-item' => [ 'class' => 'cmsgears\core\common\actions\gallery\item\Create', 'model' => $user, 'user' => true ],
			'update-gallery-item' => [ 'class' => 'cmsgears\core\common\actions\gallery\item\Update', 'model' => $user, 'user' => true ],
			'delete-gallery-item' => [ 'class' => 'cmsgears\core\common\actions\gallery\item\Delete', 'model' => $user, 'user' => true ],
			// Categories
			'assign-category' => [ 'class' => 'cmsgears\core\common\actions\category\mapper\Assign', 'model' => $user ],
			'remove-category' => [ 'class' => 'cmsgears\core\common\actions\category\mapper\Remove', 'model' => $user ],
			'toggle-category' => [ 'class' => 'cmsgears\core\common\actions\category\mapper\Toggle', 'model' => $user ],
			// Options
			'assign-option' => [ 'class' => 'cmsgears\core\common\actions\option\mapper\Assign', 'model' => $user ],
			'remove-option' => [ 'class' => 'cmsgears\core\common\actions\option\mapper\Remove', 'model' => $user ],
			'delete-option' => [ 'class' => 'cmsgears\core\common\actions\option\mapper\Delete', 'model' => $user ],
			'toggle-option' => [ 'class' => 'cmsgears\core\common\actions\option\mapper\Toggle', 'model' => $user ],
			// Metas
			'add-meta' => [ 'class' => 'cmsgears\core\common\actions\meta\Create', 'model' => $user ],
			'update-meta' => [ 'class' => 'cmsgears\core\common\actions\meta\Update', 'model' => $user ],
			'toggle-meta' => [ 'class' => 'cmsgears\core\common\actions\meta\Toggle', 'model' => $user ],
			'delete-meta' => [ 'class' => 'cmsgears\core\common\actions\meta\Delete', 'model' => $user ],
			'settings' => [ 'class' => 'cmsgears\core\common\actions\meta\UpdateMultiple', 'model' => $user ],
			// Address
			'get-address' => [ 'class' => 'cmsgears\core\common\actions\address\mapper\Read', 'model' => $user ],
			'add-address' => [ 'class' => 'cmsgears\core\common\actions\address\mapper\Create', 'model' => $user ],
			'update-address' => [ 'class' => 'cmsgears\core\common\actions\address\mapper\Update', 'model' => $user ],
			'delete-address' => [ 'class' => 'cmsgears\core\common\actions\address\mapper\Delete', 'model' => $user ],
			// Data Object - Use current logged in user to update the config and settings
			'set-data' => [ 'class' => 'cmsgears\core\common\actions\data\data\Set', 'model' => $user ],
			'remove-data' => [ 'class' => 'cmsgears\core\common\actions\data\data\Remove', 'model' => $user ],
			'set-attribute' => [ 'class' => 'cmsgears\core\common\actions\data\attribute\Set', 'model' => $user ],
			'remove-attribute' => [ 'class' => 'cmsgears\core\common\actions\data\attribute\Remove', 'model' => $user ],
			'set-config' => [ 'class' => 'cmsgears\core\common\actions\data\config\Set', 'model' => $user ],
			'remove-config' => [ 'class' => 'cmsgears\core\common\actions\data\config\Remove', 'model' => $user ],
			'set-setting' => [ 'class' => 'cmsgears\core\common\actions\data\setting\Set', 'model' => $user ],
			'remove-setting' => [ 'class' => 'cmsgears\core\common\actions\data\setting\Remove', 'model' => $user ]
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// UserController ------------------------

	public function actionProfile() {

		// Find Model
		$user = Yii::$app->core->getUser();

		// Scenario
		$user->setScenario( 'profile' );

		if( $user->load( Yii::$app->request->post(), $user->getClassName() ) && $user->validate() ) {

			// Avatar
			$avatar = File::loadFile( $user->avatar, 'Avatar' );

			// Update User
			$this->modelService->update( $user, [ 'avatar' => $avatar ] );

			$thumbUrl = isset( $user->avatar ) ? $user->avatar->getThumbUrl() : null;

			// Prepare response data
			$data = [
				'email' => $user->email, 'username' => $user->username, 'firstName' => $user->firstName,
				'lastName' => $user->lastName, 'gender' => $user->getGenderStr(), 'phone' => $user->phone,
				'thumbUrl' => $thumbUrl
			];

			// Trigger Ajax Success
			return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
		}

		// Generate Errors
		$errors = AjaxUtil::generateErrorMessage( $user );

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
	}

	public function actionAccount() {

		// Find Model
		$user = Yii::$app->core->getUser();

		// Update/Render if exist
		if( isset( $user ) ) {

			$model = new ResetPassword();

			// Old password required if it was already set
			if( !empty( $user->passwordHash ) ) {

				$model->setScenario( 'oldPassword' );
			}

			if( $model->load( Yii::$app->request->post(), 'ResetPassword' ) && $model->validate() ) {

				// Update Password
				if( $this->modelService->resetPassword( $user, $model, false ) ) {

					// Send Password Change Mail
					Yii::$app->coreMailer->sendPasswordChangeMail( $user );

					$data = [ 'email' => $user->email, 'username' => $user->username ];

					// Trigger Ajax Success
					return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
				}
			}

			// Generate Errors
			$errors = AjaxUtil::generateErrorMessage( $model, [ 'modelClass' => 'ResetPassword' ] );

			// Trigger Ajax Failure
			return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
		}

		// Model not found
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), [ 'session' => true ] );
	}

	public function actionAddress( $ctype ) {

		$user = Yii::$app->core->getUser();

		$address = null;

		// Accept only selected type for a user
		if( !in_array( $ctype, [ Address::TYPE_PRIMARY, Address::TYPE_BILLING, Address::TYPE_MAILING, Address::TYPE_SHIPPING ] ) ) {

			return AjaxUtil::generateFailure( 'Address type not allowed.' );
		}

		switch( $ctype ) {

			case Address::TYPE_PRIMARY: {

				$address = $user->primaryAddress;

				break;
			}
			case Address::TYPE_BILLING: {

				$address = $user->billingAddress;

				break;
			}
			case Address::TYPE_MAILING: {

				$address = $user->mailingAddress;

				break;
			}
			case Address::TYPE_SHIPPING: {

				$address = $user->shippingAddress;

				break;
			}
		}

		if( empty( $address ) ) {

			$address = $this->addressService->getModelObject();
		}

		if( $address->load( Yii::$app->request->post(), $address->getClassName() ) && $address->validate() ) {

			// Create/Update Address
			$address = $this->addressService->createOrUpdate( $address );

			// Create Mapping
			$modelAddress = $this->modelAddressService->activateByParentModelId( $user->id, CoreGlobal::TYPE_USER, $address->id, $ctype );

			$data = [
				'line1' => $address->line1, 'line2' => $address->line2,
				'cityName' => $address->cityName, 'country' => $address->countryName,
				'province' => $address->provinceName, 'phone' => $address->phone,
				'zip' => $address->zip
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
