<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\widgets\mappers;

// Yii Imports
use Yii;
use yii\helpers\Html;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * ObjectMapper is the base widget to map objects to model.
 *
 * @since 1.0.0
 */
class ObjectMapper extends \cmsgears\core\common\base\Widget {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Object models
	public $objects = [];

	// Type to be used to search objects for mapping.
	public $type = CoreGlobal::TYPE_SYSTEM;

	public $parentType = null;

	// Flag to search object for given type in case object models not provided or empty.
	public $searchByType = true;

	// The model using Object Trait
	public $model;

	public $binderModel	= 'ObjectBinder';
	public $mapToColumn	= false;
	public $columnName	= null;

	// Notes to help user in choosing objects.
	public $notes = '<b>Notes</b>: Choose at least one object to map.';

	// Flag to show notes
	public $showNotes = true;

	// Input type among checkbox, radio to render the chooser.
	public $inputType = 'checkbox';

	// Disable all the rendered objects.
	public $disabled = false;

	// Serach using model object service instead of trait
	public $service = false;

	// Protected --------------

	// Private ----------------

	private $objectService;

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->objectService = Yii::$app->factory->get( 'objectDataService' );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Widget --------

	public function run() {

		// Execute query only if objects are not provided and search by type is enabled.
		if( count( $this->objects ) == 0 && $this->searchByType ) {

			$this->objects = $this->objectService->getByType( $this->type );
		}

		// Configure parent type
		$this->parentType = isset( $this->parentType ) ? $this->parentType : $this->type;

		return $this->renderWidget();
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ObjectMapper --------------------------

	public function renderWidget( $config = [] ) {

		$widgetHtml = $this->render( $this->template, [ 'widget' => $this ] );

		if( $this->wrap ) {

			return Html::tag( $this->wrapper, $widgetHtml, $this->options );
		}

		return $widgetHtml;
	}

}
