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
 * The Social Link Form will be used to collect social links related to the model in action.
 *
 * @property string $sns
 * @property string $icon
 * @property string $link
 *
 * @since 1.0.0
 */
class SocialLink extends DataModel {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $cmtLinksList = [
		[ 'icon' => 'cmti-brand cmti-brand-facebook', 'title' => 'Facebook' ],
		[ 'icon' => 'cmti-brand cmti-brand-twitter', 'title' => 'Twitter' ],
		[ 'icon' => 'cmti-brand cmti-brand-linkedin', 'title' => 'LinkedIn' ],
		[ 'icon' => 'cmti-brand cmti-brand-pinterest', 'title' => 'Pintrest' ],
		[ 'icon' => 'cmti-brand cmti-brand-instagram', 'title' => 'Instagram' ],
		[ 'icon' => 'cmti-brand cmti-brand-skype', 'title' => 'Skype' ],
		[ 'icon' => 'cmti-brand cmti-brand-youtube', 'title' => 'YouTube' ],
		[ 'icon' => 'cmti-brand cmti-brand-vimeo', 'title' => 'Vimeo' ]
	];

	public static $faLinksList = [
		[ 'icon' => 'fa-facebook', 'title' => 'Facebook' ],
		[ 'icon' => 'fa-twitter', 'title' => 'Twitter' ],
		[ 'icon' => 'fa-linkedin', 'title' => 'LinkedIn' ],
		[ 'icon' => 'fa-pinterest', 'title' => 'Pintrest' ],
		[ 'icon' => 'fa-instagram', 'title' => 'Instagram' ],
		[ 'icon' => 'fa-skype', 'title' => 'Skype' ],
		[ 'icon' => 'fa-youtube', 'title' => 'YouTube' ],
		[ 'icon' => 'fa-vimeo', 'title' => 'Vimeo' ]
	];

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $sns;
	public $icon;
	public $link;

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
			[ [ 'sns', 'icon', 'link' ], 'required' ],
			[ 'link', 'url' ]
		];

		// Trim Text
		if( Yii::$app->core->trimFieldValue ) {

			$trim[] = [ [ 'sns', 'icon', 'link' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

			return ArrayHelper::merge( $trim, $rules );
		}

		return $rules;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {

		return [
			'sns' => 'Social Network',
			'icon' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ICON ),
			'link' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_LINK )
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// SocialLink ----------------------------

}
