<?php
namespace cmsgears\core\common\models\forms;

// Yii Imports
use Yii;
use yii\helpers\ArrayHelper;

// BizzList Imports
use bizzlist\core\common\config\CoreGlobal;

class SocialLink extends \cmsgears\core\common\models\forms\JsonModel {

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
	public $address;

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Model ---------

	public function rules() {

		$rules = [
			[ [ 'sns', 'icon', 'address' ], 'required' ]
		];

		if( Yii::$app->core->trimFieldValue ) {

			$trim[] = [ [ 'sns', 'icon', 'address' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

			return ArrayHelper::merge( $trim, $rules );
		}

		return $rules;
	}

	public function attributeLabels() {

		return [
			'sns' => 'Social Network',
			'icon' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ICON ),
			'address' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_LINK )
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// SocialLink ----------------------------

}
