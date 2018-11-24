 <?php foreach ($Issued as $key => $value): ?>
	    <tr data-id=''>
	      <td><?= ++$key?></td>
	      <td><?= $value->name.'-'.$value->Donation_Type?></td>
	      <td><?= $value->Approve_Quantity?></td>
	    </tr>
   <?php endforeach?>