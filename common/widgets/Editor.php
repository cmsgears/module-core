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

// CMG Imports
use cmsgears\core\frontend\config\SiteProperties;

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

			$this->config[ 'fonts' ] = SiteProperties::getInstance()->getFonts();
		}

		$editor->widget( [ 'selector' => $this->selector, 'config' => $this->config, 'loadAssets' => $this->loadAssets ] );
	}

	// Editor --------------------------------

}
