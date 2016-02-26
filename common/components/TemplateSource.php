<?php
namespace cmsgears\core\common\components;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

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

		$fileRender	= $template->renderFile;

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

		$fileRender		= $template->renderFile;
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

							Yii::$app->controller->layout = "//$template->layout";	
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

	public function renderViewAdmin( $template, $models, $config = [] ) {

		$config[ 'viewFile' ] = CoreGlobal::TEMPLATE_VIEW_ADMIN;

		return $this->renderView( $template, $models, $config );
	}

	public function renderViewPrivate( $template, $models, $config = [] ) {

		$config[ 'viewFile' ] = CoreGlobal::TEMPLATE_VIEW_PRIVATE;

		return $this->renderView( $template, $models, $config );
	}

	public function renderViewPublic( $template, $models, $config = [] ) {

		$config[ 'viewFile' ] = CoreGlobal::TEMPLATE_VIEW_PUBLIC;

		return $this->renderView( $template, $models, $config );
	}

	public function renderViewSearch( $template, $models, $config = [] ) {

		$config[ 'viewFile' ] = CoreGlobal::TEMPLATE_VIEW_SEARCH;

		return $this->renderView( $template, $models, $config );
	}

	public function renderViewCategory( $template, $models, $config = [] ) {

		$config[ 'viewFile' ] = CoreGlobal::TEMPLATE_VIEW_CATEGRY;

		return $this->renderView( $template, $models, $config );
	}

	public function renderViewTag( $template, $models, $config = [] ) {

		$config[ 'viewFile' ] = CoreGlobal::TEMPLATE_VIEW_TAG;

		return $this->renderView( $template, $models, $config );
	}

	public function renderViewAuthor( $template, $models, $config = [] ) {

		$config[ 'viewFile' ] = CoreGlobal::TEMPLATE_VIEW_AUTHOR;

		return $this->renderView( $template, $models, $config );
	}
}

?>