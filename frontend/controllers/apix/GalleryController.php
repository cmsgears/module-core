<?php
namespace cmsgears\core\frontend\controllers\apix;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\AjaxUtil;

class GalleryController extends \cmsgears\core\frontend\controllers\base\Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->crudPermission	= CoreGlobal::PERM_USER;

		$this->modelService		= Yii::$app->factory->get( 'galleryService' );
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
					'create-item' => [ 'permission' => $this->crudPermission, 'filters' => [ 'owner' => [ 'slug' => true ] ] ],
					'delete-item' => [ 'permission' => $this->crudPermission, 'filters' => [ 'owner' => [ 'slug' => true ] ] ]
				]
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'create-item' => [ 'post' ],
					'delete-item' => [ 'post' ]
				]
			]
		];
	}

	// yii\base\Controller ----

	public function actions() {

		return [
			'create-item' => [ 'class' => 'cmsgears\core\common\actions\gallery\CreateItem' ],
			'delete-item' => [ 'class' => 'cmsgears\core\common\actions\gallery\DeleteItem' ]
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// GalleryController ---------------------
}
