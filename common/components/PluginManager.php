<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\components;

/**
 * The Plugin Manager component provides events and methods to manage plugin lifecycle.
 *
 * @since 1.0.0
 */
class PluginManager extends \cmsgears\core\common\base\Component {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	public $plugins = [];

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// PluginManager -------------------------

	public function renderAdminForms( $model, $type, $apixBase = null ) {

		$plugins = $this->plugins;

		$formsHtml = "";

		foreach( $plugins as $id => $plugin ) {

			$pluginClass	= $plugin[ 'class' ];
			$modelTypes		= $plugin[ 'modelTypes' ];

			// Plugin configured for the model type
			if( in_array( $type, $modelTypes ) ) {

				$pluginObj = new $pluginClass( [ 'id' => $id, 'model' => $model, 'admin' => true, 'apixBase' => $apixBase ] );

				$formsHtml .= $pluginObj->renderAdminForms();
			}
		}

		return $formsHtml;
	}

}
