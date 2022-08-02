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
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/font-awesome.css?0.6550995170682683" />
	<link rel="stylesheet" href="//dragonheavenserver.herokuapp.com/theme/panels.css?0.5987124188213166" />
	<link rel="stylesheet" href="//dragonheavenserver.herokuapp.com/theme/main.css?0.11571635465614749" />
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/battle.css?0.08442957013868546" />
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/replay.css?0.37801222912065446" />
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/utilichart.css?0.17400900732108893" />

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
				<li><a class="button nav-first<?php if ($panels->tab === 'home') echo ' cur'; ?>" href="//dragonheavenserver.herokuapp.com/?0.1677979709245001"><img src="//dragonheavenserver.herokuapp.com/images/pokemonshowdownbeta.png?0.532125658483809" alt="Pok&eacute;mon Showdown! (beta)" /> Home</a></li>
				<li><a class="button<?php if ($panels->tab === 'pokedex') echo ' cur'; ?>" href="//dex.pokemonshowdown.com/?0.07285090555461649">Pok&eacute;dex</a></li>
				<li><a class="button<?php if ($panels->tab === 'replay') echo ' cur'; ?>" href="/?0.5604305369326219">Replays</a></li>
				<li><a class="button<?php if ($panels->tab === 'ladder') echo ' cur'; ?>" href="//dragonheavenserver.herokuapp.com/ladder/?0.8419100392368297">Ladder</a></li>
				<li><a class="button nav-last" href="//dragonheavenserver.herokuapp.com/forums/?0.17580395471004873">Forum</a></li>
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
	<script src="//dragonheaven.herokuapp.com/js/lib/jquery-1.11.0.min.js?0.8049781567340755"></script>
	<script src="//dragonheaven.herokuapp.com/js/lib/lodash.core.js?0.07661175008765642"></script>
	<script src="//dragonheaven.herokuapp.com/js/lib/backbone.js?0.8932235507054231"></script>
	<script src="//dex.pokemonshowdown.com/js/panels.js?0.9737824269089554"></script>
<?php
}

function ThemeFooterTemplate() {
	global $panels;
?>
<?php $panels->scripts(); ?>

	<script src="//dragonheaven.herokuapp.com/js/lib/jquery-cookie.js?0.011059417955540551"></script>
	<script src="//dragonheaven.herokuapp.com/js/lib/html-sanitizer-minified.js?0.39177651695728466"></script>
	<script src="//dragonheaven.herokuapp.com/js/battle-sound.js?0.9775274529250979"></script>
	<script src="//dragonheaven.herokuapp.com/config/config.js?0.368761032854497"></script>
	<script src="//dragonheaven.herokuapp.com/js/battledata.js?0.7028175702792698"></script>
	<script src="//dragonheaven.herokuapp.com/data/pokedex-mini.js?0.2743936384542427"></script>
	<script src="//dragonheaven.herokuapp.com/data/pokedex-mini-bw.js?0.6238677997039681"></script>
	<script src="//dragonheaven.herokuapp.com/data/graphics.js?0.24623821978911065"></script>
	<script src="//dragonheaven.herokuapp.com/data/pokedex.js?0.5449931822809553"></script>
	<script src="//dragonheaven.herokuapp.com/data/items.js?0.7405062956319448"></script>
	<script src="//dragonheaven.herokuapp.com/data/moves.js?0.9280330964956449"></script>
	<script src="//dragonheaven.herokuapp.com/data/abilities.js?0.8342757847011666"></script>
	<script src="//dragonheaven.herokuapp.com/data/teambuilder-tables.js?0.9269000092931998"></script>
	<script src="//dragonheaven.herokuapp.com/js/battle-tooltips.js?0.48177650736738853"></script>
	<script src="//dragonheaven.herokuapp.com/js/battle.js?0.7834349118995654"></script>
	<script src="/js/replay.js?c81925c8"></script>

</body></html>
<?php
}
