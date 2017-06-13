<?php
namespace cmsgears\core\common\base;

// Yii Imports
use \Yii;

abstract class Widget extends \yii\base\Widget {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	/**
	 * Flag to check whether the widget html need wrapper.
	 */
	public $wrap			= false;

	/**
	 * The wrapper tag to be used for widget html wrapping.
	 */
	public $wrapper			= 'div';

	/**
	 * Html options to be used for wrapper.
	 */
	public $options			= [];

	/**
	 * Flag to check whether assets can be loaded. We can load widget assets seperately in case the bundle is not added as dependency to layout asset bundle.
	 */
	public $loadAssets		= false;

	/**
	 * The path at which view template file is located. It can have alias - ex: '@widget/my-view'. By default it's the views folder within widget directory.
	 */
	public $templateDir		= null;

	/**
	 * The template directory/file used to render widget. If it's a directory, the view can be formed using multiple files.
	 */
	public $template		= 'simple';

	/**
	 * This flag can be utilised by widgets to use fallback options in case application factory having model service is not available or initialised.
	 *
	 * The widgets in need of model service can utilise factory to get required service. In case factory is not needed, widget can directly
	 * use models to query them or service in use must provided static method.
	 */
	public $factory			= true;

	/**
	 * Flag to render data from cache.
	 */
	public $cache		= false;

	/**
	 * Flag for data rendering from database based cached data.
	 */
	public $cacheDb		= false;

	/**
	 * Flag for data rendering from file based cached data.
	 */
	public $cacheFile	= false;

	/**
	 * Flag for widget autoloading.
	 */
	public $autoload	= false;

	/**
	 * Url for autoloading.
	 */
	public $autoloadUrl	= null;

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Widget --------

	/**
	 * The method returns the view path for this widget if set while calling widget.
	 */
	public function getViewPath() {

		// Return custom view path
		if( isset( $this->templateDir ) ) {

			return $this->templateDir;
		}

		// Return default view path
		return parent::getViewPath();
	}

	/**
	 * @inheritdoc
	 */
	public function run() {

		if( $this->autoload ) {

			// Render autoload widget
			return $this->renderAutoload();
		}

		// Render the widget
		return $this->renderWidget();
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	abstract public function renderWidget( $config = [] );

	public function renderAutoload( $config = [] ) {

		return $this->render( "$this->template/autoload", [ 'widget' => $this ] );
	}

	// Widget --------------------------------

}
