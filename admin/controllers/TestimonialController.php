<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\admin\controllers;

// Yii Imports
use Yii;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\resources\ModelComment;

use cmsgears\core\admin\controllers\base\CommentController;

/**
 * CommentController provides actions specific to comment model categorized for testimonials.
 *
 * @since 1.0.0
 */
class TestimonialController extends CommentController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Config
		$this->parentType	= CoreGlobal::TYPE_SITE;
		$this->commentType	= ModelComment::TYPE_TESTIMONIAL;
		$this->apixBase		= 'core/comment';
		$this->parentUrl	= '/core/sites/update?id=';
		$this->urlKey		= 'testimonials';
		$this->title		= 'Testimonial';

		// Services
		$this->parentService = Yii::$app->factory->get( 'siteService' );

		// Sidebar
		$this->sidebar = [ 'parent' => 'sidebar-core', 'child' => 'testimonials' ];

		// Return Url
		$this->returnUrl = Url::previous( $this->urlKey );
		$this->returnUrl = isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/core/testimonial/all' ], true );

		// Breadcrumbs
		$this->breadcrumbs = [
			'base' => [
				[ 'label' => 'Home', 'url' => Url::toRoute( '/dashboard' ) ]
			],
			'all' => [ [ 'label' => 'Testimonials' ] ],
			'create' => [ [ 'label' => 'Testimonials', 'url' => $this->returnUrl ], [ 'label' => 'Add' ] ],
			'update' => [ [ 'label' => 'Testimonials', 'url' => $this->returnUrl ], [ 'label' => 'Update' ] ],
			'delete' => [ [ 'label' => 'Testimonials', 'url' => $this->returnUrl ], [ 'label' => 'Delete' ] ]
		];
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
