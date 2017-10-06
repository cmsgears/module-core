<?php
namespace cmsgears\core\common\base;

// Yii Imports
use Yii;
use yii\helpers\Html;
use yii\base\InvalidConfigException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\CodeGenUtil;

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

	public $wrap		= true;

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

	public $limits		= [ 12, 16, 20, 24, 28, 32, 36 ]; // multiples of 4
	public $limit		= 12; // default limit
	public $limitParam	= 'per-page';

	// Grid Title
	public $title;

	// Flag to show/hide add button
	public $add			= true;

	// Url to add model
	public $addUrl		= 'add';

	// Add using popup
	public $addPopup	= false;

	// Widget Sections
	public $sort	= true;	// Show sort options
	public $search	= true;	// Show search options
	public $filter	= true; // Show grid filters
	public $bulk	= true; // Show bulk options
	public $report	= true; // Show report options

	public $head	= true; // Show grid title
	public $option	= true; // Show grid options - bulk, search

	public $header	= true; // Show grid header
	public $footer	= true; // Show grid footer

	// Widget Views
	public $sortView		= 'sort';
	public $searchView		= 'search';
	public $filtersView		= 'filters';
	public $bulkView		= 'bulk';
	public $reportView		= 'report';

	public $headView		= 'head';
	public $optionsView		= 'options';

	public $headerView		= 'header';

	public $dataView		= 'data';
	public $cardView		= 'cards';
	public $actionView		= 'actions';

	public $footerView		= 'footer';

	public $gridView		= 'grid';

	// Sorting - columns available for sorting
	public $sortColumns		= [];

	// Searching - columns available for searching
	public $searchColumns	= [];

	// Quick Filters - filter the data
	public $filters			= [];

	// Bulk Options
	public $bulkActions		= [];
	public $bulkPopup		= 'popup-bulk';

	// Reporting - generate report
	public $reportColumns	= [];
	public $dateClass		= 'datepicker';

	// Grid Data
	public $noDataMessage = "No rows found."; // Message displayed in case data is not available

	// Grid Layouts
	public $table	= false;	// Flag to check whether html table is required
	public $grid	= true;		// Flag to check whether responsive grid is required
	public $card	= true;		// Flag to check whether responsive card layout is required

	public $layout;

	// Notes:
	// 1. Either of the table or grid can be true
	// 2. If both grid and card are true, a toggle button will be displayed to toggle between grid and card view

	// Grid Columns
	public $columns		= []; // Required only for grid layout to configure responsive grid
	public $gridColumns	= []; // Grid columns
	public $cardColumns	= []; // Configure only if specific columns are required for card view

	// Grid Cards
	public $gridCards	= [];

	// Grid Actions
	public $actions		= [];

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
		$limit	= Yii::$app->request->getQueryParam( $this->limitParam );

		if( isset( $limit ) && in_array( $limit, $this->limits ) ) {

			$this->limit = $limit;
		}

		if( isset( $this->dataProvider ) ) {

			$this->dataProvider->pagination->pageSize = $this->limit;
		}

		// Layout
		$user			= Yii::$app->user->getIdentity();
		$gridLayout		= isset( $user ) ? $user->getDataMeta( CoreGlobal::DATA_GRID_LAYOUT ) : null;
		$this->layout	= isset( $gridLayout ) ? $gridLayout : ( isset( $this->layout ) ? $this->layout : 'data' );

		// Views
		$this->actionView	= CodeGenUtil::isAbsolutePath( $this->actionView ) ? $this->actionView : "$this->template/$this->actionView";

		$sortHtml		= $this->sort ? $this->renderSort( $config ) : null;

		$searchHtml		= $this->search ? $this->renderSearch( $config ) : null;

		$filtersHtml	= $this->filter ? $this->renderFilters( $config ) : null;

		$bulkHtml		= $this->bulk ? $this->renderBulk( $config ) : null;

		$reportHtml		= $this->report ? $this->renderReport( $config ) : null;

		$headHtml		= $this->head ? $this->renderHead( $config, [ 'sortHtml' => $sortHtml, 'searchHtml' => $searchHtml, 'filtersHtml' => $filtersHtml, 'bulkHtml' => $bulkHtml ] ) : null;

		$optionsHtml	= $this->option ? $this->renderOptions( $config, [ 'sortHtml' => $sortHtml, 'searchHtml' => $searchHtml, 'filtersHtml' => $filtersHtml, 'bulkHtml' => $bulkHtml ] ) : null;

		$headerHtml		= $this->header ? $this->renderHeader( $config ) : null;

		$dataHtml		= $this->grid ? $this->renderData( $config ) : null;

		$cardHtml		= $this->card ? $this->renderCards( $config ) : null;

		$footerHtml		= $this->footer ? $this->renderFooter( $config ) : null;

		$gridView		= CodeGenUtil::isAbsolutePath( $this->gridView ) ? $this->gridView : "$this->template/$this->gridView";

		$widgetHtml 	= $this->render( $gridView, [
								'widget' => $this,
								'sortHtml' => $sortHtml, 'searchHtml' => $searchHtml, 'filtersHtml' => $filtersHtml,
								'bulkHtml' => $bulkHtml, 'reportHtml' => $reportHtml, 'headHtml' => $headHtml, 'optionsHtml' => $optionsHtml,
								'headerHtml' => $headerHtml, 'dataHtml' => $dataHtml, 'cardHtml' => $cardHtml, 'footerHtml' => $footerHtml
							]);

		if( $this->wrap ) {

			return Html::tag( $this->wrapper, $widgetHtml, $this->options );
		}

        return $widgetHtml;
	}

	// GridWidget ----------------------------

	public function renderSort( $config = [] ) {

		$sortView		= CodeGenUtil::isAbsolutePath( $this->sortView ) ? $this->sortView : "$this->template/$this->sortView";

        return $this->render( $sortView, [ 'widget' => $this ] );
	}

	public function renderSearch( $config = [] ) {

		$searchView		= CodeGenUtil::isAbsolutePath( $this->searchView ) ? $this->searchView : "$this->template/$this->searchView";

        return $this->render( $searchView, [ 'widget' => $this ] );
	}

	public function renderFilters( $config = [] ) {

		$filtersView	= CodeGenUtil::isAbsolutePath( $this->filtersView ) ? $this->filtersView : "$this->template/$this->filtersView";

        return $this->render( $filtersView, [ 'widget' => $this ] );
	}

	public function renderBulk( $config = [] ) {

		$bulkView		= CodeGenUtil::isAbsolutePath( $this->bulkView ) ? $this->bulkView : "$this->template/$this->bulkView";

        return $this->render( $bulkView, [ 'widget' => $this ] );
	}

	public function renderReport( $config = [] ) {

		$reportView		= CodeGenUtil::isAbsolutePath( $this->reportView ) ? $this->reportView : "$this->template/$this->reportView";

        return $this->render( $reportView, [ 'widget' => $this ] );
	}

	public function renderHead( $config = [], $html = [] ) {

		$headView		= CodeGenUtil::isAbsolutePath( $this->headView ) ? $this->headView : "$this->template/$this->headView";

        return $this->render( $headView, [
			'widget' => $this, 'sortHtml' => $html[ 'sortHtml' ], 'searchHtml' => $html[ 'searchHtml' ],
			'filtersHtml' => $html[ 'filtersHtml' ], 'bulkHtml' => $html[ 'bulkHtml' ]
		]);
	}

	public function renderOptions( $config = [], $html = [] ) {

		$optionsView	= CodeGenUtil::isAbsolutePath( $this->optionsView ) ? $this->optionsView : "$this->template/$this->optionsView";

        return $this->render( $optionsView, [
			'widget' => $this, 'sortHtml' => $html[ 'sortHtml' ], 'searchHtml' => $html[ 'searchHtml' ],
			'filtersHtml' => $html[ 'filtersHtml' ], 'bulkHtml' => $html[ 'bulkHtml' ]
		]);
	}

	public function renderHeader( $config = [] ) {

		$headerView		= CodeGenUtil::isAbsolutePath( $this->headerView ) ? $this->headerView : "$this->template/$this->headerView";

        return $this->render( $headerView, [ 'widget' => $this ] );
	}

	public function renderData( $config = [] ) {

		$dataView		= CodeGenUtil::isAbsolutePath( $this->dataView ) ? $this->dataView : "$this->template/$this->dataView";

        return $this->render( $dataView, [ 'widget' => $this ] );
	}

	public function renderCards( $config = [] ) {

		$cardView		= CodeGenUtil::isAbsolutePath( $this->cardView ) ? $this->cardView : "$this->template/$this->cardView";

        return $this->render( $cardView, [ 'widget' => $this ] );
	}

	public function renderFooter( $config = [] ) {

		$footerView		= CodeGenUtil::isAbsolutePath( $this->footerView ) ? $this->footerView : "$this->template/$this->footerView";

        return $this->render( $footerView, [ 'widget' => $this ] );
	}
}
