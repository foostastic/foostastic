<?php
/** @var $userInfo \App\tmp\UserInfo */
/** @var $users \App\tmp\UserInfo[] */
?>
<!-- Ranking -->
<div class="container">
	<h1>Ranking</h1>

	<table class="table table-hover table-striped">
		<thead>
			<tr>
				<th>Pos</th>
				<th>Name</th>
				<th>Valuation</th>
			</tr>
		</thead>
		<?php $i=0; foreach ($users as $user) { ?>
			<tr <?= $userInfo->isLogged === true && $userInfo->id === $user->id ? 'class="bold"' : ''?>>
				<td class="col-md-1"><?= ++$i ?></td>
				<td class="col-md-9"><?= str_replace('@tuenti.com', '', $user->name) ?></td>
				<td class="col-md-2"><?= $user->wallet->getTotalValue() + $user->capital ?></td>
			</tr>
		<?php } ?>
	</table>
</div>