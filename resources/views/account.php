<?php
/** @var $userShares \App\ViewModels\UserShare[] **/
/** @var $players \App\Models\Player[] **/
/** @var $shareValueCalculator \App\Calculators\ShareValue **/
use App\Backends\Share;

?>
<!-- Account -->
<div class="container">
    <div class="row">
        <div class="col-md-7">
            <h2>Your players</h2>
            <?php if (count($userShares) > 0) { ?>
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
                    <?php foreach ($userShares as $userShare) {
                            ?>
                            <tr>
                                <td>
                                    <?= $userShare->playerName ?>
                                    <?php if ($userShare->amount > 1) { ?>
                                        ( x<?= $userShare->amount ?> )
                                    <?php } ?>
                                </td>
                                <td align="right"><?= $userShare->buyPrice ?> </td>
                                <td align="right"><?= $userShare->currentPrice ?></td>
                                <td align="center">
                                    <span class="label <?= $userShare->percentage >= 0 ? 'label-success' : 'label-danger' ?>">
                                        <i class="glyphicon glyphicon-chevron-<?= $userShare->percentage >= 0 ? 'up' : 'down' ?>"></i> <?= number_format(abs($userShare->percentage), 2) ?>%
                                    </span>
                                </td>
                                <td align="right">
                                    <form class="form-inline" action="/sell" method="post">
                                        <div class="form-group form-group-sm">
                                            <div class="input-group">
                                                <input type="hidden" name="shareId" value="<?= $userShare->shareId ?>">
                                                <select name="amount" class="form-control">
                                                    <?php for ($i = 1; $i <= $userShare->amount; $i++) { ?>
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
                            <?php
                        }
                        ?>
                </table>
            <?php } else { ?>
                No players yet.
            <?php } ?>
        </div>
        <div class="col-md-5">
            <h2>Market</h2>
            <?php if (count($players) > 0) { ?>
                <?php $currentDivision = 0 ?>
                <table class="table table-condensed">
                    <?php foreach ($players as /** @var \App\Models\Player $player */ $player) {
                        $shareBackend = new Share();
                        $availableStocks = $shareBackend->getPlayerAmountStock($player);
                        $drawDivisionHeader = $currentDivision != $player->getDivision();
                        $currentDivision = $player->getDivision();
                        ?>
                        <?php if ($drawDivisionHeader) { ?>
                            <tr>
                                <td colspan="3">
                                    <p class="h3">
                                        Division <?= $player->getDivision() ?>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <th class="col-md-2">Player</th>
                                <th class="col-md-1 text-right">Value</th>
                                <th class="col-md-3">&nbsp;</th>
                            </tr>
                        <?php } ?>
                            <tr>
                                <td>
                                    <?= $player->getName() ?>
                                    <?php if ($availableStocks == 1) { ?>
                                        <span class="label label-warning">Only 1 left</span>
                                    <?php }  ?>
                                    <?php if ($availableStocks == 0) { ?>
                                        <span class="label label-danger">Sold out</span>
                                    <?php } ?>
                                </td>
                                <td align="right"><?= $shareValueCalculator->getValueForPlayer($player) ?></td>
                                <td align="right">
                                    <?php if ($availableStocks == 0) { ?>
                                        <button class="btn btn-secondary btn-sm disabled" disabled>Sold out</button >
                                    <?php } else { ?>
                                    <form class="form-inline" action="/buy" method="post">
                                        <div class="form-group">
                                            <div class="input-group form-group-sm">
                                                <input type="hidden" name="stockId" value="<?= $player->getName() ?>">
                                                <select name="amount" class="form-control">
                                                    <?php for ($i = 1; $i <= $availableStocks; $i++) { ?>
                                                        <option><?= $i ?></option>
                                                    <?php } ?>
                                                </select >
                                                <span class="input-group-btn" >
                                                    <button class="btn btn-secondary btn-success btn-sm" type="submit" > Buy</button >
                                                </span >
                                            </div>
                                        </div>
                                    </form>
                                    <?php } ?>
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