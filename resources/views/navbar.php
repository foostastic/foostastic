<?php
/** @var $userInfo \App\tmp\UserInfo **/
/** @var $page String **/
?>
<!-- Static navbar -->
<nav class="navbar navbar-default">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="/">Foostastic</a>
		</div>
		<div id="navbar" class="navbar-collapse collapse">
			<?php if ($userInfo->isLogged === true) { ?>
			<ul class="nav navbar-nav">
				<li <?= $page == '/account' ? 'class="active"' : '' ?>><a href="/">Dashboard</a></li>
			</ul>
			<?php } ?>
			<ul class="nav navbar-nav">
				<li <?= $page == '/ranking' ? 'class="active"' : '' ?>><a href="/ranking">Ranking</a></li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<?php if ($userInfo->isLogged === true) { ?>
					<li><a href="#">Credit: <?= $userInfo->capital ?></a></li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
							<i class="glyphicon glyphicon-user"></i> <?= $userInfo->name ?> <span class="caret"></span></span></a>
							<ul class="dropdown-menu">
								<li><a href="/logout">Logout</a></li>
							</ul>
					</li>
				<?php } else { ?>
					<li <?= $page == '/login' ? 'class="active"' : '' ?>><a href="/login">Login</span></a></li>
				<?php } ?>
			</ul>
		</div><!--/.nav-collapse -->
	</div><!--/.container-fluid -->
</nav>