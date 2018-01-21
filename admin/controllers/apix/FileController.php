<?php
namespace cmsgears\core\admin\controllers\apix;

// Yii Imports
use Yii;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class FileController extends \cmsgears\core\admin\controllers\base\Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function __construct( $id, $module, $config = [] ) {

		parent::__construct( $id, $module, $config );

		$this->enableCsrfValidation = false;
	}

	public function init() {

		parent::init();

		// Permissions
		$this->crudPermission 	= CoreGlobal::PERM_ADMIN;

		// Services
		$this->modelService		= Yii::$app->factory->get( 'fileService' );
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
					'file-handler'  => [ 'permission' => $this->crudPermission ],
					'bulk' => [ 'permission' => $this->crudPermission ],
					'delete' => [ 'permission' => $this->crudPermission ]
				]
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'file-handler'  => [ 'post' ],
					'bulk' => [ 'post' ],
					'delete' => [ 'post' ]
				]
			]
		];
	}

	// yii\base\Controller ----

	public function actions() {

		return [
			'file-handler' => [ 'class' => 'cmsgears\core\common\actions\file\FileHandler' ],
			'bulk' => [ 'class' => 'cmsgears\core\common\actions\grid\Bulk' ],
			'delete' => [
				'class' => 'cmsgears\core\common\actions\grid\Delete',
				'config' => [ 'admin' => true ]
			]
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// FileController ------------------------

}
