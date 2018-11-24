<?php
$dabit1 = 0; $credit1 = 0;$dabit2 = 0; $credit2 = 0;$closing_balance1 = 0;$closing_balance2 = 0;
foreach ($transactions[0] as $transaction){
    if (isset($transaction->Debit)){
        $dabit1 += $transaction->Debit;
    }
    if (isset($transaction->Credit)){
        $credit1 += $transaction->Credit;
    }
}foreach ($transactions[1] as $transaction){
    if (isset($transaction->Debit)){
        $dabit2 += $transaction->Debit;
    }
    if (isset($transaction->Credit)){
        $credit2 += $transaction->Credit;
    }
}?>
<div class="panel-body">
    <div class="row">
        <div class="col-lg-12">
            <?php if ($ttype == 't'){?>
                <h1 class="page-header" style="margin-top: 5px;">اکاؤنٹ منتقل<span style="font-size: 20px">(عارضی واوچرز)</span></h1>
            <?php }else{?>
                <h1 class="page-header" style="margin-top: 5px;">اکاؤنٹ منتقل<span style="font-size: 20px">(مستقل واوچرز)</span></h1>
            <?php }?>
        </div>
    </div>
<!--    <form action="--><?//= site_url('Accounts/Books/SaveTransaction/');?><!--" method="post">-->
    <div class="row">
        <div class="col-md-6">
            <div class="Origin">
                <input class="form-control" id="OrAccountName" style="width: 50%;text-align: center" value="<?= $AccountName1?>" readonly>
                <input class="form-control" id="OrAccountCode" style="width: 50%;float: left;margin-top: -7.8%;text-align: center" value="<?= $AccountCode1?>" readonly>
                <div class="table-responsive" style="width: 100%;">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th colspan="2" style="width: 50%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th style="width: 50%">ابتدائ بیلینس</th>
                                <?php if ($balances[0][0]->OpeningBalance < 0){ $op_balance1 = ($balances[0][0]->OpeningBalance * -1);?>
                                    <th><input class="form-control" style="text-align: center" id="opBalance1" data-id="<?= $balances[0][0]->OpeningBalance?>" value="<?= 'CR : '.number_format($op_balance1,2)?>" readonly></th>
                                <?php }else{?>
                                    <th><input class="form-control" style="text-align: center" id="opBalance1" data-id="<?= $balances[0][0]->OpeningBalance?>" value="<?= number_format($balances[0][0]->OpeningBalance,2)?>" readonly></th>
                                <?php }?>
                            </tr>
                            <tr>
                                <th style="width: 50%">ڈیبٹ</th>
                                <th><input class="form-control" style="text-align: center" id="debit1" data-id="<?=$dabit1?>" value="<?= number_format($dabit1,2)?>" readonly></th>
                            </tr>
                            <tr>
                                <th style="width: 50%">کریڈٹ</th>
                                <th><input class="form-control" style="text-align: center" id="credit1" data-id="<?=$credit1?>" value="<?= number_format($credit1,2)?>" readonly></th>
                            </tr>
                            <?php $closing_balance1 = $balances[0][0]->OpeningBalance + $dabit1 - $credit1?>
                            <tr>
                                <th style="width: 50%">اختتامی بیلنس</th>
                                <?php if ($closing_balance1 < 0){ $cl_balance1 = ($closing_balance1 * -1);?>
                                    <th><input class="form-control" style="text-align: center" id="clBalance1" data-id="<?= $closing_balance1?>"  value="<?= 'CR : '.number_format($cl_balance1,2)?>" readonly></th>
                                <?php }else{?>
                                    <th><input class="form-control" style="text-align: center" id="clBalance1" data-id="<?= $closing_balance1?>"  value="<?= number_format($closing_balance1,2)?>" readonly></th>
                                <?php }?>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="dESTINATION">
                <input class="form-control" id="DesAccountName" style="width: 50%;text-align: center;" value="<?= $AccountName2?>" readonly>
                <input class="form-control" id="DesAccountCode" style="width: 50%;float: left;margin-top: -7.5%;text-align: center" value="<?= $AccountCode2?>" readonly>
                <div class="table-responsive" style="width: 100%;">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th colspan="2" style="width: 50%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th style="width: 50%">ابتدائ بیلینس</th>
                                <?php if ($balances[1][0]->OpeningBalance < 0){ $op_balance2 = ($balances[1][0]->OpeningBalance * -1);?>
                                    <th><input class="form-control" style="text-align: center" id="opBalance2" data-id="<?= $balances[1][0]->OpeningBalance?>"  value="<?= 'CR : '.number_format($op_balance2,2)?>" readonly></th>
                                <?php }else{?>
                                    <th><input class="form-control" style="text-align: center" id="opBalance2" data-id="<?= $balances[1][0]->OpeningBalance?>"  value="<?= number_format($balances[1][0]->OpeningBalance,2)?>" readonly></th>
                                <?php }?>
                            </tr>
                            <tr>
                                <th style="width: 50%">ڈیبٹ</th>
                                <th><input class="form-control" style="text-align: center" id="debit2" data-id="<?=$dabit2?>" value="<?= number_format($dabit2,2)?>" readonly></th>
                            </tr>
                            <tr>
                                <th style="width: 50%">کریڈٹ</th>
                                <th><input class="form-control" style="text-align: center" id="credit2" data-id="<?=$credit2?>" value="<?= number_format($credit2,2)?>" readonly></th>
                            </tr>
                            <?php $closing_balance2 = $balances[1][0]->OpeningBalance + $dabit2 - $credit2?>
                            <tr>
                                <th style="width: 50%">اختتامی بیلنس</th>
                                <?php if ($closing_balance2 < 0){ $cl_balance2 = ($closing_balance2 * -1);?>
                                    <th><input class="form-control" style="text-align: center" id="clBalance2" data-id="<?= $closing_balance2?>"  value="<?= 'CR : '.number_format($cl_balance2,2)?>" readonly></th>
                                <?php }else{?>
                                    <th><input class="form-control" style="text-align: center" id="clBalance2" data-id="<?= $closing_balance2?>"  value="<?= number_format($closing_balance2,2)?>" readonly></th>
                                <?php }?>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="table-responsive" style="width: 100%;">
                <input class="form-control" id="total_credit" style="width: 20%;float: left;text-align: center" value="0.00" readonly>
                <input class="form-control" id="total_debit" style="width: 20%;float: left;text-align: center;" value="0.00" readonly>
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th style="width: 5%;text-align: center"><input type="checkbox" name="" id="select_all"></th>
                            <th style="text-align: center">واوچر نمبر</th>
                            <th style="text-align: center">بتاریخ</th>
                            <th style="text-align: center;width: 20%;">ڈیبٹ</th>
                            <th style="text-align: center;width: 20%;">کریڈٹ</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($transactions[0] as $key => $transaction){ ?>
                        <tr class="trans<?= $key?>">
                            <th><input type="checkbox" name="toTemp[]" class="checkbox" data-id="<?= $key?>">
                            <?php if ($transaction->Permanent_VoucherNumber != ''){?>
                                <input type="hidden" id="tr_id<?=$key?>" name="VoucherNo" class="vouch_no" data-id="<?= $transaction->Id?>" value="<?= $transaction->Permanent_VoucherNumber?>"></th>
                            <th style="text-align: center"><?= $transaction->VoucherType.' - '.$transaction->Permanent_VoucherNumber?></th>
                                <th style="text-align: center"><?= $transaction->Permanent_VoucherDateG?></th>
                            <?php }else{?>
                                <input type="hidden" id="tr_id<?=$key?>"  name="VoucherNo" class="vouch_no" data-id="<?= $transaction->Id?>" value="<?= $transaction->VoucherNo?>"></th>
                                <th style="text-align: center"><?= $transaction->VoucherType.' - '.$transaction->VoucherNo?></th>
                                <th style="text-align: center"><?= $transaction->VoucherDateG?></th>
                            <?php } if ($transaction->Debit == 0){ ?>
                                <th style="text-align: center" id="ddebit<?= $key?>" data-id="<?=$transaction->Debit?>"></th>
                            <?php }else{ ?>
                                <th style="text-align: center" id="ddebit<?= $key?>" data-id="<?=$transaction->Debit?>"><?= number_format($transaction->Debit)?></th>
                            <?php } if ($transaction->Credit == 0){?>
                                <th style="text-align: center" id="ccredit<?=$key?>" data-id="<?=$transaction->Credit?>"></th>
                            <?php }else{ ?>
                                <th style="text-align: center" id="ccredit<?=$key?>" data-id="<?=$transaction->Credit?>"><?= number_format($transaction->Credit)?></th>
                            <?php }?>
                            <th style="display: none" class="AccountID"><?= $transaction->AccountID?></th>
                        </tr>
                    <?php }?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row">
        <button type="button" id="Proceed" class="btn btn-info" style="margin-right: 44%;">
            <span class="glyphicon glyphicon-chevron-right"></span> Proceed
        </button>
    </div>
