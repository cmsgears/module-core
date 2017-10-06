<?php
namespace cmsgears\core\common\controllers\apix;

// Yii Imports
use Yii;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\forms\ResetPassword;
use cmsgears\core\common\models\resources\Address;
use cmsgears\core\common\models\resources\ModelMeta;

use cmsgears\core\common\utilities\AjaxUtil;

class UserController extends \cmsgears\core\common\controllers\base\Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	protected $modelAddressService;

	protected $modelMetaService;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->crudPermission		= CoreGlobal::PERM_USER;

		$this->modelService			= Yii::$app->factory->get( 'userService' );
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
					'profile' => [ 'permission' => $this->crudPermission ],
					'account' => [ 'permission' => $this->crudPermission ],
					'address' => [ 'permission' => $this->crudPermission ],
					'settings' => [ 'permission' => $this->crudPermission ],
					'set-meta' => [ 'permission' => $this->crudPermission ],
					'remove-meta' => [ 'permission' => $this->crudPermission ]
				]
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'avatar' => [ 'post' ],
					'profile' => [ 'post' ],
					'account' => [ 'post' ],
					'address' => [ 'post' ],
					'settings' => [ 'post' ],
					'set-meta' => [ 'post' ],
					'remove-meta' => [ 'post' ]
				]
			]
		];
	}

	// yii\base\Controller ----

	public function actions() {

		return [
			'avatar' => [ 'class' => 'cmsgears\core\common\actions\content\UpdateAvatar' ],
			'set-meta' => [ 'class' => 'cmsgears\core\common\actions\data\SetMeta', 'model' => Yii::$app->user->identity ],
			'remove-meta' => [ 'class' => 'cmsgears\core\common\actions\data\RemoveMeta', 'model' => Yii::$app->user->identity ]
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// UserController ------------------------

	public function actionProfile() {

		// Find Model
		$user	= Yii::$app->user->getIdentity();

		$user->setScenario( 'profile' );

		if( $user->load( Yii::$app->request->post(), 'User' ) && $user->validate() ) {

			// Update User and Site Member
			$this->modelService->update( $user );

			$data	= [
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

	public function actionAccount() {

		// Find Model
		$user	= Yii::$app->user->getIdentity();
		$model	= new ResetPassword();

		if( $model->load( Yii::$app->request->post(), 'ResetPassword' ) && $model->validate() ) {

			// Update User and Site Member
			if( $this->modelService->resetPassword( $user, $model, false ) ) {

				$data	= [ 'email' => $user->email, 'username' => $user->username ];

				// Trigger Ajax Success
				return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
			}
		}

		// Generate Errors
		$errors = AjaxUtil::generateErrorMessage( $model );

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
	}

	public function actionAddress( $type ) {

		$user		= Yii::$app->user->getIdentity();
		$address	= new Address();

		if( $address->load( Yii::$app->request->post(), 'Address' ) && $address->validate() ) {

		$modelAddress	= $this->modelAddressService->createOrUpdateByType( $address, [ 'parentId' => $user->id, 'parentType' => CoreGlobal::TYPE_USER, 'type' => $type ] );
		$address		= $modelAddress->model;

		$data	= [
					'line1' => $address->line1, 'line2' => $address->line2, 'cityName' => $address->cityName,
					'country' => $address->countryName, 'province' => $address->provinceName, 'phone' => $address->phone, 'zip' => $address->zip
				];

			// Trigger Ajax Success
			return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
		}

		// Generate Errors
		$errors = AjaxUtil::generateErrorMessage( $address );

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
	}

	public function actionSettings() {

		$user			= Yii::$app->user->getIdentity();
		$modelMetas		= Yii::$app->request->post( 'ModelMeta' );
		$count			= count( $modelMetas );
		$metas			= [];

		for ( $i = 0; $i < $count; $i++ ) {

			$meta		= $modelMetas[ $i ];
			$meta		= $this->modelMetaService->initByNameType( $user->id, CoreGlobal::TYPE_USER, $meta[ 'name' ], $meta[ 'type' ] );

			$metas[]	= $meta;
		}

		// Load SchoolItem models
		if( ModelMeta::loadMultiple( $metas, Yii::$app->request->post(), 'ModelMeta' ) && ModelMeta::validateMultiple( $metas ) ) {

			$this->modelService->updateModelMetas( $user, $metas );

			$data	= [];

			foreach ( $metas as $meta ) {

				$data[]	= $meta->getFieldInfo();
			}

			// Trigger Ajax Success
			return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ) );
	}
}
