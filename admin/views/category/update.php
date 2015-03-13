<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | Update Category';
?>
<section class="wrap-content container clearfix">
	<div class="cud-box">
		<h2>Update Category</h2>
		<?php $form = ActiveForm::begin( ['id' => 'frm-category-update', 'options' => ['class' => 'frm-split' ] ] );?>

    	<?= $form->field( $model, 'category_name' ) ?>
    	<?= $form->field( $model, 'category_desc' )->textarea() ?>
    	<?= $form->field( $model, 'category_type' )->dropDownList( $typeMap ) ?>

		<div class="box-filler"></div>
		<?=Html::a( "Back", [ '/core/category/all' ], ['class' => 'btn' ] );?>
		<input type="submit" value="Update" />

		<?php ActiveForm::end(); ?>
	</div>
</section>

<script type="text/javascript">
	initSidebar( "sidebar-category", -1 );
</script>