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

use cmsgears\core\admin\controllers\base\GalleryController as BaseGalleryController;

/**
 * GalleryController provide actions specific to site galleries.
 *
 * @since 1.0.0
 */
class GalleryController extends BaseGalleryController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Config
		$this->type		= CoreGlobal::TYPE_SITE;
		$this->apixBase	= 'core/gallery';

		// Sidebar
		$this->sidebar = [ 'parent' => 'sidebar-file', 'child' => 'gallery' ];

		// Return Url
		$this->returnUrl = Url::previous( 'galleries' );
		$this->returnUrl = isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/core/gallery/all' ], true );

		// Breadcrumbs
		$this->breadcrumbs = [
			'all' => [ [ 'label' => 'Galleries' ] ],
			'create' => [ [ 'label' => 'Galleries', 'url' => $this->returnUrl ], [ 'label' => 'Add' ] ],
			'update' => [ [ 'label' => 'Galleries', 'url' => $this->returnUrl ], [ 'label' => 'Update' ] ],
			'delete' => [ [ 'label' => 'Galleries', 'url' => $this->returnUrl ], [ 'label' => 'Delete' ] ],
			'items' => [ [ 'label' => 'Galleries', 'url' => $this->returnUrl ], [ 'label' => 'Items' ] ]
		];
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// GalleryController ---------------------

	public function actionAll( $config = [] ) {

		// Remember return url for crud
		Url::remember( Yii::$app->request->getUrl(), 'galleries' );

		return parent::actionAll( $config );
	}

}
