<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\admin\controllers\apix;

// Yii Imports
use Yii;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * GalleryController provide actions specific to gallery model.
 *
 * @since 1.0.0
 */
class GalleryController extends \cmsgears\core\admin\controllers\apix\base\Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	private $fileService;

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Permission
		$this->crudPermission = CoreGlobal::PERM_GALLERY_ADMIN;

		// Services
		$this->modelService	= Yii::$app->factory->get( 'galleryService' );

		$this->fileService	= Yii::$app->factory->get( 'fileService' );
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
					'update' => [ 'permission' => $this->crudPermission ],
					'get-gallery-item' => [ 'permission' => $this->crudPermission ],
					'add-gallery-item' => [ 'permission' => $this->crudPermission ],
					'update-gallery-item' => [ 'permission' => $this->crudPermission ],
					'delete-gallery-item' => [ 'permission' => $this->crudPermission ],
					'bulk' => [ 'permission' => $this->crudPermission ],
					'generic' => [ 'permission' => $this->crudPermission ],
					'delete' => [ 'permission' => $this->crudPermission ]
				]
			],
			'verbs' => [
				'class' => VerbFilter::class,
				'actions' => [
					'update' => [ 'post' ],
					'get-gallery-item' => [ 'post' ],
					'add-gallery-item' => [ 'post' ],
					'update-gallery-item' => [ 'post' ],
					'delete-gallery-item' => [ 'post' ],
					'bulk' => [ 'post' ],
					'generic' => [ 'post' ],
					'delete' => [ 'post' ]
				]
			]
		];
	}

	// yii\base\Controller ----

	public function actions() {

		return [
			// Gallery
			'update' => [ 'class' => 'cmsgears\core\common\actions\gallery\Update', 'admin' => true ],
			'get-gallery-item' => [ 'class' => 'cmsgears\core\common\actions\gallery\item\Read', 'admin' => true ],
			'add-gallery-item' => [ 'class' => 'cmsgears\core\common\actions\gallery\item\Create', 'admin' => true ],
			'update-gallery-item' => [ 'class' => 'cmsgears\core\common\actions\gallery\item\Update', 'admin' => true ],
			'delete-gallery-item' => [ 'class' => 'cmsgears\core\common\actions\gallery\item\Delete', 'admin' => true ],
			'bulk' => [ 'class' => 'cmsgears\core\common\actions\grid\Bulk', 'admin' => true ],
			'generic' => [ 'class' => 'cmsgears\core\common\actions\grid\Generic' ],
			'delete' => [ 'class' => 'cmsgears\core\common\actions\grid\Delete' ]
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// GalleryController ---------------------

}