</div>
<script src="<?php echo base_url()."assets/";?>js/accounting.min.js"></script>
<script src="<?php echo base_url()."assets/"; ?>js/jquery-1.12.4.js"></script>
<script>
    var tran_debit = 0;
    var tran_credit = 0;
    var origin_debit = 0;
    var origin_credit = 0;
    var des_debit = 0;
    var des_credit = 0;
    var newDebit1 = 0;
    var newDebit2 = 0;
    var newCredit1 = 0;
    var newCredit2 = 0;
    var oldDebit1 = 0;
    var oldDebit2 = 0;
    var oldCredit1 = 0;
    var oldCredit2 = 0;
    var origin_opBalance = 0;
    var des_opBalance = 0;
    var new_origin_clBalance = 0;
    var new_des_clBalance = 0;
    var old_origin_clBalance = 0;
    var old_des_clBalance = 0;
    var trSum1 = 0;
    var trSum2 = 0;
    var trSum1R = 0;
    var trSum2R = 0;
    var trcrSum1R = 0;
    var trcrSum2R = 0;
    var total_debit = 0;
    var total_credit = 0;

    $('.checkbox').on('click',function(){
        var key = $(this).data('id');
        tran_debit = $('#ddebit'+key).data('id');
        tran_credit = $('#ccredit'+key).data('id');
        origin_debit = $('#debit1').data('id');
        origin_credit = $('#credit1').data('id');
        des_debit = $('#debit2').data('id');
        des_credit = $('#credit2').data('id');

        if($(this).is(':checked'))
        {
            total_debit += parseInt(tran_debit);
            total_credit += parseInt(tran_credit);

            $('#total_debit').val(accounting.formatMoney(total_debit));
            $('#total_credit').val(accounting.formatMoney(total_credit));

            $('#debit1').attr('value','');
            $('#debit1').attr('data-id','');
            $('#debit2').attr('value','');
            $('#debit2').attr('data-id','');

            newDebit1 = parseInt(origin_debit) - parseInt(newDebit2) - parseInt(tran_debit);
            newDebit2 = parseInt(tran_debit) + parseInt(newDebit2) + parseInt(des_debit);

            $('#debit1').attr('value',accounting.formatMoney(newDebit1));
            $('#debit1').attr('data-id',newDebit1);
            $('#debit2').attr('value',accounting.formatMoney(newDebit2));
            $('#debit2').attr('data-id',newDebit2);

//////////////////////////////////////////////////////////////////////////////////

            $('#credit1').attr('value','');
            $('#credit1').attr('data-id','');
            $('#credit2').attr('value','');
            $('#credit2').attr('data-id','');

            newCredit1 = parseInt(origin_credit) - parseInt(trSum1) - parseInt(tran_credit);
            trSum1 += parseInt(tran_credit);
            newCredit2 = parseInt(tran_credit) + parseInt(trSum2) + parseInt(des_credit);
            trSum2 += parseInt(tran_credit);


            $('#credit1').attr('value',accounting.formatMoney(newCredit1));
            $('#credit1').attr('data-id',newCredit1);
            $('#credit2').attr('value',accounting.formatMoney(newCredit2));
            $('#credit2').attr('data-id',newCredit2);

//////////////////////////////////////////////////////////////////////////////////

            origin_opBalance = $('#opBalance1').data('id');
            des_opBalance = $('#opBalance2').data('id');

            $('#clBalance1').attr('value','');
            $('#clBalance1').attr('data-id',new_origin_clBalance);
            $('#clBalance2').attr('value','');
            $('#clBalance2').attr('data-id','');

            new_origin_clBalance = parseInt(origin_opBalance) + parseInt(newDebit1) - parseInt(newCredit1);
            new_des_clBalance = parseInt(des_opBalance) + parseInt(newDebit2) - parseInt(newCredit2);

            $('#clBalance1').attr('value',accounting.formatMoney(new_origin_clBalance));
            $('#clBalance1').attr('data-id',new_origin_clBalance);
            $('#clBalance2').attr('data-id',new_des_clBalance);
            $('#clBalance2').attr('value',accounting.formatMoney(new_des_clBalance));

        }else {

            total_debit -= parseInt(tran_debit);
            total_credit -= parseInt(tran_credit);

            $('#total_debit').val(accounting.formatMoney(total_debit));
            $('#total_credit').val(accounting.formatMoney(total_credit));

            $('#debit1').attr('value','');
            $('#debit1').attr('data-id','');
            $('#debit2').attr('value','');
            $('#debit2').attr('data-id','');

            oldDebit1 = parseInt(newDebit1) + parseInt(tran_debit) + parseInt(trSum1R);
            trSum1R += tran_debit;
            oldDebit2 = parseInt(newDebit2) - parseInt(tran_debit) - parseInt(trSum2R);
            trSum2R += tran_debit;

            $('#debit1').attr('value',accounting.formatMoney(oldDebit1));
            $('#debit1').attr('data-id',oldDebit1);
            $('#debit2').attr('value',accounting.formatMoney(oldDebit2));
            $('#debit2').attr('data-id',oldDebit2);

//////////////////////////////////////////////////////////////////////////////////

            $('#credit1').attr('value','');
            $('#credit1').attr('data-id','');
            $('#credit2').attr('value','');
            $('#credit2').attr('data-id','');

            oldCredit1 = parseInt(newCredit1) + parseInt(tran_credit) + parseInt(trcrSum1R);
            trcrSum1R += tran_credit;
            oldCredit2 = parseInt(newCredit2) - parseInt(tran_credit) - parseInt(trcrSum2R);
            trcrSum2R += tran_credit;

            $('#credit1').attr('value',accounting.formatMoney(oldCredit1));
            $('#credit1').attr('data-id',oldCredit1);
            $('#credit2').attr('value',accounting.formatMoney(oldCredit2));
            $('#credit2').attr('data-id',oldCredit2);

/////////////////////////////////////////////////////////////////////////////////////

            origin_opBalance = $('#opBalance1').data('id');
            des_opBalance = $('#opBalance2').data('id');

            $('#clBalance1').attr('value','');
            $('#clBalance2').attr('data-id','');
            $('#clBalance2').attr('value','');
            $('#clBalance1').attr('data-id',old_origin_clBalance);

            old_origin_clBalance = parseInt(origin_opBalance) + parseInt(oldDebit1) - parseInt(oldCredit1);
            old_des_clBalance = parseInt(des_opBalance) + parseInt(oldDebit2) - parseInt(oldCredit2);

            $('#clBalance1').attr('value',accounting.formatMoney(old_origin_clBalance));
            $('#clBalance1').attr('data-id',old_origin_clBalance);
            $('#clBalance2').attr('data-id',old_des_clBalance);
            $('#clBalance2').attr('value',accounting.formatMoney(old_des_clBalance));
        }
    });
    $('#Proceed').on('click',function(){
        var checkedVals = $('.checkbox:checkbox:checked').map(function() {
            return $(this).data('id');
        }).get();
        //alert(checkedVals.join(","));
        //var res = checkedVals.substring(1, 2);
        var selected = checkedVals.length;
        //alert(checkedVals.length);

        /////////////////////////////////////////////////////////

        var post = new Object();
        post.originAccountCode = $('#OrAccountCode').val();
        post.destAccountCode = $('#DesAccountCode').val();
        post.OACB = $('#clBalance1').data('id');
        post.DACB = $('#clBalance2').data('id');
        post.level = <?=$this->uri->segment(4)?>;

        var i;
        var tr_ids = [];
        for (i=0;i<selected;i++){
            tr_ids[i] = $('#tr_id'+checkedVals[i]).data('id');
            //alert($('#tr_id'+checkedVals[i]).data('id'));
        }
        post.trans_ids = tr_ids;

        //alert($('#tr_id'+checkedVals[1]).data('id'));
        $.ajax({
            type:'post',
            url:'<?php echo site_url("Accounts/MoveAccount/updateMoveAccount");?>',
            data:post,
            success:function(response){
                var data = $.parseJSON(response);
                if(data['success']){
                    new PNotify({
                        title: 'کامیابی',
                        text: 'Done',
                        type: 'success',
                        hide: true
                    });
                }else{
                    new PNotify({
                        title: 'کامیابی',
                        text: 'Some Error',
                        type: 'error',
                        hide: true
                    });
                }
            }
        });

    });

    $("#select_all").change(function(){  //"select all" change
        $(".checkbox").prop('checked', $(this).prop("checked")); //change all ".checkbox" checked status
    });
    $('.checkbox').change(function(){
        //uncheck "select all", if one of the listed checkbox item is unchecked
        if(false == $(this).prop("checked")){ //if this item is unchecked
            $("#select_all").prop('checked', false); //change "select all" checked status to false
        }
        //check "select all" if all checkbox items are checked
        if ($('.checkbox:checked').length == $('.checkbox').length ){
            $("#select_all").prop('checked', true);
        }
    });
</script>