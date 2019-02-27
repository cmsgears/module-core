<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\widgets;

// Yii Imports
use Yii;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\base\Widget;

/**
 * The base editor calls the editor configured for application.
 *
 * @since 1.0.0
 */
class Editor extends Widget {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $loadAssets = true;

	public $selector = '.content-editor';

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

		$editorConfig = Yii::$app->core->getEditor();

		$editorClass	= is_string( $editorConfig ) ? $editorConfig : $editorConfig[ 'class' ];
		$editorConfig	= is_array( $editorConfig ) && isset( $editorConfig[ 'options' ] ) ? $editorConfig[ 'options' ] : [];

		$widgetConfig = [ 'loadAssets' => $this->loadAssets, 'selector' => $this->selector, 'config' => $this->config ];

		// Override common config by instance config
		$widgetConfig = ArrayHelper::merge( $editorConfig, $widgetConfig );

		$editorClass::widget( $widgetConfig );
	}

	// Editor --------------------------------

}
