<?php
namespace cmsgears\core\admin\controllers;

// Yii Imports
use \Yii;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class RoleController extends \cmsgears\core\admin\controllers\base\RoleController {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );

		$this->sidebar 	= [ 'parent' => 'sidebar-identity', 'child' => 'role' ];
	}

	// Instance Methods --------------------------------------------

	// RoleController --------------------

	public function actionAll() {

		// Remember return url for crud
		Url::remember( [ 'role/all' ], 'roles' );

		return parent::actionAll();
	}
}

?>