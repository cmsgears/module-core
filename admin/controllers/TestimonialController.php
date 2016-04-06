<?php
namespace cmsgears\core\admin\controllers;

// Yii Imports
use \Yii;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\models\mappers\ModelComment;

class TestimonialController extends \cmsgears\core\admin\controllers\base\CommentController {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );

        $this->rememberUrl  = 'testimonial';
        $this->returnUrl    = Url::previous( 'testimonial' );
		$this->sidebar 		= [ 'parent' => 'sidebar-core', 'child' => 'testimonials' ];
        $this->commentType  = ModelComment::TYPE_TESTIMONIAL;
	}
}

?>