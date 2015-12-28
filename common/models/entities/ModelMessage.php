<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;
use yii\validators\FilterValidator;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * ModelMessage Entity
 * 
 * @property integer $id
 * @property integer $localeId
 * @property integer $parentId
 * @property string $parentType
 * @property string $name
 * @property string $value
 */
class ModelMessage extends CmgModel {

	// Instance Methods --------------------------------------------

	/**
	 * @return Locale - parent locale.
	 */
	public function getLocale() {

		return $this->hasOne( Locale::className(), [ 'id' => 'localeId' ] );
	}

	// yii\base\Model --------------------

    /**
     * @inheritdoc
     */
	public function rules() {

		// model rules
        $rules = [
            [ [ 'localeId', 'parentId', 'parentType', 'name', 'value' ], 'required' ],
            [ 'id', 'safe' ],
            [ [ 'localeId' ], 'number', 'integerOnly' => true, 'min' => 1, 'tooSmall' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_SELECT ) ],
            [ [ 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ [ 'parentType', 'name' ], 'string', 'min' => 1, 'max' => 100 ],
            [ 'name', 'alphanumhyphenspace' ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ]
        ];
		
		// trim if required
		if( Yii::$app->cmgCore->trimFieldValue ) {

			$trim[] = [ [ 'name', 'value' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

			return ArrayHelper::merge( $trim, $rules );
		}

		return $rules;
    }

    /**
     * @inheritdoc
     */
	public function attributeLabels() {

		return [
			'localeId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_LOCALE ),
			'parentId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
			'parentType' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT_TYPE ),
			'name' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'value' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_VALUE )
		];
	}

	// ModelMessage ----------------------

	/**
	 * Validates to ensure that only one message exist with one name for a particular locale.
	 */
    public function validateNameCreate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            if( self::isExistByNameLocaleId( $this->parentId, $this->parentType, $this->name, $this->localeId ) ) {

				$this->addError( $attribute, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_EXIST ) );
            }
        }
    }

	/**
	 * Validates to ensure that only one message exist with one name.
	 */
    public function validateNameUpdate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

			$existingMessage = self::findByNameLocaleId( $this->parentId, $this->parentType, $this->name, $this->localeId );

			if( isset( $existingMessage ) && $existingMessage->id != $this->id && 
				$existingMessage->parentId == $this->parentId && $existingMessage->parentType == $this->parentType &&
				strcmp( $existingMessage->name, $this->name ) == 0 && $existingMessage->localeId == $this->localeId ) {
	
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
		
		return CoreTables::TABLE_LOCALE_MESSAGE;
	}

	// ModelMessage ----------------------

	/**
	 * @param integer $parentId
	 * @param string $parentType
	 * @param string $name
	 * @param int $localeId
	 * @return ModelMessage - by name and locale id
	 */
	public static function findByNameLocaleId( $parentId, $parentType, $name, $localeId ) {

		return self::find()->where( 'parentId=:pid AND parentType=:ptype AND name=:name AND localeId=:lid' )
							->addParams( [ ':pid' => $parentId, ':ptype' => $parentType, ':name' => $name, ':lid' => $localeId ] )
							->one();
	}

	/**
	 * @param integer $parentId
	 * @param string $parentType
	 * @param string $name
	 * @param int $localeId
	 * @return boolean - check whether message exist by name and locale id
	 */
	public static function isExistByNameLocaleId( $parentId, $parentType, $name, $localeId ) {

		return isset( self::findByNameLocaleId( $parentId, $parentType, $name, $localeId ) );
	}

	// Delete ----

	/**
	 * Delete all entries related to a locale for a parent
	 */
	public static function deleteByLocaleId( $parentId, $parentType, $localeId ) {

		self::deleteAll( 'parentId=:pid AND parentType=:type AND localeId=:id', [ ':id' => $localeId ] );
	}
}

?>