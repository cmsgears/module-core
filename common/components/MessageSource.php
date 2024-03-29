<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\components;

// Yii Imports
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * The MessageSource class provide default messages for pre-defined message keys.
 *
 * It can also read messages stored in db or utilize I18N(internationalization) for these keys.
 *
 * @since 1.0.0
 */
class MessageSource extends \yii\base\Component {

	// TODO: Use resource files to do php internationalization - gettext could be an option. Also look at zend translate.

	// TODO: Read messages from DB based on application locale.

	// TODO: Read messages from DB based on user locale.

	// Variables ---------------------------------------------------

	// Global -----------------

	// Public -----------------

	// Protected --------------

	/**
	 * The local message DB to be used directly in case file or database is not required.
	 */
	protected $messageDb = [

		// Messages --------------------------------------------------------

		// Generic Messages
		CoreGlobal::MESSAGE_REQUEST => 'Your request was processed successfully.',

		// Messages - Create/Register User
		CoreGlobal::MESSAGE_REGISTER => 'Thanks for creating your account. A confirmation email having activation link was sent to the given email address.',
		CoreGlobal::MESSAGE_ACCOUNT_ACTIVATE => null,
		CoreGlobal::MESSAGE_ACCOUNT_CONFIRM => null,
		CoreGlobal::MESSAGE_FORGOT_PASSWORD => 'A confirmation email having password reset link was sent to the given email address.',
		CoreGlobal::MESSAGE_RESET_PASSWORD => null,

		// Errors ----------------------------------------------------------

		// Errors - Generic
		CoreGlobal::ERROR_REQUEST => 'Your request was not processed. Please correct the highlighted errors and submit again.',
		CoreGlobal::ERROR_NOT_FOUND => 'The requested resource does not exist.',
		CoreGlobal::ERROR_NO_ACCESS => 'You are not allowed to access this resource.',
		CoreGlobal::ERROR_NOT_ALLOWED => 'You are not allowed to perform this action.',
		CoreGlobal::ERROR_EXIST => 'A record with the same value already exist. Please provide unique value.',
		CoreGlobal::ERROR_SELECT => 'Please choose a valid value.',
		CoreGlobal::ERROR_URL_ENTITY => 'An entry with the same url already exist.',
		CoreGlobal::ERROR_DEPENDENCY => 'This entry can not be deleted since other rows in other tables are dependent on it.',
		CoreGlobal::ERROR_SESSION_OVER => 'User related to this account not found. Most probably session is over.',
		CoreGlobal::ERROR_SESSION_EXPIRED => 'Your session expired.',
		CoreGlobal::ERROR_TOKEN_EXPIRED => 'Your token expired.',
		CoreGlobal::ERROR_PARENT_CHAIN => 'A model cannot be parent of itself.',
		CoreGlobal::ERROR_NAME => 'The name {value} has already been taken.',
		CoreGlobal::ERROR_SLUG => 'The slug {value} has already been taken.',

		// Errors - Comments
		CoreGlobal::ERROR_NO_COMMENTS => 'Comments are not allowed.',

		// Errors - Permission
		CoreGlobal::ERROR_PERM_VIEW => 'You are not allowed to view the resources.',
		CoreGlobal::ERROR_PERM_CREATE => 'You are not allowed to create the resource.',
		CoreGlobal::ERROR_PERM_UPDATE => 'You are not allowed to update the resource.',
		CoreGlobal::ERROR_PERM_DELETE => 'You are not allowed to delete the resource.',

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
		CoreGlobal::ERROR_SLUG_EXIST => 'The slug is not available.',
		CoreGlobal::ERROR_MOBILE_EXIST => 'A user already exist with the same mobile number.',
		CoreGlobal::ERROR_PASSWORD => 'Please provide a valid password having at least 5 characters.',
		CoreGlobal::ERROR_TERMS => 'Please accept our terms and conditions to continue.',

		// Errors - User Login
		CoreGlobal::ERROR_USER_NOT_EXIST => 'The given email or username is not registered.',
		CoreGlobal::ERROR_USER_VERIFICATION => 'The account with this email or username is not confirmed yet. Please follow the mail sent while registration or try to reset password.',
		CoreGlobal::ERROR_BLOCKED => 'The account is blocked by Admin. Please contact Admin to re-activate it.',
		CoreGlobal::ERROR_LOGIN_FAILED => 'The provided email or username and password does not match.',
		CoreGlobal::ERROR_PERMISSION => 'Not permitted',

		// Errors - User Account
		CoreGlobal::ERROR_PASSWORD_OLD => 'Please provide valid value for old password.',
		CoreGlobal::ERROR_ACCOUNT_CONFIRM => null,
		CoreGlobal::ERROR_PASSWORD_RESET => null,
		CoreGlobal::ERROR_CHANGE_EMAIL => 'Email change is not allowed.',
		CoreGlobal::ERROR_CHANGE_USERNAME => 'Username change is not allowed.',
		CoreGlobal::ERROR_CHANGE_MOBILE => 'Mobile change is not allowed.',

		// Errors - Apis
		CoreGlobal::ERROR_APIS_DISABLED => 'APIs are not supported by this application.',

		// Errors - View
		CoreGlobal::ERROR_NO_TEMPLATE => 'No template defined.',
		CoreGlobal::ERROR_NO_VIEW => 'Layout or view is missing.',

		// Model Fields ----------------------------------------------------

		// Generic Fields
		CoreGlobal::FIELD_CODE => 'Code',
		CoreGlobal::FIELD_ISO => 'ISO Code',

		CoreGlobal::FIELD_DEFAULT => 'Default',
		CoreGlobal::FIELD_NAME => 'Name',
		CoreGlobal::FIELD_NUMBER => 'Number',
		CoreGlobal::FIELD_SLUG => 'Slug',
		CoreGlobal::FIELD_LABEL => 'Label',
		CoreGlobal::FIELD_TITLE => 'Title',
		CoreGlobal::FIELD_TYPE => 'Type',
		CoreGlobal::FIELD_ICON => 'Icon',
		CoreGlobal::FIELD_INPUT => 'Input',
		CoreGlobal::FIELD_CLASSPATH => 'Classpath',
		CoreGlobal::FIELD_TEXTURE => 'Texture',
		CoreGlobal::FIELD_KEY => 'Key',
		CoreGlobal::FIELD_VALUE => 'Value',
		CoreGlobal::FIELD_VALUE_TYPE => 'Value Type',
		CoreGlobal::FIELD_DESCRIPTION => 'Description',
		CoreGlobal::FIELD_OTP => 'OTP',
		CoreGlobal::FIELD_NOTE => 'Note',
		CoreGlobal::FIELD_NOTES => 'Notes',
		CoreGlobal::FIELD_GUEST => 'Guest',

		CoreGlobal::FIELD_ACTIVE => 'Active',
		CoreGlobal::FIELD_STATUS => 'Status',
		CoreGlobal::FIELD_ACCESS => 'Access',
		CoreGlobal::FIELD_PRIMARY => 'Primary',
		CoreGlobal::FIELD_SECONDARY => 'Secondary',
		CoreGlobal::FIELD_MODE => 'Mode',
		CoreGlobal::FIELD_SENT => 'Sent',
		CoreGlobal::FIELD_DELIVERED => 'Delivered',
		CoreGlobal::FIELD_CONSUMED => 'Consumed',
		CoreGlobal::FIELD_TRASH => 'Trash',
		CoreGlobal::FIELD_VISIBILITY => 'Visibility',
		CoreGlobal::FIELD_PRIORITY => 'Priority',
		CoreGlobal::FIELD_SEVERITY => 'Severity',
		CoreGlobal::FIELD_SCOPE => 'Scope',
		CoreGlobal::FIELD_ORDER => 'Order',
		CoreGlobal::FIELD_LIMIT => 'Limit',
		CoreGlobal::FIELD_COUNT => 'Count',
		CoreGlobal::FIELD_EXPIRED => 'Expired',
		CoreGlobal::FIELD_PINNED => 'Pinned',
		CoreGlobal::FIELD_FEATURED => 'Featured',
		CoreGlobal::FIELD_POPULAR => 'Popular',
		CoreGlobal::FIELD_ANONYMOUS => 'Anonymous',
		CoreGlobal::FIELD_USER_MAPPED => 'User Mapped',
		CoreGlobal::FIELD_FAILED => 'Failed',
		CoreGlobal::FIELD_FAIL_COUNT => 'Failure Count',
		CoreGlobal::FIELD_STORAGE => 'Storage',
		CoreGlobal::FIELD_CAPTION => 'Caption',
		CoreGlobal::FIELD_ALT_TEXT => 'Alt Text',
		CoreGlobal::FIELD_IMG_SRCSET => 'Srcset Breakpoints',
		CoreGlobal::FIELD_IMG_SIZES => 'Responsive Sizes',
		CoreGlobal::FIELD_STARTS_AT => 'Starts At',
		CoreGlobal::FIELD_ENDS_AT => 'Ends At',
		CoreGlobal::FIELD_DATE_START => 'Start Date',
		CoreGlobal::FIELD_DATE_END => 'End Date',
		CoreGlobal::FIELD_DAY_WEEK => 'Week Day',
		CoreGlobal::FIELD_DAY_MONTH => 'Month Day',
		CoreGlobal::FIELD_TIME => 'Time',
		CoreGlobal::FIELD_TIME_START => 'Start Time',
		CoreGlobal::FIELD_TIME_END => 'End Time',
		CoreGlobal::FIELD_CREATED_AT => 'Created At',
		CoreGlobal::FIELD_MODIFIED_AT => 'Updated At',
		CoreGlobal::FIELD_TIME_ZONE => 'Time Zone',

		CoreGlobal::FIELD_SERVICE => 'Service',

		CoreGlobal::FIELD_MESSAGE => 'Message',
		CoreGlobal::FIELD_MESSAGE_SUCCESS => 'Success Message',
		CoreGlobal::FIELD_MESSAGE_FAILURE => 'Failure Message',

		CoreGlobal::FIELD_BASE => 'Base',
		CoreGlobal::FIELD_GROUP => 'Group',
		CoreGlobal::FIELD_ROLE => 'Role',
		CoreGlobal::FIELD_PERMISSION => 'Permission',
		CoreGlobal::FIELD_GENDER => 'Gender',
		CoreGlobal::FIELD_MARITAL => 'Marital Status',
		CoreGlobal::FIELD_NOK => 'Next of Kin',
		CoreGlobal::FIELD_NOK_RELATION => 'NOK Relation',
		CoreGlobal::FIELD_AGE => 'Age',
		CoreGlobal::FIELD_ROOT => 'Root',
		CoreGlobal::FIELD_PARENT => 'Parent',
		CoreGlobal::FIELD_PARENT_TYPE => 'Parent Type',
		CoreGlobal::FIELD_CHILD => 'Child',
		CoreGlobal::FIELD_LOCALE => 'Locale',
		CoreGlobal::FIELD_PROVINCE => 'Province',
		CoreGlobal::FIELD_REGION => 'Region',
		CoreGlobal::FIELD_COUNTRY => 'Country',
		CoreGlobal::FIELD_CATEGORY => 'Category',
		CoreGlobal::FIELD_OPTION_GROUP => 'Option Group',
		CoreGlobal::FIELD_OPTION => 'Option',
		CoreGlobal::FIELD_FILE => 'File',
		CoreGlobal::FIELD_TAG => 'Tag',
		CoreGlobal::FIELD_GALLERY => 'Gallery',
		CoreGlobal::FIELD_EVENT => 'Event',
		CoreGlobal::FIELD_OBJECT => 'Object',
		CoreGlobal::FIELD_THEME => 'Theme',
		CoreGlobal::FIELD_PREVIEW => 'Preview',
		CoreGlobal::FIELD_COMMENT => 'Comment',
		CoreGlobal::FIELD_COMMENTS => 'Comments',
		CoreGlobal::FIELD_REVIEWS => 'Reviews',
		CoreGlobal::FIELD_TEMPLATE => 'Template',
		CoreGlobal::FIELD_MODULE => 'Module',
		CoreGlobal::FIELD_USER => 'User',
		CoreGlobal::FIELD_ADMIN => 'Admin',
		CoreGlobal::FIELD_FRONTEND => 'Frontend',
		CoreGlobal::FIELD_BACKEND => 'Backend',
		CoreGlobal::FIELD_WEBSITE => 'Website',
		CoreGlobal::FIELD_AUTHOR => 'Author',
		CoreGlobal::FIELD_MEMBER => 'Member',
		CoreGlobal::FIELD_OWNER => 'Owner',
		CoreGlobal::FIELD_APPROVER => 'Approver',
		CoreGlobal::FIELD_FOLLOWER => 'Follower',
		CoreGlobal::FIELD_FOLLOWERS => 'Followers',
		CoreGlobal::FIELD_PROFILE => 'Profile',
		CoreGlobal::FIELD_SHARED => 'Shared',
		CoreGlobal::FIELD_AVATAR => 'Avatar',
		CoreGlobal::FIELD_AVATAR_URL => 'Avatar URL',
		CoreGlobal::FIELD_BANNER => 'Banner',
		CoreGlobal::FIELD_BANNER_M => 'Mobile Banner',
		CoreGlobal::FIELD_VIDEO => 'Video',
		CoreGlobal::FIELD_VIDEO_M => 'Mobile Video',
		CoreGlobal::FIELD_DOCUMENT => 'Document',

		CoreGlobal::FIELD_MULTIPLE => 'Multiple',
		CoreGlobal::FIELD_GLOBAL => 'Global',
		CoreGlobal::FIELD_SESSION => 'Session',
		CoreGlobal::FIELD_TOKEN => 'Token',
		CoreGlobal::FIELD_VALIDATORS => 'Validators',
		CoreGlobal::FIELD_RENDERER => 'Render Engine',
		CoreGlobal::FIELD_FILE_RENDER => 'File Render',
		CoreGlobal::FIELD_EVENT_LOG => 'Event Log',
		CoreGlobal::FIELD_IP => 'IP Address',
		CoreGlobal::FIELD_IP_NUM => 'IP Number',
		CoreGlobal::FIELD_AGENT_BROWSER => 'Browser Agent',
		CoreGlobal::FIELD_HTML_OPTIONS => 'HTML Options',
		CoreGlobal::FIELD_DATA => 'Data',
		CoreGlobal::FIELD_DATA_WIDGET => 'Widget Data',
		CoreGlobal::FIELD_COMPRESS => 'Store Compressed',
		CoreGlobal::FIELD_GRID_CACHE => 'Grid Cache',
		CoreGlobal::FIELD_GRID_CACHE_VALID => 'Grid Cache Valid',

		// Role Fields
		CoreGlobal::FIELD_ADMIN_URL => 'Admin Url',
		CoreGlobal::FIELD_HOME_URL => 'Home Url',

		// Address Fields
		CoreGlobal::FIELD_LINE1 => 'Line 1',
		CoreGlobal::FIELD_LINE2 => 'Line 2',
		CoreGlobal::FIELD_LINE3 => 'Line 3',
		CoreGlobal::FIELD_CITY => 'City',
		CoreGlobal::FIELD_ZIP => 'Postal Code',
		CoreGlobal::FIELD_ZIP_SUB => 'Postal Code Ext',
		CoreGlobal::FIELD_MOBILE => 'Mobile',
		CoreGlobal::FIELD_PHONE => 'Phone',
		CoreGlobal::FIELD_FAX => 'Fax',
		CoreGlobal::FIELD_LONGITUDE => 'Longitude',
		CoreGlobal::FIELD_LATITUDE => 'Latitude',
		CoreGlobal::FIELD_ZOOM => 'Zoom Level',
		CoreGlobal::FIELD_ADDRESS => 'Address',
		CoreGlobal::FIELD_ADDRESS_TYPE => 'Address Type',
		CoreGlobal::FIELD_ZONE => 'Zone',
		CoreGlobal::FIELD_REGIONS => 'Regions',
		CoreGlobal::FIELD_ZIP_CODES => 'Zip Codes',
		CoreGlobal::FIELD_LANDMARK => 'Landmark',
		CoreGlobal::FIELD_LOCATION => 'Location',

		// User Fields
		CoreGlobal::FIELD_EMAIL => 'Email',
		CoreGlobal::FIELD_USERNAME => 'Username',
		CoreGlobal::FIELD_PASSWORD => 'Password',
		CoreGlobal::FIELD_PASSWORD_REPEAT => 'Confirm Password',
		CoreGlobal::FIELD_PASSWORD_OLD => 'Old Password',
		CoreGlobal::FIELD_FIRSTNAME => 'First Name',
		CoreGlobal::FIELD_MIDDLENAME => 'Middle Name',
		CoreGlobal::FIELD_LASTNAME => 'Last Name',
		CoreGlobal::FIELD_DOB => 'Date of Birth',
		CoreGlobal::FIELD_TERMS => 'Terms',

		// Verification Fields
		CoreGlobal::FIELD_MOBILE_VERIFIED => 'Mobile Verified',
		CoreGlobal::FIELD_EMAIL_VERIFIED => 'Email Verified',
		CoreGlobal::FIELD_TOKEN_VERIFY => 'Verify Token',
		CoreGlobal::FIELD_TOKEN_VERIFY_VALIDITY => 'Verify Token Validity',
		CoreGlobal::FIELD_OTP_VALIDITY => 'OTP Validity',

		// File Fields
		CoreGlobal::FIELD_EXTENSION => 'Extension',
		CoreGlobal::FIELD_DIRECTORY => 'Directory',
		CoreGlobal::FIELD_SIZE => 'Size',
		CoreGlobal::FIELD_URL => 'Url',
		CoreGlobal::FIELD_LINK => 'Link',

		// Notification/Reminder/Message Fields
		CoreGlobal::FIELD_NOTIFIER => 'Notifier',
		CoreGlobal::FIELD_SENDER => 'Sender',
		CoreGlobal::FIELD_PUBLISHER => 'Publisher',
		CoreGlobal::FIELD_RECIPIENT => 'Recipient',

		// Site/Site Member Fields
		CoreGlobal::FIELD_SITE => 'Site',

		// Content Fields
		CoreGlobal::FIELD_SUMMARY => 'Summary',
		CoreGlobal::FIELD_HELP => 'Help',
		CoreGlobal::FIELD_CONTENT => 'Content',

		// Views
		CoreGlobal::FIELD_LAYOUT => 'Layout',
		CoreGlobal::FIELD_LAYOUT_GROUP => 'Layout Group',
		CoreGlobal::FIELD_STYLE => 'Style',
		CoreGlobal::FIELD_BASE_PATH => 'Base Path',
		CoreGlobal::FIELD_VIEW => 'View',
		CoreGlobal::FIELD_VIEW_PATH => 'View Path',
		CoreGlobal::FIELD_VIEW_COUNT => 'Views',
		CoreGlobal::FIELD_REFERRAL_COUNT => 'Referrals',
		CoreGlobal::FIELD_LIKE_COUNT => 'Likes',
		CoreGlobal::FIELD_WISH_COUNT => 'Wish Count',
		CoreGlobal::FIELD_RATINGS => 'Ratings',
		CoreGlobal::FIELD_WEIGHT => 'Weight',
		CoreGlobal::FIELD_RANK => 'Rank',

		// Forms
		CoreGlobal::FIELD_FORM => 'Form',
		CoreGlobal::FIELD_CAPTCHA => 'Captcha',
		CoreGlobal::FIELD_MAIL_USER => 'Send User Mail',
		CoreGlobal::FIELD_MAIL_ADMIN => 'Send Admin Mail',
		CoreGlobal::FIELD_FORM_UNIQUE => 'Unique Submit',
		CoreGlobal::FIELD_FORM_UPDATE => 'Update Submit',
		CoreGlobal::FIELD_META => 'Meta',
		CoreGlobal::FIELD_RATING => 'Rating',

		// Visibility
		CoreGlobal::FIELD_PRIVATE => 'Private',
		CoreGlobal::FIELD_PUBLIC => 'Public',

		// Dependency - Generic Mapper
		CoreGlobal::FIELD_SOURCE => 'sourceField',
		CoreGlobal::FIELD_SOURCE_TYPE => 'sourceTypeField',
		CoreGlobal::FIELD_TARGET => 'targetField',
		CoreGlobal::FIELD_TARGET_TYPE => 'targetTypeField',

		// SEO
		CoreGlobal::FIELD_SEO_NAME => 'SEO Name',
		CoreGlobal::FIELD_SEO_DESCRIPTION => 'SEO Description',
		CoreGlobal::FIELD_SEO_KEYWORDS => 'SEO Keywords',
		CoreGlobal::FIELD_SEO_ROBOT => 'SEO Robot'
	];

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		$loginLink = Url::toRoute( [ '/login' ] );
		$loginLink = "<a id=\"link-login\" href=\"$loginLink\">Login</a>";

