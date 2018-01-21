<?php
namespace cmsgears\core\admin\controllers\apix;

// Yii Imports
use Yii;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class GalleryController extends \cmsgears\core\admin\controllers\base\Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	private $fileService;

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Permissions
		$this->crudPermission	= CoreGlobal::PERM_GALLERY_ADMIN;

		// Services
		$this->modelService		= Yii::$app->factory->get( 'galleryService' );

		$this->fileService		= Yii::$app->factory->get( 'fileService' );
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
					'createItem' => [ 'permission' => $this->crudPermission ],
					'updateItem' => [ 'permission' => $this->crudPermission ],
					'deleteItem' => [ 'permission' => $this->crudPermission ],
					'bulk' => [ 'permission' => $this->crudPermission ],
					'delete' => [ 'permission' => $this->crudPermission ]
				]
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'createItem' => [ 'post' ],
					'updateItem' => [ 'post' ],
					'deleteItem' => [ 'post' ],
					'bulk' => [ 'post' ],
					'delete' => [ 'post' ]
				]
			]
		];
	}

	// yii\base\Controller ----

	public function actions() {

		return [
			'create-item' => [ 'class' => 'cmsgears\core\common\actions\gallery\CreateItem' ],
			'update-item' => [ 'class' => 'cmsgears\core\common\actions\gallery\UpdateItem' ],
			'delete-item' => [ 'class' => 'cmsgears\core\common\actions\gallery\UpdateItem' ],
			'bulk' => [ 'class' => 'cmsgears\core\common\actions\grid\Bulk' ],
			'delete' => [ 'class' => 'cmsgears\core\common\actions\grid\Delete' ]
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// GalleryController ---------------------

}
