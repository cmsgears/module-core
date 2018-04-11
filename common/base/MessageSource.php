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
use yii\base\Component;
use yii\i18n\MessageFormatter;

/**
 * MessageSource stores and provide the messages and message templates provided by the module.
 * These messages can be generic, errors, warnings and form fields label.
 *
 * @since 1.0.0
 */
class MessageSource extends Component {

	// Variables ---------------------------------------------------

	// Global -----------------

	// Public -----------------

	// Protected --------------

	protected $messageDb = [];

	protected $formatter;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->formatter = new MessageFormatter();
	}

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG parent classes --------------------

	// MessageSource -------------------------

	/**
	 * Find the message corresponding to given message key and returns the formatted
	 * message using the message parameters and language.
	 *
	 * @param string $key
	 * @param array $params
	 * @param string $language
	 * @return string
	 */
	public function getMessage( $key, $params = [], $language = null ) {

		// Retrieve Message
		$message = $this->messageDb[ $key ];

		// Return formatted message
		return $this->formatter->format( $message, $params, $language );
	}

}
