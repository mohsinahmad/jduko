<?php foreach ($SlipNo as $key => $value) {
    if ($value->Paid == 1){?>
        <tr class="">
    <?php }else{?>
        <tr class="" style="background: #efe6e6">
    <?php }?>
    <td><?= $value->Code?></td>
    <td><?= $value->Slip_Number?></td>
    <td><?= $value->Name?></td>
    <td><?= $value->Slip_Date_G?></td>
    <td><?php if($value->Collection_Day == 1) { echo '۱۰ ذی الحج'; } elseif($value->Collection_Day == 2) { echo '۱۱ ذی الحج'; } else if( $value->Collection_Day == 3 ){ echo '۱۲ ذی الحج';} ?></td>
    <td><?= $value->Time?></td>
    <td><?= $value->total_quantity?></td>
    <?php if($value->Paid == 1){?>
        <td><?= number_format($value->Total_Amount)?></td>
    <?php }else{ ?>
        <td></td>
    <?php }?>
    <td>
        <a href="<?= site_url('Qurbani/Receipt/EditReceipt').'/'.$value->S_ID.'/'.$value->Collection_Day?>"> <button type="button"  data-id="<?= $value->S_ID; ?>" class="btn btn-success" style="background-color: #517751;border-color: #517751;"><i class="fa fa-pencil-square-o"></i>
            </button></a>
    </td>
    <td>
        <a target="_blank" href="<?= site_url('Qurbani/Receipt/ViewReceipt').'/'.$value->S_ID?>"> <button type="button"  data-id="<?$value->S_ID?>" class="btn btn-success " style="background-color: #517751;border-color: #517751;"><i class="fa fa-print"></i></button></a>
    </td>
    </tr>
<?php }?>