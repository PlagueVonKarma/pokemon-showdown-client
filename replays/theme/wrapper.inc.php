<?php

if ((substr($_SERVER['REMOTE_ADDR'],0,11) === '69.164.163.') ||
		(substr(@$_SERVER['HTTP_X_FORWARDED_FOR'],0,11) === '69.164.163.')) {
	die('website disabled');
}

/********************************************************************
 * Header
 ********************************************************************/

function ThemeHeaderTemplate() {
	global $panels;
?>
<!DOCTYPE html>
<html><head>

	<meta charset="utf-8" />

	<title><?php if ($panels->pagetitle) echo htmlspecialchars($panels->pagetitle).' - '; ?>Pok&eacute;mon Showdown</title>

<?php if ($panels->pagedescription) { ?>
	<meta name="description" content="<?php echo htmlspecialchars($panels->pagedescription); ?>" />
<?php } ?>

	<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=IE8" />
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/font-awesome.css?0.007563098033766202" />
	<link rel="stylesheet" href="//dragonheavenserver.herokuapp.com/theme/panels.css?0.03807065061795001" />
	<link rel="stylesheet" href="//dragonheavenserver.herokuapp.com/theme/main.css?0.42981152532136124" />
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/battle.css?0.8755303060537294" />
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/replay.css?0.6181919651197829" />
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/utilichart.css?0.7304421386750994" />

	<!-- Workarounds for IE bugs to display trees correctly. -->
	<!--[if lte IE 6]><style> li.tree { height: 1px; } </style><![endif]-->
	<!--[if IE 7]><style> li.tree { zoom: 1; } </style><![endif]-->

	<script type="text/javascript">
		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', 'UA-26211653-1']);
		_gaq.push(['_setDomainName', 'pokemonshowdown.com']);
		_gaq.push(['_setAllowLinker', true]);
		_gaq.push(['_trackPageview']);

		(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();
	</script>
</head><body>

	<div class="pfx-topbar">
		<div class="header">
			<ul class="nav">
				<li><a class="button nav-first<?php if ($panels->tab === 'home') echo ' cur'; ?>" href="//dragonheavenserver.herokuapp.com/?0.48838238125818667"><img src="//dragonheavenserver.herokuapp.com/images/pokemonshowdownbeta.png?0.8943126364778109" alt="Pok&eacute;mon Showdown! (beta)" /> Home</a></li>
				<li><a class="button<?php if ($panels->tab === 'pokedex') echo ' cur'; ?>" href="//dex.pokemonshowdown.com/?0.2645293298691165">Pok&eacute;dex</a></li>
				<li><a class="button<?php if ($panels->tab === 'replay') echo ' cur'; ?>" href="/?0.7575153590392498">Replays</a></li>
				<li><a class="button<?php if ($panels->tab === 'ladder') echo ' cur'; ?>" href="//dragonheavenserver.herokuapp.com/ladder/?0.131603674912534">Ladder</a></li>
				<li><a class="button nav-last" href="//dragonheavenserver.herokuapp.com/forums/?0.04468893035035015">Forum</a></li>
			</ul>
			<ul class="nav nav-play">
				<li><a class="button greenbutton nav-first nav-last" href="http://play.pokemonshowdown.com/">Play</a></li>
			</ul>
			<div style="clear:both"></div>
		</div>
	</div>
<?php
}

/********************************************************************
 * Footer
 ********************************************************************/

function ThemeScriptsTemplate() {
?>
	<script src="//dragonheaven.herokuapp.com/js/lib/jquery-1.11.0.min.js?0.6814685485820815"></script>
	<script src="//dragonheaven.herokuapp.com/js/lib/lodash.core.js?0.618095425539309"></script>
	<script src="//dragonheaven.herokuapp.com/js/lib/backbone.js?0.08133019721787482"></script>
	<script src="//dex.pokemonshowdown.com/js/panels.js?0.5031646537366707"></script>
<?php
}

function ThemeFooterTemplate() {
	global $panels;
?>
<?php $panels->scripts(); ?>

	<script src="//dragonheaven.herokuapp.com/js/lib/jquery-cookie.js?0.5018663501386011"></script>
	<script src="//dragonheaven.herokuapp.com/js/lib/html-sanitizer-minified.js?0.3793136336308045"></script>
	<script src="//dragonheaven.herokuapp.com/js/battle-sound.js?0.9393216451134121"></script>
	<script src="//dragonheaven.herokuapp.com/config/config.js?0.5030990921195715"></script>
	<script src="//dragonheaven.herokuapp.com/js/battledata.js?0.09225170646378933"></script>
	<script src="//dragonheaven.herokuapp.com/data/pokedex-mini.js?0.8216435038514602"></script>
	<script src="//dragonheaven.herokuapp.com/data/pokedex-mini-bw.js?0.15705870743551942"></script>
	<script src="//dragonheaven.herokuapp.com/data/graphics.js?0.20695028388770664"></script>
	<script src="//dragonheaven.herokuapp.com/data/pokedex.js?0.8350447160396308"></script>
	<script src="//dragonheaven.herokuapp.com/data/items.js?0.9974755190290991"></script>
	<script src="//dragonheaven.herokuapp.com/data/moves.js?0.9173326195626241"></script>
	<script src="//dragonheaven.herokuapp.com/data/abilities.js?0.7478643259833153"></script>
	<script src="//dragonheaven.herokuapp.com/data/teambuilder-tables.js?0.005989829156309412"></script>
	<script src="//dragonheaven.herokuapp.com/js/battle-tooltips.js?0.045326794218140565"></script>
	<script src="//dragonheaven.herokuapp.com/js/battle.js?0.3853884230971978"></script>
	<script src="/js/replay.js?c81925c8"></script>

</body></html>
<?php
}
