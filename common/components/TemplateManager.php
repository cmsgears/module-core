<?php
/**
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 * @license https://www.cmsgears.org/license/
 * @package module
 * @subpackage core
 */
namespace cmsgears\core\common\components;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/*
 * TemplateManager find appropriate template and render the view based on given configurations.
 *
 * @author Bhagwat Singh Chouhan <bhagwat.chouhan@gmail.com>
 * @since 1.0.0
 */
class TemplateManager extends \yii\base\Component {

	// Variables ---------------------------------------------------

	// Global -----------------

	// Public -----------------

	public $templatesPath		= null;

	public $renderers			= [ 'default' => 'Default' ];

	// Protected --------------

	// Private ----------------

	private $templateService;

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->templateService	= Yii::$app->factory->get( 'templateService' );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// TemplateManager -----------------------

	public function getTemplatesPath() {

		return $this->templatesPath;
	}

	public function getRenderers() {

		return $this->renderers;
	}

	public function getRenderPath( $template ) {

		$fileRender	= $template->fileRender;

		// Render from file
		if( $fileRender && !isset( $this->templatesPath ) ) {

			$theme	= Yii::$app->core->site->theme;

			if( isset( $theme ) ) {

				return "$theme->basePath/$template->viewPath";
			}
		}

		return "$this->templatesPath/$template->viewPath";
	}

	protected function renderView( $template, $models, $config ) {

		$page			= isset( $config[ 'page' ] ) ? $config[ 'page' ] : false;
		$layout			= isset( $config[ 'layout' ] ) ? $config[ 'layout' ] : true;
		$layoutPath		= isset( $config[ 'layoutPath' ] ) ? $config[ 'layoutPath' ] : null;
		$view			= isset( $config[ 'viewFile' ] ) ? $config[ 'viewFile' ] : null;

		$fileRender		= $template->fileRender;
		$renderEngine	= $template->renderer;

		// Render from file
		if( $fileRender ) {

			$theme	= Yii::$app->core->site->theme;

			// Default Rendering using php view file
			if( isset( $theme ) && isset( $renderEngine ) && strcmp( $renderEngine, 'default' ) == 0 ) {

				$path	= "$theme->basePath/$template->viewPath/$view";

				// Render using controller
				if( $page ) {

					// Layout is required to render
					if( $layout ) {

						// Override DB Layout
						if( isset( $layoutPath ) ) {

							Yii::$app->controller->layout = $layoutPath;
						}
						// Use DB Layout by default
						else if( isset( $template->layout ) ) {

							if( $template->layoutGroup ) {

								Yii::$app->controller->layout = "//$template->layout/$view";
							}
							else {

								Yii::$app->controller->layout = "//$template->layout";
							}
						}
					}

					return Yii::$app->controller->render( $path, $models );
				}
				// Render using view
				else {

					return Yii::$app->controller->view->render( $path, $models );
				}
			}
			else {

				return "<p>Theme or appropriate renderer is not found for this resource. Please configure appropriate theme.</p>";
			}
		}
		else {

			switch( $renderEngine ) {

				case 'twig': {

					$tplName	= uniqid( 'string_template_', true );
					$twig		= new \Twig_Environment( new \Twig_Loader_Array( [ $tplName => $template->content ] ) );

					$content	= $twig->render( $tplName, $models );

					return $content;

					break;
				}
				case 'smarty': {

					// TODO: Add smarty support

					break;
				}
			}
		}
	}

	// Message / Notifications

	/**
	 * Render generic message using appropriate template.
	 */
	public function renderMessage( $template, $models, $config = [] ) {

		return $this->renderView( $template, $models, $config );
	}

	// Default Page Views

	public function renderViewGeneric( $template, $models, $viewFile, $config = [] ) {

		$config[ 'viewFile' ]	= isset( $config[ 'viewFile' ] ) ? $config[ 'viewFile' ] : $viewFile;
		$config[ 'page' ]		= isset( $config[ 'page' ] ) ? $config[ 'page' ] : true;

		return $this->renderView( $template, $models, $config );
	}

	/**
	 * Admin view to be used for review purpose for data created by site users. The data collected by user will be submitted for admin review as part of approval process.
	 */
	public function renderViewAdmin( $template, $models, $config = [] ) {

		return $this->renderViewGeneric( $template, $models, CoreGlobal::TEMPLATE_VIEW_ADMIN, $config );
	}

	/**
	 * Private view to be viewed by logged in users. It's required for specific cases where views are different for logged in vs non logged in users.
	 */
	public function renderViewPrivate( $template, $models, $config = [] ) {

		return $this->renderViewGeneric( $template, $models, CoreGlobal::TEMPLATE_VIEW_PRIVATE, $config );
	}

	/**
	 * Public view to be viewed by all users. Private view might override in specific scenarios.
	 */
	public function renderViewPublic( $template, $models, $config = [] ) {

		return $this->renderViewGeneric( $template, $models, CoreGlobal::TEMPLATE_VIEW_PUBLIC, $config );
	}

	/**
	 * Print page for public/private views.
	 */
	public function renderViewPrint( $template, $models, $config = [] ) {

		return $this->renderViewGeneric( $template, $models, CoreGlobal::TEMPLATE_VIEW_PRINT, $config );
	}

	/**
	 * Default search page for public/private views.
	 */
	public function renderViewSearch( $template, $models, $config = [] ) {

		return $this->renderViewGeneric( $template, $models, CoreGlobal::TEMPLATE_VIEW_SEARCH, $config );
	}

	/**
	 * Category search page for public/private views.
	 */
	public function renderViewCategory( $template, $models, $config = [] ) {

		return $this->renderViewGeneric( $template, $models, CoreGlobal::TEMPLATE_VIEW_CATEGRY, $config );
	}

	/**
	 * Tag search page for public/private views.
	 */
	public function renderViewTag( $template, $models, $config = [] ) {

		return $this->renderViewGeneric( $template, $models, CoreGlobal::TEMPLATE_VIEW_TAG, $config );
	}

	/**
	 * Author search page for public/private views.
	 */
	public function renderViewAuthor( $template, $models, $config = [] ) {

		return $this->renderViewGeneric( $template, $models, CoreGlobal::TEMPLATE_VIEW_AUTHOR, $config );
	}
}
