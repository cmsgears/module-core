<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\resources;

// Yii Imports
use Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\SluggableBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\interfaces\base\IApproval;
use cmsgears\core\common\models\interfaces\base\IAuthor;
use cmsgears\core\common\models\interfaces\base\IMultiSite;
use cmsgears\core\common\models\interfaces\base\INameType;
use cmsgears\core\common\models\interfaces\base\IOwner;
use cmsgears\core\common\models\interfaces\base\ISlugType;
use cmsgears\core\common\models\interfaces\base\IVisibility;
use cmsgears\core\common\models\interfaces\resources\IContent;
use cmsgears\core\common\models\interfaces\resources\IData;
use cmsgears\core\common\models\interfaces\resources\IGridCache;
use cmsgears\core\common\models\interfaces\resources\ITemplate;

use cmsgears\core\common\models\base\CoreTables;

use cmsgears\core\common\models\traits\base\ApprovalTrait;
use cmsgears\core\common\models\traits\base\AuthorTrait;
use cmsgears\core\common\models\traits\base\MultiSiteTrait;
use cmsgears\core\common\models\traits\base\NameTypeTrait;
use cmsgears\core\common\models\traits\base\OwnerTrait;
use cmsgears\core\common\models\traits\base\SlugTypeTrait;
use cmsgears\core\common\models\traits\base\VisibilityTrait;
use cmsgears\core\common\models\traits\resources\ContentTrait;
use cmsgears\core\common\models\traits\resources\DataTrait;
use cmsgears\core\common\models\traits\resources\GridCacheTrait;
use cmsgears\core\common\models\traits\resources\TemplateTrait;

use cmsgears\core\common\behaviors\AuthorBehavior;

/**
 * Forms with fields can be used to collect data.
 *
 * @property integer $id
 * @property integer $siteId
 * @property integer $userId
 * @property integer $templateId
 * @property integer $createdBy
 * @property integer $modifiedBy
 * @property string $name
 * @property string $slug
 * @property string $type
 * @property string $icon
 * @property string $texture
 * @property string $title
 * @property string $description
 * @property string $success
 * @property string $failure
 * @property boolean $captcha
 * @property boolean $visibility
 * @property integer $status
 * @property string $sender
 * @property string $replyTo
 * @property string $mailTo
 * @property string $ccTo
 * @property string $bccTo
 * @property boolean $userMail Send mail to user if set and email field exist.
 * @property boolean $adminMail Send mail to admin if set.
 * @property boolean $uniqueSubmit
 * @property boolean $updateSubmit
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 * @property string $htmlOptions
 * @property string $content
 * @property string $data
 * @property string $gridCache
 * @property boolean $gridCacheValid
 * @property datetime $gridCachedAt
 *
 * @since 1.0.0
 */
