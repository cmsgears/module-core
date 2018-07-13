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
use cmsgears\core\common\models\resources\ModelMeta;

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

	// Protected --------------

	protected $addressService;
	protected $modelAddressService;

	protected $modelMetaService;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->crudPermission = CoreGlobal::PERM_USER;

		$this->modelService			= Yii::$app->factory->get( 'userService' );
		$this->addressService		= Yii::$app->factory->get( 'addressService' );
		$this->modelAddressService	= Yii::$app->factory->get( 'modelAddressService' );
		$this->modelMetaService		= Yii::$app->factory->get( 'modelMetaService' );
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
					'avatar' => [ 'permission' => $this->crudPermission ],
					'clear-avatar' => [ 'permission' => $this->crudPermission ],
					'get-address' => [ 'permission' => $this->crudPermission ],
					'add-address' => [ 'permission' => $this->crudPermission ],
					'update-address' => [ 'permission' => $this->crudPermission ],
					'delete-address' => [ 'permission' => $this->crudPermission ],
					'profile' => [ 'permission' => $this->crudPermission ],
					'account' => [ 'permission' => $this->crudPermission ],
					'address' => [ 'permission' => $this->crudPermission ],
					'settings' => [ 'permission' => $this->crudPermission ],
					'set-config' => [ 'permission' => $this->crudPermission ],
					'remove-config' => [ 'permission' => $this->crudPermission ],
					'set-setting' => [ 'permission' => $this->crudPermission ],
					'remove-setting' => [ 'permission' => $this->crudPermission ]
				]
			],
			'verbs' => [
				'class' => VerbFilter::class,
				'actions' => [
					'avatar' => [ 'post' ],
					'clear-avatar' => [ 'post' ],
					'get-address' => [ 'post' ],
					'add-address' => [ 'post' ],
					'update-address' => [ 'post' ],
					'delete-address' => [ 'post' ],
					'profile' => [ 'post' ],
					'account' => [ 'post' ],
					'address' => [ 'post' ],
					'settings' => [ 'post' ],
					'set-config' => [ 'post' ],
					'remove-config' => [ 'post' ],
					'set-setting' => [ 'post' ],
					'remove-setting' => [ 'post' ]
				]
			]
		];
	}

	// yii\base\Controller ----

	public function actions() {

		return [
			'avatar' => [ 'class' => 'cmsgears\core\common\actions\content\Avatar' ],
			'clear-avatar' => [ 'class' => 'cmsgears\core\common\actions\content\ClearAvatar' ],
			'get-address' => [ 'class' => 'cmsgears\core\common\actions\address\Read', 'parent' => true ],
			'add-address' => [ 'class' => 'cmsgears\core\common\actions\address\Create', 'parent' => true ],
			'update-address' => [ 'class' => 'cmsgears\core\common\actions\address\Update', 'parent' => true ],
			'delete-address' => [ 'class' => 'cmsgears\core\common\actions\address\Delete', 'parent' => true ],
			// Use current logged in user to update the config and settings
			'set-config' => [ 'class' => 'cmsgears\core\common\actions\data\SetConfig', 'model' => Yii::$app->user->identity ],
			'remove-config' => [ 'class' => 'cmsgears\core\common\actions\data\RemoveConfig', 'model' => Yii::$app->user->identity ],
			'set-setting' => [ 'class' => 'cmsgears\core\common\actions\data\SetSetting', 'model' => Yii::$app->user->identity ],
			'remove-setting' => [ 'class' => 'cmsgears\core\common\actions\data\RemoveSetting', 'model' => Yii::$app->user->identity ]
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

				// Update User
				$this->modelService->update( $user );

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
		$user	= Yii::$app->user->getIdentity();

		// Update/Render if exist
		if( isset( $user ) ) {

			$model	= new ResetPassword();

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

				$modelAddress	= $this->modelAddressService->createOrUpdateByType( $address, [ 'parentId' => $user->id, 'parentType' => CoreGlobal::TYPE_USER, 'type' => $type ] );
				$address		= $modelAddress->model;

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

	public function actionSettings() {

		$user		= Yii::$app->user->getIdentity();
		$modelMetas	= Yii::$app->request->post( 'ModelMeta' );
		$count		= count( $modelMetas );
		$metas		= [];

		for( $i = 0; $i < $count; $i++ ) {

			$meta	= $modelMetas[ $i ];
			$meta	= $this->modelMetaService->initByNameType( $user->id, CoreGlobal::TYPE_USER, $meta[ 'name' ], $meta[ 'type' ] );

			$metas[] = $meta;
		}

		// Load models
		if( ModelMeta::loadMultiple( $metas, Yii::$app->request->post(), 'ModelMeta' ) && ModelMeta::validateMultiple( $metas ) ) {

			$this->modelService->updateModelMetas( $user, $metas );

			$data = [];

			foreach( $metas as $meta ) {

				$data[]	= $meta->getFieldInfo();
			}

			// Trigger Ajax Success
			return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ) );
	}

}
