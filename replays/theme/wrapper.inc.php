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
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/font-awesome.css?0.9408255342144847" />
	<link rel="stylesheet" href="//dragonheavenserver.herokuapp.com/theme/panels.css?0.993830701811675" />
	<link rel="stylesheet" href="//dragonheavenserver.herokuapp.com/theme/main.css?0.13170792861974912" />
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/battle.css?0.5153054428361468" />
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/replay.css?0.14238065769277508" />
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/utilichart.css?0.5625294705855317" />

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
				<li><a class="button nav-first<?php if ($panels->tab === 'home') echo ' cur'; ?>" href="//dragonheavenserver.herokuapp.com/?0.3397012811693878"><img src="//dragonheavenserver.herokuapp.com/images/pokemonshowdownbeta.png?0.9090603805653543" alt="Pok&eacute;mon Showdown! (beta)" /> Home</a></li>
				<li><a class="button<?php if ($panels->tab === 'pokedex') echo ' cur'; ?>" href="//dex.pokemonshowdown.com/?0.5731795043488777">Pok&eacute;dex</a></li>
				<li><a class="button<?php if ($panels->tab === 'replay') echo ' cur'; ?>" href="/?0.5369752587080452">Replays</a></li>
				<li><a class="button<?php if ($panels->tab === 'ladder') echo ' cur'; ?>" href="//dragonheavenserver.herokuapp.com/ladder/?0.07715762036291385">Ladder</a></li>
				<li><a class="button nav-last" href="//dragonheavenserver.herokuapp.com/forums/?0.030046416924943475">Forum</a></li>
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
	<script src="//dragonheaven.herokuapp.com/js/lib/jquery-1.11.0.min.js?0.9607479430307877"></script>
	<script src="//dragonheaven.herokuapp.com/js/lib/lodash.core.js?0.23798177329929948"></script>
	<script src="//dragonheaven.herokuapp.com/js/lib/backbone.js?0.8648663854551937"></script>
	<script src="//dex.pokemonshowdown.com/js/panels.js?0.7727775743097498"></script>
<?php
}

function ThemeFooterTemplate() {
	global $panels;
?>
<?php $panels->scripts(); ?>

	<script src="//dragonheaven.herokuapp.com/js/lib/jquery-cookie.js?0.9416038886408689"></script>
	<script src="//dragonheaven.herokuapp.com/js/lib/html-sanitizer-minified.js?0.9563611448033689"></script>
	<script src="//dragonheaven.herokuapp.com/js/battle-sound.js?0.5662750700421644"></script>
	<script src="//dragonheaven.herokuapp.com/config/config.js?0.3273651860317055"></script>
	<script src="//dragonheaven.herokuapp.com/js/battledata.js?0.9489202731442863"></script>
	<script src="//dragonheaven.herokuapp.com/data/pokedex-mini.js?0.8788874456018911"></script>
	<script src="//dragonheaven.herokuapp.com/data/pokedex-mini-bw.js?0.8545076590157681"></script>
	<script src="//dragonheaven.herokuapp.com/data/graphics.js?0.27982491143712274"></script>
	<script src="//dragonheaven.herokuapp.com/data/pokedex.js?0.7660947262722819"></script>
	<script src="//dragonheaven.herokuapp.com/data/items.js?0.8677061040033258"></script>
	<script src="//dragonheaven.herokuapp.com/data/moves.js?0.2853888660611652"></script>
	<script src="//dragonheaven.herokuapp.com/data/abilities.js?0.9682102654808868"></script>
	<script src="//dragonheaven.herokuapp.com/data/teambuilder-tables.js?0.21287864850630545"></script>
	<script src="//dragonheaven.herokuapp.com/js/battle-tooltips.js?0.06392369512237406"></script>
	<script src="//dragonheaven.herokuapp.com/js/battle.js?0.6007445752185052"></script>
	<script src="/js/replay.js?c81925c8"></script>

</body></html>
<?php
}
