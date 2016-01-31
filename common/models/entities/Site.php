<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;
use yii\validators\FilterValidator;
use yii\helpers\ArrayHelper;
use yii\behaviors\SluggableBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\traits\AttributeTrait;

/**
 * Site Entity
 *
 * @property integer $id
 * @property integer $avatarId
 * @property integer $bannerId
 * @property integer $themeId
 * @property string $name
 * @property string $slug
 * @property short $order
 * @property short $active
 */
class Site extends NamedCmgEntity {

	use AttributeTrait;

	public $attributeType	= CoreGlobal::TYPE_SITE;

	// Instance Methods --------------------------------------------

	/**
	 * @return File - file url
	 */
	public function getAvatar() {

		return $this->hasOne( CmgFile::className(), [ 'id' => 'avatarId' ] );
	}

	/**
	 * @return File - file url
	 */
	public function getBanner() {

		return $this->hasOne( CmgFile::className(), [ 'id' => 'bannerId' ] );
	}

	public function getTheme() {

		return $this->hasOne( Theme::className(), [ 'id' => 'themeId' ] );
	}

	/**
	 * @return array - list of site Users
	 */
	public function getUsers() {

    	return $this->hasMany( User::className(), [ 'id' => 'memberId' ] )
					->viaTable( CoreTables::TABLE_SITE_MEMBER, [ 'siteId' => 'id' ] );
	}

	/**
	 * @return string representation of flag
	 */
	public function getActiveStr() {

		return Yii::$app->formatter->asBoolean( $this->active ); 
	}

	// yii\base\Component ----------------

    /**
     * @inheritdoc
     */
    public function behaviors() {

        return [

            'sluggableBehavior' => [
                'class' => SluggableBehavior::className(),
                'attribute' => 'name',
                'slugAttribute' => 'slug',
                'ensureUnique' => true
            ]
        ];
    }

	// yii\base\Model --------------------

    /**
     * @inheritdoc
     */
	public function rules() {

		// model rules
        $rules = [
            [ [ 'name' ], 'required' ],
            [ [ 'id' ], 'safe' ],
            [ [ 'name' ], 'string', 'min' => 1, 'max' => 100 ],
            [ 'name', 'alphanumhyphenspace' ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ],
            [ 'slug', 'string', 'min' => 1, 'max' => 150 ],
            [ 'order', 'number', 'integerOnly' => true ],
            [ 'active', 'boolean' ],
            [ [ 'avatarId', 'bannerId' ], 'number', 'integerOnly' => true, 'min' => 1 ]
        ];

		// trim if required
		if( Yii::$app->cmgCore->trimFieldValue ) {

			$trim[] = [ [ 'name' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

			return ArrayHelper::merge( $trim, $rules );
		}

		return $rules;
    }

    /**
     * @inheritdoc
     */
	public function attributeLabels() {

		return [
			'avatarId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_AVATAR ),
			'bannerId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_BANNER ),
			'name' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'slug' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_SLUG ),
			'order' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ORDER ),
			'active' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ACTIVE )
		];
	}

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

    /**
     * @inheritdoc
     */
	public static function tableName() {

		return CoreTables::TABLE_SITE;
	}

	// Site ------------------------------

	/**
	 * @return Site - by slug
	 */
	public static function findBySlug( $slug ) {

		return self::find()->where( 'slug=:slug', [ ':slug' => $slug ] )->one();
	}
}

?>