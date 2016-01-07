<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;
use yii\validators\FilterValidator;
use yii\helpers\ArrayHelper;
use yii\behaviors\SluggableBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * Tag Entity
 *
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property string $type 
 * @property string $icon
 */
class Tag extends CmgEntity {

	// Instance Methods --------------------------------------------

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
            [ 'id', 'safe' ],
            [ 'name', 'alphanumhyphenspace' ],
            [ [ 'name', 'type', 'icon' ], 'string', 'min' => 1, 'max' => 100 ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ],
            [ 'slug', 'string', 'min' => 1, 'max' => 150 ]
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
			'name' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'slug' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_SLUG ),
			'type' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_TYPE )
		];
	}

	// Tag -------------------------------

	/**
	 * Validates to ensure that name is used only for one tag for a particular type
	 */
    public function validateNameCreate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            if( self::isExistByTypeName( $this->type, $this->name ) ) {

				$this->addError( $attribute, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_EXIST ) );
            }
        }
    }

	/**
	 * Validates to ensure that name is used only for one tag for a particular type
	 */
    public function validateNameUpdate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

			$existingTag = self::findByTypeName( $this->type, $this->name );

			if( isset( $existingTag ) && $existingTag->id != $this->id && 
				strcmp( $existingTag->name, $this->name ) == 0 && $existingTag->type == $this->type ) {

				$this->addError( $attribute, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_EXIST ) );
			}
        }
    }

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

    /**
     * @inheritdoc
     */
	public static function tableName() {

		return CoreTables::TABLE_TAG;
	}

	// Tag -------------------------------

	/**
	 * @return Tag - by type
	 */
	public static function findBySlug( $slug ) {

		return self::find()->where( 'slug=:slug', [ ':slug' => $slug ] )->one();
	}

	/**
	 * @return Tag - by type
	 */
	public static function findByType( $type ) {

		return self::find()->where( 'type=:type', [ ':type' => $type ] )->all();
	}

	/**
	 * @return Tag - by type and name
	 */
	public static function findByTypeName( $type, $name ) {

		return self::find()->where( 'type=:type AND name=:name', [ ':type' => $type, ':name' => $name ] )->one();
	}

	/**
	 * @return Tag - checks whether tag exist by type and name
	 */
	public static function isExistByTypeName( $type, $name ) {

		$tag = self::findByTypeName( $type, $name );

		return isset( $tag );
	}
}

?>