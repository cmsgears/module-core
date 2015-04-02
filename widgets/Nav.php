<?php
namespace cmsgears\core\widgets;

use \Yii;
use yii\base\Widget;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class Nav extends Widget {

	// Variables ---------------------------------------------------

	// Public Variables --------------------

    public $items   = [];
    public $options = [];

    public $route;
    public $params;

	// Constructor and Initialisation ------------------------------
	
	// yii\base\Object

    public function init() {

        parent::init();

        if( $this->route === null && Yii::$app->controller !== null ) {

            $this->route = Yii::$app->controller->getRoute();
        }

        if( $this->params === null ) {

            $this->params = Yii::$app->request->getQueryParams();
        }
    }

	// Instance Methods --------------------------------------------

	// yii\base\Widget

    public function run() {

        echo $this->renderItems();
    }
	
	// Nav

    public function renderItems() {

        $items = [];

        foreach( $this->items as $i => $item ) {

            $items[] = $this->renderItem($item);
        }

        return Html::tag( 'ul', implode("\n", $items), $this->options );
    }

    public function renderItem( $item ) {

        if( !isset( $item['label'] ) ) {

            throw new InvalidConfigException( "The 'label' option is required." );
        }

        $label      = $item['label'];
        $url        = $item['url'];
        $options    = [];
		
		if( isset( $item['options'] ) ) {
			
			$options = $item['options'];
		}

        return Html::tag( 'li', Html::a($label, $url, null), $options );
    }
}