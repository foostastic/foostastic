<?php
/** @var $userInfo \App\tmp\UserInfo **/
?>
<!-- Account -->
<div class="container">
    <div class="row">
        <div class="col-md-7">
            <h2>Your players</h2>
            <?php if (count($userInfo->wallet->getAllByStock()) > 0) { ?>
                <table class="table table-condensed">
                    <thead>
                        <tr>
                            <th class="col-md-2">Player</th>
                            <th class="col-md-1 text-right">Spent</th>
                            <th class="col-md-1 text-right">Current</th>
                            <th class="col-md-1">&nbsp;</th>
                            <th class="col-md-2">&nbsp;</th>
                        </tr>
                    </thead>
                    <?php foreach ($userInfo->wallet->getAllByStock() as $stockId => $purchases) {
                        $currentPrice = $shareValueCalculator->getValueForPlayerName($stockId);
                        $stockPrice = $currentPrice;
                        $amount = 0;
                        $buyPrice = 0;
                        foreach ($purchases as $purchase) {
                            $amount += $purchase->purchaseAmount;
                            $buyPrice += $purchase->purchaseAmount * $purchase->purchaseValue;
                        }
                        $difference = $stockPrice * $amount - $buyPrice;
                        $percentage = $difference*100/$buyPrice;
                        ?>
                            <tr>
                                <td><?= $stockId ?></td>
                                <td align="right"><?= $buyPrice ?> </td>
                                <td align="right"><?= $currentPrice ?></td>
                                <td align="center">
                                    <span class="label <?= $percentage >= 0 ? 'label-success' : 'label-danger' ?>">
                                        <i class="glyphicon glyphicon-chevron-<?= $percentage >= 0 ? 'up' : 'down' ?>"></i> <?= number_format(abs($percentage), 2) ?>%
                                    </span>
                                </td>
                                <td align="right">
                                    <form class="form-inline" action="/sell" method="post">
                                        <div class="form-group form-group-sm">
                                            <div class="input-group">
                                                <input type="hidden" name="stockId" value="<?= $stockId?>">
                                                <select name="amount" class="form-control">
                                                    <?php for ($i = 1; $i <= $amount; $i++) { ?>
                                                        <option><?= $i ?></option>
                                                    <?php } ?>
                                                </select>
                                                <span class="input-group-btn">
                                                    <button class="btn btn-secondary btn-danger btn-sm" type="submit">Sell</button>
                                                </span>
                                            </div>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                </table>
            <?php } else { ?>
                No players yet.
            <?php } ?>
        </div>
        <div class="col-md-5">
            <h2>Market</h2>
            <?php if (count($players) > 0) { ?>
                <table class="table table-condensed">
                    <thead>
                    <tr>
                        <th class="col-md-2">Player</th>
                        <th class="col-md-1 text-right">Value</th>
                        <th class="col-md-3">&nbsp;</th>
                    </tr>
                    </thead>
                    <?php foreach ($players as /** @var \App\Models\Player $player */ $player) { ?>
                            <tr>
                                <td><?= $player->getName() ?></td>
                                <td align="right"><?= $shareValueCalculator->getValueForPlayer($player) ?></td>
                                <td align="right">
                                    <form class="form-inline" action="/buy" method="post">
                                        <div class="form-group">
                                            <div class="input-group form-group-sm">
                                                <input type="hidden" name="stockId" value="<?= $player->getName() ?>">
                                                <select name="amount" class="form-control">
                                                    <?php for ($i = 1; $i <= 10; $i++) { ?>
                                                        <option><?= $i ?></option>
                                                    <?php } ?>
                                                </select >
                                                <span class="input-group-btn" >
                                                    <button class="btn btn-secondary btn-success btn-sm" type="submit" > Buy</button >
                                                </span >
                                            </div>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                    <?php } ?>
                </table>
            <?php } else { ?>
                No players available for purchase.
            <?php } ?>
        </div>
    </div>
</div>