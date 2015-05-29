<?php
namespace cmsgears\core\common\components;

// Yii Imports
use \Yii;
use yii\base\Component;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class MessageSource extends Component {

	// Variables ---------------------------------------------------

	/**
	 * The local message DB to be used directly in case file or database is not required.
	 */
	private $messageDb = [
		// Generic Messages
		CoreGlobal::MESSAGE_REQUEST => 'Your request was processed successfully.',
		// Messages - Create/Register User
		CoreGlobal::MESSAGE_REGISTER => 'Thanks for creating your account. A confirmation email having activation link was sent to the given email address.',
		CoreGlobal::MESSAGE_ACCOUNT_CONFIRM => 'Thanks for confirming your account. Please login to continue with us.',
		CoreGlobal::MESSAGE_FORGOT_PASSWORD => 'A confirmation email having password reset link was sent to the given email address.',
		CoreGlobal::MESSAGE_RESET_PASSWORD => 'Your password reset request was processed successfully. Please login to continue with us.',
		// Errors - Generic
		CoreGlobal::ERROR_REQUEST => 'Your request was not processed. Please correct the highlighted errors and submit again.',
		CoreGlobal::ERROR_NOT_FOUND => 'The requested resource does not exist.',
		CoreGlobal::ERROR_NOT_ALLOWED => 'You are not allowed to perform this action.',
		CoreGlobal::ERROR_EXIST => 'An entry with the same name already exist. Please provide a different name.',
		CoreGlobal::ERROR_SELECT => 'Please choose a valid value.',
		CoreGlobal::ERROR_URL_ENTITY => 'An entry with the same url already exist.',
		// Errors - Validators
		CoreGlobal::ERROR_AN_SPACE => 'Please provide a valid value without any special character.',
		CoreGlobal::ERROR_AN_PUN => "Please provide valid value having alphanumeric and (? ! . , \" ').",
		CoreGlobal::ERROR_AN_HYPHEN => 'Please provide valid value having alphanumeric and hyphen.',
		CoreGlobal::ERROR_AN_HYPHEN_SPACE => 'Please provide valid value having alphanumeric, space and hyphen.',
		CoreGlobal::ERROR_AN_DOT_U => 'Please provide valid value having alphanumeric, dot(.) or underscore(_) characters.',
		CoreGlobal::ERROR_PHONE => 'Please provide a valid number having numeric digits or hyphen(-), space or plus(+) at beginning.',
		// Errors - Create/Register User
		CoreGlobal::ERROR_EMAIL_EXIST => 'A user already exist with the same Email.',
		CoreGlobal::ERROR_USERNAME_EXIST => 'A user already exist with the same username.',
		CoreGlobal::ERROR_PASSWORD => 'Please provide a valid password having at least 5 characters.',
		CoreGlobal::ERROR_TERMS => 'Please accept our terms and conditions to continue.',
		CoreGlobal::ERROR_USER_NOT_EXIST => 'A user with the provided email is not registered.',	
		CoreGlobal::ERROR_USER_VERIFICATION	=> 'The account with this email is not confirmed yet. Please follow the mail sent while registration or try to reset password.',
		CoreGlobal::ERROR_BLOCKED => 'The account is blocked by Admin. Please contact Admin to re-activate it.',
		CoreGlobal::ERROR_LOGIN_FAILED => 'The provided username and password does not match.',
		// Errors - User Account
		CoreGlobal::ERROR_ACCOUNT_CONFIRM => 'Either your account does not exist or the confirmation link is not valid. Please try to reset your password.',
		CoreGlobal::ERROR_PASSWORD_RESET => 'Either your account does not exist or the reset link is not valid. Please try to reset your password.',
		// Errors - Apis
		CoreGlobal::ERROR_APIS_DISABLED => "APIs are not supported by this application.",
		// Model Fields ----------------------------------------------------
		// Generic Fields
		CoreGlobal::FIELD_CODE => "Code",
		CoreGlobal::FIELD_NAME => "Name",
		CoreGlobal::FIELD_TITLE => "Title",
		CoreGlobal::FIELD_VALUE => "Value",
		CoreGlobal::FIELD_MESSAGE => "Message",
		CoreGlobal::FIELD_DESCRIPTION => "Description",
		CoreGlobal::FIELD_SLUG => "Slug",
		CoreGlobal::FIELD_CREATED_AT => "Created At",
		CoreGlobal::FIELD_MODIFIED_AT => "Updated At",
		CoreGlobal::FIELD_VISIBILITY => "Visibility",
		CoreGlobal::FIELD_STATUS => "Status",
		CoreGlobal::FIELD_TYPE => "Type",
		CoreGlobal::FIELD_MARK => "Mark",
		CoreGlobal::FIELD_ORDER => "Order",
		CoreGlobal::FIELD_TIME => "Time",
		CoreGlobal::FIELD_ICON => "Icon",
		CoreGlobal::FIELD_CONTENT => "Content",
		CoreGlobal::FIELD_ROLE => "Role",
		CoreGlobal::FIELD_PERMISSION => "Permission",
		CoreGlobal::FIELD_GENDER => "Gender",
		CoreGlobal::FIELD_PARENT => "Parent",
		CoreGlobal::FIELD_PARENT_TYPE => "Parent Type",
		CoreGlobal::FIELD_LOCALE => "Locale",
		CoreGlobal::FIELD_PROVINCE => "Province",
		CoreGlobal::FIELD_COUNTRY => "Country",
		CoreGlobal::FIELD_CATEGORY => "category",
		CoreGlobal::FIELD_FILE => "File",
		CoreGlobal::FIELD_TAG => "Tag",
		CoreGlobal::FIELD_AVATAR => "Avatar",
		CoreGlobal::FIELD_BANNER => "Banner",
		CoreGlobal::FIELD_USER => "User",
		CoreGlobal::FIELD_AUTHOR => "Author",
		// Role Fields
		CoreGlobal::FIELD_HOME_URL => "Home Url",
		// Address Fields
		CoreGlobal::FIELD_LINE1 => "Line 1",
		CoreGlobal::FIELD_LINE2 => "Line 2",
		CoreGlobal::FIELD_LINE3 => "Line 3",
		CoreGlobal::FIELD_CITY => "City",
		CoreGlobal::FIELD_ZIP => "Postal Code",
		CoreGlobal::FIELD_PHONE => "Phone",
		CoreGlobal::FIELD_FAX => "Fax",
		CoreGlobal::FIELD_ADDRESS => "Address",
		CoreGlobal::FIELD_ADDRESS_TYPE => "Address Type",
		// User Fields
		CoreGlobal::FIELD_EMAIL => "Email",
		CoreGlobal::FIELD_USERNAME => "Username",
		CoreGlobal::FIELD_FIRSTNAME => "First Name",
		CoreGlobal::FIELD_LASTNAME => "Last Name",
		CoreGlobal::FIELD_DOB => "Date of Birth",
		CoreGlobal::FIELD_NEWSLETTER => "Newsletter",
		// File Fields
		CoreGlobal::FIELD_EXTENSION => "Extension",
		CoreGlobal::FIELD_DIRECTORY => "Directory",
		CoreGlobal::FIELD_URL => "Url",
		CoreGlobal::FIELD_LINK => "Link",
		// Notification/Reminder Fields
		CoreGlobal::FIELD_NOTIFIER => "Notifier",
		// Site/Site Member Fields
		CoreGlobal::FIELD_SITE => "Site",
		// Meta Fields
		CoreGlobal::FIELD_FFIELD_TYPE => "Field Type",
		CoreGlobal::FIELD_FFIELD_META => "Field Meta"
	];

	/**
	 * Initialise the Core Message DB Component.
	 */
    public function init() {

        parent::init();
    }

	/**
	 * Returns message using the message templates declared within $messageDb.
	 */
	public function getMessage( $messageKey, $params = [], $language = null ) {

		// TODO: Use Yii internationalisation to support languages
		return $this->messageDb[ $messageKey ];
	}
}

?>