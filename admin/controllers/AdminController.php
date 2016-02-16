<?php
namespace cmsgears\core\admin\controllers;

// Yii Imports
use \Yii;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class AdminController extends \cmsgears\core\admin\controllers\base\UserController {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );

		$this->sidebar 		= [ 'parent' => 'sidebar-identity', 'child' => 'admin' ];

		$this->roleType			= CoreGlobal::TYPE_SYSTEM;
		$this->permissionSlug	= CoreGlobal::PERM_ADMIN;
		$this->showCreate 		= false;
	}

	// Instance Methods --------------------------------------------

	// UserController --------------------

	public function actionAll() {

		Url::remember( [ 'admin/all' ], 'users' );

		return parent::actionAll();
	}
}

?>