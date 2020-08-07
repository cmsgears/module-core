<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\admin\controllers\optiongroup;

// Yii Imports
use Yii;
use yii\helpers\Url;

/**
 * OptionController provides actions specific to option model.
 *
 * @since 1.0.0
 */
class OptionController extends \cmsgears\core\admin\controllers\base\category\OptionController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Permission
		$this->crudPermission = CoreGlobal::PERM_CORE;

		// Config
		$this->apixBase = 'core/optiongroup/option';

		// Sidebar
		$this->sidebar = [ 'parent' => 'sidebar-core', 'child' => 'option-group' ];

		// Return Url
		$this->returnUrl = Url::previous( 'options' );
		$this->returnUrl = isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/core/optiongroup/option/all' ], true );

		// All Url
		$allUrl = Url::previous( 'ogroups' );
		$allUrl = isset( $allUrl ) ? $allUrl : Url::toRoute( [ '/core/optiongroup/all' ], true );

		// Breadcrumbs
		$this->breadcrumbs = [
			'base' => [
				[ 'label' => 'Home', 'url' => Url::toRoute( '/dashboard' ) ],
				[ 'label' => 'Option Groups', 'url' =>  $allUrl ]
			],
			'all' => [ [ 'label' => 'Options' ] ],
			'create' => [ [ 'label' => 'Options', 'url' => $this->returnUrl ], [ 'label' => 'Add' ] ],
			'update' => [ [ 'label' => 'Options', 'url' => $this->returnUrl ], [ 'label' => 'Update' ] ],
			'delete' => [ [ 'label' => 'Options', 'url' => $this->returnUrl ], [ 'label' => 'Delete' ] ]
		];
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// OptionController ----------------------

	public function actionAll( $pid ) {

		Url::remember( Yii::$app->request->getUrl(), 'options' );

		return parent::actionAll( $pid );
	}

}
