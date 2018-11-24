<form role ="from" action="<?= site_url('Qurbani/ChrumQuantity/ViewComparativeSale_Report')?>" method="POST" target="_blank" id="UserInput">
    <br><h1 style="text-align: center;">تقا بلی جائزے فروخت کی رپورٹ</h1><br><br>
    <div style="border:1px solid #eee;box-shadow:0 0 10px rgba(0, 0, 0, .15);max-width:950px;margin:auto;padding:20px;">
        <h3>اندراج کوائف برائے ۱۴۳۷ھ:</h3>
        <?php foreach ($OLD_Data as $key => $item) { $variable = $item;?>
            <div class="row">
                <div class="form-group test">
                    <div class="col-xs-2">
                        <div class="form-group">
                            <?php if ($key == 0){?>
                                <label class="control-label" for="inputSuccess">چرم کی قسم</label>
                            <?php }?>
                            <input class="form-control" id="" name="" style="width: 150px;" value="<?= $variable->chrum_type?>" type="text" required readonly>
                            <input type="hidden" name="Chrum_type_Id[]" value="<?= $variable->chrum_type_id?>">
                            <input type="hidden" name="id[]" value="<?= $variable->Id?>">
                            <?php if (isset($variable->Id)){?>
                                <input type="hidden" name="edit[]" value="<?= $variable->Id?>">
                            <?php }?>
                        </div>
                    </div>
                    <div class="col-xs-2">
                        <div class="form-group">
                            <?php if ($key == 0){?>
                                <label class="control-label" for="inputSuccess">تعداد چرم تازہ</label>
                            <?php }?>
                            <input class="form-control fresh_quantity" name="Fresh_Quantity[]" style="width: 150px;" value="<?= isset($variable)?$variable->Fresh_Quantity:''?>" type="text">
                        </div>
                    </div>
                    <div class="col-xs-2">
                        <div class="form-group">
                            <?php if ($key == 0){?>
                                <label class="control-label" for="inputSuccess">تعداد چرم باسی</label>
                            <?php }?>
                            <input class="form-control old_quantity" name="Old_Quantity[]" style="width: 150px;" value="<?= isset($variable)?$variable->Old_Quantity:''?>" type="text">
                        </div>
                    </div>
                    <div class="col-xs-2">
                        <div class="form-group">
                            <?php if ($key == 0){?>
                                <label class="control-label" for="inputSuccess">قیمت فی کھال تازہ</label>
                            <?php }?>
                            <input class="form-control fresh_price" name="Fresh_Rate[]" style="width: 150px;" value="<?= isset($variable)?$variable->Fresh_Rate:''?>" type="text">
                        </div>
                    </div>
                    <div class="col-xs-2">
                        <div class="form-group">
                            <?php if ($key == 0){?>
                                <label class="control-label" for="inputSuccess">قیمت فی کھال باسی</label>
                            <?php }?>
                            <input class="form-control old_price" name="Old_Rate[]" style="width: 150px;" value="<?= isset($variable)?$variable->Old_Rate:''?>" type="text">
                        </div>
                    </div>
                    <div class="col-xs-2">
                        <div class="form-group">
                            <?php if ($key == 0){?>
                                <label class="control-label" for="inputSuccess">قیمت چرم</label>
                            <?php }?>
                            <input class="form-control total" name="Amount[]" style="width: 140px;" value="<?= isset($variable)?$variable->Amount:''?>" type="text">
                        </div>
                    </div>
                </div>
            </div>
        <?php }?>
        <label class="control-label" for="inputSuccess">تفصیل</label><br>
        <textarea name="details" id="details" rows="3" cols="20" style="width:50%;"></textarea>
        <br>
        <br>
        <input type="submit" name="get" value="محفوظ کریں" style="line-height: 210%;">
        <!-- <input type="button" id="report" name="call" value="رپورٹ حا صل کریں" style="line-height: 210%;"> -->
    </div>
</form>
<script src="<?= base_url()."assets/js/"; ?>jquery-1.12.4.js"></script>
<script type="text/javascript">

    // for (var i = 0; i <= 4; i++) {
    $(document).on('keyup','.old_price',function(){
        var old_price = $(this).val();
        var fresh_price = $(this).parents('div').parents('div.test').find('.fresh_price').val();
        var fresh_quantity = $(this).parents('div').parents('div.test').find('.fresh_quantity').val();
        var old_quantity = $(this).parents('div').parents('div.test').find('.old_quantity').val();
        var total_price = (fresh_price * fresh_quantity) + (old_price * old_quantity);
        $(this).parents('div').parents('div.test').find('.total').val(total_price);
    });
    // }

</script>
