<?php if (isset($_SESSION['comp_id'])){
    $Access_level = $_SESSION['comp_id'];
}elseif (isset($_SESSION['comp'])){
    $Access_level = $_SESSION['comp'];
}else{ $Access_level = ''; }?>
<form role ="from" action="<?php echo site_url('Accounts/CheckBalance/Update_Balance');?>" method="POST" id="UserInput">
    <br><h1 style="text-align: center;">بیلنس چیک</h1><br><br>
    <div style="border:1px solid #eee;
            box-shadow:0 0 10px rgba(0, 0, 0, .15);
            max-width:700px;
            margin:auto;
            padding:20px;">

        <div style="line-height:10%;" class="row">
            <div class="col-md-6">
                <input type="button" name="" id="go" class="" value="Go" style="line-height: 160%">
            </div>
        </div>
        <br>
        <div class="row" id="check">
        	<div class="row">
				<div class="col-md-6">
					<!-- <h5 style="text-decoration: underline; margin-right: 13%">ابتدائ بیلنس</h5> -->
				</div>
				<div class="col-md-6">
					<h5 style="float:left;direction: ltr;margin-left: 8%"><span><?php echo $level[0]->LevelName?></span></h5>
				</div>
			</div>
			<div class="table-responsive" align="middle" style="width: 108%;text-align: center;">
				<table class="table-bordered" style="width: 85%;margin-right: 5%;">
					<thead>
					<tr style="line-height: 243%;">
						<th style="text-align: center; width: 10%">ID</th>
						<th style="text-align: center; width: 17%">Debit</th>
						<th style="text-align: center; width: 17%">Credit</th>
						<th style="text-align: center; width: 20%">OpeningBalance</th>
						<th style="text-align: center; width: 20%">CurrentBalance</th>
						<th style="text-align: center; width: 25%">Cal_Closing</th>
						<th style="text-align: center; ">defference</th>
					</tr>
					</thead>
					<tbody>
					<?php 
					$total = 0;
					foreach($debitandcredit as $key => $value):?>
					<tr>
					<?php $cal_Closing= $OpenAndCurrent[$key]->OpeningBalance +$value[0]->Debit -$value[0]->Credit ?>
					<?php if ($cal_Closing != $OpenAndCurrent[$key]->CurrentBalance){?>
						<td><?php echo $OpenAndCurrent[$key]->ChartOfAccountId?></td>
						<input type="hidden" style="width: 64px;" name="LinkID[]" value="<?php echo $OpenAndCurrent[$key]->ChartOfAccountId?>" readonly>
						<td><?=number_format($value[0]->Debit,2);?></td>
						<td><?=number_format($value[0]->Credit,2)?></td>
						<td><?=number_format($OpenAndCurrent[$key]->OpeningBalance,2)?></td>
						<td><?=number_format($OpenAndCurrent[$key]->CurrentBalance,2)?></td>
						
						<td class="cal_Closing"><?=number_format($cal_Closing,2)?></td>
						<input type="hidden" style="width: 120px;" name="Cal_Closing[]" value="<?=$cal_Closing?>" readonly>

						<?php $deff = $cal_Closing - $OpenAndCurrent[$key]->CurrentBalance?>
						<td><?=number_format($deff,2)?></td>
						<?php $total += $deff;?>
						<?php }?>
					</tr>
				<?php endforeach?>
					</tbody>
					<tfoot>
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td><?php echo $total;?></td>
						</tr>
					</tfoot>
				</table>
			</div>
   	 			<input type="submit" name="" id="update" class="" value="Update" style="line-height: 160%">
        </div>
    </div>
</form>
<style type="text/css">
    .button {
        padding: 5px 8px;
        text-align: center;
        font-size: 13px;
    }
</style>
<script src="<?php echo base_url()."assets/"; ?>js/jquery-1.12.4.js"></script>
<script> 	
 	$(document).ready(function(){
 		$('#check').hide();
 	});

 	$('#go').click(function(){
 		$('#check').show();
 	});

 	// $('#update').click(function(){
 	// 	// $('.cal_Closing').html();
 	// 	alert($('.cal_Closing').html());
 	// });
</script>