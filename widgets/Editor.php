<?php
namespace cmsgears\core\widgets;

// Yii Imports
use \Yii;
use yii\base\Widget;
use yii\base\InvalidConfigException;

class Editor extends Widget {

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

		$editorClass	= Yii::$app->cmgCore->getEditorClass();
		$editor			= Yii::createObject( $editorClass );
		
		$editor->widget( [ 'selector' => $this->selector ] );
    }
}

?>