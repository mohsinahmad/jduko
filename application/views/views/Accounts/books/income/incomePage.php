<link rel="stylesheet" href="<?= base_url()."assets/"; ?>css/jquery-ui.css">
<script src="<?= base_url()."assets/"; ?>js/jquery-1.12.4.js"></script>
<script src="<?= base_url()."assets/"; ?>js/jquery-ui.js"></script>
<style>
    div.dataTables_info{
        display: none;
    }
    div.dataTables_paginate{
         display: none;
    }
    div.dataTables_length label{
          display: none;
    }
</style>
<form action="<?= site_url('Accounts/Books/SaveIncTransaction/');?><?= $this->uri->segment(4);?>/<?= $this->uri->segment(5);?>" method="post">
    <div class="row">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header" style="margin-top: 10px;">   آمدنی واؤچر - عارضی واؤچر </h1>
                    <input type="hidden" id="url" value="<?= $this->uri->segment(3);?>">
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group" id="shooba" style="width: 250px; align-content: center;">
                        <label class="control-label" for="inputSuccess">شعبہ</label>
                        <select class="form-control" style="height: 50px;" id="departId" name="departId" autofocus>
                            <option value = "" disabled="true" selected="true"> منتخب کریں</option>
                            <?php foreach($departments as $department): ?>
                                <option value="<?= $department->Id;?>"><?= $department->DepartmentName; ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">رسید بک بنام</label>
                        <input type="text" class="form-control" id="bookName" name="bookName" style="width: 250px;" value="<?= $_SESSION['user'][0]->UserName;?>" readonly="true">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">جلد نمبر</label>
                        <input class="form-control" name="bookNo" style="width: 250px;" type="text" required>
                    </div>
                </div>
                <div class="col-xs-6 row" >
                    <label class="control-label" for="inputSuccess">رسیدات نمبر</label>
                    <div class="form-group">
                        <div class="col-xs-3">
                            <input type="text" class="form-control" name="reciptNo1" required>
                        </div>
                        <span style="float: right;">-</span>
                        <div class="col-xs-3">
                            <input type="text" class="form-control" name="reciptNo2" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group" style="width:250px;">
                        <label>عیسوی تاریخ</label>
                        <div class="form-group">
                            <input class="form-control englishDate" type="date" id="" name="englishDate" value="<?= date('Y-m-d'); ?>" placeholder="انگرزیی کی تاریخ منتخب کریں">
                        </div>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">ہجری تاریخ</label>
                        <input class="form-control islamicDate" id="islamicDate" name="islamicDate" value="<?php ?>" style="width: 250px; direction: ltr"   type="text" readonly>
                    </div>
                </div>
                <input type="hidden" name="companyId" value="<?= $this->uri->segment(5);?>">
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group" style="width:250px;">
                        <label>تفصیل</label>
                        <textarea class="form-control" rows="3" id="Edetails" name="transac_details"></textarea>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">جمع شدہ رقم</label>
                        <input  class="form-control" id="totalAmount" style="width: 250px;"  type="text">
                    </div>
                </div>
            </div>
            <div class="row" style="padding: 10px;">
                <button type="submit"  class="btn btn-primary data-save">محفوظ کریں</button>
                <button type="button" class="btn btn-primary addAcc"><i class="fa fa-plus"></i>اکاؤنٹ شامل کریں</button>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body" style="padding: 0px;">
                <div class="container-fluid" >
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="data-table">
                            <thead>
                                <tr>
                                    <th style="width: 150px;">اکاونٹ کا نام</th>
                                    <th style="width: 150px;">ڈیبٹ</th>
                                    <th style="width: 150px;">کریڈٹ</th>
<!--                                    <th style="width: 17%;">چیک نمبر</th>-->
<!--                                    <th style="width: 13%;">ڈیپوزٹ سلپ نمبر</th>-->
                                    <th style="width: 150px;">تفصیل</th>
                                    <th style="width: 60px;"></th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th><span style="float: left">کل:</span></th>
                                    <th> <span id="totald">0</span></th>
                                    <th> <span id="totalc">0</span></th>
