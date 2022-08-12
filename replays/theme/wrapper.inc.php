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
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/font-awesome.css?0.5271985597801838" />
	<link rel="stylesheet" href="//dragonheavenserver.herokuapp.com/theme/panels.css?0.20831794233428247" />
	<link rel="stylesheet" href="//dragonheavenserver.herokuapp.com/theme/main.css?0.5000757219504572" />
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/battle.css?0.2530428122692434" />
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/replay.css?0.2689434905087533" />
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/utilichart.css?0.09744752800226841" />

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
				<li><a class="button nav-first<?php if ($panels->tab === 'home') echo ' cur'; ?>" href="//dragonheavenserver.herokuapp.com/?0.7362673135194546"><img src="//dragonheavenserver.herokuapp.com/images/pokemonshowdownbeta.png?0.4305478086287309" alt="Pok&eacute;mon Showdown! (beta)" /> Home</a></li>
				<li><a class="button<?php if ($panels->tab === 'pokedex') echo ' cur'; ?>" href="//dex.pokemonshowdown.com/?0.5289036431971805">Pok&eacute;dex</a></li>
				<li><a class="button<?php if ($panels->tab === 'replay') echo ' cur'; ?>" href="/?0.2766699416351408">Replays</a></li>
				<li><a class="button<?php if ($panels->tab === 'ladder') echo ' cur'; ?>" href="//dragonheavenserver.herokuapp.com/ladder/?0.47885045182281005">Ladder</a></li>
				<li><a class="button nav-last" href="//dragonheavenserver.herokuapp.com/forums/?0.9309811378243444">Forum</a></li>
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
	<script src="//dragonheaven.herokuapp.com/js/lib/jquery-1.11.0.min.js?0.499008526789974"></script>
	<script src="//dragonheaven.herokuapp.com/js/lib/lodash.core.js?0.9109961357253298"></script>
	<script src="//dragonheaven.herokuapp.com/js/lib/backbone.js?0.6688429404476759"></script>
	<script src="//dex.pokemonshowdown.com/js/panels.js?0.9652166017732411"></script>
<?php
}

function ThemeFooterTemplate() {
	global $panels;
?>
<?php $panels->scripts(); ?>

	<script src="//dragonheaven.herokuapp.com/js/lib/jquery-cookie.js?0.7600904250071068"></script>
	<script src="//dragonheaven.herokuapp.com/js/lib/html-sanitizer-minified.js?0.7466040303339532"></script>
	<script src="//dragonheaven.herokuapp.com/js/battle-sound.js?0.40564824381106424"></script>
	<script src="//dragonheaven.herokuapp.com/config/config.js?0.2550061464746167"></script>
	<script src="//dragonheaven.herokuapp.com/js/battledata.js?0.8805206753192736"></script>
	<script src="//dragonheaven.herokuapp.com/data/pokedex-mini.js?0.3786520663686521"></script>
	<script src="//dragonheaven.herokuapp.com/data/pokedex-mini-bw.js?0.33918390763957595"></script>
	<script src="//dragonheaven.herokuapp.com/data/graphics.js?0.3797355103558113"></script>
	<script src="//dragonheaven.herokuapp.com/data/pokedex.js?0.5266645779811336"></script>
	<script src="//dragonheaven.herokuapp.com/data/items.js?0.8271572832704979"></script>
	<script src="//dragonheaven.herokuapp.com/data/moves.js?0.81746298276723"></script>
	<script src="//dragonheaven.herokuapp.com/data/abilities.js?0.7139736061275064"></script>
	<script src="//dragonheaven.herokuapp.com/data/teambuilder-tables.js?0.360948113674709"></script>
	<script src="//dragonheaven.herokuapp.com/js/battle-tooltips.js?0.06505393703772921"></script>
	<script src="//dragonheaven.herokuapp.com/js/battle.js?0.5639560057880597"></script>
	<script src="/js/replay.js?c81925c8"></script>

</body></html>
<?php
}
