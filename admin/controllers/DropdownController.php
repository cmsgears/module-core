<?php
namespace cmsgears\core\admin\controllers;

// Yii Imports
use \Yii;
use yii\helpers\Url;

// CMG Imports

class DropdownController extends \cmsgears\core\admin\controllers\base\DropdownController {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );
		
		$this->sidebar 	= [ 'parent' => 'sidebar-core', 'child' => 'dropdown' ];
	}

	// Instance Methods --------------------------------------------

	// DropdownController --------------------

	public function actionAll() {

		Url::remember( [ 'dropdown/all' ], 'categories' );

		return parent::actionAll();
	}
}

?>