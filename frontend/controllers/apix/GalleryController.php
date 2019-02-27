<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\frontend\controllers\apix;

// Yii Imports
use Yii;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\frontend\controllers\base\Controller;

/**
 * GalleryController handles the ajax requests specific to Gallery Model.
 *
 * @since 1.0.0
 */
class GalleryController extends Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Permission
		$this->crudPermission = CoreGlobal::PERM_USER;

		// Services
		$this->modelService = Yii::$app->factory->get( 'galleryService' );
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
					'get-item' => [ 'permission' => $this->crudPermission, 'filters' => [ 'owner' => [ 'slug' => true ] ] ],
					'add-item' => [ 'permission' => $this->crudPermission, 'filters' => [ 'owner' => [ 'slug' => true ] ] ],
					'update-item' => [ 'permission' => $this->crudPermission, 'filters' => [ 'owner' => [ 'slug' => true ] ] ],
					'delete-item' => [ 'permission' => $this->crudPermission, 'filters' => [ 'owner' => [ 'slug' => true ] ] ]
				]
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'get-item' => [ 'post' ],
					'add-item' => [ 'post' ],
					'update-item' => [ 'post' ],
					'delete-item' => [ 'post' ]
				]
			]
		];
	}

	// yii\base\Controller ----

	public function actions() {

		return [
			'get-item' => [ 'class' => 'cmsgears\core\common\actions\gallery\ReadItem' ],
			'add-item' => [ 'class' => 'cmsgears\core\common\actions\gallery\CreateItem' ],
			'update-item' => [ 'class' => 'cmsgears\core\common\actions\gallery\UpdateItem' ],
			'delete-item' => [ 'class' => 'cmsgears\core\common\actions\gallery\DeleteItem' ]
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// GalleryController ---------------------

}
