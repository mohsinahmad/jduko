<?php foreach($trans[0] as $tran):?>
    <?php if(!$tran == array()):?>

        <tr class="odd gradeX">
            <td><?php echo $tran->AccountName; ?></td>
            <td class="debit"><?php echo $tran->Debit; ?></td>
            <td><?php echo $tran->Credit; ?></td>
            <td><?php echo $tran->Remarks; ?></td>
            <td>
                <input type="checkbox" name="toTemp[]" class="checkbox">
            </td>
        </tr>
    <?php endif?>
<?php endforeach?>