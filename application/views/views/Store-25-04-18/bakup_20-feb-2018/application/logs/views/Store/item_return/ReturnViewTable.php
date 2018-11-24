 <?php foreach ($revived as $key => $value): ?>
	    <tr data-id='<?php echo $value->r_ID?>'>
	      <td><?php echo ++$key?></td>
	      <td><?php echo $value->name.'-'.$value->Donation_Type?></td>
	      <td><?php echo $value->return_quantity?></td>
	      <td><?php echo $value->Item_remarks?></td>
	      
	    </tr>
   <?php endforeach?>