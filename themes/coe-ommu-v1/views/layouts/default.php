<?php
if(isset($_GET['protocol']) && $_GET['protocol'] == 'script') {
	echo $cs=Yii::app()->getClientScript()->getScripts();
	
} else {
	Yii::import('webroot.themes.'.Yii::app()->theme->name.'.components.*');
	Yii::import('webroot.themes.'.Yii::app()->theme->name.'.components.public.*');
	$module = strtolower(Yii::app()->controller->module->id);
	$controller = strtolower(Yii::app()->controller->id);
	$action = strtolower(Yii::app()->controller->action->id);
	$currentAction = strtolower(Yii::app()->controller->id.'/'.Yii::app()->controller->action->id);
	$currentModule = strtolower(Yii::app()->controller->module->id.'/'.Yii::app()->controller->id);
	$currentModuleAction = strtolower(Yii::app()->controller->module->id.'/'.Yii::app()->controller->id.'/'.Yii::app()->controller->action->id);

	/**
	 * = Global condition
	 ** Construction condition
	 */
	$setting = OmmuSettings::model()->findByPk(1,array(
		'select' => 'online, site_type, site_url, site_title, construction_date, signup_inviteonly, general_include',
	));
	$construction = (($setting->online == 0 && date('Y-m-d', strtotime($setting->construction_date)) > date('Y-m-d')) && (Yii::app()->user->isGuest || (!Yii::app()->user->isGuest && in_array(!Yii::app()->user->level, array(1,2))))) ? 1 : 0 ;

	/**
	 * = Dialog Condition
	 * $construction = 1 (construction active)
	 */
	if($construction == 1)
		$dialogWidth = !empty($this->dialogWidth) ? ($this->dialogFixed == false ? $this->dialogWidth.'px' : '600px') : '900px';
	else {
		if($this->dialogDetail == true)
			$dialogWidth = !empty($this->dialogWidth) ? ($this->dialogFixed == false ? $this->dialogWidth.'px' : '600px') : '700px';
		else
			$dialogWidth = '';
	}
	$display = ($this->dialogDetail == true && !Yii::app()->request->isAjaxRequest) ? 'style="display: block;"' : '';
	
	/**
	 * = pushState condition
	 */
	$title = CHtml::encode($this->pageTitle).' | '.$setting->site_title;
	$description = $this->pageDescription;
	$keywords = $this->pageMeta;
	$urlAddress = Utility::getProtocol().'://'.Yii::app()->request->serverName.Yii::app()->request->requestUri;
	$apps = $this->dialogDetail == true ? ($this->dialogFixed == false ? 'apps' : 'module') : '';

	if(Yii::app()->request->isAjaxRequest && !isset($_GET['ajax'])) {
		if(Yii::app()->session['theme_active'] != Yii::app()->theme->name) {
			$return = array(
				'redirect' => $urlAddress,		
			);

		} else {
			$page = $this->contentOther == true ? 1 : 0;
			$dialog = $this->dialogDetail == true ? (empty($this->dialogWidth) ? 1 : 2) : 0;		// 0 = static, 1 = dialog, 2 = notifier
			$header = /* $this->widget('SidebarAccountMenu', array(), true) */'';
			
			if($this->contentOther == true) {
				$render = array(
					'content' => $content, 
					'other' => $this->contentAttribute,
				);
			} else
				$render = $content;
			
			$return = array(
				'partial' => 'off',
				'titledoc' => CHtml::encode($this->pageTitle),
				'title' => $title,
				'description' => $description,
				'keywords' => $keywords,
				'address' => $urlAddress,
				'dialogWidth' => $dialogWidth,			
			);
			$return['page'] = $page;
			$return['dialog'] = $dialog;
			$return['apps'] = $apps;
			$return['header'] = $this->dialogDetail != true ? $header : '';
			$return['render'] = $render;
			$return['script'] = $cs=Yii::app()->getClientScript()->getOmmuScript();
		}
		echo CJSON::encode($return);

	} else {
		$cs = Yii::app()->getClientScript();
		$cs->registerCssFile(Yii::app()->theme->baseUrl.'/css/master.css');
		//$cs->registerCssFile(Yii::app()->theme->baseUrl.'/css/form.css');
		//$cs->registerCssFile(Yii::app()->theme->baseUrl.'/css/typography.css');
		//$cs->registerCssFile(Yii::app()->theme->baseUrl.'/css/layout.css');
		//$cs->registerCssFile(Yii::app()->request->baseUrl.'/externals/content.css');
		//$cs->registerCoreScript('jquery', CClientScript::POS_END);
		$cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/plugin/jquery-2.1.3.min.js', CClientScript::POS_END);
		$cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/plugin/bootstrap.min.js', CClientScript::POS_END);
		$cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/plugin/jquery.appear.js', CClientScript::POS_END);
		$cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/plugin/waypoint.js', CClientScript::POS_END);
		$cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/plugin/modernizr-latest.js', CClientScript::POS_END);
		$cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/plugin/jquery.easing.1.3.js', CClientScript::POS_END);
		$cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/plugin/SmoothScroll.js', CClientScript::POS_END);
		$cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/plugin/jquery.magnific-popup.min.js', CClientScript::POS_END);
		$cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/plugin/jquery.superslides.js', CClientScript::POS_END);
		$cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/plugin/jquery.flexslider.js', CClientScript::POS_END);
		$cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/plugin/jquery.simple-text-rotator.js', CClientScript::POS_END);
		$cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/plugin/jquery.cubeportfolio.js', CClientScript::POS_END);
		$cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/plugin/owl.carousel.min.js', CClientScript::POS_END);
		$cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/plugin/jquery.parallax-1.1.3.js', CClientScript::POS_END);
		$cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/plugin/skrollr.min.js', CClientScript::POS_END);
		$cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/plugin/jquery.fitvids.js', CClientScript::POS_END);
		$cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/plugin/jquery.mb.YTPlayer.js', CClientScript::POS_END);
		// Twitter
		$cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/plugin/tweecool.min.js', CClientScript::POS_END);
		$cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/plugin/tweecool.js', CClientScript::POS_END);
		// Revolution Slider
		$cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/plugin/rev_slider/jquery.themepunch.revolution.min.js', CClientScript::POS_END);
		$cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/plugin/rev_slider/jquery.themepunch.tools.min.js', CClientScript::POS_END);
		$cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/plugin/rev_slider/rev_plugins.js', CClientScript::POS_END);
		// Page Plugins
		$cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/plugin/plugins.js', CClientScript::POS_END);
		// Portfolio Plugins
		$cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/plugin/portfolio.js', CClientScript::POS_END);	
		Yii::app()->clientScript->scriptMap=array(
			'jquery.js'=>false,
		);		
		
		//Javascript Attribute
		$jsAttribute = array(
			'baseUrl'=>BASEURL,
			'lastTitle'=>$title,
			'lastDescription'=>$description,
			'lastKeywords'=>$keywords,
			'lastUrl'=>$urlAddress,
			'dialogConstruction'=>$construction == 1 ? 1 : 0,
			'dialogGroundUrl'=>$this->dialogDetail == true ? ($this->dialogGroundUrl != '' ? $this->dialogGroundUrl : '') : '',
		);
		if($this->contentOther == true)
			$jsAttribute['contentOther'] = $this->contentAttribute;
		if(!empty($this->location_id)) {
			$jsAttribute['location_office'] = Utility::formatFileType(Utility::formatFileType($this->location_office), false);
			$jsAttribute['location_office_url'] = Utility::getProtocol().'://'.Yii::app()->request->serverName.$this->location_office_url;			
		}
	?>
<!DOCTYPE html>
<html>
 <head>
  <meta charset="UTF-8" />
  <title><?php echo $title;?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <meta name="author" content="Ommu Platform (support@ommu.co)" />
  <script type="text/javascript">
	var globals = '<?php echo CJSON::encode($jsAttribute);?>';
  </script>
  <?php echo $setting->general_include != '' ? $setting->general_include : ''?>
  <link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl?>/favicon.ico" />
  <style type="text/css"></style>	
 </head>
 <body <?php echo $this->dialogDetail == true ? 'style="overflow-y: hidden;"' : '';?> class="parallax">
 
	<!-- Page Loader -->
	<?php 
	$location = false;
	$location_footer = false;
	if(in_array($controller, array('jateng','jabar','jatim','banten','jogja','jakarta'))) {
		$location_footer = true;
		if($action == 'index')
			$location = true;
	}
	if($module == null && $currentAction == 'site/index' || $location == true) {
		$this->widget('MainBannerLoader');
	}?>

	<!-- Navigation - select your nav color - dark-nav or white-nav -->
	<nav id="navigation" class="white-nav <?php echo $module == null && $currentAction == 'site/index' ? '' : ($location == true ? '' : 'relative-nav');?>">
		<!-- Navigation -->
		<div class="navigation <?php echo ($module == null && $currentAction == 'site/index' || $location == true) ? 'first-nav' : '';?> double-nav raleway">
			<!-- Navigation Inner -->
			<div class="nav-inner clearfix">
				<!-- Logo Area -->
				<div class="logo f-left">
					<!-- Logo Link -->
					<a href="<?php echo Yii::app()->createUrl('site/index')?>" class="logo-link scroll" title="<?php echo $setting->site_title;?>">
						<!-- Logo Image / data-second-logo for only white nav -->
						<?php if($module == null && $currentAction == 'site/index' || $location == true) {?>
							<img src="<?php echo Yii::app()->theme->baseUrl;?>/images/logo_white.png" data-second-logo="<?php echo Yii::app()->theme->baseUrl;?>/images/logo_dark.png" alt="<?php echo $setting->site_title;?>" />
						<?php } else {?>
							<img src="<?php echo Yii::app()->theme->baseUrl;?>/images/logo_dark.png" alt="<?php echo $setting->site_title;?>" />
						<?php }?>
					</a>
				</div>
				<!-- End Logo Area -->
				<!-- Mobile Menu Button -->
				<a class="mobile-nav-button"><i class="fa fa-bars"></i></a>
				<!-- Navigation Links -->
				<div class="nav-menu clearfix f-right">
					<!-- Nav List -->
					<ul class="nav uppercase normal">
						<li class="dropdown-toggle nav-toggle">
							<a href="<?php echo Yii::app()->createUrl('site/index')?>"><?php echo Yii::t('phrase', 'Home');?></a>
						</li>
						<li class="dropdown-toggle nav-toggle">
							<a href="<?php echo Yii::app()->createUrl('page/view', array('id'=>6,'t'=>Utility::getUrlTitle(Phrase::trans(1539, 2))))?>"><?php echo Yii::t('phrase', 'Tentang Kami');?></a>
						</li>
						<li class="dropdown-toggle nav-toggle">
							<a href="<?php echo Yii::app()->createUrl('article/site/index', array('category'=>1,'t'=>Utility::getUrlTitle(Phrase::trans(1531, 2))));?>"><?php echo Phrase::trans(1531, 2);?></a>
						</li>
						<li class="dropdown-toggle nav-toggle">
							<a href="javascript:void(0);" title="<?php echo Yii::t('phrase', 'Budaya');?>"><?php echo Yii::t('phrase', 'Budaya');?></a>
							<!-- DropDown Menu -->
							<?php ?>
							<ul class="dropdown-menu pull-left clearfix">
								<li><a href="<?php echo Yii::app()->createUrl('article/site/index', array('category'=>2,'t'=>Utility::getUrlTitle(Phrase::trans(1533, 2))));?>"><?php echo Phrase::trans(1533, 2);?></a></li>
								<li><a href="<?php echo Yii::app()->createUrl('article/site/index', array('category'=>3,'t'=>Utility::getUrlTitle(Phrase::trans(1535, 2))));?>"><?php echo Phrase::trans(1535, 2);?></a></li>
								<li><a href="<?php echo Yii::app()->createUrl('article/site/index', array('category'=>4,'t'=>Utility::getUrlTitle(Phrase::trans(1537, 2))));?>"><?php echo Phrase::trans(1537, 2);?></a></li>
							</ul>
							<!-- End DropDown -->
						</li>
						<li class="dropdown-toggle nav-toggle">
							<a href="javascript:void(0);"><?php echo Yii::t('phrase', 'Member CoE');?></a>
							<!-- DropDown Menu -->
							<?php $this->widget('MainArticleLocation', array(
								'layout'=>'menu_article_location',
							)); ?>
							<!-- End DropDown -->
						</li>
						<li class="dropdown-toggle nav-toggle">
							<a href="<?php echo Yii::app()->createUrl('article/collections/index');?>"><?php echo Phrase::trans(1547, 2);?></a>
						</li>
						<li class="dropdown-toggle nav-toggle">
							<a href="<?php echo Yii::app()->createUrl('support/contact/feedback')?>"><?php echo Yii::t('phrase', 'Kontak Kami');?></a>
						</li>
						<li class="dropdown-toggle nav-toggle">
							<a href="#" class="tahoma"><i class="fa fa-search"></i></a>
							<!-- DropDown Menu -->
							<ul class="dropdown-menu pull-right clearfix">
								<li class="ml-20 mt-15 mr-20 mb-15 raleway mini-text gray">
									<form action="<?php echo Yii::app()->createUrl('search/result');?>" method="post" class="search-form">
										<input type="text" name="keyword" id="search" class="transparent uppercase" placeholder="<?php echo Yii::t('phrase', 'Kata Kunci...');?>">
										<button type="submit">
										<i class="fa fa-search"></i>
										</button>
									</form>
								</li>
							</ul>
						</li>
					</ul>
					<!-- End Nav List -->
				</div>
				<!-- End Navigation Links -->
			</div>
			<!-- End Navigation Inner -->
		</div>
	</nav>
	<!-- End Nav -->
	
	<?php echo $content;?>
	
	<?php if($location_footer == true) {
		$this->widget('FooterArticleLocationAddress', array(
			'location'=>$this->location_id,
		));
	}?>
	
	<!-- Footer -->
	<footer class="big-footer fullwidth dark-footer t-left">
		<!-- Footer Inner -->
		<?php if($location_footer == false) {?>
		<div class="clearfix boxed footer_inner">
			<?php $this->widget('FooterAbouts'); ?>
			<?php $this->widget('FooterArticleLocation'); ?>
			<?php $this->widget('FooterArticle', array(
				'type'=>'news',
				'category'=>array(1),
			)); ?>
			<?php $this->widget('FooterArticle', array(
				'type'=>'budaya',
				'category'=>array(2,3,4,5),
			)); ?>
		</div>
		<?php }?>
		<!-- End Footer Inner -->
		<!-- Bottom -->
		<div class="footer_bottom">
			<!-- Bottom Inner -->
			<div class="boxed clearfix">
				<?php $this->widget('FooterCopyrightAndSocial'); ?>
			</div>
			<!-- End Inner -->
		</div>
		<!-- End Footer, Bottom -->
	</footer>
	<!-- End Footer -->	
	
	<div id="back-top"><a href="#<?php echo $module == null && $currentAction == 'site/index' ? 'home' : ($location == true ? 'page-header' : 'page-header');?>" class="scroll t-center white"><i class="fa fa-angle-up"></i></a></div>
	
	<?php $this->widget('FrontGoogleAnalytics'); ?>
 </body>
</html>
<?php }
}?>