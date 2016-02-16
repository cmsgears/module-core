<?php
namespace cmsgears\core\admin\controllers\gallery;

// Yii Imports
use \Yii;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class TemplateController extends \cmsgears\core\admin\controllers\base\TemplateController {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );
		
		$this->sidebar 		= [ 'parent' => 'sidebar-core', 'child' => 'gallery-template' ];

		$this->type			= CoreGlobal::TYPE_GALLERY;
	}

	// Instance Methods ---------------------------------------------

	// CategoryController --------------------

	public function actionAll() {
		
		Url::remember( [ 'gallery/template/all' ], 'templates' );

		return parent::actionAll();
	}
}

?>