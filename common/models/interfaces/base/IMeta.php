<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\interfaces\base;

/**
 * The interface IMeta provide globals and methods for model meta and meta tables.
 *
 * @since 1.0.0
 */
interface IMeta {

	const VALUE_TYPE_TEXT		= 'text';
	const VALUE_TYPE_FLAG		= 'flag';
	const VALUE_TYPE_LIST		= 'list';
	const VALUE_TYPE_MAP		= 'map';
	const VALUE_TYPE_CSV		= 'csv';
	const VALUE_TYPE_OBJECT		= 'json';
	const VALUE_TYPE_HTML		= 'html';
	const VALUE_TYPE_MARKDOWN	= 'markdown';

	/**
	 * Generate and return label using meta name.
	 *
	 * It replace underscore(_) by space and update first letter of words to uppercase.
	 *
	 * @return string generated label.
	 */
	public function generateLabel();

	/**
	 * Check whether value type is text.
	 *
	 * @return boolean
	 */
	public function isText();

	/**
	 * Check whether value type is boolean i.e. 0 or 1.
	 *
	 * @return boolean
	 */
	public function isFlag();

	/**
	 * Check whether value type is list i.e. indexed array.
	 *
	 * @return boolean
	 */
	public function isList();

	/**
	 * Check whether value type is map i.e. associative array.
	 *
	 * @return boolean
	 */
	public function isMap();

	/**
	 * Check whether value type is csv i.e. string having comma separated values.
	 *
	 * @return boolean
	 */
	public function isCsv();

	/**
	 * Check whether value type is object i.e. JOSN.
	 *
	 * @return boolean
	 */
	public function isObject();

	/**
	 * Check whether value type is HTML.
	 *
	 * @return boolean
	 */
	public function isHtml();

	/**
	 * Check whether value type is Markdown.
	 *
	 * @return boolean
	 */
	public function isMarkdown();

	/**
	 * Convert and return the value stored in [[value]] using [[valueType]].
	 *
	 * @return mixed Converted value.
	 */
	public function getFieldValue();

	/**
	 * Generate and return the map having label, name and value as keys.
	 *
	 * @return array Map of label, name and value.
	 */
	public function getFieldInfo();
}
