 <?php foreach ($revived as $key => $value): ?>
	    <tr data-id='<?php echo $value->r_ID?>'>
	      <td><?php echo ++$key?></td>
	      <td><?php echo $value->name?></td>
	      <td><?php echo $value->return_quantity?></td>
            <td><select id="donation<?php echo $key?>" name="donation[]" class="donation"></select></td>
            <td><select style="display: none" id="detail<?php echo $key?>" name="detail[]" class="detail"></select></td>
	      <td><?php echo $value->Item_remarks?></td>
	      <td style="display: none;"><?php echo $value->item_id?></td>
<!--	      <td class="quantity" style="display: none;"><input type="hidden"  name="quantity[]" value="--><?php //echo $value->return_quantity?><!--"></td>-->
	    </tr>
   <?php endforeach?>