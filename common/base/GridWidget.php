<?php
namespace cmsgears\core\common\base;

// Yii Imports
use \Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\base\InvalidConfigException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\core\common\config\CoreProperties;

/**
 * The class GridWidget can be used by widgets showing data grids.
 */
abstract class GridWidget extends Widget {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $template	= 'simple';

	// HTML options used by the widget div
	public $options		= [ 'class' => 'grid-data' ];

	// Additional data
	public $data 		= [];

	public $provider 	= true; // Flag to check whether data provider is required

	// Data provider having sort, pagination and query to provide data
	public $dataProvider;

	// Models - used instead of data provider
	public $models;

	public $limits = [ 12, 20, 28, 36 ];
	public $limit = 12;

	// Grid Title
	public $title;

	// Flag to show/hide add button
	public $add = true;

	// Url to add model
	public $addUrl = 'add';

	// Add using popup
	public $addPopup = false;

	// Widget Sections
	public $sort = true;
	public $filter = true;
	public $report = true;
	public $option = true;
	public $header = true;
	public $footer = true;

	// Widget Views
	public $sortView;
	public $filtersView;
	public $reportView;
	public $optionsView;
	public $headerView;
	public $dataView;
	public $cardView;
	public $actionView;
	public $footerView;
	public $gridView;

	// Sorting - columns available for sorting
	public $sortColumns = [];

	// Quick Filters - filter the data
	public $filters = [];

	// Reporting - generate report
	public $reportColumns = [];
	public $dateClass = 'datepicker';

	// Grid Options
	public $bulkActions = [];
	public $bulkPopup = 'popup-bulk';
	public $searchColumns = [];

	// Grid Data
	public $noDataMessage = "No rows found."; // Message displayed in case not data available

	// Grid Layouts
	public $table = false; // Flag to check whether html table is required
	public $grid = true; // Flag to check whether responsive grid is required
	public $card = true; // Flag to check whether responsive card layout is required

	public $layout;

	// Notes:
	// 1. Either of the table or grid can be true
	// 2. If both grid and card are true, a toggle button will be displayed to toggle between grid and card view

	// Grid Columns
	public $gridColumns = []; // Required only for grid layout
	public $columns = [];

	// Grid Cards
	public $gridCards = [];

	// Grid Actions
	public $actions = [];

	// Import/Export
	public $import = false;
	public $export = false;

	// Grid having static columns
	public $fixedColumns	= [];

	public $leftColumns		= [];
	public $rightColumns	= [];

	public $leftCols	= 1;
	public $midCols		= 1;
	public $rightCols	= 1;

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

        if( $this->provider && !isset( $this->dataProvider ) ) {

            throw new InvalidConfigException( 'Data provider is required to generate grid.' );
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

		// Limit
		$limit			= Yii::$app->request->getQueryParam( 'limit' );

		if( isset( $limit ) && in_array( $limit, $this->limits ) ) {

			$this->limit = $limit;
		}

		if( isset( $this->dataProvider ) ) {

			$this->dataProvider->pagination->pageSize = $this->limit;
		}

		// Layout
		$user			= Yii::$app->user->getIdentity();
		$gridLayout		= isset( $user ) ? $user->getDataMeta( 'grid-layout' ) : null;
		$this->layout	= isset( $gridLayout ) ? $gridLayout : ( isset( $this->layout ) ? $this->layout : 'table' );

		// Views

		$sortHtml		= $this->sort ? $this->renderSort( $config ) : null;

		$filtersHtml	= $this->filter ? $this->renderFilters( $config ) : null;

		$reportHtml		= $this->report ? $this->renderReport( $config ) : null;

		$optionsHtml	= $this->option ? $this->renderOptions( $config ) : null;

		$headerHtml		= $this->header ? $this->renderHeader( $config ) : null;

		$dataHtml		= $this->renderData( $config );

		$footerHtml		= $this->footer ? $this->renderFooter( $config ) : null;

		$gridView		= isset( $this->gridView ) ? $this->gridView : $this->template . '/grid';

		$widgetHtml 	= $this->render( $gridView, [
								'widget' => $this,
								'sortHtml' => $sortHtml, 'filtersHtml' => $filtersHtml, 'reportHtml' => $reportHtml, 'optionsHtml' => $optionsHtml,
								'headerHtml' => $headerHtml, 'dataHtml' => $dataHtml, 'footerHtml' => $footerHtml
							]);

        return Html::tag( 'div', $widgetHtml, $this->options );
	}

	// GridWidget ----------------------------

	public function renderSort( $config = [] ) {

		$sortView		= isset( $this->sortView ) ? $this->sortView : $this->template . '/sort';

        return $this->render( $sortView, [ 'widget' => $this ] );
	}

	public function renderFilters( $config = [] ) {

		$filtersView	= isset( $this->filtersView ) ? $this->filtersView : $this->template . '/filters';

        return $this->render( $filtersView, [ 'widget' => $this ] );
	}

	public function renderReport( $config = [] ) {

		$reportView		= isset( $this->reportView ) ? $this->reportView : $this->template . '/report';

        return $this->render( $reportView, [ 'widget' => $this ] );
	}

	public function renderOptions( $config = [] ) {

		$optionsView	= isset( $this->optionsView ) ? $this->optionsView : $this->template . '/options';

        return $this->render( $optionsView, [ 'widget' => $this ] );
	}

	public function renderHeader( $config = [] ) {

		$headerView		= isset( $this->headerView ) ? $this->headerView : $this->template . '/header';

        return $this->render( $headerView, [ 'widget' => $this ] );
	}

	public function renderData( $config = [] ) {

		$dataView		= isset( $this->dataView ) ? $this->dataView : $this->template . '/data';

        return $this->render( $dataView, [ 'widget' => $this ] );
	}

	public function renderFooter( $config = [] ) {

		$footerView		= isset( $this->footerView ) ? $this->footerView : $this->template . '/footer';

        return $this->render( $footerView, [ 'widget' => $this ] );
	}
}
