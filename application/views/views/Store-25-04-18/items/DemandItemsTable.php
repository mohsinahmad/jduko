<?php $count = 1;
foreach($demandItems as $key => $demandItem): ?>
    <tr class="toEdit">
        <td style="text-align: center"><?= $demandItem->code?></td>
        <td class="ItemName<?= $count?>" style="text-align: center"><?= $demandItem->name?></td>
        <?php if(isset($approvequantity[$key]->Approve_Quantity)){?>
        <td class="ApproveQuantity<?= $count?>" style="text-align: center"><?= $approvequantity[$key]->Approve_Quantity?></td>
        <?php }else{?>
        <td class="ApproveQuantity<?= $count?>" style="text-align: center">0.00</td>
        <?php }?>
        <input type="hidden" name="demand_id" class="demand_id" value="<?= $demandItem->d_id?>">
        <input type="hidden" name="Item_Id[]" class="Item_Id<?= $count?>" value="<?= $demandItem->Item_Id?>">
        <input type="hidden" name="approved_ID" class="approved_ID" value="<?= $approvequantity[$key]->Approve_id?>">
    </tr>
    <?php ++$count;
endforeach ?>