class Form extends \cmsgears\core\common\models\base\Resource implements IApproval, IAuthor, IContent, IData,
	IGridCache, IMultiSite, INameType, IOwner, ISlugType, ITemplate, IVisibility {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $modelType = CoreGlobal::TYPE_FORM;

	// Private ----------------

	// Traits ------------------------------------------------------

	use ApprovalTrait;
	use AuthorTrait;
	use ContentTrait;
	use DataTrait;
	use GridCacheTrait;
	use MultiSiteTrait;
	use NameTypeTrait;
	use OwnerTrait;
	use SlugTypeTrait;
	use TemplateTrait;
	use VisibilityTrait;

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	 /**
	 * @inheritdoc
	 */
	public function behaviors() {

		return [
			'authorBehavior' => [
				'class' => AuthorBehavior::class
			],
			'timestampBehavior' => [
				'class' => TimestampBehavior::class,
				'createdAtAttribute' => 'createdAt',
				'updatedAtAttribute' => 'modifiedAt',
				'value' => new Expression('NOW()')
			],
			'sluggableBehavior' => [
				'class' => SluggableBehavior::class,
				'attribute' => 'name',
				'slugAttribute' => 'slug', // Unique for Site Id
				'immutable' => true,
				'ensureUnique' => true,
				'uniqueValidator' => [ 'targetAttribute' => [ 'siteId', 'slug' ] ]
			]
		];
	}

	// yii\base\Model ---------

	/**
	 * @inheritdoc
	 */
	public function rules() {

		// Model Rules
		$rules = [
			// Required, Safe
			[ [ 'siteId', 'name', 'captcha', 'visibility', 'status' ], 'required' ],
			[ [ 'id', 'mailTo', 'ccTo', 'bccTo', 'htmlOptions', 'content' ], 'safe' ],
			// Unique
			//[ 'name', 'unique', 'targetAttribute' => [ 'siteId', 'type', 'name' ], 'message' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NAME ) ],
			[ 'slug', 'unique', 'targetAttribute' => [ 'siteId', 'slug' ], 'message' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_SLUG ) ],
			// Text Limit
			[ 'type', 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ [ 'icon', 'texture' ], 'string', 'min' => 1, 'max' => Yii::$app->core->largeText ],
			[ [ 'name', 'sender', 'replyTo' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xLargeText ],
			[ 'slug', 'string', 'min' => 0, 'max' => Yii::$app->core->xxLargeText ],
			[ [ 'title', 'success', 'failure' ], 'string', 'min' => 0, 'max' => Yii::$app->core->xxxLargeText ],
			[ 'description', 'string', 'min' => 0, 'max' => Yii::$app->core->xtraLargeText ],
			// Other
			[ [ 'visibility', 'status' ], 'number', 'integerOnly' => true, 'min' => 0 ],
			[ [ 'captcha', 'userMail', 'adminMail', 'uniqueSubmit', 'updateSubmit', 'gridCacheValid' ], 'boolean' ],
			[ 'templateId', 'number', 'integerOnly' => true, 'min' => 0, 'tooSmall' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_SELECT ) ],
			[ [ 'siteId', 'userId', 'createdBy', 'modifiedBy' ], 'number', 'integerOnly' => true, 'min' => 1 ],
			[ [ 'createdAt', 'modifiedAt', 'gridCachedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
		];

		// Trim Text
		if( Yii::$app->core->trimFieldValue ) {

			$trim[] = [ [ 'name', 'title', 'description', 'success', 'failure', 'htmlOptions' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

			return ArrayHelper::merge( $trim, $rules );
		}

		return $rules;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {

		return [
			'siteId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_SITE ),
			'userId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_USER ),
			'templateId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TEMPLATE ),
			'name' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'slug' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_SLUG ),
			'type' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
			'icon' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ICON ),
			'texture' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TEXTURE ),
			'description' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DESCRIPTION ),
			'success' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_MESSAGE_SUCCESS ),
			'failure' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_MESSAGE_FAILURE ),
			'captcha' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CAPTCHA ),
			'visibility' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_VISIBILITY ),
			'status' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_STATUS ),
			'sender' => 'Sender',
			'replyTo' => 'Reply To',
			'mailTo' => 'Mail To',
			'ccTo' => 'CC To',
			'bccTo' => 'BCC To',
			'userMail' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_MAIL_USER ),
			'adminMail' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_MAIL_ADMIN ),
			'uniqueSubmit' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_FORM_UNIQUE ),
			'updateSubmit' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_FORM_UPDATE ),
			'htmlOptions' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_HTML_OPTIONS ),
			'content' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CONTENT ),
			'data' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_META ),
			'gridCache' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_GRID_CACHE )
		];
	}

	// yii\db\BaseActiveRecord

	/**
	 * @inheritdoc
	 */
	public function beforeSave( $insert ) {

		if( parent::beforeSave( $insert ) ) {

			if( $this->templateId <= 0 ) {

				$this->templateId = null;
			}

			// Default Status - New
			if( empty( $this->status ) || $this->status <= 0 ) {

				$this->status = self::STATUS_NEW;
			}

			// Default Type - Default
			$this->type = $this->type ?? CoreGlobal::TYPE_DEFAULT;

			// Default Visibility - Private
			$this->visibility = $this->visibility ?? self::VISIBILITY_PRIVATE;

			return true;
		}

		return false;
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Form ----------------------------------

	/**
	 * Return all the fields associated with the form.
	 *
	 * @return FormField[]
	 */
	public function getFields() {

		return $this->hasMany( FormField::class, [ 'formId' => 'id' ] )
				->orderBy( 'order DESC' );
	}

	/**
	 * Return all the active fields associated with the form.
	 *
	 * @return FormField[]
	 */
	public function getActiveFields() {

		$fieldTable = FormField::tableName();

		return $this->hasMany( FormField::class, [ 'formId' => 'id' ] )
				->where( $fieldTable . ".active=1" )
				->orderBy( 'order DESC' );
	}

	/**
	 * Return map of form fields having field name as key and field itself as value.
	 *
	 * @return array FormField map
	 */
	public function getFieldsMap() {

		$formFields = $this->activeFields;
		$fieldsMap	= [];

		foreach( $formFields as $formField ) {

			$fieldsMap[ $formField->name ] = $formField;
		}

		return $fieldsMap;
	}

	/**
	 * Returns string representation of captcha flag.
	 *
	 * @return string
	 */
	public function getCaptchaStr() {

		return Yii::$app->formatter->asBoolean( $this->captcha );
	}

	/**
	 * Returns string representation of user mail flag.
	 *
	 * @return string
	 */
	public function getUserMailStr() {

		return Yii::$app->formatter->asBoolean( $this->userMail );
	}

	/**
	 * Returns string representation of admin mail flag.
	 *
	 * @return string
	 */
	public function getAdminMailStr() {

		return Yii::$app->formatter->asBoolean( $this->adminMail );
	}

	/**
	 * Returns string representation of unique submit flag.
	 *
	 * @return string
	 */
	public function getUniqueSubmitStr() {

		return Yii::$app->formatter->asBoolean( $this->uniqueSubmit );
	}

	/**
	 * Returns string representation of update submit flag.
	 *
	 * @return string
	 */
	public function getUpdateSubmitStr() {

		return Yii::$app->formatter->asBoolean( $this->updateSubmit );
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return CoreTables::getTableName( CoreTables::TABLE_FORM );
	}

	// CMG parent classes --------------------

	// Form ----------------------------------

	// Read - Query -----------

	/**
	 * @inheritdoc
	 */
	public static function queryWithHasOne( $config = [] ) {

		$relations = isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'site', 'template', 'user' ];

		$config[ 'relations' ] = $relations;

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the form with fields.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with fields.
	 */
	public static function queryWithFields( $config = [] ) {

		$config[ 'relations' ] = [ 'fields' ];

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
