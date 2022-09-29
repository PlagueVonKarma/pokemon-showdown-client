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
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/font-awesome.css?0.9513533340973064" />
	<link rel="stylesheet" href="//dragonheavenserver.herokuapp.com/theme/panels.css?0.7826215445952234" />
	<link rel="stylesheet" href="//dragonheavenserver.herokuapp.com/theme/main.css?0.08060290638331447" />
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/battle.css?0.32192260705492637" />
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/replay.css?0.2135913895439132" />
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/utilichart.css?0.5121422893709731" />

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
				<li><a class="button nav-first<?php if ($panels->tab === 'home') echo ' cur'; ?>" href="//dragonheavenserver.herokuapp.com/?0.5823639783261112"><img src="//dragonheavenserver.herokuapp.com/images/pokemonshowdownbeta.png?0.8423722692163065" alt="Pok&eacute;mon Showdown! (beta)" /> Home</a></li>
				<li><a class="button<?php if ($panels->tab === 'pokedex') echo ' cur'; ?>" href="//dex.pokemonshowdown.com/?0.9178895541892158">Pok&eacute;dex</a></li>
				<li><a class="button<?php if ($panels->tab === 'replay') echo ' cur'; ?>" href="/?0.3544736392244412">Replays</a></li>
				<li><a class="button<?php if ($panels->tab === 'ladder') echo ' cur'; ?>" href="//dragonheavenserver.herokuapp.com/ladder/?0.15905125110983387">Ladder</a></li>
				<li><a class="button nav-last" href="//dragonheavenserver.herokuapp.com/forums/?0.2802512895867195">Forum</a></li>
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
	<script src="//dragonheaven.herokuapp.com/js/lib/jquery-1.11.0.min.js?0.19002048655966375"></script>
	<script src="//dragonheaven.herokuapp.com/js/lib/lodash.core.js?0.9765473077506519"></script>
	<script src="//dragonheaven.herokuapp.com/js/lib/backbone.js?0.3958701696500697"></script>
	<script src="//dex.pokemonshowdown.com/js/panels.js?0.3841158146926342"></script>
<?php
}

function ThemeFooterTemplate() {
	global $panels;
?>
<?php $panels->scripts(); ?>

	<script src="//dragonheaven.herokuapp.com/js/lib/jquery-cookie.js?0.2943626997758857"></script>
	<script src="//dragonheaven.herokuapp.com/js/lib/html-sanitizer-minified.js?0.256712366233395"></script>
	<script src="//dragonheaven.herokuapp.com/js/battle-sound.js?0.29913714829485816"></script>
	<script src="//dragonheaven.herokuapp.com/config/config.js?0.7726082545305479"></script>
	<script src="//dragonheaven.herokuapp.com/js/battledata.js?0.09022195556720702"></script>
	<script src="//dragonheaven.herokuapp.com/data/pokedex-mini.js?0.35209292590255936"></script>
	<script src="//dragonheaven.herokuapp.com/data/pokedex-mini-bw.js?0.7766487057822338"></script>
	<script src="//dragonheaven.herokuapp.com/data/graphics.js?0.7716975628809946"></script>
	<script src="//dragonheaven.herokuapp.com/data/pokedex.js?0.2587682313190436"></script>
	<script src="//dragonheaven.herokuapp.com/data/items.js?0.3370302701160053"></script>
	<script src="//dragonheaven.herokuapp.com/data/moves.js?0.24851467269693717"></script>
	<script src="//dragonheaven.herokuapp.com/data/abilities.js?0.053612278949983105"></script>
	<script src="//dragonheaven.herokuapp.com/data/teambuilder-tables.js?0.7771530099349091"></script>
	<script src="//dragonheaven.herokuapp.com/js/battle-tooltips.js?0.0018214933767035646"></script>
	<script src="//dragonheaven.herokuapp.com/js/battle.js?0.742773827460381"></script>
	<script src="/js/replay.js?c81925c8"></script>

</body></html>
<?php
}
