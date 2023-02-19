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
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/font-awesome.css?0.7011322529164352" />
	<link rel="stylesheet" href="//dragonheavenserver.herokuapp.com/theme/panels.css?0.7661760436650595" />
	<link rel="stylesheet" href="//dragonheavenserver.herokuapp.com/theme/main.css?0.8386866608296613" />
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/battle.css?0.6434929027087553" />
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/replay.css?0.5316539975276053" />
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/utilichart.css?0.24536491299227792" />

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
				<li><a class="button nav-first<?php if ($panels->tab === 'home') echo ' cur'; ?>" href="//dragonheavenserver.herokuapp.com/?0.4777574299447531"><img src="//dragonheavenserver.herokuapp.com/images/pokemonshowdownbeta.png?0.21728628279790763" alt="Pok&eacute;mon Showdown! (beta)" /> Home</a></li>
				<li><a class="button<?php if ($panels->tab === 'pokedex') echo ' cur'; ?>" href="//dex.pokemonshowdown.com/?0.007061636134843452">Pok&eacute;dex</a></li>
				<li><a class="button<?php if ($panels->tab === 'replay') echo ' cur'; ?>" href="/?0.08357586108463289">Replays</a></li>
				<li><a class="button<?php if ($panels->tab === 'ladder') echo ' cur'; ?>" href="//dragonheavenserver.herokuapp.com/ladder/?0.12714710279997288">Ladder</a></li>
				<li><a class="button nav-last" href="//dragonheavenserver.herokuapp.com/forums/?0.5969544263709248">Forum</a></li>
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
	<script src="//dragonheaven.herokuapp.com/js/lib/jquery-1.11.0.min.js?0.9079245702365994"></script>
	<script src="//dragonheaven.herokuapp.com/js/lib/lodash.core.js?0.6468175450178406"></script>
	<script src="//dragonheaven.herokuapp.com/js/lib/backbone.js?0.7562031207758846"></script>
	<script src="//dex.pokemonshowdown.com/js/panels.js?0.27590991679136856"></script>
<?php
}

function ThemeFooterTemplate() {
	global $panels;
?>
<?php $panels->scripts(); ?>

	<script src="//dragonheaven.herokuapp.com/js/lib/jquery-cookie.js?0.6353241380135628"></script>
	<script src="//dragonheaven.herokuapp.com/js/lib/html-sanitizer-minified.js?0.7840540617201304"></script>
	<script src="//dragonheaven.herokuapp.com/js/battle-sound.js?0.9411036497899665"></script>
	<script src="//dragonheaven.herokuapp.com/config/config.js?0.6800850137405967"></script>
	<script src="//dragonheaven.herokuapp.com/js/battledata.js?0.1946738283735212"></script>
	<script src="//dragonheaven.herokuapp.com/data/pokedex-mini.js?0.17968698693506346"></script>
	<script src="//dragonheaven.herokuapp.com/data/pokedex-mini-bw.js?0.4836572892839641"></script>
	<script src="//dragonheaven.herokuapp.com/data/graphics.js?0.24405138582560282"></script>
	<script src="//dragonheaven.herokuapp.com/data/pokedex.js?0.4434528593789271"></script>
	<script src="//dragonheaven.herokuapp.com/data/items.js?0.8582752441503518"></script>
	<script src="//dragonheaven.herokuapp.com/data/moves.js?0.9913425855215277"></script>
	<script src="//dragonheaven.herokuapp.com/data/abilities.js?0.7569599038529395"></script>
	<script src="//dragonheaven.herokuapp.com/data/teambuilder-tables.js?0.7325295840995405"></script>
	<script src="//dragonheaven.herokuapp.com/js/battle-tooltips.js?0.7786875651640677"></script>
	<script src="//dragonheaven.herokuapp.com/js/battle.js?0.3922764177898024"></script>
	<script src="/js/replay.js?c81925c8"></script>

</body></html>
<?php
}
