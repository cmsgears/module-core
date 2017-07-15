<?php
namespace cmsgears\core\common\widgets;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\frontend\config\SiteProperties;

class Editor extends \cmsgears\core\common\base\Widget {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $selector;

	public $fonts	= 'default'; // 'default' OR 'site'

	public $config	= [];

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Widget --------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// cmsgears\core\common\base\Widget

	public function renderWidget( $config = [] ) {

		$editorClass	= Yii::$app->core->getEditorClass();
		$editor			= Yii::createObject( $editorClass );

		if( $this->fonts == 'site' ) {

			$this->config[ 'fonts' ]	= SiteProperties::getInstance()->getFonts();
		}

		$editor->widget( [ 'selector' => $this->selector, 'config' => $this->config, 'loadAssets' => $this->loadAssets ] );
	}

	// Editor --------------------------------

}
