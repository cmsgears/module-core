<?php
namespace cmsgears\core\common\base;

// Yii Imports
use \Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

// CMG Imports
use cmsgears\core\common\config\CacheProperties;
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\CodeGenUtil;

/**
 * The class PageWidget can be used by widgets which need pagination support via either pagination links or user scroll.
 */
abstract class PageWidget extends Widget {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $wrap			= true;

	public $options			= [ ];

	/**
	 * Html options to be used for all models wrapper.
	 */
	public $wrapperOptions	= [ 'class' => 'row' ];

	/**
	 * Flag to check whether the single model html need wrapper.
	 */
	public $wrapSingle		= true;

	/**
	 * The wrapper tag to be used for single model html wrapping.
	 */
	public $singleWrapper	= 'div';

	/**
	 * Html options to be used for single model wrapper.
	 */
	public $singleOptions	= [ 'class' => 'col' ];

	/*
	 * Base path used for all and single paths.
	 */
	public $basePath		= null;

	/*
	 * Path to be used for View All button or link if applicable.
	 */
	public $allPath			= 'all';

	/**
	 * It can be used to show link to view all models.
	 */
	public $showAllPath		= false;

	/*
	 * Path to view single model page if applicable.
	 */
	public $singlePath		= 'single';

	/*
	 * Check whether pagination is required for this widget.
	 */
	public $pagination		= true;

	/*
	 * Paging can be done using standard Yii Widget i.e. LinkPager. If paging is false, scroll/action based paging can be used to show remaining pages instead of clickable page links.
	 */
	public $paging			= true;

    public $nextLabel		= '&raquo;';

    public $prevLabel		= '&laquo;';

	public $route			= null;

	public $excludeParams	= [];

	/**
	 * Ajaxify the paging links in case $paging is set to true. In such cases, URL params will be updated using History API if supported by browser and the page will be loaded via ajax without refreshing full page.
	 */
	public $ajaxifyLinks	= false;

	/*
	 * Ajax url used to fetch pages. It must be a relative path for appropriate apix controller.
	 */
	public $ajaxUrl			= null;

	/**
	 * Default page limit.
	 */
	public $limit			= 5;

	/*
	 * Useful in case limited text is displayed for a model irrespective of total length. It will be useful in maintaining same height for all the models.
	 */
	public $textLimit		= CoreGlobal::DISPLAY_TEXT_SMALL;

	// Filter Models	- multisite environment

	/**
	 * Exclude main site models and show models from all other sites.
	 */
	public $excludeMain		= false;

	/**
	 * Show models only from current site ignoring all other sites.
	 */
	public $siteModels		= false;

	/**
	 * DataProvider to fetch initial page.
	 */
	public $dataProvider	= null;

	/**
	 * First page of models.
	 */
	public $modelPage		= null;

	/**
	 * Page info having total details.
	 */
	public $pageInfo		= null;

	/**
	 * Page links generated by LinkPager.
	 */
	public $pageLinks		= null;

	public $caching			= false;

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$cacheProperties	= CacheProperties::getInstance();

		$this->caching		= $cacheProperties->isCaching();

		// Init models
		$this->initModels();

		// Init Pagination
		$this->initPaging();
	}

	/**
	 * Initialise data provider and fetch first page of models.
	 */
	abstract public function initModels( $config = [] );

	/**
	 * Initialise paging if applicable.
	 */
	public function initPaging( $config = [] ) {

		// Init Pagination
		if( $this->pagination && $this->paging && isset( $this->dataProvider ) ) {

			$pagination			= $this->dataProvider->getPagination();

			$this->pageInfo		= CodeGenUtil::getPaginationDetail( $this->dataProvider );

			if( isset( $this->route ) ) {

				$pagination->route = $this->route;
			}

			if( count( $this->excludeParams ) > 0 ) {

				$pagination->excludeParams	= $this->excludeParams;
			}

			$this->pageLinks	= LinkPager::widget([
										'pagination' => $pagination,
										'nextPageLabel' => $this->nextLabel, 'prevPageLabel' => $this->prevLabel
									]);
		}
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Widget --------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// cmsgears\core\common\base\Widget

	public function renderWidget( $config = [] ) {

		// Models and HTML
		$models			= $this->modelPage;
		$modelsHtml		= [];

		// Views
		$wrapperView	= $this->template . '/wrapper';
		$singleView		= $this->template . '/single';

		if( Yii::$app->core->multiSite && Yii::$app->core->subDirectory ) {

			$siteName	= Yii::$app->core->getSiteName();

			if( isset( $this->basePath ) ) {

				$this->basePath = Url::toRoute( [ "$siteName/$this->basePath" ] );
			}
			else {

				$this->basePath = Url::toRoute( [ "$siteName" ] );
			}
		}

	   if( isset( $this->basePath ) ) {

			$this->allPath		= Url::toRoute( [ "/$this->basePath/$this->allPath" ], true );
			$this->singlePath	= Url::toRoute( [ "/$this->basePath/$this->singlePath" ], true );
		}
		else {

			$this->allPath		= Url::toRoute( [ "/$this->allPath" ], true );
			$this->singlePath	= Url::toRoute( [ "/$this->singlePath" ], true );
		}

		$idxCounter	= 0;

		foreach( $models as $model ) {

			$content = $this->render( $singleView, [ 'index' => $idxCounter, 'model' => $model, 'widget' => $this ] );

			if( $this->wrapSingle ) {

				$modelsHtml[] = Html::tag( $this->singleWrapper, $content, $this->singleOptions );
			}
			else {

				$modelsHtml[] = $this->render( $singleView, [ 'index' => $idxCounter, 'model' => $model, 'widget' => $this ] );
			}

			$idxCounter++;
		}

		$modelsHtml		= implode( '', $modelsHtml );

		$content		= $this->render( $wrapperView, [ 'modelsHtml' => $modelsHtml, 'widget' => $this, 'modelCount' => $idxCounter ] );

		if( $this->wrap ) {

			return Html::tag( $this->wrapper, $content, $this->options );
		}
		else {

			return $content;
		}
	}

	// PageWidget ----------------------------

	public function getPaginationOptions() {

		$pageLimits		= [ '5' => 5, '10' => 10, '15' => 15, '20' => 20 ];
		$pageLimit		= Yii::$app->request->get( 'per-page' );
		$pageLimit		= isset( $pageLimit ) && in_array( $pageLimit, $pageLimits ) ? $pageLimit : $this->limit;
		$pageLimitIdx	= array_search( $pageLimit, $pageLimits );

		$options		= CodeGenUtil::generateSelectOptionsFromArray( $pageLimits, $pageLimitIdx );

		return $options;
	}
}
