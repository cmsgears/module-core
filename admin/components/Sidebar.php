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
use yii\base\Component;
use yii\helpers\ArrayHelper;

/**
 * The component sidebar forms the sidebar for admin by merging the sidebar html for
 * the modules and plugins specified in application configuration. This sidebar is
 * specific only for Admin Application.
 */
class Sidebar extends Component {

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

	// Sidebar -------------------------------

	/**
	 * @return string - the html merged from each module sidebar view.
	 */
	public function getSidebarHtml( $parent, $child ) {

		// TODO: Use caching

		$sidebarHtml	= "";
		$modules		= $this->modules;

		// Collect sidebar html from all the modules
		foreach( $modules as $module ) {

			$module	= Yii::$app->getModule( $module );
			$html	= $module->getSidebarHtml();

			ob_start();

			if( file_exists( $html ) ) {

				include( $html );
			}

			$sidebarHtml .= ob_get_contents();

			ob_get_clean();
		}

		return $sidebarHtml;
	}

	/**
	 * @return array - of available plugins to generate plugin list.
	 */
	public function getPlugins() {

		return $this->plugins;
	}

	/**
	 * @return array - of available config options from modules and plugins.
	 */
	public function getConfig() {

		$config		= [];
		$modules	= $this->modules;

		// Collect settings from all the modules
		foreach( $modules as $module ) {

			$module = Yii::$app->getModule( $module );

			if( isset( $module->config	) ) {

				$config = ArrayHelper::merge( $config, $module->config );
			}
		}

		$plugins = $this->plugins;

		// Collect settings from all the plugins
		foreach( $plugins as $plugin ) {

			if( isset( $plugin	) ) {

				$config = ArrayHelper::merge( $config, $plugin );
			}
		}

		return $config;
	}

}
