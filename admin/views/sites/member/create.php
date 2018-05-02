<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= 'Add Site Member | ' . $coreProperties->getSiteTitle();
$returnUrl		= $this->context->returnUrl;
?>
<div class="box-crud-wrap row">
	<div class="box-crud-wrap-main colf colf3x2">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-member', 'options' => [ 'class' => 'form' ] ] ); ?>
		<div class="box box-crud">
			<div class="box-header">
				<div class="box-header-title">Member Details</div>
			</div>
			<div class="box-content-wrap">
				<div class="box-content mapper mapper-auto">
					<div class="auto-fill auto-fill-basic">
						<div class="auto-fill-source row row-medium" cmt-app="site" cmt-controller="member" cmt-action="autoSearch" action="core/sites/member/auto-search?sid=<?= $siteId ?>" cmt-keep cmt-custom>
							<div class="relative">
								<div class="auto-fill-search clearfix">
									<div class="frm-icon-element icon-right">
										<span class="icon cmti cmti-search"></span>
										<input class="cmt-key-up auto-fill-text search-name" type="text" name="name" placeholder="Username" autocomplete="off" />
									</div>
								</div>
								<div class="auto-fill-items-wrap">
									<ul class="auto-fill-items vnav"></ul>
								</div>
							</div>
						</div>
						<div class="filler-height filler-height-large"></div>
						<div class="site-member <?= empty( $model->name ) ? 'hidden-easy' : null ?>">
							<div class="row">
								<div class="col col2">
									<?= $form->field( $model, 'name' )->textInput( [ 'readonly' => true ] )->label( 'User' ) ?>
								</div>
								<div class="col col2">
									<?= $form->field( $model, 'siteId', [ 'errorOptions' => [ 'class' => 'help-block hidden' ] ] )->hiddenInput( [ 'value'=> $siteId ] )->label( false )  ?>
									<?= $form->field( $model, 'userId', [ 'errorOptions' => [ 'class' => 'help-block hidden' ] ] )->hiddenInput()->label( false )  ?>
									<?= $form->field( $model, 'roleId' )->dropDownList( $roleMap, [ 'class' => 'cmt-select' ] ) ?>
								</div>
							</div>
							<div class="row">
								<div class="col col2">
									<?= Yii::$app->formDesigner->getIconCheckbox( $form, $model, 'pinned', null, 'cmti cmti-checkbox' ) ?>
								</div>
								<div class="col col2">
									<?= Yii::$app->formDesigner->getIconCheckbox( $form, $model, 'featured', null, 'cmti cmti-checkbox' ) ?>
								</div>
							</div>
							<div class="filler-height"></div>
							<div class="row row-inline">
								<?php if( $model->hasErrors() ) { ?>
								<span class="error">Site member already exist.</span>
								<?php } ?>
							</div>
						</div>
						<div class="filler-height"></div>
					</div>
				</div>
			</div>
		</div>

		<div class="filler-height filler-height-medium"></div>

		<div class="align align-right">
			<?= Html::a( 'Cancel', $returnUrl, [ 'class' => 'btn btn-medium' ] ); ?>
			<input class="element-medium" type="submit" value="Create" />
		</div>

		<div class="filler-height filler-height-medium"></div>
		<?php ActiveForm::end(); ?>
	</div>
	<div class="box-crud-wrap-sidebar colf colf3">

	</div>
</div>
