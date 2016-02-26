<?php
namespace cmsgears\core\admin\controllers;

// Yii Imports
use \Yii;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\Gallery;
use cmsgears\core\common\models\entities\ModelComment;

use cmsgears\core\admin\services\GalleryService;

class TestimonialController extends \cmsgears\core\admin\controllers\base\TestimonialController {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );
		
		$this->sidebar 		= [ 'parent' => 'sidebar-core', 'child' => 'testimonials' ];
	}

	// Instance Methods --------------------------------------------

	// GalleryController -----------------

	public function actionAll() {

		// Remember return url for crud
		Url::remember( [ 'testimonial/all' ], 'testimonials' );
        $this->type = ModelComment::TYPE_TESTIMONIAL;
		return parent::actionAll();
	}
}

?>