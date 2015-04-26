<?php
namespace cmsgears\core\common\models\entities;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * LocaleMessage Entity
 *
 * @property integer $id
 * @property integer $localeId 
 * @property short $type
 * @property integer $parentId 
 * @property string $name
 * @property string $value
 */
class LocaleMessage extends CmgEntity {

	// Instance Methods --------------------------------------------

	/**
	 * @return Locale - parent Locale.
	 */
	public function getLocale() {

		return $this->hasOne( Locale::className(), [ 'id' => 'localeId' ] );
	}

	// yii\base\Model --------------------

	public function rules() {

        return [
            [ [ 'localeId', 'type', 'name', 'value' ], 'required' ],
            [ [ 'id', 'parentId' ], 'safe' ],
            [ 'name', 'alphanumhyphenspace' ],
            [ 'name', 'string', 'min'=>1, 'max'=>100 ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ]
        ];
    }

	public function attributeLabels() {

		return [
			'locale' => 'Locale',
			'name' => 'Name',
			'value' => 'Value'
		];
	}

	// Config ----------------------------
	
	/**
	 * Validates to ensure that only one message exist with one name.
	 */
    public function validateNameCreate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

			if( isset( $this->parentId ) ) {

	            if( self::isExistByLocaleIdNameParentId( $this->localeId, $this->name, $this->parentId, $this->type ) ) {

					$this->addError( $attribute, Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::ERROR_EXIST ) );
	            }
			}
			else {

	            if( self::isExistByLocaleIdName( $this->localeId, $this->name ) ) {
	
					$this->addError( $attribute, Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::ERROR_EXIST ) );
	            }
			}
        }
    }

	/**
	 * Validates to ensure that only one message exist with one name.
	 */
    public function validateNameUpdate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

			if( isset( $this->parentId ) ) {

				$existingMessage = self::findByLocaleIdNameParentId( $this->localeId, $this->name, $this->parentId, $this->type );

				if( isset( $existingMessage ) && $existingMessage->id != $this->id && 
					strcmp( $existingMessage->name, $this->name ) == 0 && $existingMessage->localeId == $this->localeId 
					&& $existingMessage->parentId == $this->parentId && $existingMessage->type == $this->type ) {
	
					$this->addError( $attribute, Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::ERROR_EXIST ) );
				}
			}
			else {

				$existingMessage = self::findByLocaleIdName( $this->localeId, $this->name );
	
				if( isset( $existingMessage ) && $existingMessage->id != $this->id && 
					strcmp( $existingMessage->name, $this->name ) == 0 && $existingMessage->localeId == $this->localeId ) {
	
					$this->addError( $attribute, Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::ERROR_EXIST ) );
				}
			}
        }
    }

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord

	public static function tableName() {
		
		return CoreTables::TABLE_LOCALE_MESSAGE;
	}

	// LocaleMessage

	public static function findById( $id ) {

		return Role::find()->where( 'id=:id', [ ':id' => $id ] )->one();
	}

	public static function findByLocale( $locale ) {

		return self::find()->where( 'localeId=:id', [ ':id' => $locale->id ] )->one();
	}

	public static function findByLocaleId( $localeId ) {

		return self::find()->where( 'localeId=:id', [ ':id' => $localeId ] )->one();
	}

	public static function findByLocaleName( $locale, $name ) {

		return self::find()->where( 'localeId=:id AND name=:name', [ ':id' => $locale->id, ':name' => $name ] )->one();
	}

	public static function findByLocaleIdName( $localeId, $name ) {

		return self::find()->where( 'localeId=:id AND name=:name', [ ':id' => $localeId, ':name' => $name ] )->one();
	}

	public static function isExistByLocaleIdName( $localeId, $name ) {

		return isset( self::findByLocaleIdName( $localeId, $name ) );
	}

	public static function findByLocaleIdNameParentId( $localeId, $name, $parentId, $type ) {

		return self::find()->where( 'localeId=:id AND name=:name AND parentId=:pid AND type=:type' )
							->addParams( [ ':id' => $localeId, ':name' => $name, ':pid' => $parentId, ':type' => $type ] )
							->one();
	}

	public static function isExistByLocaleIdNameParentId( $localeId, $name, $parentId, $type ) {

		return isset( self::findByLocaleIdNameParentId( $localeId, $name, $parentId, $type ) );
	}
}

?>