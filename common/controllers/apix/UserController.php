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
use cmsgears\core\common\models\resources\File;

use cmsgears\core\common\controllers\base\Controller;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * UserController handles the ajax requests specific to User model.
 *
 * @since 1.0.0
 */
class UserController extends Controller {

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
		$this->metaService			= Yii::$app->factory->get( 'modelMetaService' );
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
					// Metas
					'add-meta' => [ 'permission' => $this->crudPermission ],
					'update-meta' => [ 'permission' => $this->crudPermission ],
					'toggle-meta' => [ 'permission' => $this->crudPermission ],
					'delete-meta' => [ 'permission' => $this->crudPermission ],
					'settings' => [ 'permission' => $this->crudPermission ],
					// Options
					'assign-option' => [ 'permission' => $this->crudPermission ],
					'remove-option' => [ 'permission' => $this->crudPermission ],
					'delete-option' => [ 'permission' => $this->crudPermission ],
					'toggle-option' => [ 'permission' => $this->crudPermission ],
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
					// Metas
					'add-meta' => [ 'post' ],
					'update-meta' => [ 'post' ],
					'toggle-meta' => [ 'post' ],
					'delete-meta' => [ 'post' ],
					'settings' => [ 'post' ],
					// Options
					'assign-option' => [ 'post' ],
					'remove-option' => [ 'post' ],
					'delete-option' => [ 'post' ],
					'toggle-option' => [ 'post' ],
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

		return [
			// Avatar
			'assign-avatar' => [ 'class' => 'cmsgears\core\common\actions\content\avatar\Assign' ],
			'clear-avatar' => [ 'class' => 'cmsgears\core\common\actions\content\avatar\Clear' ],
			// Metas
			'add-meta' => [ 'class' => 'cmsgears\core\common\actions\meta\Create', 'model' => Yii::$app->user->identity ],
			'update-meta' => [ 'class' => 'cmsgears\core\common\actions\meta\Update', 'model' => Yii::$app->user->identity ],
			'toggle-meta' => [ 'class' => 'cmsgears\core\common\actions\meta\Toggle', 'model' => Yii::$app->user->identity ],
			'delete-meta' => [ 'class' => 'cmsgears\core\common\actions\meta\Delete', 'model' => Yii::$app->user->identity ],
			'settings' => [ 'class' => 'cmsgears\core\common\actions\meta\UpdateMultiple', 'model' => Yii::$app->user->identity ],
			// Options
			'assign-option' => [ 'class' => 'cmsgears\core\common\actions\option\Assign', 'model' => Yii::$app->user->identity ],
			'remove-option' => [ 'class' => 'cmsgears\core\common\actions\option\Remove', 'model' => Yii::$app->user->identity ],
			'delete-option' => [ 'class' => 'cmsgears\core\common\actions\option\Delete', 'model' => Yii::$app->user->identity ],
			'toggle-option' => [ 'class' => 'cmsgears\core\common\actions\option\Toggle', 'model' => Yii::$app->user->identity ],
			// Address
			'get-address' => [ 'class' => 'cmsgears\core\common\actions\address\Read', 'model' => Yii::$app->user->identity ],
			'add-address' => [ 'class' => 'cmsgears\core\common\actions\address\Create', 'model' => Yii::$app->user->identity ],
			'update-address' => [ 'class' => 'cmsgears\core\common\actions\address\Update', 'model' => Yii::$app->user->identity ],
			'delete-address' => [ 'class' => 'cmsgears\core\common\actions\address\Delete', 'model' => Yii::$app->user->identity ],
			// Data Object - Use current logged in user to update the config and settings
			'set-data' => [ 'class' => 'cmsgears\core\common\actions\data\data\Set', 'model' => Yii::$app->user->identity ],
			'remove-data' => [ 'class' => 'cmsgears\core\common\actions\data\data\Remove', 'model' => Yii::$app->user->identity ],
			'set-attribute' => [ 'class' => 'cmsgears\core\common\actions\data\attribute\Set', 'model' => Yii::$app->user->identity ],
			'remove-attribute' => [ 'class' => 'cmsgears\core\common\actions\data\attribute\Remove', 'model' => Yii::$app->user->identity ],
			'set-config' => [ 'class' => 'cmsgears\core\common\actions\data\config\Set', 'model' => Yii::$app->user->identity ],
			'remove-config' => [ 'class' => 'cmsgears\core\common\actions\data\config\Remove', 'model' => Yii::$app->user->identity ],
			'set-setting' => [ 'class' => 'cmsgears\core\common\actions\data\setting\Set', 'model' => Yii::$app->user->identity ],
			'remove-setting' => [ 'class' => 'cmsgears\core\common\actions\data\setting\Remove', 'model' => Yii::$app->user->identity ]
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// UserController ------------------------

	public function actionProfile() {

		// Find Model
		$user = Yii::$app->user->getIdentity();

		// Update/Render if exist
		if( isset( $user ) ) {

			// Scenario
			$user->setScenario( 'profile' );

			if( $user->load( Yii::$app->request->post(), $user->getClassName() ) && $user->validate() ) {

				// Avatar
				$avatar = File::loadFile( $user->avatar, 'Avatar' );

				// Update User
				$this->modelService->update( $user, [ 'avatar' => $avatar ] );

				// Prepare response data
				$data = [
					'email' => $user->email, 'username' => $user->username, 'firstName' => $user->firstName,
					'lastName' => $user->lastName, 'gender' => $user->getGenderStr(), 'phone' => $user->phone
				];

				// Trigger Ajax Success
				return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
			}

			// Generate Errors
			$errors = AjaxUtil::generateErrorMessage( $user );

			// Trigger Ajax Failure
			return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
		}

		// Model not found
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), [ 'session' => true ] );
	}

	public function actionAccount() {

		// Find Model
		$user = Yii::$app->user->getIdentity();

		// Update/Render if exist
		if( isset( $user ) ) {

			$model = new ResetPassword();

			// Old password required if it was already set
			if( !empty( $user->passwordHash ) ) {

				$model->setScenario( 'oldPassword' );
			}

			if( $model->load( Yii::$app->request->post(), 'ResetPassword' ) && $model->validate() ) {

				// Update User
				if( $this->modelService->resetPassword( $user, $model, false ) ) {

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

	public function actionAddress( $type ) {

		$user = Yii::$app->user->getIdentity();

		// Update/Render if exist
		if( isset( $user ) ) {

			$address = $this->addressService->getModelObject();

			if( $address->load( Yii::$app->request->post(), $address->getClassName() ) && $address->validate() ) {

				$modelAddress = $this->modelAddressService->createOrUpdateByType( $address, [ 'parentId' => $user->id, 'parentType' => CoreGlobal::TYPE_USER, 'type' => $type ] );

				$address = $modelAddress->model;

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

		// Model not found
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), [ 'session' => true ] );
	}

}
