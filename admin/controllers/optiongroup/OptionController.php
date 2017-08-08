<?php
namespace cmsgears\core\admin\controllers\optiongroup;

// Yii Imports
use Yii;
use yii\helpers\Url;

class OptionController extends \cmsgears\core\admin\controllers\base\category\OptionController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Sidebar
		$this->sidebar			= [ 'parent' => 'sidebar-core', 'child' => 'option-group' ];

		// Return Url
		$this->returnUrl		= Url::previous( 'options' );
		$this->returnUrl		= isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/core/optiongroup/option/all' ], true );

		// Breadcrumbs
		$this->breadcrumbs	= [
			'base' => [ [ 'label' => 'Option Groups', 'url' =>  [ '/core/optiongroup/all' ] ] ],
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

	public function actionAll( $cid ) {

		Url::remember( Yii::$app->request->getUrl(), 'options' );

		return parent::actionAll( $cid );
	}
}
