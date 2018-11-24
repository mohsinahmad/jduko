<?php if(isset($type)):
    foreach($transactions as $transaction):
        foreach($transaction as $transaction_2):
            $book = $this->BookModel->getBookName($transaction_2->BookID)?>
            <tr class="odd gradeX">
                <td><?= $transaction_2->VoucherNo?>-<?= $transaction_2->VoucherType?></td>
                <td><?= $transaction_2->DepartmentName?></td>
                <td><?= $transaction_2->PaidTo?></td>
                <td><?= $transaction_2->VoucherDateG?></td>
                <td><?= $transaction_2->VoucherDateH?></td>
                <td><textarea class="form-control" style="height: 35px; width: 120px;" rows="1" readonly><?= $transaction->Remarks?></textarea></td>
                <td><?= number_format($transaction_2->debit)?></td>
            </tr>
        <?php endforeach ;
    endforeach;
else:
    foreach($transactions as $transaction): ?>
        <tr class="odd gradeX">
            <td><?= $transaction->VoucherNo?>-<?= $transaction->VoucherType?></td>
            <td><?= $transaction->DepartmentName?></td>
            <td><?= $transaction->PaidTo?></td>
            <td><?= $transaction->VoucherDateG?></td>
            <td><?= $transaction->VoucherDateH?></td>
            <td><textarea class="form-control" style="height: 35px; width: 120px;" rows="1" readonly><?= $transaction->Remarks?></textarea></td>
            <td><?= number_format($transaction->debit)?></td>
        </tr>
    <?php endforeach;
endif?>