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
use Yii;
use yii\helpers\Html;
use yii\base\InvalidConfigException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\UrlUtil;

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

	public $wrap = true;

	public $template = 'simple';

	// HTML options used by the widget div
	public $options = [ 'class' => 'grid-data' ];

	// Additional data
	public $data = [];

	public $provider = true; // Flag to check whether data provider is required

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
	public $add = true;

	// Base URL to form sub URLs
	public $baseUrl = null;

	// Url to add model
	public $addUrl = 'add';

	// Add using popup
	public $addPopup = false;

	// Widget Sections
	public $sort	= true;	// Show sort options
	public $search	= true;	// Show search options
	public $filter	= true; // Show grid filters
	public $bulk	= true; // Show bulk options
	public $import	= false; // Show import options
	public $export	= false; // Show export options
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
	public $importView		= 'import';
	public $exportView		= 'export';
	public $reportView		= 'report';

	public $headView	= 'head';
	public $optionsView	= 'options';

	public $headerView = 'header';

	public $dataView	= 'data';
	public $listView	= 'list';
	public $cardView	= 'cards';
	public $actionView	= 'actions';

	public $footerView = 'footer';

	public $gridView = 'grid';

	// Sorting - columns available for sorting
	public $sortColumns = [];

	// Searching - columns available for searching
	public $searchColumns = [];

	// Quick Filters - filter the data
	public $filters = [];

	// Bulk Options
	public $bulkActions = [];
	public $bulkPopup	= 'popup-bulk';

	// Reporting - generate report
	public $reportColumns	= [];
	public $dateClass		= 'datepicker';

	// Grid Data
	public $noDataMessage = "No rows found."; // Message displayed in case data is not available

	// Grid Layouts
	public $grid	= true;		// Flag to check whether responsive grid is required
	public $table	= false;	// Flag to check whether html table is required
	public $list	= false;	// Flag to check whether responsive grid using list is required
	public $card	= true;		// Flag to check whether responsive card layout is required

	public $layout = 'data'; // It can be data, table, list or card

	// Notes:
	// 1. Either of the table or grid can be true
	// 2. If both grid and card are true, a toggle button will be displayed to toggle between grid and card view

	// Grid Columns
	public $columns		= []; // Required only for grid layout to configure responsive grid
	public $gridColumns	= []; // Grid columns
	public $cardColumns	= []; // Configure only if specific columns are required for card view

	// Grid Cards
	public $gridCards = [];

	// Grid Actions
	public $actions = [];

	// Grid having static columns
	public $fixedColumns = [];

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
		$limit = Yii::$app->request->getQueryParam( $this->limitParam );

		if( isset( $limit ) && in_array( $limit, $this->limits ) ) {

			$this->limit = $limit;
		}

		if( isset( $this->dataProvider ) ) {

			$this->dataProvider->pagination->pageSize = $this->limit;
		}

		// Layout
		$user		= Yii::$app->user->getIdentity();
		$uLayout	= $user->getDataConfigMeta( CoreGlobal::DATA_GRID_LAYOUT );
		$layout		= Yii::$app->request->getQueryParam( 'layout' );

		$gridLayout	= isset( $user ) && isset( $uLayout ) ? $uLayout : $this->layout;
		$gridLayout	= isset( $layout ) && in_array( $layout, [ 'data', 'table', 'list', 'card' ] ) ? $layout : $gridLayout;

		$gridLayout = $this->grid && $gridLayout == 'data' ? 'data' : $gridLayout;
		$gridLayout = $this->table && $gridLayout == 'table' ? 'table' : $gridLayout;
		$gridLayout = $this->list && $gridLayout == 'list' ? 'list' : $gridLayout;
		$gridLayout = $this->card && $gridLayout == 'card' ? 'card' : $gridLayout;

		$this->layout = $gridLayout;

		// Views
		$this->actionView = UrlUtil::isAbsolutePath( $this->actionView ) ? $this->actionView : "$this->template/$this->actionView";

		$sortHtml		= $this->sort ? $this->renderSort( $config ) : null;

		$searchHtml		= $this->search ? $this->renderSearch( $config ) : null;

		$filtersHtml	= $this->filter ? $this->renderFilters( $config ) : null;

		$bulkHtml		= $this->bulk ? $this->renderBulk( $config ) : null;

		$importHtml		= $this->import ? $this->renderImport( $config ) : null;

		$exportHtml		= $this->export ? $this->renderExport( $config ) : null;

		$reportHtml		= $this->report ? $this->renderReport( $config ) : null;

		$headHtml = $this->head ? $this->renderHead( $config, [
			'sortHtml' => $sortHtml, 'searchHtml' => $searchHtml,
			'filtersHtml' => $filtersHtml, 'bulkHtml' => $bulkHtml ] ) : null;

		$optionsHtml = $this->option ? $this->renderOptions( $config, [
			'sortHtml' => $sortHtml, 'searchHtml' => $searchHtml,
			'filtersHtml' => $filtersHtml, 'bulkHtml' => $bulkHtml ] ) : null;

		$headerHtml = $this->header ? $this->renderHeader( $config ) : null;

		$dataHtml	= $this->grid && $this->layout == 'data' ? $this->renderData( $config ) : null;
		$dataHtml	= $this->table && $this->layout == 'table' ? $this->renderData( $config ) : $dataHtml;

		$listHtml = $this->list && $this->layout == 'list' ? $this->renderList( $config ) : null;

		$cardHtml = $this->card && $this->layout == 'card' ? $this->renderCards( $config ) : null;

		$footerHtml = $this->footer ? $this->renderFooter( $config ) : null;

		$gridView = UrlUtil::isAbsolutePath( $this->gridView ) ? $this->gridView : "$this->template/$this->gridView";

		$widgetHtml = $this->render( $gridView, [
			'widget' => $this,
			'sortHtml' => $sortHtml, 'searchHtml' => $searchHtml, 'filtersHtml' => $filtersHtml,
			'bulkHtml' => $bulkHtml, 'importHtml' => $importHtml, 'exportHtml' => $exportHtml,
			'reportHtml' => $reportHtml, 'headHtml' => $headHtml, 'optionsHtml' => $optionsHtml,
			'headerHtml' => $headerHtml, 'footerHtml' => $footerHtml,
			'dataHtml' => $dataHtml, 'listHtml' => $listHtml, 'cardHtml' => $cardHtml
		]);

		if( $this->wrap ) {

			return Html::tag( $this->wrapper, $widgetHtml, $this->options );
		}

        return $widgetHtml;
	}

	// GridWidget ----------------------------

	public function renderSort( $config = [] ) {

		$sortView = UrlUtil::isAbsolutePath( $this->sortView ) ? $this->sortView : "$this->template/$this->sortView";

        return $this->render( $sortView, [ 'widget' => $this ] );
	}

	public function renderSearch( $config = [] ) {

		$searchView = UrlUtil::isAbsolutePath( $this->searchView ) ? $this->searchView : "$this->template/$this->searchView";

        return $this->render( $searchView, [ 'widget' => $this ] );
	}

	public function renderFilters( $config = [] ) {

		$filtersView = UrlUtil::isAbsolutePath( $this->filtersView ) ? $this->filtersView : "$this->template/$this->filtersView";

        return $this->render( $filtersView, [ 'widget' => $this ] );
	}

	public function renderBulk( $config = [] ) {

		$bulkView = UrlUtil::isAbsolutePath( $this->bulkView ) ? $this->bulkView : "$this->template/$this->bulkView";

        return $this->render( $bulkView, [ 'widget' => $this ] );
	}

	public function renderImport( $config = [] ) {

		$importView = UrlUtil::isAbsolutePath( $this->importView ) ? $this->importView : "$this->template/$this->importView";

        return $this->render( $importView, [ 'widget' => $this ] );
	}

	public function renderExport( $config = [] ) {

		$exportView = UrlUtil::isAbsolutePath( $this->exportView ) ? $this->exportView : "$this->template/$this->exportView";

        return $this->render( $exportView, [ 'widget' => $this ] );
	}

	public function renderReport( $config = [] ) {

		$reportView = UrlUtil::isAbsolutePath( $this->reportView ) ? $this->reportView : "$this->template/$this->reportView";

        return $this->render( $reportView, [ 'widget' => $this ] );
	}

	public function renderHead( $config = [], $html = [] ) {

		$headView = UrlUtil::isAbsolutePath( $this->headView ) ? $this->headView : "$this->template/$this->headView";

        return $this->render( $headView, [
			'widget' => $this, 'sortHtml' => $html[ 'sortHtml' ], 'searchHtml' => $html[ 'searchHtml' ],
			'filtersHtml' => $html[ 'filtersHtml' ], 'bulkHtml' => $html[ 'bulkHtml' ]
		]);
	}

	public function renderOptions( $config = [], $html = [] ) {

		$optionsView = UrlUtil::isAbsolutePath( $this->optionsView ) ? $this->optionsView : "$this->template/$this->optionsView";

        return $this->render( $optionsView, [
			'widget' => $this, 'sortHtml' => $html[ 'sortHtml' ], 'searchHtml' => $html[ 'searchHtml' ],
			'filtersHtml' => $html[ 'filtersHtml' ], 'bulkHtml' => $html[ 'bulkHtml' ]
		]);
	}

	public function renderHeader( $config = [] ) {

		$headerView = UrlUtil::isAbsolutePath( $this->headerView ) ? $this->headerView : "$this->template/$this->headerView";

        return $this->render( $headerView, [ 'widget' => $this ] );
	}

	public function renderData( $config = [] ) {

		$dataView = UrlUtil::isAbsolutePath( $this->dataView ) ? $this->dataView : "$this->template/$this->dataView";

        return $this->render( $dataView, [ 'widget' => $this ] );
	}

	public function renderList( $config = [] ) {

		$listView = UrlUtil::isAbsolutePath( $this->listView ) ? $this->listView : "$this->template/$this->listView";

        return $this->render( $listView, [ 'widget' => $this ] );
	}

	public function renderCards( $config = [] ) {

		$cardView = UrlUtil::isAbsolutePath( $this->cardView ) ? $this->cardView : "$this->template/$this->cardView";

        return $this->render( $cardView, [ 'widget' => $this ] );
	}

	public function renderFooter( $config = [] ) {

		$footerView = UrlUtil::isAbsolutePath( $this->footerView ) ? $this->footerView : "$this->template/$this->footerView";

        return $this->render( $footerView, [ 'widget' => $this ] );
	}

}
