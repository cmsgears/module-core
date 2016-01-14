<?php
namespace cmsgears\core\common\widgets;

// Yii Imports
use \Yii;
use yii\base\Widget;
use yii\base\InvalidConfigException;

class Editor extends \cmsgears\core\common\base\Widget {

	// Variables ---------------------------------------------------

	// Public Variables --------------------

	public $selector;

	// Constructor and Initialisation ------------------------------
	
	// yii\base\Object

    public function init() {

        parent::init();
    }

	// Instance Methods --------------------------------------------

	// yii\base\Widget

    public function run() {

		$this->renderWidget();
    }

	public function renderWidget( $config = [] ) {

		$editorClass	= Yii::$app->cmgCore->getEditorClass();
		$editor			= Yii::createObject( $editorClass );

		$editor->widget( [ 'selector' => $this->selector, 'loadAssets' => $this->loadAssets ] );
	}
}

?>