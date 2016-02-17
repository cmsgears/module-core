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

	protected function renderView( $template, $models, $page, $templateView ) {

		$fileRender		= $template->renderFile;
		$renderEngine 	= $template->renderer;

		// Render from file
		if( $fileRender ) {

			$theme	= Yii::$app->cmgCore->site->theme;

			// Default Rendering using php view file
			if( isset( $theme ) && isset( $renderEngine ) && strcmp( $renderEngine, 'default' ) == 0 ) {

				$path	= "$theme->basePath/$template->viewPath/$templateView";

				// Render using controller
				if( $page ) {

					if( isset( $template->layout ) ) {
	
						Yii::$app->controller->layout = "//$template->layout";
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

	public function renderViewAdmin( $template, $models, $page = false ) {

		return $this->renderView( $template, $models, $page, CoreGlobal::TEMPLATE_VIEW_ADMIN );
	}

	public function renderViewPrivate( $template, $models, $page = false ) {

		return $this->renderView( $template, $models, $page, CoreGlobal::TEMPLATE_VIEW_PRIVATE );
	}

	public function renderViewPublic( $template, $models, $page = false ) {

		return $this->renderView( $template, $models, $page, CoreGlobal::TEMPLATE_VIEW_PUBLIC );
	}
}

?>