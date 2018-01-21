<?php
namespace cmsgears\core\common\widgets;

// Yii Imports
use yii\helpers\Json;

// CMG Imports
use cmsgears\core\common\assets\ActiveFormAsset;

class ActiveForm extends \yii\widgets\ActiveForm {

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

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Widget --------

	// yii\widgets\ActiveForm -

    public function registerClientScript() {

        $id			= $this->options[ 'id' ];
        $options	= Json::htmlEncode( $this->getClientOptions() );
        $attributes = Json::htmlEncode( $this->attributes );
        $view		= $this->getView();

        ActiveFormAsset::register( $view );

        $view->registerJs( "jQuery('#$id').yiiActiveForm($attributes, $options);" );
    }

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ActiveForm ----------------------------

}
