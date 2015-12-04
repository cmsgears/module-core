<?php
// Yii Import
use yii\helpers\Html; 
use yii\widgets\LinkPager;
use yii\helpers\Url;
?>
<?php include 'header.php'; ?>
<section class="wrap-content container clearfix">
	<div class="cud-box frm-split">
		<h2><?= ucfirst( $type ) ?> Settings</h2>
		<?php 
			if( isset( $fieldsMap ) && count( $fieldsMap ) > 0 ) {

				foreach ( $fieldsMap as $field ) { 
		?>
				<label><?= $field->label ?></label>
				<label><?= $field->getFieldValue() ?></label>
		<?php 
				}

				echo "<div class='box-filler'></div>";
				echo Html::a( "<input type='button' value='Update' class='btn'>", ["/cmgcore/settings/update?type=$type"], null );
				echo "<div class='box-filler'></div>";
			}
			else {
		?>
		<p>No settings found.</p>
		<?php } ?>
	</div>
</section>