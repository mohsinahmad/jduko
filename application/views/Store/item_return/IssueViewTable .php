<input type="hidden" id="mainid" value ="<?php echo $thisId;?>">
<?php
$count = 0;
//echo '<pre>';
//print_r($Issued);

//print_r($ApprovedQuantity);

foreach ($Issued as $key => $value){ $count = $key + 1;?>
    <tr data-id=''>
        <td><?= $count?></td>
        <td><?= $value->name.'-'.$value->Donation_Type?></td>
        <td><?= $ApprovedQuantity[$key]->Approve_Quantity?></td>
    </tr>
<?php }

?>