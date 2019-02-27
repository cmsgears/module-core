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

use cmsgears\core\common\models\forms\BaseForm;

/**
 * The comment form collects the comment data.
 *
 * @property integer $baseId
 * @property integer $bannerId
 * @property integer $videoId
 * @property string $name
 * @property string $email
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
	public $name;
	public $email;
	public $avatarUrl;
	public $websiteUrl;
	public $rating;
	public $anonymous = false;
	public $content;

	public $captcha;

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
			[ [ 'name', 'email' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xxLargeText ],
			[ [ 'avatarUrl', 'websiteUrl' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xxxLargeText ],
			// Check captcha need for testimonial and review
			[ [ 'name', 'email' ], 'required', 'on' => [ self::SCENARIO_IDENTITY, self::SCENARIO_ALL ] ],
			[ 'rating', 'required', 'on' => [ self::SCENARIO_REVIEW, self::SCENARIO_TESTIMONIAL, self::SCENARIO_FEEDBACK , self::SCENARIO_ALL ] ],
			[ 'captcha', 'captcha', 'captchaAction' => '/core/site/captcha', 'on' => [ self::SCENARIO_CAPTCHA, self::SCENARIO_ALL ] ],
			// Other
			[ [ 'avatarUrl', 'websiteUrl' ], 'url' ],
			[ 'anonymous', 'boolean' ],
			[ 'rating', 'number', 'integerOnly' => true, 'min' => 0 ],
			[ [ 'baseId', 'bannerId', 'videoId' ], 'number', 'integerOnly' => true, 'min' => 1 ]
		];

		// Enable captcha for non-logged in users
		$user = Yii::$app->user->getIdentity();

		// Captcha is mandatory for guest users to comment
		if( !isset( $user ) ) {

			$rules[] = [ [ 'name', 'email', 'captcha' ], 'required' ];
			$rules[] = [ 'captcha', 'captcha', 'captchaAction' => '/core/site/captcha' ];
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
			'name' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'email' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_EMAIL ),
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
