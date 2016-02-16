<?php
namespace cmsgears\core\admin\controllers;

// Yii Imports
use \Yii;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\Gallery;

use cmsgears\core\admin\services\GalleryService;

class GalleryController extends \cmsgears\core\admin\controllers\base\GalleryController {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );
		
		$this->sidebar 		= [ 'parent' => 'sidebar-core', 'child' => 'gallery' ];
	}

	// Instance Methods --------------------------------------------

	// GalleryController -----------------

	public function actionAll() {

		// Remember return url for crud
		Url::remember( [ 'gallery/all' ], 'galleries' );

		return parent::actionAll();
	}
}

?>