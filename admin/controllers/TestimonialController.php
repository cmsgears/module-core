<?php
namespace cmsgears\core\admin\controllers;

// Yii Imports
use \Yii;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\resources\ModelComment;

class TestimonialController extends \cmsgears\core\admin\controllers\base\CommentController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

 	public function init() {

        parent::init();

		$this->sidebar 			= [ 'parent' => 'sidebar-core', 'child' => 'testimonials' ];

        $this->commentUrl	  	= 'testimonial';
		$this->returnUrl		= Url::previous( $this->commentUrl );
		$this->returnUrl		= isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/core/testimonial/all' ], true );

        $this->parentType		= CoreGlobal::TYPE_SITE;
        $this->commentType  	= ModelComment::TYPE_TESTIMONIAL;

		$this->parentService	= Yii::$app->factory->get( 'siteService' );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// TestimonialController -----------------

}
