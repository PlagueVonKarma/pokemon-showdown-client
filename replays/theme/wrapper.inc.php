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
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/font-awesome.css?0.8088205310877534" />
	<link rel="stylesheet" href="//dragonheavenserver.herokuapp.com/theme/panels.css?0.3578134113306122" />
	<link rel="stylesheet" href="//dragonheavenserver.herokuapp.com/theme/main.css?0.7459520298899076" />
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/battle.css?0.44452495343841036" />
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/replay.css?0.5024913920096052" />
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/utilichart.css?0.5186199497749995" />

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
				<li><a class="button nav-first<?php if ($panels->tab === 'home') echo ' cur'; ?>" href="//dragonheavenserver.herokuapp.com/?0.3422201070875328"><img src="//dragonheavenserver.herokuapp.com/images/pokemonshowdownbeta.png?0.7175214760662756" alt="Pok&eacute;mon Showdown! (beta)" /> Home</a></li>
				<li><a class="button<?php if ($panels->tab === 'pokedex') echo ' cur'; ?>" href="//dex.pokemonshowdown.com/?0.7898497777189848">Pok&eacute;dex</a></li>
				<li><a class="button<?php if ($panels->tab === 'replay') echo ' cur'; ?>" href="/?0.44119966655611953">Replays</a></li>
				<li><a class="button<?php if ($panels->tab === 'ladder') echo ' cur'; ?>" href="//dragonheavenserver.herokuapp.com/ladder/?0.24825984486283104">Ladder</a></li>
				<li><a class="button nav-last" href="//dragonheavenserver.herokuapp.com/forums/?0.3781371338741122">Forum</a></li>
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
	<script src="//dragonheaven.herokuapp.com/js/lib/jquery-1.11.0.min.js?0.44633390615253665"></script>
	<script src="//dragonheaven.herokuapp.com/js/lib/lodash.core.js?0.3294052215579186"></script>
	<script src="//dragonheaven.herokuapp.com/js/lib/backbone.js?0.3617057178812162"></script>
	<script src="//dex.pokemonshowdown.com/js/panels.js?0.8149674946524337"></script>
<?php
}

function ThemeFooterTemplate() {
	global $panels;
?>
<?php $panels->scripts(); ?>

	<script src="//dragonheaven.herokuapp.com/js/lib/jquery-cookie.js?0.5409041672282866"></script>
	<script src="//dragonheaven.herokuapp.com/js/lib/html-sanitizer-minified.js?0.14475387327499356"></script>
	<script src="//dragonheaven.herokuapp.com/js/battle-sound.js?0.9879676492946734"></script>
	<script src="//dragonheaven.herokuapp.com/config/config.js?0.8811224961913728"></script>
	<script src="//dragonheaven.herokuapp.com/js/battledata.js?0.00014914453552994367"></script>
	<script src="//dragonheaven.herokuapp.com/data/pokedex-mini.js?0.308134279900371"></script>
	<script src="//dragonheaven.herokuapp.com/data/pokedex-mini-bw.js?0.840336871232148"></script>
	<script src="//dragonheaven.herokuapp.com/data/graphics.js?0.65707602443556"></script>
	<script src="//dragonheaven.herokuapp.com/data/pokedex.js?0.995551765798552"></script>
	<script src="//dragonheaven.herokuapp.com/data/items.js?0.2253320735275035"></script>
	<script src="//dragonheaven.herokuapp.com/data/moves.js?0.8997447645068075"></script>
	<script src="//dragonheaven.herokuapp.com/data/abilities.js?0.8517376212146623"></script>
	<script src="//dragonheaven.herokuapp.com/data/teambuilder-tables.js?0.001640228652307174"></script>
	<script src="//dragonheaven.herokuapp.com/js/battle-tooltips.js?0.332031555183536"></script>
	<script src="//dragonheaven.herokuapp.com/js/battle.js?0.08372464777070299"></script>
	<script src="/js/replay.js?c81925c8"></script>

</body></html>
<?php
}
