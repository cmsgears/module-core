<?php
namespace cmsgears\core\admin\controllers;

// Yii Imports
use \Yii;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class PermissionController extends \cmsgears\core\admin\controllers\base\PermissionController {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );

		$this->sidebar 	= [ 'parent' => 'sidebar-identity', 'child' => 'permission' ];
	}

	// Instance Methods --------------------------------------------

	// RoleController --------------------

	public function actionAll() {

		// Remember return url for crud
		Url::remember( [ 'permission/all' ], 'permissions' );

		return parent::actionAll();
	}

	public function actionMatrix() {

		$this->sidebar 	= [ 'parent' => 'sidebar-identity', 'child' => 'matrix' ];

		// Remember return url for crud
		Url::remember( [ 'permission/matrix' ], 'roles' );

		return parent::actionMatrix();
	}
}

?>