<?php $amount = 0;
if(isset($type)):
    foreach($transactions as $transaction):
        foreach($transaction as $key => $transaction_2): ?>
            <tr class="odd gradeX">
                <td><?= $transaction_2->Permanent_VoucherNumber?>-<?= $transaction_2->VoucherType?></td>
                <td><?= $transaction_2->DepartmentName?></td>
                <td><?= $transaction_2->PaidTo?></td>
                <td><?= $transaction_2->Permanent_VoucherDateG?></td>
                <td><?= $transaction_2->Permanent_VoucherDateH?></td>
                <td><textarea class="form-control" style="height: 35px; width: 120px;" rows="1" readonly><?= $transaction_2->Remarks?></textarea></td>
                <?php (isset($bookAmount[$key][0]->BookAmount) && $bookAmount[$key][0]->BookAmount != 0)?$amount=$bookAmount[$key][0]->BookAmount:$amount=$bookAmount[$key][1]->BookAmount; ?>
                <td><?= number_format($amount)?></td>
                <td>
                    <input type="checkbox" name="toTemp[]" class="checkbox">
                    <input type="hidden" name="VoucherNo" class="vouch_no" value="<?= $transaction_2->VoucherNo?>">
                </td>
                <td>
                    <?php ($this->uri->segment(3) === "permanentVoucher")?$vouch_type = 1: $vouch_type = 0;
                    $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,33);
                    if($_SESSION['user'][0]->IsAdmin == 1){?>
                        <a href="<?= site_url('Accounts/Books/EditTransaction').'/'.$transaction_2->t_id.'/'.$this->uri->segment(5).'/'.$vouch_type ;?>" <button type="button"  data-id="<?= $transaction_2->t_id; ?>" class="btn btn-success " style="background-color: #517751;border-color: #517751;"><i class="fa fa-pencil-square-o"></i>
                        </button></a> <?php }?>
                </td>
                <td><?php if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[5] == '1')){?>
                        <a target="_blank" href="<?= site_url('Accounts/Books/viewVoucher').'/'.$transaction_2->t_id.'/'.$this->uri->segment(5);?>" <button type="button"  data-id="<?= $transaction_2->t_id; ?>" class="btn btn-success " style="background-color: #517751;border-color: #517751;"><i class="fa fa-print"></i>
                        </button></a><?php }?>
                </td>
            </tr>
        <?php endforeach;
    endforeach;
else:
    foreach($transactions as $key => $transaction): ?>
        <tr class="odd gradeX">
            <td><?= $transaction->Permanent_VoucherNumber?>-<?= $transaction->VoucherType?></td>
            <td><?= $transaction->VoucherNo?>-<?= $transaction->VoucherType?></td>
            <td><?= $transaction->DepartmentName?></td>
            <td><?= $transaction->PaidTo?></td>
            <td><?= $transaction->Permanent_VoucherDateG?></td>
            <td><?= $transaction->Permanent_VoucherDateH?></td>
            <td><textarea class="form-control" style="height: 35px; width: 120px;" rows="1" readonly><?= $transaction->Remarks?></textarea></td>
            <?php (isset($bookAmount[$key][0]->BookAmount) && $bookAmount[$key][0]->BookAmount != 0)?$amount=$bookAmount[$key][0]->BookAmount:$amount=$bookAmount[$key][1]->BookAmount; ?>
            <td><?= number_format($amount)?></td>
            <td>
                <input type="checkbox" name="toTemp[]" class="checkbox">
                <input type="hidden" name="VoucherNo" class="vouch_no" value="<?= $transaction->VoucherNo?>">
            </td>
            <td>
                <?php ($this->uri->segment(3) === "permanentVoucher")?$vouch_type = 1: $vouch_type = 0;
                $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,33);
                if($_SESSION['user'][0]->IsAdmin == 1){?>
                    <a href="<?= site_url('Accounts/Books/EditTransaction').'/'.$transaction->t_id.'/'.$this->uri->segment(5).'/'.$vouch_type ;?>" <button type="button"  data-id="<?= $transaction->t_id; ?>" class="btn btn-success " style="background-color: #517751;border-color: #517751;"><i class="fa fa-pencil-square-o"></i>
                    </button></a><?php }?>
            </td>
            <td><?php if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[5] == '1')){?>
                    <a target="_blank" href="<?= site_url('Accounts/Books/viewVoucher').'/'.$transaction->t_id.'/'.$this->uri->segment(5);?>" <button type="button"  data-id="<?= $transaction->t_id; ?>" class="btn btn-success " style="background-color: #517751;border-color: #517751;"><i class="fa fa-print"></i>
                    </button></a><?php }?>
            </td>
        </tr>
    <?php endforeach;
endif?>