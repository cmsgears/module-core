<?php
namespace cmsgears\core\common\widgets;

// Yii Imports
use \Yii;
use yii\base\Widget;
use yii\base\InvalidConfigException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class Editor extends \cmsgears\core\common\base\Widget {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $selector;

	public $config = [];

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

		$editor->widget( [ 'selector' => $this->selector, 'config' => $this->config, 'loadAssets' => $this->loadAssets ] );
	}

	// Editor --------------------------------

}
