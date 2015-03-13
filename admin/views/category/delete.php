<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | Delete Category';
?>
<section class="wrap-content container clearfix">
	<div class="cud-box">
		<h2>Delete Category</h2>
		<?php $form = ActiveForm::begin( ['id' => 'frm-category-delete', 'options' => ['class' => 'frm-split' ] ] );?>

    	<?= $form->field( $model, 'category_name' )->textInput( [ 'disabled'=>'true' ] ) ?>
    	<?= $form->field( $model, 'category_desc' )->textarea( [ 'disabled'=>'true' ] ) ?>
		<?= $form->field( $model, 'category_type' )->dropDownList( $typeMap, [ 'disabled'=>'true' ] ) ?>

		<div class="box-filler"></div>

		<?php
			$error = Yii::$app->session->getFlash( "failure" );

			if( isset($error) ) {
				
				echo $error;
			}
		?>
		<div class="box-filler"></div>

		<?=Html::a( "Cancel", [ '/core/category/all' ], ['class' => 'btn' ] );?>
		<input type="submit" value="Delete" />

		<?php ActiveForm::end(); ?>
	</div>
</section>

<script type="text/javascript">
	initSidebar( "sidebar-category", -1 );
</script>