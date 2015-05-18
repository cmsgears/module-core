<?php
namespace cmsgears\core\widgets;

use \Yii;
use yii\base\Widget;
use yii\helpers\Html;

use cmsgears\core\common\services\GalleryService;

class Gallery extends Widget {

	// Variables ---------------------------------------------------

	// Public Variables --------------------

	// html options for Yii Widget
	public $options 	= [];

    public $galleryName;

	// Constructor and Initialisation ------------------------------

	// yii\base\Object

    public function init() {

        parent::init();
    }

	// Instance Methods --------------------------------------------

	// yii\base\Widget

    public function run() {

        echo $this->renderItems();
    }

    public function renderItems() {

		$gallery	= GalleryService::findByName( $this->galleryName );
		$items 		= [];

		if( isset( $gallery ) ) {

			// Generate Items Html

			$gitems = $gallery->files;
	
	        foreach( $gitems as $item ) {
	
	            $items[] = $this->renderItem( $item );
	        }
		}
		else {

			echo "<p>Gallery does not exist. Please create it via admin having name set to $this->galleryName.</p>";
		}
		
		$itemsHtml		= implode( "\n", $items );
		$galleryHtml	= $this->renderGallery( $gallery, $itemsHtml );

        return Html::tag( 'div', $galleryHtml, $this->options );
    }

    public function renderGallery( $gallery, $itemsHtml ) {

		return "<div class='gallery'><h2>$gallery->name</h2><p>$gallery->description</p></div><div class='items'><ul>$itemsHtml</ul></div>";
	}

    public function renderItem( $item ) {

		$image			= $item->file;
		$imageUrl		= $image->getFileUrl();
		$imageAlt		= $image->altText;

		$itemDesc		= $image->description;
		$itemLink		= $image->link;
		
		if( isset( $itemLink ) && strlen( $itemLink ) > 0 ) {

			$itemHtml		= "<li>
								<div class='wrap-item'>
									<div class='flip1'><img src='$imageUrl' class='fluid' /></div>
									<div class='flip2'><span class='info'>$itemDesc</span><span class='link'><a href='$itemLink' class='btn small'>View</a></span></div>
							</li>";
		}
		else {

			$itemHtml		= "<li>
								<div class='wrap-item'>
									<div class='flip1'><img src='$imageUrl' class='fluid' /></div>
									<div class='flip2'><span class='info'>$itemDesc</span></div>
							</li>";
		}

		return $itemHtml;
    }
}

?>