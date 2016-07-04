<?php
namespace cmsgears\core\common\models\resources;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\entities\User;

use cmsgears\core\common\models\traits\ResourceTrait;

/**
 * Activity Entity - It can be used to log user activities. A model can be optionally associated with it to identify model specific activities.
 *
 * @property long $id
 * @property long $userId
 * @property long $parentId
 * @property string $parentType
 * @property short $type
 * @property string $ip
 * @property string $agent
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 * @property string $content
 */
class Activity extends \cmsgears\core\common\models\base\Resource {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	use ResourceTrait;

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

        return [
            [ [ 'userId' ], 'required' ],
            [ [ 'id', 'content' ], 'safe' ],
            [ [ 'parentType', 'type', 'ip' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
            [ [ 'agent' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xLargeText ],
            [ [ 'userId', 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ [ 'createdAt', 'modifiedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {

        return [
            'userId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_USER ),
            'parentId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
            'parentType' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT_TYPE ),
            'ip' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_IP ),
            'agent' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_AGENT_BROWSER ),
            'content' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CONTENT )
        ];
    }

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

    // ModelActivity -------------------------

    /**
     * @return User - associated user
     */
    public function getUser() {

        return $this->hasOne( Activity::className(), [ 'id' => 'userId' ] );
    }

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

    /**
     * @inheritdoc
     */
    public static function tableName() {

        return CoreTables::TABLE_MODEL_ACTIVITY;
    }

	// CMG parent classes --------------------

	// ModelActivity -------------------------

	// Read - Query -----------

	public static function queryWithAll( $config = [] ) {

		$relations				= isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'user' ];
		$config[ 'relations' ]	= $relations;

		return parent::queryWithAll( $config );
	}

	public static function queryWithUser( $config = [] ) {

		$config[ 'relations' ]	= [ 'user' ];

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------
}

?>