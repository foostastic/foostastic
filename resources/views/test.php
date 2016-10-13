<?php foreach ($userInfo->wallet as $stock) { ?>
	<tr>
		<td><?= ++$i ?></td>
		<td><?= $stock->stockId ?></td>
		<td><?= $stock->purchaseValue ?></td>
		<td><input type="text" size="2"><i class="glyphicon glyphicon-usd"></i></td>
	</tr>
<?php } ?>

<?php foreach ($market as $stock) { ?>
	<tr>
		<td><?= ++$i ?></td>
		<td><?= $stock['name'] ?></td>
		<td><?= $stock['valuation'] ?></td>
		<td><input type="text" size="2"><i class="glyphicon glyphicon-usd"></i></td>
	</tr>
<?php } ?>
