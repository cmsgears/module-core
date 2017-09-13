<?php
namespace cmsgears\core\common\models\traits;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\entities\User;

/**
 * CreateModifyTrait can be used to add creator and modifier methods to relevant models.
 */
trait CreateModifyTrait {

	/**
	 * @return User - who created the model
	 */
	public function getCreator() {

		$userTable = CoreTables::TABLE_USER;

		return $this->hasOne( User::className(), [ 'id' => 'createdBy' ] )->from( "$userTable as creator" );
	}

	/**
	 * @return User - who modified the model
	 */
	public function getModifier() {

		$userTable = CoreTables::TABLE_USER;

		return $this->hasOne( User::className(), [ 'id' => 'modifiedBy' ] )->from( "$userTable as modifier" );
	}

    public function isCreator( $user = null, $strict = false ) {

        if( !isset( $user ) && !$strict ) {

            $user	= Yii::$app->user->getIdentity();
        }

        if( isset( $user ) ) {

            if( isset( $this->createdBy ) ) {

                return $this->createdBy == $user->id;
            }
            else {

                return $this->createdBy == $user->id;
            }
        }

        return false;
    }

	public static function queryWithCreator( $config = [] ) {

		$config[ 'relations' ]	= [ 'creator' ];

		return parent::queryWithAll( $config );
	}

	public static function queryWithModifier( $config = [] ) {

		$config[ 'relations' ]	= [ 'modifier' ];

		return parent::queryWithAll( $config );
	}

	public static function queryByCreatorId( $userId ) {

		return static::find()->where( 'createdBy=:cid', [ ':cid' => $userId ] );
	}
}
