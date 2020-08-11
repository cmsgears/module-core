<?php
$coreProperties = $this->context->getCoreProperties();
$this->title	= 'Settings | ' . $coreProperties->getSiteTitle();

$themeTemplates	= Yii::getAlias( '@themes/admin/views/templates' );

$settings = Yii::$app->sidebar->getConfig();
?>
<div class="data-crud-wrap data-crud-wrap-basic">
	<div class="data-crud-wrap-main">
		<div id="data-crud-settings" class="data-crud">
			<div class="data-crud-title">
				<div class="wrap-popout-title">
					<span class="title">Core</span>
					<span id="popout-settings-trigger" class="inline-block right cmt-auto-hide" ldata-target="#popout-settings">
						<i class="btn-icon cmti cmti-list text text-micro"></i>
					</span>
				</div>
				<div class="wrap-popout-settings">
					<div id="popout-settings" class="hidden-easy">
						<div class="row max-cols-50">
							<?php
								foreach( $settings as $setting ) {

									$settingTitle = ucwords( str_replace( "-", " ", $setting ) );
							?>
								<div class="popout-setting popout-setting-<?= $setting ?> col col4" cmt-app="core" cmt-controller="settings" cmt-action="getContent" action="settings/index?type=<?= $setting ?>">
									<span class="cmt-click"><?= $settingTitle ?></span>
									<?php include "$themeTemplates/components/spinners/element.php"; ?>
								</div>
							<?php
								}
							?>
						</div>
					</div>
				</div>
			</div>
			<div class="data-crud-form">
				<div class="align align-center">
					<?php include "$themeTemplates/components/spinners/default.php"; ?>
				</div>
			</div>
		</div>
	</div>
</div>
