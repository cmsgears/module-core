<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\widgets\mappers;

/**
 * ObjectSuggest maps objects to models using auto-suggest.
 *
 * @since 1.0.0
 */
abstract class ObjectSuggest extends ObjectMapper {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Mapping type to classify the mapping
	public $ctype;

	public $label;

	// Search not required for auto-suggest
	public $searchByType = false;

	// Level not required for auto-suggest
	public $levelList = false;

	// Input not required for auto-suggest
	public $inputType = 'none';

	public $notes = '<b>Notes</b>: Type in search box to filter objects and select the object to map.';

	public $showNotes = true;

	// Override default view path
	public $template = 'suggest';

	public $mapperTemplate = 'objectMapperTemplate';

	public $modelObjects = [];

	// Application
	public $app = 'mapper';

	// Controller where mapping request need to be triggered
	public $controller = 'model';

	// Controller action to handle the search request
	public $action = 'autoSearch';

	// Explicit URL to handle the controller search action request
	public $actionUrl = 'core/object/auto-search';

	// Controller action to handle the mapping request
	public $mapAction = 'mapItem';

	// Explicit URL to handle the controller mapping action request
	public $mapActionUrl = null;

	// Controller action to handle the delete request
	public $deleteAction = 'deleteItem';

	// Explicit URL to handle the controller delete action request
	public $deleteActionUrl	= null;

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Widget --------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ObjectSuggest -------------------------

}
