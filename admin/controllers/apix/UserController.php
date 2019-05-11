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
 * UserController provides actions specific to user model.
 *
 * @since 1.0.0
 */
class UserController extends \cmsgears\core\admin\controllers\base\Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	public $metaService;

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->crudPermission = CoreGlobal::PERM_IDENTITY;

		$this->modelService	= Yii::$app->factory->get( 'userService' );
		$this->metaService	= Yii::$app->factory->get( 'modelMetaService' );
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
					// Model
					'auto-search' => [ 'permission' => CoreGlobal::PERM_ADMIN ],
					'bulk' => [ 'permission' => $this->crudPermission ],
					'generic' => [ 'permission' => $this->crudPermission ],
					'delete' => [ 'permission' => $this->crudPermission ]
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
					// Model
					'auto-search' => [ 'post' ],
					'bulk' => [ 'post' ],
					'generic' => [ 'post' ],
					'delete' => [ 'post' ]
				]
			]
		];
	}

	// yii\base\Controller ----

	public function actions() {

		$user = Yii::$app->core->getUser();

		return [
			// Avatar
			'assign-avatar' => [ 'class' => 'cmsgears\core\common\actions\content\avatar\Assign' ],
			'clear-avatar' => [ 'class' => 'cmsgears\core\common\actions\content\avatar\Clear' ],
			// Banner
			'assign-banner' => [ 'class' => 'cmsgears\core\common\actions\content\banner\Assign' ],
			'clear-banner' => [ 'class' => 'cmsgears\core\common\actions\content\banner\Clear' ],
			// Video
			'assign-video' => [ 'class' => 'cmsgears\core\common\actions\content\video\Assign' ],
			'clear-video' => [ 'class' => 'cmsgears\core\common\actions\content\video\Clear' ],
			// Gallery
			'update-gallery' => [ 'class' => 'cmsgears\core\common\actions\gallery\Update' ],
			'get-gallery-item' => [ 'class' => 'cmsgears\core\common\actions\gallery\item\Read' ],
			'add-gallery-item' => [ 'class' => 'cmsgears\core\common\actions\gallery\item\Create' ],
			'update-gallery-item' => [ 'class' => 'cmsgears\core\common\actions\gallery\item\Update' ],
			'delete-gallery-item' => [ 'class' => 'cmsgears\core\common\actions\gallery\item\Delete' ],
			// Options
			'assign-option' => [ 'class' => 'cmsgears\core\common\actions\option\Assign' ],
			'remove-option' => [ 'class' => 'cmsgears\core\common\actions\option\Remove' ],
			'delete-option' => [ 'class' => 'cmsgears\core\common\actions\option\Delete' ],
			'toggle-option' => [ 'class' => 'cmsgears\core\common\actions\option\Toggle' ],
			// Metas
			'add-meta' => [ 'class' => 'cmsgears\core\common\actions\meta\Create' ],
			'update-meta' => [ 'class' => 'cmsgears\core\common\actions\meta\Update' ],
			'toggle-meta' => [ 'class' => 'cmsgears\core\common\actions\meta\Toggle' ],
			'delete-meta' => [ 'class' => 'cmsgears\core\common\actions\meta\Delete' ],
			'settings' => [ 'class' => 'cmsgears\core\common\actions\meta\UpdateMultiple' ],
			// Address
			'get-address' => [ 'class' => 'cmsgears\core\common\actions\address\Read' ],
			'add-address' => [ 'class' => 'cmsgears\core\common\actions\address\Create' ],
			'update-address' => [ 'class' => 'cmsgears\core\common\actions\address\Update' ],
			'delete-address' => [ 'class' => 'cmsgears\core\common\actions\address\Delete' ],
			// Data Object
			'set-data' => [ 'class' => 'cmsgears\core\common\actions\data\data\Set' ],
			'remove-data' => [ 'class' => 'cmsgears\core\common\actions\data\data\Remove' ],
			'set-attribute' => [ 'class' => 'cmsgears\core\common\actions\data\attribute\Set' ],
			'remove-attribute' => [ 'class' => 'cmsgears\core\common\actions\data\attribute\Remove' ],
			'set-config' => [ 'class' => 'cmsgears\core\common\actions\data\config\Set' ],
			'remove-config' => [ 'class' => 'cmsgears\core\common\actions\data\config\Remove' ],
			'set-setting' => [ 'class' => 'cmsgears\core\common\actions\data\setting\Set' ],
			'remove-setting' => [ 'class' => 'cmsgears\core\common\actions\data\setting\Remove' ],
			// Model
			'auto-search' => [ 'class' => 'cmsgears\core\common\actions\content\AutoSearch' ],
			'bulk' => [ 'class' => 'cmsgears\core\common\actions\grid\Bulk' ],
			'generic' => [ 'class' => 'cmsgears\core\common\actions\grid\Generic' ],
			'delete' => [ 'class' => 'cmsgears\core\common\actions\grid\Delete' ]
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// UserController ------------------------

}
