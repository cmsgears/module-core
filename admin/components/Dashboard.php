<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\admin\components;

// Yii Imports
use Yii;

/**
 * Dashboard component forms the dashboard for admin by merging the dashboard html for the
 * modules and plugins specified in application configuration. This dashboard is specific
 * only for Admin Application.
 *
 * @since 1.0.0
 */
class Dashboard extends \yii\base\Component {

	// Variables ---------------------------------------------------

	// Global -----------------

	// Public -----------------

	public $modules	= [];

	public $plugins	= [];

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG parent classes --------------------

	// Dashboard -----------------------------

	/**
	 * @return string - the html merged from each module to generate dashboard.
	 */
	public function getDashboardHtml() {

		// TODO: Use caching

		$dashboardHtml = "";

		$modules = $this->modules;

		// Collect sidebar html from all the modules
		foreach( $modules as $module ) {

			$module	= Yii::$app->getModule( $module );
			$html	= $module->getDashboardHtml();

			ob_start();

			if( file_exists( $html ) ) {

				include( $html );
			}

			$dashboardHtml .= ob_get_contents();

			ob_get_clean();
		}

		return $dashboardHtml;
	}

	/**
	 * @return string - the html merged from each plugin to generate dashboard.
	 */
	public function getPluginsHtml() {

		// TODO: Add options to render plugins on dashboard.
	}

}
