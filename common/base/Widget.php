<?php
namespace cmsgears\core\common\base;

// Yii Imports
use yii\base\Widget as BaseWidget;
use yii\helpers\Html;

// CMG Imports
use cmsgears\core\common\config\CoreProperties;
use cmsgears\core\common\config\CacheProperties;

use cmsgears\core\common\utilities\CodeGenUtil;

abstract class Widget extends BaseWidget {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	/**
	 * Title can be either text or html displayed on top of widget.
	 *
	 * @var type string
	 */
	public $title;

	/**
	 * Flag to check whether the widget html need wrapper.
	 */
	public $wrap = false;

	/**
	 * The wrapper tag to be used for widget html wrapping.
	 */
	public $wrapper = 'div';

	/**
	 * Html options to be used for wrapper.
	 */
	public $options = [];

	/**
	 * Flag to check whether assets can be loaded. We can load widget assets separately in case the
	 * bundle is not added as dependency to layout asset bundle.
	 */
	public $loadAssets = false;

	/**
	 * The path at which view template file is located. It can have alias - ex: '@widget/my-view'.
	 * By default it's the views folder within widget directory.
	 */
	public $templateDir = null;

	/**
	 * The template directory/file used to render widget. If it's a directory, the view can be formed
	 * using multiple files. It can be absolute path to directly access the view.
	 */
	public $template = 'default';

	// Additional content
	public $buffer		= false;
	public $bufferData	= null;

	/**
	 * This flag can be utilised by widgets to use fallback options in case application factory having
	 * model service is not available or initialised.
	 *
	 * The widgets in need of model service can utilise factory to get required service. In case factory
	 * is not needed, widget can directly use models to query them or service in use must provided static method.
	 */
	public $factory = true;

	/**
	 * Flag to render data from cache.
	 */
	public $cache = false;

	/**
	 * Flag for data rendering from database based cached data.
	 */
	public $cacheDb = false;

	/**
	 * Flag for data rendering from file based cached data.
	 */
	public $cacheFile = false;

	/**
	 * Flag for widget autoloading.
	 */
	public $autoload			= false;
	public $autoloadTemplate	= 'autoload';

	/**
	 * Auto Loading using CMT-JS Framework.
	 */
	public $autoloadApp			= 'autoload';
	public $autoloadController	= 'autoload';
	public $autoloadAction		= 'autoload';

	/**
	 * Url for autoloading.
	 */
	public $autoloadUrl = null;

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

    public function init() {

        parent::init();

        $this->autoload	= ( isset( $this->autoloadUrl ) && CoreProperties::getInstance()->isAutoLoad() ) ? true : false;

		$this->cache	= $this->cache ? $this->cache : CacheProperties::getInstance()->isCaching();
    }

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

		$this->bufferData = $this->buffer ? ob_get_clean() : null;

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

		$autoloadView	= CodeGenUtil::isAbsolutePath( $this->autoloadTemplate ) ? $this->autoloadTemplate : "$this->template/$this->autoloadTemplate";

		$widgetHtml		= $this->render( $autoloadView, [ 'widget' => $this ] );

		if( $this->wrap ) {

			return Html::tag( $this->wrapper, $widgetHtml, $this->options );
		}

		return $widgetHtml;
	}

	// Widget --------------------------------

}
