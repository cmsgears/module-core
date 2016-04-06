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

		// Messages --------------------------------------------------------

		// Generic Messages
		CoreGlobal::MESSAGE_REQUEST => 'Your request was processed successfully.',

		// Messages - Create/Register User
		CoreGlobal::MESSAGE_REGISTER => 'Thanks for creating your account. A confirmation email having activation link was sent to the given email address.',
		CoreGlobal::MESSAGE_ACCOUNT_CONFIRM => 'Thanks for confirming your account. Please login to continue with us.',
		CoreGlobal::MESSAGE_FORGOT_PASSWORD => 'A confirmation email having password reset link was sent to the given email address.',
		CoreGlobal::MESSAGE_RESET_PASSWORD => 'Your password reset request was processed successfully. Please login to continue with us.',

		// Errors ----------------------------------------------------------

		// Errors - Generic
		CoreGlobal::ERROR_REQUEST => 'Your request was not processed. Please correct the highlighted errors and submit again.',
		CoreGlobal::ERROR_NOT_FOUND => 'The requested resource does not exist.',
		CoreGlobal::ERROR_NOT_ALLOWED => 'You are not allowed to perform this action.',
		CoreGlobal::ERROR_EXIST => 'An entry with the same name already exist. Please provide a different name.',
		CoreGlobal::ERROR_SELECT => 'Please choose a valid value.',
		CoreGlobal::ERROR_URL_ENTITY => 'An entry with the same url already exist.',
		CoreGlobal::ERROR_DEPENDENCY => 'This entry can not be deleted since other rows in other tables are dependent on it.',
		CoreGlobal::ERROR_SESSION_OVER => 'User related to this account not found. Most probably session is over.',

		// Errors - Validators
		CoreGlobal::ERROR_AN_SPACE => 'Please provide a valid value without any special character.',
		CoreGlobal::ERROR_AN_SPACE_U => 'Please provide a valid value without any special character except underscore(_).',
		CoreGlobal::ERROR_AN_PUN => "Please provide valid value having alphanumeric and (? ! . , \" ').",
		CoreGlobal::ERROR_AN_HYPHEN => 'Please provide valid value having alphanumeric and hyphen.',
		CoreGlobal::ERROR_AN_HYPHEN_SPACE => 'Please provide valid value having alphanumeric, space and hyphen.',
		CoreGlobal::ERROR_AN_DOT_U => 'Please provide valid value having alphanumeric, dot(.) or underscore(_) characters.',
		CoreGlobal::ERROR_AN_U => 'Please provide valid value having alphanumeric or underscore(_) characters.',
		CoreGlobal::ERROR_PHONE => 'Please provide a valid number having numeric digits or hyphen(-), space or plus(+) at beginning.',

		// Errors - Create/Register User
		CoreGlobal::ERROR_EMAIL_EXIST => 'A user already exist with the same Email.',
		CoreGlobal::ERROR_USERNAME_EXIST => 'A user already exist with the same username.',
		CoreGlobal::ERROR_PASSWORD => 'Please provide a valid password having at least 5 characters.',
		CoreGlobal::ERROR_TERMS => 'Please accept our terms and conditions to continue.',

		// Errors - User Login
		CoreGlobal::ERROR_USER_NOT_EXIST => 'A user with the provided email is not registered.',
		CoreGlobal::ERROR_USER_VERIFICATION	=> 'The account with this email is not confirmed yet. Please follow the mail sent while registration or try to reset password.',
		CoreGlobal::ERROR_BLOCKED => 'The account is blocked by Admin. Please contact Admin to re-activate it.',
		CoreGlobal::ERROR_LOGIN_FAILED => 'The provided username and password does not match.',
		CoreGlobal::ERROR_PERMISSION => 'Not permitted',

		// Errors - User Account
		CoreGlobal::ERROR_PASSWORD_OLD => 'Please provide valid value for old password.',
		CoreGlobal::ERROR_ACCOUNT_CONFIRM => 'Either your account does not exist or the confirmation link is not valid. Please try to reset your password.',
		CoreGlobal::ERROR_PASSWORD_RESET => 'Either your account does not exist or the reset link is not valid. Please try to reset your password.',
		CoreGlobal::ERROR_CHANGE_EMAIL => 'Email change is not allowed.',
		CoreGlobal::ERROR_CHANGE_USERNAME => 'Username change is not allowed.',

		// Errors - Apis
		CoreGlobal::ERROR_APIS_DISABLED => 'APIs are not supported by this application.',

		// Errors - View
		CoreGlobal::ERROR_NO_TEMPLATE => 'No teplate defined.',
		CoreGlobal::ERROR_NO_VIEW => 'Layout or view is missing.',

		// Model Fields ----------------------------------------------------

		// Generic Fields
		CoreGlobal::FIELD_CODE => 'Code',
		CoreGlobal::FIELD_NAME => 'Name',
		CoreGlobal::FIELD_LABEL => 'Label',
		CoreGlobal::FIELD_TITLE => 'Title',
		CoreGlobal::FIELD_ACTIVE => 'Active',
		CoreGlobal::FIELD_VALUE => 'Value',
		CoreGlobal::FIELD_MESSAGE => 'Message',
		CoreGlobal::FIELD_MESSAGE_SUCCESS => 'Success Message',
		CoreGlobal::FIELD_MESSAGE_FAILURE => 'Failure Message',
		CoreGlobal::FIELD_DESCRIPTION => 'Description',
		CoreGlobal::FIELD_SESSION => 'Session',
		CoreGlobal::FIELD_SLUG => 'Slug',
		CoreGlobal::FIELD_LIMIT => 'Limit',
		CoreGlobal::FIELD_CREATED_AT => 'Created At',
		CoreGlobal::FIELD_MODIFIED_AT => 'Updated At',
		CoreGlobal::FIELD_VISIBILITY => 'Visibility',
		CoreGlobal::FIELD_STATUS => 'Status',
		CoreGlobal::FIELD_TYPE => 'Type',
		CoreGlobal::FIELD_VALUE_TYPE => 'Value Type',
		CoreGlobal::FIELD_VALIDATORS => 'Validators',
		CoreGlobal::FIELD_CONSUMED => 'Consumed',
		CoreGlobal::FIELD_ORDER => 'Order',
		CoreGlobal::FIELD_FEATURED => 'Featured',
		CoreGlobal::FIELD_DEFAULT => 'Default',
		CoreGlobal::FIELD_TIME => 'Time',
		CoreGlobal::FIELD_ICON => 'Icon',
		CoreGlobal::FIELD_ROLE => 'Role',
		CoreGlobal::FIELD_PERMISSION => 'Permission',
		CoreGlobal::FIELD_GENDER => 'Gender',
		CoreGlobal::FIELD_PARENT => 'Parent',
		CoreGlobal::FIELD_PARENT_TYPE => 'Parent Type',
		CoreGlobal::FIELD_LOCALE => 'Locale',
		CoreGlobal::FIELD_PROVINCE => 'Province',
		CoreGlobal::FIELD_COUNTRY => 'Country',
		CoreGlobal::FIELD_CATEGORY => 'category',
		CoreGlobal::FIELD_FILE => 'File',
		CoreGlobal::FIELD_TAG => 'Tag',
		CoreGlobal::FIELD_AVATAR => 'Avatar',
		CoreGlobal::FIELD_AVATAR_URL => 'Avatar URL',
		CoreGlobal::FIELD_WEBSITE => 'Website',
		CoreGlobal::FIELD_BANNER => 'Banner',
		CoreGlobal::FIELD_VIDEO => 'Video',
		CoreGlobal::FIELD_GALLERY => 'Gallery',
		CoreGlobal::FIELD_USER => 'User',
		CoreGlobal::FIELD_AUTHOR => 'Author',
		CoreGlobal::FIELD_MEMBER => 'Member',
		CoreGlobal::FIELD_OWNER => 'Owner',
		CoreGlobal::FIELD_TEMPLATE => 'Template',
		CoreGlobal::FIELD_RENDERER => 'Render Engine',
		CoreGlobal::FIELD_EVENT => 'Event',
		CoreGlobal::FIELD_EVENT_LOG => 'Event Log',
		CoreGlobal::FIELD_DATE_START => 'Start Date',
		CoreGlobal::FIELD_DATE_END => 'End Date',
		CoreGlobal::FIELD_TIME_START => 'Start Time',
		CoreGlobal::FIELD_TIME_END => 'End Time',
		CoreGlobal::FIELD_DAY_WEEK => 'Week Day',
		CoreGlobal::FIELD_DAY_MONTH => 'Month Day',
		CoreGlobal::FIELD_IP => 'IP Address',
		CoreGlobal::FIELD_AGENT_BROWSER => 'Browser Agent',
		CoreGlobal::FIELD_DATA => 'Data',
		CoreGlobal::FIELD_HTML_OPTIONS => 'HTML Options',
		CoreGlobal::FIELD_COMPRESS => 'Store Compressed',

		// Role Fields
		CoreGlobal::FIELD_HOME_URL => 'Home Url',

		// Address Fields
		CoreGlobal::FIELD_LINE1 => 'Line 1',
		CoreGlobal::FIELD_LINE2 => 'Line 2',
		CoreGlobal::FIELD_LINE3 => 'Line 3',
		CoreGlobal::FIELD_CITY => 'City',
		CoreGlobal::FIELD_ZIP => 'Postal Code',
		CoreGlobal::FIELD_PHONE => 'Phone',
		CoreGlobal::FIELD_FAX => 'Fax',
		CoreGlobal::FIELD_LONGITUDE => 'Longitude',
		CoreGlobal::FIELD_LATITUDE => 'Latitude',
		CoreGlobal::FIELD_ZOOM => 'Zoom Level',
		CoreGlobal::FIELD_ADDRESS => 'Address',
		CoreGlobal::FIELD_ADDRESS_TYPE => 'Address Type',

		// User Fields
		CoreGlobal::FIELD_EMAIL => 'Email',
		CoreGlobal::FIELD_USERNAME => 'Username',
		CoreGlobal::FIELD_PASSWORD => 'Password',
		CoreGlobal::FIELD_PASSWORD_REPEAT => 'Confirm Password',
		CoreGlobal::FIELD_FIRSTNAME => 'First Name',
		CoreGlobal::FIELD_LASTNAME => 'Last Name',
		CoreGlobal::FIELD_DOB => 'Date of Birth',
		CoreGlobal::FIELD_TERMS => 'Terms',

		// File Fields
		CoreGlobal::FIELD_EXTENSION => 'Extension',
		CoreGlobal::FIELD_DIRECTORY => 'Directory',
		CoreGlobal::FIELD_SIZE => 'Size',
		CoreGlobal::FIELD_URL => 'Url',
		CoreGlobal::FIELD_LINK => 'Link',

		// Notification/Reminder/Message Fields
		CoreGlobal::FIELD_NOTIFIER => 'Notifier',
		CoreGlobal::FIELD_SENDER => 'Sender',
		CoreGlobal::FIELD_RECIPIENT => 'Recipient',

		// Site/Site Member Fields
		CoreGlobal::FIELD_SITE => 'Site',

		// Content Fields
		CoreGlobal::FIELD_SUMMARY => 'Summary',
		CoreGlobal::FIELD_CONTENT => 'Content',

		// Views
		CoreGlobal::FIELD_LAYOUT => 'Layout',
		CoreGlobal::FIELD_STYLE => 'Style',
		CoreGlobal::FIELD_BASE_PATH => 'Base Path',
		CoreGlobal::FIELD_VIEW_PATH => 'View Path',
		CoreGlobal::FIELD_VIEW_COUNT => 'View Count',

		// Forms
		CoreGlobal::FIELD_FORM => 'Form',
		CoreGlobal::FIELD_CAPTCHA => 'Captcha',
		CoreGlobal::FIELD_MAIL_USER => 'Send User Mail',
		CoreGlobal::FIELD_MAIL_ADMIN => 'Send Admin Mail',
		CoreGlobal::FIELD_META => 'Meta',
		CoreGlobal::FIELD_RATING => 'Rating',

		// Visibility
		CoreGlobal::FIELD_PRIVATE => 'Private',
		CoreGlobal::FIELD_PUBLIC => 'Public'
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