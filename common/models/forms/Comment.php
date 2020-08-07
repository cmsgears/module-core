<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\forms;

// Yii Imports
use Yii;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * The comment form collects the comment data.
 *
 * @property integer $baseId
 * @property integer $bannerId
 * @property integer $videoId
 * @property integer $parentId
 * @property string $title
 * @property string $name
 * @property string $email
 * @property string $mobile
 * @property string $phone
 * @property string $avatarUrl
 * @property string $websiteUrl
 * @property integer $rating
 * @property boolean $anonymous
 * @property string $content
 *
 * @since 1.0.0
 */
class Comment extends BaseForm {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	const SCENARIO_ALL			= 'all';
	const SCENARIO_IDENTITY		= 'identity';
	const SCENARIO_REVIEW		= 'review';
	const SCENARIO_FEEDBACK		= 'feedback';
	const SCENARIO_TESTIMONIAL	= 'testimonial';
	const SCENARIO_CAPTCHA		= 'captcha';

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $baseId;
	public $bannerId;
	public $videoId;
	public $title;
	public $name;
	public $email;
	public $mobile;
	public $phone;
	public $avatarUrl;
	public $websiteUrl;
	public $rate1 = 0;
	public $rate2 = 0;
	public $rate3 = 0;
	public $rate4 = 0;
	public $rate5 = 0;
	public $rating = 0;
	public $anonymous = false;
	public $content;

	public $captcha;

	public $captchaAction = '/core/site/captcha';

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Model ---------

	/**
	 * @inheritdoc
	 */
	public function rules() {

		// Model Rules
		$rules = [
			// Required, Safe
			[ [ 'content' ], 'required' ],
			[ [ 'content' ], 'safe' ],
			// Email
			[ 'email', 'email' ],
			// Text Limit
			[ [ 'mobile', 'phone' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ [ 'name', 'email' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xxLargeText ],
			[ [ 'title', 'avatarUrl', 'websiteUrl' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xxxLargeText ],
			// Check captcha need for testimonial and review
			[ [ 'name', 'email' ], 'required', 'on' => [ self::SCENARIO_IDENTITY, self::SCENARIO_ALL ] ],
			[ 'rating', 'required', 'on' => [ self::SCENARIO_REVIEW, self::SCENARIO_TESTIMONIAL, self::SCENARIO_FEEDBACK , self::SCENARIO_ALL ] ],
			[ 'captcha', 'captcha', 'captchaAction' => $this->captchaAction, 'on' => [ self::SCENARIO_CAPTCHA, self::SCENARIO_ALL ] ],
			// Other
			[ [ 'avatarUrl', 'websiteUrl' ], 'url' ],
			[ 'anonymous', 'boolean' ],
			[ [ 'rate1', 'rate2', 'rate3', 'rate4', 'rate5', 'rating' ], 'number', 'integerOnly' => true, 'min' => 0 ],
			[ [ 'baseId', 'bannerId', 'videoId' ], 'number', 'integerOnly' => true, 'min' => 1 ]
		];

		// Enable captcha for non-logged in users
		$user = Yii::$app->core->getUser();

		// Captcha is mandatory for guest users to comment
		if( !isset( $user ) ) {

			$rules[] = [ [ 'name', 'email', 'captcha' ], 'required' ];
			$rules[] = [ 'captcha', 'captcha', 'captchaAction' => $this->captchaAction ];
		}

		// Trim Text
		if( Yii::$app->core->trimFieldValue ) {

			$trim[] = [ [ 'name', 'email', 'avatarUrl', 'websiteUrl' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

			return ArrayHelper::merge( $trim, $rules );
		}

		return $rules;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {

		return [
			'baseId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
			'title' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TITLE ),
			'name' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'email' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_EMAIL ),
			'mobile' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_MOBILE ),
			'phone' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PHONE ),
			'avatarUrl' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_AVATAR_URL ),
			'websiteUrl' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_WEBSITE ),
			'rating' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_RATING ),
			'anonymous' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ANONYMOUS ),
			'content' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_MESSAGE )
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Comment -------------------------------

}
