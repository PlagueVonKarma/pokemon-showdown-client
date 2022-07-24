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
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/font-awesome.css?0.7045220707512387" />
	<link rel="stylesheet" href="//dragonheavenserver.herokuapp.com/theme/panels.css?0.8451049134174615" />
	<link rel="stylesheet" href="//dragonheavenserver.herokuapp.com/theme/main.css?0.5726286166258956" />
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/battle.css?0.43861677987677705" />
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/replay.css?0.04699312841913983" />
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/utilichart.css?0.8580706901342889" />

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
				<li><a class="button nav-first<?php if ($panels->tab === 'home') echo ' cur'; ?>" href="//dragonheavenserver.herokuapp.com/?0.5609300547299636"><img src="//dragonheavenserver.herokuapp.com/images/pokemonshowdownbeta.png?0.30736686038321137" alt="Pok&eacute;mon Showdown! (beta)" /> Home</a></li>
				<li><a class="button<?php if ($panels->tab === 'pokedex') echo ' cur'; ?>" href="//dex.pokemonshowdown.com/?0.9257765029493918">Pok&eacute;dex</a></li>
				<li><a class="button<?php if ($panels->tab === 'replay') echo ' cur'; ?>" href="/?0.4974126652925204">Replays</a></li>
				<li><a class="button<?php if ($panels->tab === 'ladder') echo ' cur'; ?>" href="//dragonheavenserver.herokuapp.com/ladder/?0.39683651469844783">Ladder</a></li>
				<li><a class="button nav-last" href="//dragonheavenserver.herokuapp.com/forums/?0.7771346402355062">Forum</a></li>
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
	<script src="//dragonheaven.herokuapp.com/js/lib/jquery-1.11.0.min.js?0.7481924430112763"></script>
	<script src="//dragonheaven.herokuapp.com/js/lib/lodash.core.js?0.6088362795549984"></script>
	<script src="//dragonheaven.herokuapp.com/js/lib/backbone.js?0.26161270154288174"></script>
	<script src="//dex.pokemonshowdown.com/js/panels.js?0.42765017802533745"></script>
<?php
}

function ThemeFooterTemplate() {
	global $panels;
?>
<?php $panels->scripts(); ?>

	<script src="//dragonheaven.herokuapp.com/js/lib/jquery-cookie.js?0.6565284168198064"></script>
	<script src="//dragonheaven.herokuapp.com/js/lib/html-sanitizer-minified.js?0.6932093445758203"></script>
	<script src="//dragonheaven.herokuapp.com/js/battle-sound.js?0.5722132376841416"></script>
	<script src="//dragonheaven.herokuapp.com/config/config.js?0.8517787423998133"></script>
	<script src="//dragonheaven.herokuapp.com/js/battledata.js?0.11495451122611833"></script>
	<script src="//dragonheaven.herokuapp.com/data/pokedex-mini.js?0.12112579885065133"></script>
	<script src="//dragonheaven.herokuapp.com/data/pokedex-mini-bw.js?0.3729585842620975"></script>
	<script src="//dragonheaven.herokuapp.com/data/graphics.js?0.4774016413410984"></script>
	<script src="//dragonheaven.herokuapp.com/data/pokedex.js?0.8176641408912644"></script>
	<script src="//dragonheaven.herokuapp.com/data/items.js?0.4357344829051013"></script>
	<script src="//dragonheaven.herokuapp.com/data/moves.js?0.17214281242915908"></script>
	<script src="//dragonheaven.herokuapp.com/data/abilities.js?0.7647889258020257"></script>
	<script src="//dragonheaven.herokuapp.com/data/teambuilder-tables.js?0.35794755279991763"></script>
	<script src="//dragonheaven.herokuapp.com/js/battle-tooltips.js?0.5681681675349803"></script>
	<script src="//dragonheaven.herokuapp.com/js/battle.js?0.9414941351072645"></script>
	<script src="/js/replay.js?c81925c8"></script>

</body></html>
<?php
}
