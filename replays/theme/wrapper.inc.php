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
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/font-awesome.css?0.4497320009813479" />
	<link rel="stylesheet" href="//dragonheavenserver.herokuapp.com/theme/panels.css?0.2696807925390734" />
	<link rel="stylesheet" href="//dragonheavenserver.herokuapp.com/theme/main.css?0.5485144528848056" />
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/battle.css?0.031306714462392415" />
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/replay.css?0.8768983353309923" />
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/utilichart.css?0.14252040349604167" />

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
				<li><a class="button nav-first<?php if ($panels->tab === 'home') echo ' cur'; ?>" href="//dragonheavenserver.herokuapp.com/?0.3534448884809107"><img src="//dragonheavenserver.herokuapp.com/images/pokemonshowdownbeta.png?0.08562748689470379" alt="Pok&eacute;mon Showdown! (beta)" /> Home</a></li>
				<li><a class="button<?php if ($panels->tab === 'pokedex') echo ' cur'; ?>" href="//dex.pokemonshowdown.com/?0.9684097433808412">Pok&eacute;dex</a></li>
				<li><a class="button<?php if ($panels->tab === 'replay') echo ' cur'; ?>" href="/?0.986166014552599">Replays</a></li>
				<li><a class="button<?php if ($panels->tab === 'ladder') echo ' cur'; ?>" href="//dragonheavenserver.herokuapp.com/ladder/?0.013175849227370806">Ladder</a></li>
				<li><a class="button nav-last" href="//dragonheavenserver.herokuapp.com/forums/?0.8371279432401899">Forum</a></li>
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
	<script src="//dragonheaven.herokuapp.com/js/lib/jquery-1.11.0.min.js?0.5265905055561173"></script>
	<script src="//dragonheaven.herokuapp.com/js/lib/lodash.core.js?0.47274257685994847"></script>
	<script src="//dragonheaven.herokuapp.com/js/lib/backbone.js?0.35823197872083945"></script>
	<script src="//dex.pokemonshowdown.com/js/panels.js?0.5824030300881533"></script>
<?php
}

function ThemeFooterTemplate() {
	global $panels;
?>
<?php $panels->scripts(); ?>

	<script src="//dragonheaven.herokuapp.com/js/lib/jquery-cookie.js?0.5117589597058425"></script>
	<script src="//dragonheaven.herokuapp.com/js/lib/html-sanitizer-minified.js?0.12276328050869822"></script>
	<script src="//dragonheaven.herokuapp.com/js/battle-sound.js?0.8385784974135657"></script>
	<script src="//dragonheaven.herokuapp.com/config/config.js?0.514829670100843"></script>
	<script src="//dragonheaven.herokuapp.com/js/battledata.js?0.1317052193074948"></script>
	<script src="//dragonheaven.herokuapp.com/data/pokedex-mini.js?0.7552973493729207"></script>
	<script src="//dragonheaven.herokuapp.com/data/pokedex-mini-bw.js?0.7167481591768219"></script>
	<script src="//dragonheaven.herokuapp.com/data/graphics.js?0.47299361936486606"></script>
	<script src="//dragonheaven.herokuapp.com/data/pokedex.js?0.30803543361591745"></script>
	<script src="//dragonheaven.herokuapp.com/data/items.js?0.4840149041938875"></script>
	<script src="//dragonheaven.herokuapp.com/data/moves.js?0.42197100501902907"></script>
	<script src="//dragonheaven.herokuapp.com/data/abilities.js?0.4637151217760831"></script>
	<script src="//dragonheaven.herokuapp.com/data/teambuilder-tables.js?0.9470932375497574"></script>
	<script src="//dragonheaven.herokuapp.com/js/battle-tooltips.js?0.20944496307645122"></script>
	<script src="//dragonheaven.herokuapp.com/js/battle.js?0.5821267752557866"></script>
	<script src="/js/replay.js?c81925c8"></script>

</body></html>
<?php
}