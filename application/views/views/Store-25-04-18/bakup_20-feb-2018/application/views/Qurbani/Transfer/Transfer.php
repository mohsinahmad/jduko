<form action="<?= site_url('Qurbani/Transfer/Save')?>" method="POST">
    <div class="row">
        <?php if($this->session->flashdata('success')) :?>
            <div class="alert alert-success alert-dismissable" id="dalert">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <?= $this->session->flashdata('success');?>
            </div>
        <?php endif ?>
        <?php if($this->session->flashdata('error')) :?>
            <div class="alert alert-danger alert-dismissable" id="dalert">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <?= $this->session->flashdata('error');?>
            </div>
        <?php endif;
        if (isset($edit)){
            $total = $edit[0]->Total_Collection;
            $total_transfer_all = $edit[0]->Total_Transfer;
            $Cash_Balance = $total - $total_transfer_all;
            $thisAmount = $edit[0]->This_Transfer_Amount;
            $remaining = $Cash_Balance - $thisAmount;
        }else{
            $quantity_Ijtemai = $Ijtemai[0]->Total;
            $quantity_Infiradi = $Infiradi[0]->Quantity + $Infiradi[0]->Total;
            $total = $Ijtemai[0]->Total + $Infiradi[0]->Total;
            $Cash_Balance = $total - $total_transfer[0]->Total_transfer;
            $total_transfer_all = $total_transfer[0]->Total_transfer;
            $remaining = 0;
        } ?>
        <div class="heading">
            <br>
            <h1 class="page-header" style="margin-top: 10px;">رقوم کی منتقلی کی تفصیل</h1>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="form-group">
                    <div class="col-xs-4">
                        <div class="form-group">
                            <input class="form-control" id="Date" type="hidden" name="Date" style="width: 250px;" value="<?= date('Y-m-d')?>" autofocus required>
                            <label class="control-label" for="inputSuccess">اب تک کی جمع شدہ رقم </label>
                            <input class="form-control" id="" type="text" name="" style="width: 250px;" value="<?= number_format($total);?>" readonly>
                            <input class="form-control" id="" type="hidden" name="Total_Collection" style="width: 250px;" value="<?= ($total);?>" readonly>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label class="control-label" for="inputSuccess">اب تک کی منتقل شدہ رقم</label>
                            <input class="form-control" id="" type="text" name="" style="width: 250px;" value="<?= number_format($total_transfer_all)?>" readonly>
                            <input class="form-control" id="" type="hidden" name="Total_Transfer" style="width: 250px;" value="<?= ($total_transfer_all)?>" readonly>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label class="control-label" for="inputSuccess">اس وقت کا کیش بیلنس</label>
                            <input class="form-control" type="text" style="width: 250px;" value="<?= number_format($Cash_Balance)?>" readonly>
                            <input class="form-control" id="grandTotal" type="hidden" name="grandTotal" style="width: 250px;" value="<?= ($Cash_Balance)?>" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label class="control-label" for="inputSuccess">اس دفع کی منتقلی</label>
                            <?php if (isset($edit)){?>
                                <input class="form-control" id="" type="text" name="" style="width: 250px;" value="<?= number_format($thisAmount)?>" required>
                                <input class="form-control" id="TransferAmount" type="hidden" name="This_Transfer_Amount" style="width: 250px;" value="<?= number_format($thisAmount)?>" required>
                            <?php }else{?>
                                <input class="form-control" id="TransferAmount" type="number" name="This_Transfer_Amount" style="width: 250px;" value="" required>
                            <?php }?>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label class="control-label" for="inputSuccess">منتقلی کے بعد بقایا </label>
                            <input class="form-control" id="Remaining" type="text" name="Cash_In_Hand_After_This" style="width: 250px;" value="<?= number_format($remaining)?>" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php if (isset($edit)){?>
            <input class="form-control" name="edit" value="<?= $edit[0]->Slip_Number?>" type="hidden">
            <input class="form-control" name="CreatedBy" value="<?= $edit[0]->CreatedBy?>" type="hidden">
            <input class="form-control" name="CreatedOn" value="<?= $edit[0]->CreatedOn?>" type="hidden">
        <?php }?>
        <button type="submit" id="submit" class="btn btn-primary" disabled>منتقل کریں</button>
    </div>
</form>
<script src="<?= base_url()."assets/"?>js/jquery-1.12.4.js"></script>
<script>
    $(document).on('ready',function() {
        $("#TransferAmount").keyup(function(){
            var TransferAmount = $(this).val();
            var TotalAmount = $('#grandTotal').val();
            var remaining = parseInt(TotalAmount) - parseInt(TransferAmount);

            $('#Remaining').val('');
            $('#Remaining').val(remaining);

            if(parseInt(TransferAmount) > parseInt(TotalAmount)){
                alert('منتقل کردہ رقم بیلینس سے ذیادہ ہے۔');
                $('#submit').attr("disabled",true);
            }else if(TransferAmount == 0){
                alert('منتقل کردہ رقم صفر ہے۔');
                $('#submit').attr("disabled",true);
            }else{
                $('#submit').removeAttr("disabled");
            }
        });
    });
</script>