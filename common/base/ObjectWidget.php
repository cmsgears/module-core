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
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/**
 * ObjectWidget widget dynamically show the object model.
 *
 * @since 1.0.0
 */
abstract class ObjectWidget extends Widget {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $wrap = true;

	/**
	 * Slug to identify the model.
	 * 
	 * @var string
	 */
	public $slug;

	/**
	 * Check whether default avatar can be used in absence of model banner.
	 *
	 * @var boolean
	 */
	public $defaultAvatar = false;

	/**
	 * Check whether avatar can be lazy loaded.
	 *
	 * @var boolean
	 */
	public $lazyAvatar = false;

	/**
	 * Check whether avatar can be responsive.
	 *
	 * @var boolean
	 */
	public $resAvatar = false;

	/**
	 * Check whether default banner can be used in absence of model banner.
	 *
	 * @var boolean
	 */
	public $defaultBanner = false;

	/**
	 * Check whether banner can be lazy loaded.
	 *
	 * @var boolean
	 */
	public $lazyBanner = false;

	/**
	 * Check whether banner can be responsive.
	 *
	 * @var boolean
	 */
	public $resBanner = false;

	/**
	 *
	 * @var \cmsgears\core\common\models\entities\ObjectData
	 */
	public $model;

	/**
	 * The JSON data stored in model.
	 *
	 * @var Object
	 */
	public $modelData;

	// Protected --------------

	protected $type;

	/**
	 * The model service used to find the model.
	 *
	 * @var \cmsgears\core\common\services\interfaces\entities\IObjectService
	 */
	protected $modelService;

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Widget --------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	/**
	 * Render the view by passing widget data to view
	 */
	public function renderWidget( $config = [] ) {

		if( isset( $this->model ) && $this->model->isActive() && $this->model->isVisible() ) {

			// Model Template
			$model		= $this->model;
			$template	= $model->template;

			// Model Data
			$this->modelData = json_decode( $model->data );

			// Use template defined by Admin
			if( isset( $template ) && $template->fileRender ) {

				$this->templateDir	= $template->viewPath;
				$this->template		= !empty( $template->view ) ? $template->view : 'view';

				// Override template view
				if( !empty( $model->viewPath ) ) {

					$mPath = preg_split( '/\//', $model->viewPath );

					$this->template		= array_pop( $mPath );
					$this->templateDir	= join( '/', $mPath );
				}
			}

			// Pass model and data to widget view
			$widgetHtml = $this->render( $this->template, [ 'widget' => $this ] );

			// Wrap the view
			if( $this->wrap ) {

				// Apply template options - Overrides widget options
				$htmlOptions = isset( $template ) && !empty( $template->htmlOptions ) ? json_decode( $template->htmlOptions, true ) : [];

				$options = !empty( $htmlOptions ) ? ArrayHelper::merge( $this->options, $htmlOptions ) : $this->options;

				// Apply model options - Overrides widget and template options
				$htmlOptions = !empty( $model->htmlOptions ) ? json_decode( $model->htmlOptions, true ) : $htmlOptions;

				$options = !empty( $htmlOptions ) ? ArrayHelper::merge( $options, $htmlOptions ) : $options;

				$type = $this->modelService->getParentType();

				$classOption = isset( $options[ 'class' ] ) ? $options[ 'class' ] : null;

				if( isset( $template ) && !strpos( $classOption, "{$type}-{$template->slug}" ) ) {

					$classOption = "{$type} $classOption obj-{$type} {$type}-{$template->slug} {$type}-{$model->slug}";
				}
				else {

					$classOption = "{$type} $classOption obj-{$type} {$type}-{$model->slug}";
				}

				// Notes: Avoid assigning id to handle multiple objects on same page
				//$options[ 'id' ]	= "{$type}-{$model->slug}"; // Force Unique Id

				$options[ 'class' ] = join( ' ', array_unique( preg_split( '/ /', $classOption ) ) );

				return Html::tag( $this->wrapper, $widgetHtml, $options );
			}

			return $widgetHtml;
		}
	}

	// ObjectWidget --------------------------

}
