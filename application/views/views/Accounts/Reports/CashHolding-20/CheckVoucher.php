<?php if (isset($_SESSION['comp_id'])){
    $Access_level = $_SESSION['comp_id'];
}elseif (isset($_SESSION['comp'])){
    $Access_level = $_SESSION['comp'];
}else{ $Access_level = ''; }
?>
<form role ="from" action="<?php echo site_url('Accounts/CashHolding/ViewCashHoldingReport');?>" method="POST" id="UserInput">
    <br><h1 style="text-align: center;">Checking Voucher</h1><br><br>
    <div style="border:1px solid #eee;
            box-shadow:0 0 10px rgba(0, 0, 0, .15);
            max-width:700px;
            margin:auto;
            padding:20px;">

        <div style="line-height:10%;" class="row">
        </div>
        <br>
            <div class="row" id="check">
            <div class="row">
                <div class="col-md-4">
                    <label class="control-label" for="inputSuccess" >کیش بیلنس</label>
                    <?php if(isset($RemainingCash[0]->remainingCash)){?>
                        <input type="text" class="form-control" name="remainingCash" value="<?php echo $RemainingCash[0]->remainingCash?>">
                    <?php } else{?>
                        <input type="text" required class="form-control" name="remainingCash">
                    <?php }?>
                </div>
                <div class="col-md-4">
                    <label class="control-label" for="inputSuccess" >زیرتحویل کیشیئر رقم</label>
                    <?php if(isset($RemainingCash[0]->holdcash)){?>
                        <input type="text" class="form-control"  name="holdcash" value="<?php echo $RemainingCash[0]->holdcash?>">
                    <?php } else{?>
                        <input type="text" required class="form-control" name="holdcash">
                    <?php }?>
                </div>
                <div class="col-md-4">
                    <label class="control-label" for="inputSuccess" style="font-size: 0.9em">زیرتحویل کیشیئر رقم مورخہ</label>
                    <?php if(isset($RemainingCash[0]->holdcashdate)){?>
                        <input type="date" class="form-control" id="datepicker2" value="<?php echo $RemainingCash[0]->holdcashdate?>" name="holdcashdate">
                    <?php } else{?>
                        <input type="date" class="form-control" id="datepicker2" value="<?php echo date("Y-m-d");?>" name="holdcashdate">
                    <?php }?>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-4">
                    <label class="control-label" for="inputSuccess" style="font-size: 0.9em">زیر تحویل کیشیئر کی رقم مبلغ آٹھ لاکھ روپے مورخ</label>
                    <?php if(isset($RemainingCash[0]->holdcashdate)){?>
                        <input type="date" class="form-control" id="datepicker" value="<?php echo $RemainingCash[0]->cashrecive?>" name="cashrecive">
                    <?php } else{?>
                        <input type="date" class="form-control" required id="datepicker" value="<?php echo date("Y-m-d");?>" name="cashrecive">
                    <?php }?>
                </div>
                <div class="col-md-4">
                    <label class="control-label" for="inputSuccess" style="font-size: 0.9em">
                        رپورٹ نمبر
                    </label>
                    <?php if(isset($RemainingCash[0]->pageno)){?>
                        <input type="number" class="form-control" value="<?php echo $RemainingCash[0]->pageno?>" name="pageno">
                    <?php } else{?>
                        <input type="number" required class="form-control" name="pageno">
                    <?php }?>
                    <?php $date = $Reportdate[5].$Reportdate[6].$Reportdate[4].$Reportdate[8].$Reportdate[9].$Reportdate[7].$Reportdate[0].$Reportdate[1].$Reportdate[2].$Reportdate[3];?>
                    <input class="reportdate" type="hidden" value="<?php echo $Reportdate;?>" name="reportdate">
                <input type="hidden" name="voucher" value="<?php echo $from;?>">
                </div>
            </div>

            <br>
            <?php if($RemainingCash != array()){?>
                <?php foreach($RemainingCash as $R_key => $rcash){?>
                    <div class="row otherEXP" id="otherEXP">
                        <div class="col-md-4">
                            <label class="control-label" for="inputSuccess" >دیگر وصولیاں</label>
                            <input type="text" required required class="form-control" name="otherexpdetail[]" value="<?php echo $rcash->otherexpdetail?>">
                        </div>
                        <div class="col-md-4">
                            <label class="control-label" for="inputSuccess" >رقم</label>
                            <input type="number" step="any" required class="form-control otherexp" name="otherexp[]" value="<?php echo $rcash->otherexp?>">
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-info btn-circle del"><i class="fa fa-trash-o"></i></button>
                            <button type="button" class="btn btn-info btn-circle addR" ><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                <?php }?>
            <?php } else{?>
                <div class="row otherEXP" id="otherEXP">
                    <div class="col-md-4">
                        <label class="control-label" for="inputSuccess" >دیگر وصولیاں</label>
                        <input type="text" required class="form-control" name="otherexpdetail[]">
                    </div>
                    <div class="col-md-4">
                        <label class="control-label"  for="inputSuccess" >رقم</label>
                        <input type="number" step="any" required class="form-control" name="otherexp[]">
                    </div>
                    <div class="col-md-4">
                        <button type="button" class="btn btn-info btn-circle del"><i class="fa fa-trash-o"></i></button>
                        <button type="button" class="btn btn-info btn-circle addR" ><i class="fa fa-plus"></i></button>
                    </div>
                </div>
            <?php }?>
            <br>
            <input type="submit" name=""  class="" value="Get Value" style="line-height: 160%">
            <br>
            <div class="table-responsive" align="middle" style="width: 108%;text-align: center;">
                <table class="table-bordered" style="width: 85%;margin-right: 5%;">
                    <thead>
                    <tr style="line-height: 243%;">
                        <th style="text-align: center; width: 17%">واؤچر نمبر</th>
                        <th style="text-align: center; width: 17%">تاریخ</th>
                        <th style="text-align: center; width: 20%">مصارف کی تفصیل</th>
                        <th style="text-align: center; width: 20%">کل رقم</th>
                        <th style="text-align: center; width: 25%">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php  foreach($Vouchers as $key => $value){
                        foreach ($value as $a_key => $item) {?>
                            <tr>
                                <?php if (isset($GetSaveVoucher[$a_key][0]->VoucherType)){?>
                                    <input type="hidden" name="vouchertype[]" value="<?=$GetSaveVoucher[$a_key][0]->VoucherType?>">
                                    <input type="hidden" class="voucherNo" name="voucherNo[]" value="<?=$GetSaveVoucher[$a_key][0]->Permanent_VoucherNumber?>">
                                <?php } else{?>
                                    <input type="hidden" name="vouchertype[]" value="<?=$item->VoucherType?>">
                                    <input type="hidden" name="voucherNo[]" value="<?=$item->Permanent_VoucherNumber?>">
                                <?php }?>
                                <?php if (isset($GetSaveVoucher[$a_key][0]->Permanent_VoucherDateG)){?>
                                    <input type="hidden" name="VoucherDateH[]" value="<?=$GetSaveVoucher[$a_key][0]->Permanent_VoucherDateH?>">
                                    <input type="hidden" name="VoucherDateG[]" value="<?=$GetSaveVoucher[$a_key][0]->Permanent_VoucherDateG?>">
                                <?php } else{?>
                                    <input type="hidden" name="VoucherDateH[]" value="<?=$item->Permanent_VoucherDateH?>">
                                    <input type="hidden" name="VoucherDateG[]" value="<?=$item->Permanent_VoucherDateG?>">
                                <?php }?>
                                <?php if (isset($GetSaveVoucher[$a_key][0]->Remarks)){?>
                                    <input type="hidden" name="Remarks[]" value="<?=$GetSaveVoucher[$a_key][0]->Remarks?>">
                                <?php } else{?>
                                    <input type="hidden" name="Remarks[]" value="<?=$item->Remarks?>">
                                <?php }?>
                                <?php if (isset($bookAmountSave[$a_key][0]->BookAmount)){?>
                                    <input type="hidden" name="credit[]" value="<?=$bookAmountSave[$a_key][0]->BookAmount?>">
                                <?php } else{?>
                                    <input type="hidden" name="credit[]" value="<?=$bookAmount[$key][$a_key][0]->BookAmount?>">
                                <?php }?>
                                <?php if (isset($GetSaveVoucher[$a_key][0]->LevelID)){?>
                                    <input type="hidden" name="levelID[]" value="<?=$GetSaveVoucher[$a_key][0]->LevelID?>">
                                <?php } else{?>
                                    <input type="hidden" name="levelID[]" value="<?=$item->LevelID?>">
                                <?php }?>
                                <?php if(isset($GetSaveVoucher[$a_key][0]->VoucherType)){?>
                                    <td><?=$GetSaveVoucher[$a_key][0]->Permanent_VoucherNumber.'-'.$GetSaveVoucher[$a_key][0]->VoucherType;?></td>
                                <?php }else{?>
                                    <td><?=$item->Permanent_VoucherNumber.'-'.$item->VoucherType;?></td>
                                <?php }?>
                                <?php if(isset($GetSaveVoucher[$a_key][0]->Permanent_VoucherDateG)){?>
                                    <td><?=$GetSaveVoucher[$a_key][0]->Permanent_VoucherDateG?><br><?=$GetSaveVoucher[$a_key][0]->Permanent_VoucherDateH?></td>
                                <?php } else{?>
                                    <td><?=$item->Permanent_VoucherDateG?><br><?=$item->Permanent_VoucherDateH?></td>
                                <?php }?>
                                <?php if(isset($GetSaveVoucher[$a_key][0]->Remarks)){?>
                                    <td><?=$GetSaveVoucher[$a_key][0]->Remarks?></td>
                                <?php } else{?>
                                    <td><?=$item->Remarks?></td>
                                <?php }?>
                                <?php if (isset($bookAmountSave[$a_key][0]->BookAmount)){?>
                                    <td><?=$bookAmountSave[$a_key][0]->BookAmount?></td>
                                <?php } else{?>
                                    <?php if(isset($bookAmount[$key][$a_key][0]->BookAmount)){?>
                                        <td><?=$bookAmount[$key][$a_key][0]->BookAmount?></td>
                                    <?php }?>
                                <?php }?>
                                <?php if (isset($GetSaveVoucher[$a_key][0]->VoucherType)){?>
                                    <td><input style="margin-right: 45%;" type="checkbox" name="getvouchers[<?php echo $a_key?>]" class="checkbox" checked></td>
                                <?php } else{?>
                                    <td><input style="margin-right: 45%;" type="checkbox" name="getvouchers[<?php echo $a_key?>]" class="checkbox"></td>
                                <?php }?>
                            </tr>
                        <?php } }?>
                    </tbody>
                </table>
            </div>

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
    $(document).ready(function() {

//$('checkbox')
        $('input[type=submit]').click(function(e){

            if($('.checkbox:checkbox:checked').length == 0){

                alert('کم سے کم اک واوچر پر چیک لگایں');
                e.preventDefault();
            }

        });

        $('input[type=submit]').click(function(){

        //   var values = $("input[name='otherexpdetail\\[\\]']")
         //      .map(function(){return $(this).val();}).get();

          // alert(values);

        //   localStorage.setItem('one',values);

       });

        /*$('table').DataTable();
        $('input[type=search]').css('width','50%');
        $('select[name=DataTables_Table_0_length]').css('padding','0px');
        $('.row').eq(7).find('.col-sm-6').eq(0).removeClass("col-sm-6");
        $('.row').eq(7).find('div').eq(0).addClass('col-sm-4');
        $('.row').eq(7).find('.col-sm-6').eq(0).removeClass("col-sm-6");
        $('.row').eq(7).find('div').eq(3).addClass('col-sm-8');*/

        var voucherNo = $('.voucherNo').val()
        var otherexp = $('.otherexp').val()
        if(voucherNo != undefined || otherexp != undefined){
            $(function(){
                var rdate = $('.reportdate').val();
                (new PNotify({
                    title: 'تصدیق درکار',
                    text: 'کیا آپ پرانے واؤچر حذف کرنا چاہتے ہیں؟',
                    icon: 'glyphicon glyphicon-question-sign',
                    hide: false,
                    type: "success",
                    confirm: {
                        confirm: true,
                        buttons: [{text: "جی ہاں", addClass: "", promptTrigger: true, click: function(notice, value){ notice.remove(); notice.get().trigger("pnotify.confirm", [notice, value]); }},{text: "نیہں", addClass: "", click: function(notice){ notice.remove(); notice.get().trigger("pnotify.cancel", notice); }}]
                    },
                    buttons: {
                        closer: false,
                        sticker: false
                    },
                    history: {
                        history: false
                    },
                    addclass: 'stack-modal',
                    stack: {'dir1': 'down', 'dir2': 'right', 'modal': true}
                })).get().on('pnotify.confirm', function(){
                    $.ajax({
                        url:'<?php echo site_url('Accounts/CashHolding/DeleteOldvoucher'); ?>'+'/'+rdate,
                        returnType:'JSON',
                        success:function(response){
                            var data = $.parseJSON(response);
                            if(data['success']){
                                //$('form').find('input:text').val('');
                                new PNotify({
                                    title: 'حذف',
                                    text: 'حذف کر دیے گیے ہیں',
                                    type: 'success',
                                    hide: true
                                });
                                setTimeout(function(){
                                 location.relaod;
                                },0.5);
                            }
                        }
                    });
                }).on('pnotify.cancel', function(){

                    var data = localStorage.one.split(',');
                   if(data != '' && data != undefined)
                    {
                        $("input[name='otherexpdetail\\[\\]']").each(function (index, value) {
                            $(this).map(function () {
                                $(this).val(data[index]);
                            });
                        });
                    }
                });

            });
        }

        $('.addR').on('click',function(){
            var div = $('#otherEXP');
            var clone1= div.clone(true).find('input[type=text]').val("").end();
            clone1.find('label').hide();
            var clone = div.clone(true).find('input[type=number]').val("").end();
            clone.find('label').hide();

            clone1.insertAfter('div#otherEXP:last');
        });

        $('.del').on('click',function(){
            var div = $(this);
            var lengthClass = $('.otherEXP').length;
            if (lengthClass == 1) {
                new PNotify({
                    title: "ہوشیار",
                    text: "Cannot remove the only remaining field!",
                    type: 'error',
                    hide: true
                });
            }else{
                div.closest('#otherEXP').remove();
                new PNotify({
                    title: "حذف",
                    text: "حذف کامیاب",
                    type: 'success',
                    hide: true
                });
            }
        });

    });

</script>