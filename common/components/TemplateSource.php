<?php
namespace cmsgears\core\common\components;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\services\entities\TemplateService;

class TemplateSource extends \yii\base\Component {

	// Variables ---------------------------------------------------

	public $templatesPath		= null;

	public $renderers			= [ 'default' => 'Default' ];

	// Init --------------------------------------------------------

    public function init() {

        parent::init();
    }

	// TemplateSource ----------------------------------------------

	public function getTemplatesPath() {

		return $this->templatesPath;
	}

	public function getRenderers() {

		return $this->renderers;
	}

	public function getRenderPath( $template ) {

		$fileRender	= $template->fileRender;

		// Render from file
		if( $fileRender ) {

			$theme	= Yii::$app->cmgCore->site->theme;

			if( isset( $theme ) ) {

				return "$theme->basePath/$template->viewPath";
			}
		}
	}

	protected function renderView( $template, $models, $config ) {

		$page			= isset( $config[ 'page' ] ) ? $config[ 'page' ] : false;
		$layout			= isset( $config[ 'layout' ] ) ? $config[ 'layout' ] : true;
		$layoutPath		= isset( $config[ 'layoutPath' ] ) ? $config[ 'layoutPath' ] : null;
		$view			= isset( $config[ 'viewFile' ] ) ? $config[ 'viewFile' ] : null;

		$fileRender		= $template->fileRender;
		$renderEngine 	= $template->renderer;

		// Render from file
		if( $fileRender ) {

			$theme	= Yii::$app->cmgCore->site->theme;

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
	}

	// --------- Message / Notifications ---------------------- //

	/**
	 * Render generic message using appropriate template.
	 */
	public function renderMessage( $template, $models, $config = [] ) {

		return $this->renderView( $template, $models, $config );
	}

	/**
	 * Render generic message using appropriate template and trigger notification.
	 */
	public function triggerNotification( $templateSlug, $models, $config = [] ) {

		$notificationManager	= Yii::$app->notificationManager;

		if( Yii::$app->cmgCore->isNotifications() && isset( $notificationManager ) ) {

			$template	= TemplateService::findBySlug( $templateSlug );

			$message	= $this->renderMessage( $template, $models, $config );

			Yii::$app->notificationManager->triggerNotification( $message, $models, $config );
		}
	}

	// --------- Default Page Views --------------------------- //

	/**
	 * Admin view to be used for review purpose for data created by site users. The data collected by user will be submitted for admin review as part of approval process.
	 */
	public function renderViewAdmin( $template, $models, $config = [] ) {

		$config[ 'viewFile' ] 	= isset( $config[ 'viewFile' ] ) ? $config[ 'viewFile' ] : CoreGlobal::TEMPLATE_VIEW_ADMIN;
		$config[ 'page' ] 		= isset( $config[ 'page' ] ) ? $config[ 'page' ] : true;

		return $this->renderView( $template, $models, $config );
	}

	/**
	 * Private view to be viewed by logged in users. It's required for specific cases where views are different for logged in vs non logged in users.
	 */
	public function renderViewPrivate( $template, $models, $config = [] ) {

		$config[ 'viewFile' ] 	= isset( $config[ 'viewFile' ] ) ? $config[ 'viewFile' ] : CoreGlobal::TEMPLATE_VIEW_PRIVATE;
		$config[ 'page' ] 		= isset( $config[ 'page' ] ) ? $config[ 'page' ] : true;

		return $this->renderView( $template, $models, $config );
	}

	/**
	 * Public view to be viewed by all users. Private view might override in specific scenarios.
	 */
	public function renderViewPublic( $template, $models, $config = [] ) {

		$config[ 'viewFile' ] 	= isset( $config[ 'viewFile' ] ) ? $config[ 'viewFile' ] : CoreGlobal::TEMPLATE_VIEW_PUBLIC;
		$config[ 'page' ] 		= isset( $config[ 'page' ] ) ? $config[ 'page' ] : true;

		return $this->renderView( $template, $models, $config );
	}

	/**
	 * Default search page for public/private views.
	 */
	public function renderViewSearch( $template, $models, $config = [] ) {

		$config[ 'viewFile' ] 	= isset( $config[ 'viewFile' ] ) ? $config[ 'viewFile' ] : CoreGlobal::TEMPLATE_VIEW_SEARCH;
		$config[ 'page' ] 		= isset( $config[ 'page' ] ) ? $config[ 'page' ] : true;

		return $this->renderView( $template, $models, $config );
	}

	/**
	 * Category search page for public/private views.
	 */
	public function renderViewCategory( $template, $models, $config = [] ) {

		$config[ 'viewFile' ] 	= isset( $config[ 'viewFile' ] ) ? $config[ 'viewFile' ] : CoreGlobal::TEMPLATE_VIEW_CATEGRY;
		$config[ 'page' ] 		= isset( $config[ 'page' ] ) ? $config[ 'page' ] : true;

		return $this->renderView( $template, $models, $config );
	}

	/**
	 * Tag search page for public/private views.
	 */
	public function renderViewTag( $template, $models, $config = [] ) {

		$config[ 'viewFile' ] 	= isset( $config[ 'viewFile' ] ) ? $config[ 'viewFile' ] : CoreGlobal::TEMPLATE_VIEW_TAG;
		$config[ 'page' ] 		= isset( $config[ 'page' ] ) ? $config[ 'page' ] : true;

		return $this->renderView( $template, $models, $config );
	}

	/**
	 * Author search page for public/private views.
	 */
	public function renderViewAuthor( $template, $models, $config = [] ) {

		$config[ 'viewFile' ] 	= isset( $config[ 'viewFile' ] ) ? $config[ 'viewFile' ] : CoreGlobal::TEMPLATE_VIEW_AUTHOR;
		$config[ 'page' ] 		= isset( $config[ 'page' ] ) ? $config[ 'page' ] : true;

		return $this->renderView( $template, $models, $config );
	}
}

?>