		$forgotLink	= Url::toRoute( [ '/forgot-password' ] );
		$forgotLink	= "<a id=\"link-forgot-password\" href=\"$forgotLink\">reset</a>";

		if( empty( $this->messageDb[ CoreGlobal::MESSAGE_ACCOUNT_ACTIVATE ] ) ) {

			$this->messageDb[ CoreGlobal::MESSAGE_ACCOUNT_ACTIVATE ] = "Congratulations! Your account has been successfully activated. <br/><br/>Please $loginLink to continue with us.";
		}

		if( empty( $this->messageDb[ CoreGlobal::MESSAGE_ACCOUNT_CONFIRM ] ) ) {

			$this->messageDb[ CoreGlobal::MESSAGE_ACCOUNT_CONFIRM ] = "Congratulations! Your account has been successfully confirmed. <br/><br/>Please $loginLink to continue with us.";
		}

		if( empty( $this->messageDb[ CoreGlobal::MESSAGE_RESET_PASSWORD ] ) ) {

			$this->messageDb[ CoreGlobal::MESSAGE_RESET_PASSWORD ] = "Your password reset request was processed successfully. Please $loginLink to continue with us.";
		}

		if( empty( $this->messageDb[ CoreGlobal::ERROR_ACCOUNT_CONFIRM ] ) ) {

			$this->messageDb[ CoreGlobal::ERROR_ACCOUNT_CONFIRM ] = "Either your account does not exist or the confirmation link is not valid. Please try to $forgotLink your password.";
		}

		if( empty( $this->messageDb[ CoreGlobal::ERROR_PASSWORD_RESET ] ) ) {

			$this->messageDb[ CoreGlobal::ERROR_PASSWORD_RESET ] = "Either your account does not exist or the reset link is not valid. Please try to $forgotLink your password.";
		}
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// MessageSource -------------------------

	/**
	 * Returns message using the message templates declared within $messageDb.
	 */
	public function getMessage( $messageKey, $params = [], $language = null ) {

		// TODO: Use Yii internationalisation to support languages
		return $this->messageDb[ $messageKey ];
	}

}
