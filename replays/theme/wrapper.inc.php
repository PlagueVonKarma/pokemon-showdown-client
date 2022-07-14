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
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/font-awesome.css?0.43073212307357234" />
	<link rel="stylesheet" href="//dragonheavenserver.herokuapp.com/theme/panels.css?0.4963946989872554" />
	<link rel="stylesheet" href="//dragonheavenserver.herokuapp.com/theme/main.css?0.7461055136937" />
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/battle.css?0.10030291683221892" />
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/replay.css?0.8326155485332218" />
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/utilichart.css?0.13641165866375338" />

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
				<li><a class="button nav-first<?php if ($panels->tab === 'home') echo ' cur'; ?>" href="//dragonheavenserver.herokuapp.com/?0.014588350637277037"><img src="//dragonheavenserver.herokuapp.com/images/pokemonshowdownbeta.png?0.5913777007722838" alt="Pok&eacute;mon Showdown! (beta)" /> Home</a></li>
				<li><a class="button<?php if ($panels->tab === 'pokedex') echo ' cur'; ?>" href="//dex.pokemonshowdown.com/?0.2664913911722053">Pok&eacute;dex</a></li>
				<li><a class="button<?php if ($panels->tab === 'replay') echo ' cur'; ?>" href="/?0.03229807088887604">Replays</a></li>
				<li><a class="button<?php if ($panels->tab === 'ladder') echo ' cur'; ?>" href="//dragonheavenserver.herokuapp.com/ladder/?0.0552341055001524">Ladder</a></li>
				<li><a class="button nav-last" href="//dragonheavenserver.herokuapp.com/forums/?0.3035105819974473">Forum</a></li>
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
	<script src="//dragonheaven.herokuapp.com/js/lib/jquery-1.11.0.min.js?0.3063464229882542"></script>
	<script src="//dragonheaven.herokuapp.com/js/lib/lodash.core.js?0.5234656957322732"></script>
	<script src="//dragonheaven.herokuapp.com/js/lib/backbone.js?0.9044277891035886"></script>
	<script src="//dex.pokemonshowdown.com/js/panels.js?0.26589073501253235"></script>
<?php
}

function ThemeFooterTemplate() {
	global $panels;
?>
<?php $panels->scripts(); ?>

	<script src="//dragonheaven.herokuapp.com/js/lib/jquery-cookie.js?0.6089204812808029"></script>
	<script src="//dragonheaven.herokuapp.com/js/lib/html-sanitizer-minified.js?0.951179644303364"></script>
	<script src="//dragonheaven.herokuapp.com/js/battle-sound.js?0.9217520372866455"></script>
	<script src="//dragonheaven.herokuapp.com/config/config.js?0.2259153284301838"></script>
	<script src="//dragonheaven.herokuapp.com/js/battledata.js?0.9047805321128819"></script>
	<script src="//dragonheaven.herokuapp.com/data/pokedex-mini.js?0.42111429083967056"></script>
	<script src="//dragonheaven.herokuapp.com/data/pokedex-mini-bw.js?0.22281714778246697"></script>
	<script src="//dragonheaven.herokuapp.com/data/graphics.js?0.9015346411879221"></script>
	<script src="//dragonheaven.herokuapp.com/data/pokedex.js?0.9090471525984021"></script>
	<script src="//dragonheaven.herokuapp.com/data/items.js?0.5651392484166509"></script>
	<script src="//dragonheaven.herokuapp.com/data/moves.js?0.6519861067410258"></script>
	<script src="//dragonheaven.herokuapp.com/data/abilities.js?0.5542200485456232"></script>
	<script src="//dragonheaven.herokuapp.com/data/teambuilder-tables.js?0.9451426095276563"></script>
	<script src="//dragonheaven.herokuapp.com/js/battle-tooltips.js?0.3013913860527464"></script>
	<script src="//dragonheaven.herokuapp.com/js/battle.js?0.14868087827298804"></script>
	<script src="/js/replay.js?c81925c8"></script>

</body></html>
<?php
}