<!--                                    <th></th>-->
<!--                                    <th></th>-->
                                    <th></th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<div id="gridSystemModal" class="modal fade" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="gridModalLabel">  آمدنی واؤچر </h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid bd-example-row">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="inputSuccess" >اکاونٹ کا نام</label>
                                <select style="width: 100%;padding-top: 0px;padding-bottom: 0px;" id="accountName" class="form-control js-example-basic-single"> <!--  -->
                                    <option value="0"> منتخب کریں</option>
                                    <?php foreach($accounts as $account): ?>
                                        <option value="<?= $account->id;?>"><?= $account->parentName.' - '.$account->AccountName; ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 ">
                            <div class="form-group">
                                <label class="control-label" for="inputSuccess" >اکاونٹ کا کوڈ </label>
                                <select style="width: 100%;padding-top: 0px;padding-bottom: 0px;" id="accountCode"  class="form-control js-example-basic-single">
                                    <option value="0"> منتخب کریں</option>
                                    <?php foreach($accounts as $account): ?>
                                        <option value="<?= $account->id;?>"><?= $account->AccountCode; ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 ">
                            <div class="form-group">
                                <label class="control-label" for="inputSuccess" >ڈیبٹ</label>
                                <input class="form-control recieved" id="recieved" onkeyup="recBlur(this)" type="number" style="width: 100%">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="inputSuccess" >کریڈٹ</label>
                                <input class="form-control payment" id="payment"  onkeyup="payBlur(this)"  type="number" style="width: 100%">
                            </div>
                        </div>
                    </div>
<!--                    <div class="row" id="chequeData">-->
                    <div class="row" id="" style="display: none">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="inputSuccess" >چیک نمبر</label>
                                <input class="form-control chequeno" id="chequeno" name="chequeno"  type="text" style="width: 100%">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="inputSuccess" >چیک کی تاریخ</label>
                                <input class="form-control chequedate" id="chequedate" name="chequedate"  type="date" style="width: 100%" value="<?= date('Y-m-d'); ?>">
                            </div>
                        </div>
                    </div>
<!--                    <div class="row chequeData5">-->
<!--                     <div class="row " style="display: none">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="radio-inline"><input type="radio" value="0" name="DepositType" checked>نقد</label>
                                <label class="radio-inline"><input type="radio" value="1" name="DepositType">چیک</label>
                            </div>
                        </div>
                    </div> -->
