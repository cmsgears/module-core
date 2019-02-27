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

	public static $cmtLinksMap	= [
		'cmti-social-facebook' => 'Facebook',
		'cmti-social-twitter' => 'Twitter',
		'cmti-social-linkedin' => 'LinkedIn',
		'cmti-social-picasa' => 'Picasa',
		'cmti-social-pinterest' => 'Pintrest',
		'cmti-social-instagram' => 'Instagram',
		'cmti-social-google-plus' => 'Google +',
		'cmti-social-skype' => 'Skype',
		'cmti-social-youtube' => 'YouTube',
		'cmti-social-vimeo' => 'Vimeo'
	];

	public static $faLinksMap	= [
		'fa-facebook' => 'Facebook',
		'fa-twitter' => 'Twitter',
		'fa-linkedin' => 'LinkedIn',
		'fa-picasa' => 'Picasa',
		'fa-pinterest' => 'Pintrest',
		'fa-instagram' => 'Instagram',
		'fa-google-plus' => 'Google +',
		'fa-skype' => 'Skype',
		'fa-youtube' => 'YouTube',
		'fa-vimeo' => 'Vimeo'
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
