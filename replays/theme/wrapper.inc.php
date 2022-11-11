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
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/font-awesome.css?0.07488150738049582" />
	<link rel="stylesheet" href="//dragonheavenserver.herokuapp.com/theme/panels.css?0.6860381722228621" />
	<link rel="stylesheet" href="//dragonheavenserver.herokuapp.com/theme/main.css?0.5854866697508754" />
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/battle.css?0.4892967077918744" />
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/replay.css?0.49869687437982946" />
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/utilichart.css?0.9577708206350275" />

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
				<li><a class="button nav-first<?php if ($panels->tab === 'home') echo ' cur'; ?>" href="//dragonheavenserver.herokuapp.com/?0.3430213766597776"><img src="//dragonheavenserver.herokuapp.com/images/pokemonshowdownbeta.png?0.301729552386383" alt="Pok&eacute;mon Showdown! (beta)" /> Home</a></li>
				<li><a class="button<?php if ($panels->tab === 'pokedex') echo ' cur'; ?>" href="//dex.pokemonshowdown.com/?0.594436348770472">Pok&eacute;dex</a></li>
				<li><a class="button<?php if ($panels->tab === 'replay') echo ' cur'; ?>" href="/?0.5280594016591267">Replays</a></li>
				<li><a class="button<?php if ($panels->tab === 'ladder') echo ' cur'; ?>" href="//dragonheavenserver.herokuapp.com/ladder/?0.22214796015147642">Ladder</a></li>
				<li><a class="button nav-last" href="//dragonheavenserver.herokuapp.com/forums/?0.7671189883583474">Forum</a></li>
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
	<script src="//dragonheaven.herokuapp.com/js/lib/jquery-1.11.0.min.js?0.034621114092795"></script>
	<script src="//dragonheaven.herokuapp.com/js/lib/lodash.core.js?0.7103579609673125"></script>
	<script src="//dragonheaven.herokuapp.com/js/lib/backbone.js?0.9180483026493753"></script>
	<script src="//dex.pokemonshowdown.com/js/panels.js?0.5853786221782868"></script>
<?php
}

function ThemeFooterTemplate() {
	global $panels;
?>
<?php $panels->scripts(); ?>

	<script src="//dragonheaven.herokuapp.com/js/lib/jquery-cookie.js?0.4650288866004386"></script>
	<script src="//dragonheaven.herokuapp.com/js/lib/html-sanitizer-minified.js?0.7188455289311708"></script>
	<script src="//dragonheaven.herokuapp.com/js/battle-sound.js?0.8144678024308489"></script>
	<script src="//dragonheaven.herokuapp.com/config/config.js?0.4583567519737155"></script>
	<script src="//dragonheaven.herokuapp.com/js/battledata.js?0.6191970386240122"></script>
	<script src="//dragonheaven.herokuapp.com/data/pokedex-mini.js?0.16132888318998084"></script>
	<script src="//dragonheaven.herokuapp.com/data/pokedex-mini-bw.js?0.66555620622026"></script>
	<script src="//dragonheaven.herokuapp.com/data/graphics.js?0.5966122278894672"></script>
	<script src="//dragonheaven.herokuapp.com/data/pokedex.js?0.21655214098722753"></script>
	<script src="//dragonheaven.herokuapp.com/data/items.js?0.933300644728656"></script>
	<script src="//dragonheaven.herokuapp.com/data/moves.js?0.1401742670589492"></script>
	<script src="//dragonheaven.herokuapp.com/data/abilities.js?0.14534686889176496"></script>
	<script src="//dragonheaven.herokuapp.com/data/teambuilder-tables.js?0.5038560061578912"></script>
	<script src="//dragonheaven.herokuapp.com/js/battle-tooltips.js?0.8221797308429681"></script>
	<script src="//dragonheaven.herokuapp.com/js/battle.js?0.19234602327074635"></script>
	<script src="/js/replay.js?c81925c8"></script>

</body></html>
<?php
}