<!--                    <div class="row" id="chequeData2">-->
                    <div class="row" id="" style="display: none">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="inputSuccess" >ڈیپوزٹ سلپ نمبر</label>
                                <input class="form-control depositSlipNo" id="depositSlipNo" name="depositSlipNo"  type="text" style="width: 100%">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="inputSuccess" >ڈپازٹ کی تاریخ</label>
                                <input class="form-control depositDate" id="depositDate" name="depositDate"  type="date" style="width: 100%" value="<?= date('Y-m-d'); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row" id="chequeData4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="inputSuccess" >بینک کا نام</label>
                                <input class="form-control BankName" id="BankName" name="BankName"  type="text" style="width: 100%">
                            </div>
                        </div>
                    </div>
                    <label class="control-label chequeData3" for="inputSuccess" >چیک کی قسم</label>
                    <div class="row chequeData3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="radio-inline"><input type="radio" value="transfer" name="ChequeType" checked>منتقل</label>
                                <label class="radio-inline"><input type="radio" value="clearing" name="ChequeType">کلیرنگ</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="radio-inline"><input type="radio" value="outofcity" name="ChequeType">آوٹ سٹی</label>
                                <label class="radio-inline"><input type="radio" value="deposit" name="ChequeType">آن لائن</label>
                            </div>
                        </div>
                    </div>
                    <div class="row ser">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="inputSuccess" >تفصیل</label>
                                <textarea class="form-control" rows="3" id="Edetailss" name="details"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="dataEdit" class="btn btn-primary dataEdit">محفوظ کریں</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $( function() {
        var level = '<?= $this->uri->segment(5);?>';
        $.ajax({
            type:'GET',
            url:'<?= site_url('Accounts/CompanyStructures/GetParentCode')?>'+'/'+level,
            success:function(response) {
                var data = $.parseJSON(response);
                if(data[0]['ParentCode'] != 101)
                {
                    $('#departId').attr('disabled',true);
                    $('#shooba').append('<input type="hidden" name="departId" value="0">');
                }
            }
        });

        $(".addAcc").click(function () {
            var value = $('#Edetails').val();
            $('#Edetailss').val(value);
            $("#gridSystemModal").modal('show');
            $('#chequeData').hide();
            $('#chequeData2').hide();
            $('.chequeData3').hide();
            $('#chequeData4').hide();
            $('.chequeData5').hide();
            $('.dataEdit').removeAttr('disabled');
        });

        $('#gridSystemModal').on('hidden.bs.modal', function (e) {
            $(this)
                .find("textarea")
                .val('')
                .end()
                .find("input[type=number]")
                .prop("disabled", false)
                .val('')
                .end();

            var dsum = 0;
            var csum = 0;
            var credit = 0;
            var debit = 0;

            $('tr').each(function () {
                $(this).find('.debit').each(function () {
                    var debit = $(this).val();
                    if (!isNaN(debit) && debit.length !== 0) {
                        dsum += parseFloat(debit);
                    }
                });

                $('#totald').text('');
                $('#totald').text(dsum);

                $(this).find('.credit').each(function () {
                    var credit = $(this).val();
                    if (!isNaN(credit) && credit.length !== 0) {
                        csum += parseFloat(credit);
                    }
                });

                $('#totalc').text('');
                $('#totalc').text(csum);
            });
        });
    });

    $('.dataEdit').on('click',function(){
        var post = new Object();
        post.debit = "";
        post.credit = "";
        post.name = "";
        var error = "";
        var rec = $('#recieved').val();
        var pay = $('#payment').val();
        var id = $('#accountName').val();
        if(rec){
            var $str  = rec;
            var $strlen = $str.length;
            var $as = $str.indexOf('.');
            var $ap = $str.substring(0,$as);
            var $aplen = $ap.length;
            $as++;
            var $bp = $str.substring($as,$strlen);
            var $bplen = $bp.length;
        }else{
            var $str  = pay;
            var $strlen = $str.length;
            var $as = $str.indexOf('.');
            var $ap = $str.substring(0,$as);
            var $aplen = $ap.length;
            $as++;
            var $bp = $str.substring($as,$strlen);
            var $bplen = $bp.length;
        }

        if($aplen >= 10 || $strlen > 12 || $bplen >= 10){
            error = 1;
            new PNotify({
                title: 'انتباہ',
                text: "رقم غلط ہے",
                type: 'error',
                delay: 3000,
                hide: true
            });
            $(this).removeAttr("data-dismiss","modal");
        }
        if(($('#recieved').val() == 0) && ($('#payment').val() == 0)) {
            new PNotify({
                title: 'انتباہ',
                text: "رقم اندراج کریں!!!",
                type: 'error',
                delay: 3000,
                hide: true
            });
            $(this).removeAttr("data-dismiss","modal");
            //event.preventDefault();
        }
        if(!id){
            error = 1;
            new PNotify({
                title: 'انتباہ',
                text: " براہ مہربانی  اکاؤنٹ  منتخب کریں",
                type: 'error',
                delay: 3000,
                hide: true
            });
            $(this).removeAttr("data-dismiss","modal");
        }
        if(!error){
            post.name = '';
            post.code = '';
            // var id = $('#accountName').val();
            $.ajax({
                type: 'POST',
                dataType:  'json',
                url:'<?= site_url('Accounts/Books/getAccountCode')?>'+'/'+id,
                success:function(response){
                    //var data = $.parseJSON(response);
                    post.name = response._name;
                    post.code = response._code;
                    post.type = response._type;
                }
            }).done(function() {
                post.is_debit = $('#recieved').val();
                if(!post.is_debit == ""){
                    post.debit = post.is_debit;
                }
                post.is_credit = $('#payment').val();
                if(!post.is_credit == ""){
                    post.credit = post.is_credit;
                }
                post.details = $('#Edetailss').val();
                post.a_id = $('#accountName').val();
                post.chequeno = $('.chequeno').val();
                post.chequedate = $('#chequedate').val();
                post.depositSlipNo = $('#depositSlipNo').val();
                post.depositDate = $('#depositDate').val();
                post.ChequeType = $('input[name=ChequeType]:checked').val();
                post.DepositType = $('input[name=DepositType]:checked').val();
                post.BankName = $('#BankName').val();

                var str = post.code;
                var res =str[0];
                $('tbody').append('<tr class="addnew"><td><input class="form-control accountname" name="account[]" type="text" style="width: 100%" readonly value="'+post.name+'"  ></td><td><input class="form-control debit" name="recieved[]" type="text" readonly style="width: 100%" value='+post.debit+' ></td><td><input class="form-control credit" name="payment[]" type="text" readonly style="width: 100%" value='+post.credit+' ></td><td class="center"><textarea class="form-control"   rows="1" name="details[]" readonly >'+post.details+'</textarea></td><td style="display: none"><input type="hidden" class="accountId" name="AccountID[]" value='+post.a_id+'></td><td style="display: none"><input type="hidden" class="accountType" name="" value='+post.type+'></td><td style="display: none"><input type="hidden" class="bdepositDate" name="bdepositDate[]" value='+post.depositDate+'></td><td style="display: none"><input type="hidden" class="ChequeType" name="ChequeType[]" value='+post.ChequeType+'></td><td style="display: none"><input type="hidden" class="DepositType" name="DepositType[]" value='+post.DepositType+'></td><td style="display: none"><input type="hidden" class="BankName" name="BankName[]" value='+post.BankName+'></td><td class="center"><button type="button" class="btn btn-info btn-circle del" ><i class="fa fa-minus"></i></button></td></tr>');

//                if (post.type == 2){
//                    $('tbody').append('<tr class="addnew"><td><input class="form-control accountname" name="account[]" type="text" style="width: 100%" readonly value="'+post.name+'"  ></td><td><input class="form-control debit" name="recieved[]" type="text" readonly style="width: 100%" value='+post.debit+' ></td><td><input class="form-control credit" name="payment[]" type="text" readonly style="width: 100%" value='+post.credit+' ></td><td><input class="form-control recieved" name="bchequeno[]" type="text" style="width: 100%" readonly value='+post.chequeno+'  ></td><td style="display: none"><input class="form-control" name="bchequedate[]" type="hidden" style="width: 100%" value='+post.chequedate+'  ></td><td><input class="form-control recieved" name="bdepositSlipNo[]" type="text" style="width: 100%" readonly value='+post.depositSlipNo+' ></td><td class="center"><textarea class="form-control"   rows="1" name="details[]" readonly >'+post.details+'</textarea></td><td style="display: none"><input type="hidden" class="accountId" name="AccountID[]" value='+post.a_id+'></td><td style="display: none"><input type="hidden" class="accountType" name="" value='+post.type+'></td><td style="display: none"><input type="hidden" class="bdepositDate" name="bdepositDate[]" value='+post.depositDate+'></td><td style="display: none"><input type="hidden" class="ChequeType" name="ChequeType[]" value='+post.ChequeType+'></td><td style="display: none"><input type="hidden" class="DepositType" name="DepositType[]" value='+post.DepositType+'></td><td style="display: none"><input type="hidden" class="BankName" name="BankName[]" value='+post.BankName+'></td><td class="center"><button type="button" class="btn btn-info btn-circle del" ><i class="fa fa-minus"></i></button></td></tr>');
//                }else if(res == 4 && post.type != 2){
//                    $('tbody').append('<tr class="addnew"><td><input class="form-control accountname" name="account[]" type="text" style="width: 100%" readonly value="'+post.name+'"  ></td><td><input class="form-control debit" name="recieved[]" type="text" readonly style="width: 100%" value='+post.debit+' ></td><td><input class="form-control credit" name="payment[]" type="text" readonly style="width: 100%" value='+post.credit+' ></td><td><input class="form-control recieved" name="bchequeno[]" type="text" style="width: 100%" readonly value='+post.chequeno+'  ></td><td style="display: none"><input class="form-control" name="bchequedate[]" type="hidden" style="width: 100%" value='+post.chequedate+'  ></td><td><input class="form-control recieved" name="bdepositSlipNo[]" type="text" style="width: 100%" readonly value="" ></td><td class="center"><textarea class="form-control"   rows="1" name="details[]" readonly >'+post.details+'</textarea></td><td style="display: none"><input type="hidden" class="accountId" name="AccountID[]" value='+post.a_id+'></td><td style="display: none"><input type="hidden" class="accountType" name="" value='+post.type+'></td><td style="display: none"><input type="hidden" class="bdepositDate" name="bdepositDate[]" value='+post.depositDate+'></td><td style="display: none"><input type="hidden" class="ChequeType" name="ChequeType[]" value='+post.ChequeType+'></td><td style="display: none"><input ty pe="hidden" class="DepositType" name="DepositType[]" value='+post.DepositType+'></td><td style="display: none"><input type="hidden" class="BankName" name="BankName[]" value='+post.BankName+'></td><td class="center"><button type="button" class="btn btn-info btn-circle del" ><i class="fa fa-minus"></i></button></td></tr>');
//                }else {
//                    $('tbody').append('<tr class="addnew"><td><input class="form-control accountname" name="account[]" type="text" style="width: 100%" readonly value="'+post.name+'"  ></td><td><input class="form-control debit" name="recieved[]" type="text" readonly style="width: 100%" value='+post.debit+' ></td><td><input class="form-control credit" name="payment[]" type="text" readonly style="width: 100%" value='+post.credit+' ></td><td><input class="form-control recieved" name="bchequeno[]" type="text" style="width: 100%" readonly value='+post.chequeno+'></td><td style="display: none"><input type="hidden" class="form-control" name="bchequedate[]"  style="width: 100%" value="" ></td><td><input class="form-control recieved" name="bdepositSlipNo[]" type="text" style="width: 100%" readonly value=""></td><td class="center"><textarea class="form-control" rows="1" name="details[]" readonly >'+post.details+'</textarea></td><td style="display: none"><input type="hidden" class="accountId" name="AccountID[]" value='+post.a_id+'></td><td style="display: none"><input type="hidden" class="ChequeType" name="ChequeType[]" value="" ></td><td style="display: none"><input type="hidden" class="accountType" name="" value='+post.type+'></td><td style="display: none"><input type="hidden" class="bdepositDate" name="bdepositDate[]" value=""><td style="display: none"><input type="hidden" class="BankName" name="BankName[]" value=""></td><td style="display: none"><input type="hidden" class="DepositType" name="DepositType[]" value=""></td><td class="center"><button type="button" class="btn btn-info btn-circle del" ><i class="fa fa-minus"></i></button></td></tr>');
//                }
                $('#gridSystemModal').modal('toggle');
            });
        }
        $('.dataEdit').attr('disabled',true);
    });

    $( "#data-table" ).on( "click", ".del", function(e) {
        e.preventDefault();
        var tr = $(this).parents('tr');
        var debit = tr.find('.debit').val();
        dsum = $('#totald').text();
        var newTotald = dsum - debit;
        $('#totald').text(newTotald);

        var credit = tr.find('.credit').val();
        csum = $('#totalc').text();
        var newTotalc = csum - credit;
        $('#totalc').text(newTotalc);
        $( this ).parents( "tr" ).remove();
    });

    $('#accountName').on('change',function(){
        var accountId = $(this).val();
        $.ajax({
            type:"POST",
            url:'<?= site_url('Accounts/Books/getAccountCode');?>'+'/'+accountId,
            success:function(response){
                var str= response._code;
                var res=str[0];

                $('#accountCode').val(response._id);
                $('select').trigger('change.select2');

                if(response._type == 2){
                    $('#chequeData').hide();
                    $('#chequeData2').show();
                    $('.chequeData3').hide();
                    $('#chequeData4').hide();
                    $('.chequeData5').hide();
                }else if(res == 4 && response._type != 2 ){
                    $('#chequeData2').hide();
                    $('.chequeData5').show();

                    //alert($('input[name=DepositType]:checked').val());
                    //  if ($('#cheque')) {
                    //     alert("asdasd");
                    //  $('#chequeData').show();
                    //  $('.chequeData3').show();
                    //  $('#chequeData4').show();
                    // }
                }else{
                    $('#chequeData').hide();
                    $('#chequeData2').hide();
                    $('.chequeData3').hide();
                    $('#chequeData4').hide();
                    $('.chequeData5').hide();
                }
            }
        });
    });

    $("form").submit(function( event ) {
        var level = '<?= $this->uri->segment(5);?>';
        $.ajax({
            type:'GET',
            url:'<?= site_url('Accounts/CompanyStructures/GetParentCode')?>'+'/'+level,
            success:function(response) {
                var data = $.parseJSON(response);
                if(data[0]['ParentCode'] == 101)
                {
                    if($('#departId').val() == null || $('#departId').val()== 0){
                        new PNotify({
                            title: 'انتباہ',
                            text: "شعبہ منتخب کرنا ضروری ہے",
                            type: 'error',
                            delay: 3000,
                            hide: true
                        });
                        event.preventDefault();
                    }
                }
            }
        });

        var bookType = '<?= $this->uri->segment(4);?>';
        var typeArr = [];
        if(bookType == "inc"){
            var depart = $('#departId').val();
            var paid = $('#paidTo').val();
            var engDate = $('.englishDate').val();
            var islDate = $('.islamicDate').val();
            if(!engDate || !islDate){
                new PNotify({
                    title: 'انتباہ',
                    text: "تمام خانے ضروری ہیں",
                    type: 'error',
                    delay: 3000,
                    hide: true
                });
                event.preventDefault();
            }

            $('tbody tr').each(function () {
                var accType = $(this).find('.accountType').val();
                typeArr.push(accType);
            });

            // $('tbody tr').each(function () {
            //     var accType = $(this).find('.accountType').val();
            //     var debit = $(this).find('.debit').val();
            //     if(accType == 1 && !debit){
            //         new PNotify({
            //             title: 'انتباہ',
            //             text: "کیش بک ڈیبٹ ہونی چاہئے",
            //             type: 'error',
            //             delay: 3000,
            //             hide: true
            //         });
            //         event.preventDefault();
            //     }
            // });

            // alert(typeArr);
           // var check = ""
            // typeArr.forEach(function(e){
            //     if(e == 1){
            //         check = 1;
            //     }
            // });
            if($('#totald').text() != $('#totalc').text()){
                new PNotify({
                    title: 'انتباہ',
                    text: "ڈیبٹ اور کریڈٹ برابر ہونا چاہئے",
                    type: 'error',
                    delay: 3000,
                    hide: true
                });
                event.preventDefault();
            }else if(($('#totald').text() == 0) || ($('#totalc').text() == 0)) {
                new PNotify({
                    title: 'انتباہ',
                    text: "رقم اندراج کریں!!!",
                    type: 'error',
                    delay: 3000,
                    hide: true
                });
                event.preventDefault();
            }else if($('#totalAmount').val() != $('#totald').text()){
                new PNotify({
                    title: 'انتباہ',
                    text: "جمع شدہ رقم اور ڈیبٹ ، کریڈٹ برابر نہیں ہے!!!",
                    type: 'error',
                    delay: 3000,
                    hide: true
                });
                event.preventDefault();
            }else{
                return;
            }

           // else{
            //     new PNotify({
            //         title: 'انتباہ',
            //         text: "کم از کم ایک کیش بک درکار ہے",
            //         type: 'error',
            //         delay: 3000,
            //         hide: true
            //     });
            //     event.preventDefault();
            // }
        }
    });
</script>