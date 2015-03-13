<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . " | Create User";
?>
<section class="wrap-content container clearfix">
	<div class="cud-box">
		<h2>Create User</h2>
		<?php $form = ActiveForm::begin( ['id' => 'frm-user-create', 'options' => ['class' => 'frm-split' ] ] );?>

    	<?= $form->field( $model, 'user_email' ) ?>
    	<?= $form->field( $model, 'user_username' ) ?>
		<?= $form->field( $model, 'user_role' )->dropDownList( $roles )  ?>
		<?= $form->field( $model, 'user_gender' )->dropDownList( $genders )  ?>
		<?= $form->field( $model, 'user_firstname' ) ?>
		<?= $form->field( $model, 'user_lastname' ) ?>
		<?= $form->field( $model, 'user_mobile' ) ?>
		<?= $form->field( $model, 'user_newsletter' )->checkbox() ?>
		
		<?=Html::a( "Cancel", [ '/cmgcore/user/all' ], ['class' => 'btn' ] );?>
		<input type="submit" value="Create" />
		
		<?php ActiveForm::end(); ?>
	</div>	
</section>
<script type="text/javascript">
	initSidebar( "sidebar-identity", 6 );
</script>