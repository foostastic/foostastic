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
		<?php $i=0; foreach ($users as $user) {
			$changeText = "";
			if ($user->lastChange !== null && $user->lastChange->getLogTime()->timestamp > time() - 86400) {
				$lastPoints = $user->lastChange->getPoints();
				$change = $user->totalPoints - $lastPoints;
				$changeText = $change > 0 ? " <span style='color: green;'><i class='glyphicon glyphicon-arrow-up'></i> $change</span>"
					: " <span style='color: red;'><i class='glyphicon glyphicon-arrow-down'></i> $change</span>";
			}
			?>
			<tr <?= $userInfo->isLogged === true && $userInfo->id === $user->id ? 'class="bold"' : ''?>>
				<td class="col-md-1"><?= ++$i ?></td>
				<td class="col-md-9"><?= str_replace('@tuenti.com', '', $user->name) ?></td>
				<td class="col-md-2"><?= $user->totalPoints ?><?= $changeText ?></td>
			</tr>
		<?php } ?>
	</table>
</div>