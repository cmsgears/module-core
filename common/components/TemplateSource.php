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

	public function renderViewAdmin( $template, $models, $viewRenderer = null ) {

		$fileRender	= $template->renderFile;

		// Render from file
		if( $fileRender ) {

			$theme	= Yii::$app->cmgCore->site->theme;

			if( isset( $theme ) && isset( $viewRenderer ) ) {

				$path	= "$theme->basePath/$template->viewPath/$template->adminView";

				return $viewRenderer->render( $path, $models );
			}
			else {

				return "<p>Theme not found for this site. Please configure appropriate theme.</p>";	
			}
		}
	}

	public function renderViewUser( $template, $models, $viewRenderer = null ) {

		$fileRender	= $template->renderFile;

		// Render from file
		if( $fileRender ) {

			$theme	= Yii::$app->cmgCore->site->theme;

			if( isset( $theme ) && isset( $viewRenderer ) ) {

				$path	= "$theme->basePath/$template->viewPath/$template->userView";

				return $viewRenderer->render( $path, $models );
			}
			else {

				return "<p>Theme not found for this site. Please configure appropriate theme.</p>";	
			}
		}
	}

	public function renderViewPublic( $template, $models, $viewRenderer = null ) {

		$fileRender	= $template->renderFile;

		// Render from file
		if( $fileRender ) {

			$theme	= Yii::$app->cmgCore->site->theme;

			if( isset( $theme ) && isset( $viewRenderer ) ) {

				if( isset( $template->layout ) ) {

					$viewRenderer->layout	= "//$template->layout";
				}

				$path	= "$theme->basePath/$template->viewPath/$template->publicView";

				return $viewRenderer->render( $path, $models );
			}
			else {

				return "<p>Theme not found for this site. Please configure appropriate theme.</p>";	
			}
		}
	}
}

?>