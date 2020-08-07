<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\base;

// Yii Imports
use Yii;

/**
 * The base Plugin component to be extended by the actual plugins.
 *
 * @since 1.0.0
 */
abstract class Plugin extends Component {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	/**
	 * The id assigned to process the forms.
	 *
	 * @var string
	 */
	public $id;

	/**
	 * The unique key of the plugin to manage the data associated with it.
	 *
	 * @var string
	 */
	public $key;

	/**
	 * The model.
	 *
	 * @var \cmsgears\core\common\models\base\ActiveRecord
	 */
	public $model;

	/**
	 * The model types to check whether the given model is configured for the plugin.
	 *
	 * @var array
	 */
	public $modelTypes;

	/**
	 * Flag to check whether plugin is called by the model admin.
	 *
	 * @var boolean
	 */
	public $admin;

	/**
	 * The path to search for the admin forms.
	 *
	 * @var string
	 */
	public $adminViewsPath;

	/**
	 * The path to search for the site forms.
	 *
	 * @var string
	 */
	public $siteViewsPath;

	/**
	 * The settings model class to save plugin specific settings.
	 *
	 * @var string
	 */
	public $settingsModelClass;

	/**
	 * The configuration model class to save plugin specific configuration.
	 *
	 * @var string
	 */
	public $configModelClass;

	/**
	 * The attributes model class to save plugin specific attributes.
	 *
	 * @var string
	 */
	public $metaModelClass;

	/**
	 * The data model class to save plugin specific data.
	 *
	 * @var string
	 */
	public $dataModelClass;

	/**
	 * The plugin model class to save plugin specific settings.
	 *
	 * @var string
	 */
	public $pluginModelClass;

	/**
	 * The apix base path to update settings, config, attributes, data and to perform
	 * other AJAX based actions.
	 *
	 * @var string
	 */
	public $apixBase;

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Plugin --------------------------------

	public function renderAdminForms() {

		$formsHtml = "";

		if( isset( $this->adminViewsPath ) ) {

			$adminViewsPath = Yii::getAlias( $this->adminViewsPath );

			// Render Settings
			if( isset( $this->settingsModelClass ) ) {

				$formsHtml .= Yii::$app->controller->renderFile( "$adminViewsPath/settings.php", [ 'plugin' => $this ] );
			}

			// Render Config
			if( isset( $this->configModelClass ) ) {

				$formsHtml .= Yii::$app->controller->renderFile( "$adminViewsPath/config.php", [ 'plugin' => $this ] );
			}

			// Render Attributes
			if( isset( $this->metaModelClass ) ) {

				$formsHtml .= Yii::$app->controller->renderFile( "$adminViewsPath/meta.php", [ 'plugin' => $this ] );
			}

			// Render Data
			if( isset( $this->dataModelClass ) ) {

				$formsHtml .= Yii::$app->controller->renderFile( "$adminViewsPath/data.php", [ 'plugin' => $this ] );
			}

			// Render Plugin
			if( isset( $this->pluginModelClass ) ) {

				$formsHtml .= Yii::$app->controller->renderFile( "$adminViewsPath/plugin.php", [ 'plugin' => $this ] );
			}
		}

		return $formsHtml;
	}

}
