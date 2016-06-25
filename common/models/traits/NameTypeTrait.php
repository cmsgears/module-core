<?php
namespace cmsgears\core\common\models\traits;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

trait NameTypeTrait {

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii classes ---------------------------

	// CMG interfaces ------------------------

	// CMG classes ---------------------------

	// NameTypeTrait -------------------------

	// Validators -------------

	/**
	 * Validate name on creation to ensure that name is unique for given type.
	 */
    public function validateNameCreate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            if( static::isExistByNameType( $this->name, $this->type ) ) {

                $this->addError( $attribute, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_EXIST ) );
            }
        }
    }

	/**
	 * Validate name on creation to ensure that name is unique for all rows.
	 */
    public function validateNameUpdate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

			$existingEntity = static::findByNameType( $this->name, $this->type );

			if( isset( $existingEntity ) && $existingEntity->id != $this->id ) {

				$this->addError( $attribute, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_EXIST ) );
			}
        }
    }

	// Static Methods ----------------------------------------------

	// Yii classes ---------------------------

	// CMG classes ---------------------------

	// NameTypeTrait -------------------------

	// Read - Query -----------

    public static function queryByType( $type ) {

        return static::find()->where( 'type=:type', [ ':type' => $type ] );
    }

    public static function queryByTypeName( $type, $name ) {

        return self::find()->where( 'type=:type AND slug=:name', [ ':type' => $type, ':name' => $name ] );
    }

	// Read - Find ------------

    /**
     * @return array - ActiveRecord by type
     */
    public static function findByType( $type ) {

        return static::find()->where( 'type=:type', [ ':type' => $type ] )->all();
    }

	/**
	 * @return ActiveRecord - by name
	 */
	public static function findByName( $name ) {

		if( static::$multiSite ) {

			$siteId	= Yii::$app->cmgCore->siteId;

			return static::find()->where( 'name=:name AND siteId=:siteId', [ ':name' => $name, ':siteId' => $siteId ] )->one();
		}
		else {

			return static::find()->where( 'name=:name', [ ':name' => $name ] )->one();
		}
	}

	/**
	 * @return ActiveRecord - by name and type
	 */
	public static function findByNameType( $name, $type ) {

		if( static::$multiSite ) {

			$siteId	= Yii::$app->cmgCore->siteId;

			return static::find()->where( 'name=:name AND type=:type AND siteId=:siteId', [ ':name' => $name, ':type' => $type, ':siteId' => $siteId ] )->one();
		}
		else {

			return static::find()->where( 'name=:name AND type=:type', [ ':name' => $name, ':type' => $type ] )->one();
		}
	}

    /**
     * @return boolean - check whether model exist for given name and type
     */
	public static function isExistByNameType( $name, $type ) {

		$model	= static::findByNameType( $name, $type );

		return isset( $model );
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}

?>