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
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/font-awesome.css?0.5455089406745133" />
	<link rel="stylesheet" href="//dragonheavenserver.herokuapp.com/theme/panels.css?0.7271858474905923" />
	<link rel="stylesheet" href="//dragonheavenserver.herokuapp.com/theme/main.css?0.1948504449956352" />
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/battle.css?0.30345806777457374" />
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/replay.css?0.5859547752475749" />
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/utilichart.css?0.2941235978606651" />

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
				<li><a class="button nav-first<?php if ($panels->tab === 'home') echo ' cur'; ?>" href="//dragonheavenserver.herokuapp.com/?0.1373406641107644"><img src="//dragonheavenserver.herokuapp.com/images/pokemonshowdownbeta.png?0.07040148712722827" alt="Pok&eacute;mon Showdown! (beta)" /> Home</a></li>
				<li><a class="button<?php if ($panels->tab === 'pokedex') echo ' cur'; ?>" href="//dex.pokemonshowdown.com/?0.14822376745897037">Pok&eacute;dex</a></li>
				<li><a class="button<?php if ($panels->tab === 'replay') echo ' cur'; ?>" href="/?0.32002215371319576">Replays</a></li>
				<li><a class="button<?php if ($panels->tab === 'ladder') echo ' cur'; ?>" href="//dragonheavenserver.herokuapp.com/ladder/?0.25789489880908834">Ladder</a></li>
				<li><a class="button nav-last" href="//dragonheavenserver.herokuapp.com/forums/?0.9015346714939956">Forum</a></li>
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
	<script src="//dragonheaven.herokuapp.com/js/lib/jquery-1.11.0.min.js?0.7991571912323041"></script>
	<script src="//dragonheaven.herokuapp.com/js/lib/lodash.core.js?0.16429829612030833"></script>
	<script src="//dragonheaven.herokuapp.com/js/lib/backbone.js?0.006935704201384452"></script>
	<script src="//dex.pokemonshowdown.com/js/panels.js?0.804827081855918"></script>
<?php
}

function ThemeFooterTemplate() {
	global $panels;
?>
<?php $panels->scripts(); ?>

	<script src="//dragonheaven.herokuapp.com/js/lib/jquery-cookie.js?0.5474286395683756"></script>
	<script src="//dragonheaven.herokuapp.com/js/lib/html-sanitizer-minified.js?0.5715344923034553"></script>
	<script src="//dragonheaven.herokuapp.com/js/battle-sound.js?0.17729213232694252"></script>
	<script src="//dragonheaven.herokuapp.com/config/config.js?0.5835640627614822"></script>
	<script src="//dragonheaven.herokuapp.com/js/battledata.js?0.31662057760905604"></script>
	<script src="//dragonheaven.herokuapp.com/data/pokedex-mini.js?0.7185502590599899"></script>
	<script src="//dragonheaven.herokuapp.com/data/pokedex-mini-bw.js?0.7907566039713616"></script>
	<script src="//dragonheaven.herokuapp.com/data/graphics.js?0.7895738742014597"></script>
	<script src="//dragonheaven.herokuapp.com/data/pokedex.js?0.3614840939494399"></script>
	<script src="//dragonheaven.herokuapp.com/data/items.js?0.5381658223610419"></script>
	<script src="//dragonheaven.herokuapp.com/data/moves.js?0.8663821260058033"></script>
	<script src="//dragonheaven.herokuapp.com/data/abilities.js?0.43917967582861417"></script>
	<script src="//dragonheaven.herokuapp.com/data/teambuilder-tables.js?0.42642125512892237"></script>
	<script src="//dragonheaven.herokuapp.com/js/battle-tooltips.js?0.19412567324948182"></script>
	<script src="//dragonheaven.herokuapp.com/js/battle.js?0.15406602431034888"></script>
	<script src="/js/replay.js?c81925c8"></script>

</body></html>
<?php
}
