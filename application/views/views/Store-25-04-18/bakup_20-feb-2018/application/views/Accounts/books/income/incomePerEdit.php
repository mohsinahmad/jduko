<link rel="stylesheet" href="<?= base_url()."assets/"; ?>css/jquery-ui.css">
<script src="<?= base_url()."assets/"; ?>js/jquery-1.12.4.js"></script>
<script src="<?= base_url()."assets/"; ?>js/jquery-ui.js"></script>
<style>
    div.dataTables_info{
        display: none;
    }div.dataTables_paginate{
         display: none;
     }div.dataTables_length label{
          display: none;
      }
</style>
<form action="<?= site_url('Accounts/Books/UpdateIncTransactions/');?><?= $this->uri->segment(4);?>/<?= $this->uri->segment(5);?>/<?= $this->uri->segment(6);?>" method="post">
    <div class="row">
        <div class="panel-body">
            <h1 class="page-header" style="margin-top: 10px;">  آمدنی واؤچر - مستقل واؤچر<span style="font-size: 34px;"> (تدوین)</span></h1>
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group" style="width: 250px; align-content: center;">
                        <label class="control-label" for="inputSuccess">شعبہ</label>
                        <input type="hidden" value="<?= $transaction[0]->DepartmentId ?>" name="DepartmentId">
                        <select class="form-control" style="height: 50px;" id="departId" name="DepartmentId" autofocus = 'true' disabled>
                            <option value="<?= $transaction[0]->DepartmentId ?>"><?= $transaction[0]->DepartmentName ?></option>
                            <?php foreach($departments as $department): ?>
                                <option value="<?= $department->Id;?>"><?= $department->DepartmenipctName; ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">رسید بک بنام</label>
                        <input  class="form-control" id="bookName" name="bookName" style="width: 250px;"  type="text" value="<?= $_SESSION['user'][0]->UserName;?>" readonly="true">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">جلد نمبر</label>
                        <input  class="form-control"  name="bookNo" style="width: 250px;"  type="text" value="<?= $transaction[0]->BookNo;?>" readonly="true">
                    </div>
                </div>
                <?php $recipt = explode('-', $transaction[0]->ReciptNo);
                $recipt1 = $recipt[0];
                $recipt2 = $recipt[1]; ?>
                <div class="col-xs-6 row" >
                    <label class="control-label" for="inputSuccess">رسیدات نمبر</label>
                    <div class="form-group">
                        <div class="col-xs-3">
                            <input type="text" class="form-control" name="reciptNo1" value="<?= "$recipt1";?>" readonly="true">
                        </div>
                        <span style="float: right;">-</span>
                        <div class="col-xs-3">
                            <input type="text" class="form-control" name="reciptNo2" value="<?= "$recipt2";?>" readonly="true">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group" style="width:250px;">
                        <label>شمسی تاریخ</label>
                        <div class="form-group">
                            <input class="form-control EenglishDate" type="date" name="VoucherDateG" value="<?= $transaction[0]->VoucherDateG ?>" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">قمری تاریخ</label>
                        <input class="form-control" id="EislamicDate" name="VoucherDateH" value="<?= $transaction[0]->VoucherDateH ?>" style="width: 250px;"  type="text" readonly>
                    </div>
                </div>
                <input type="hidden" id="companyId" name="LevelID" value="<?=  $transaction[0]->level_id?>">
                <input type="hidden" id="voucher_num" name="VoucherNo" value="<?=  $transaction[0]->VoucherNo?>">
                <input type="hidden" id="voucher_num" name="Permanent_VoucherNumber" value="<?=  $transaction[0]->Permanent_VoucherNumber?>">
                <input type="hidden" id="voucher_num" name="Permanent_VoucherDateH" value="<?=  $transaction[0]->Permanent_VoucherDateH?>">
                <input type="hidden" id="voucher_num" name="Permanent_VoucherDateG" value="<?=  $transaction[0]->Permanent_VoucherDateG?>">
                <input type="hidden" id="voucher_num" name="CreatedOn" value="<?=  $transaction[0]->CreatedOn?>">
                <input type="hidden" id="voucher_num" name="Createdby" value="<?=  $transaction[0]->Createdby?>">
                <input type="hidden" id="voucher_type" name="VoucherType" value="<?=  $transaction[0]->VoucherType?>">
                <input type="hidden" id="sep_seq" name="sep_seq" value="<?=  $transaction[0]->Seprate_series_num?>">
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group" style="width:250px;">
                        <label>تفصیل</label>
                        <textarea class="form-control" rows="3" id="Edetails" name="Remarks"><?= $transaction[0]->Remarks ?></textarea>
                    </div>
                </div>
                <div class="col-xs-6">
                </div>
            </div>
            <div class="row" style="padding: 10px;">
                <?php $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,17);
                if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[2] == '1')){?>
                    <button type="submit"  class="btn btn-primary data-save">محفوظ کریں</button> <?php }?>
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
                                <th style="width: 17%;">چیک نمبر</th>
                                <th style="width: 13%;">ڈیپوزٹ سلپ نمبر</th>
                                <th style="width: 150px;">تفصیل</th>
                                <th style="width: 60px;">تدوین</th>
                            </tr>
                            </thead>
                            <tbody class="toEdit">
                            <?php $count = 0;?>
                            <?php foreach($transaction as $trans):?>
                                <tr class="addnew">
                                    <td>
                                        <input class="form-control" name="Account[]" type="text" style="width: 100%" readonly value='<?= $trans->AccountName;?>'>
                                    </td>
                                    <td>
                                        <input class="form-control debit" name="Debit[]" type="text" readonly style="width: 100%" id="debit" value='<?= $trans->Debit;?>' >
                                    </td>
                                    <td>
                                        <input class="form-control credit" name="Credit[]" type="text" readonly style="width: 100%" id="credit" value='<?= $trans->Credit;?>' >
                                    </td>
                                    <td>
                                        <input class="form-control ChequeNumber" name="ChequeNumber[]" type="text" readonly style="width: 100%" id="ChequeNumber" value='<?= $trans->ChequeNumber;?>' >
                                    </td>
                                    <td style="display: none">
                                        <input class="form-control ChequeDate" name="ChequeDate[]" type="text" readonly style="width: 100%" value='<?= $trans->ChequeDate;?>' >
                                    </td>
                                    <td>
                                        <input class="form-control recieved" name="bdepositSlipNo[]" type="text" style="width: 100%" readonly id="depositSlipNo" value="<?= $trans->DepositSlipNo;?>" >
                                    </td>
                                    <td style="display: none">
                                        <input type="hidden" id="" name="bdepositDate[]" value="<?= $transaction[0]->DepositDateG?>">
                                    </td>
                                    <td class="center">
                                        <textarea class="form-control" rows="1" name="Description[]" id="details" readonly><?= $trans->Description;?></textarea>
                                    </td>
                                    <td style="display: none">
                                        <input type="hidden" class="accountId" name="AccountID[]" id="account_Id" value='<?= $trans->AccountID;?>'>
                                    </td>
                                    <td style="display: none">
                                        <input type="hidden" class="accountType" name="" value='<?= $trans->AccountType;?>'>
                                    </td>
                                    <td style="display: none">
                                        <input type="hidden" class="accountNo" name="" value='<?= $trans->AccountCode;?>'>
                                    </td>
                                    <td style="display: none">
                                        <input type="hidden" class="chequeType" name="" value='<?= $trans->ChequeType;?>'>
                                    </td>
                                    <td style="display: none">
                                        <input type="hidden" class="depositType" name="" value='<?= $trans->DepositType;?>'>
                                    </td>
                                    <td style="display: none">
                                        <input type="hidden" class="bankName" name="" value='<?= $trans->BankName;?>'>
                                    </td>
                                    <td class="center">
                                        <button type="button" class="btn btn-info btn-circle edit" id="toEdit<?= ++$count;?>" ><i class="fa fa-plus"></i></button>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>کل:</th>
                                <th><span id="totald">0</span></th>
                                <th><span id="totalc">0</span></th>
                                <th></th>
                                <th></th>
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
<div id="edit" class="modal fade" tabindex="-1" role="dialog"  aria-labelledby="gridModalLabel" aria-hidden="true">
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
                                <select style="width: 100%;padding-top: 0px;padding-bottom: 0px;" id="EditaccountName" class="form-control" readonly><!-- <select style="width: 100%;" id="accountName" class="js-example-basic-multiple form-control"> -->
                                    <option></option>
                                    <?php foreach($accounts as $account): ?>
                                        <option value="<?= $account->id;?>"><?= $account->AccountName; ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 ">
                            <div class="form-group">
                                <label class="control-label" for="inputSuccess" >اکاونٹ کا کوڈ </label>
                                <select style="width: 100%;padding-top: 0px;padding-bottom: 0px;" id="EditaccountCode"  class="form-control" readonly>
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
                                <input class="form-control recieved" id="Editrecieved" onkeyup="recBlur(this)" type="number" style="width: 100%" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="inputSuccess" >کریڈٹ</label>
                                <input class="form-control payment" id="Editpayment"  onkeyup="payBlur(this)"  type="number" style="width: 100%" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="chequeData2">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="inputSuccess" >ڈیپوزٹ سلپ نمبر</label>
                                <input class="form-control depositSlipNo" id="depositSlipNo" name="depositSlipNo"  type="text" style="width: 100%" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="inputSuccess" >ڈپازٹ کی تاریخ</label>
                                <input class="form-control depositDate" id="depositDate" name="depositDate"  type="date" style="width: 100%" value="<?= date('Y-m-d'); ?>" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="chequeData">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="inputSuccess" >چیک نمبر</label>
                                <input class="form-control chequeno" id="Editchequeno" name="chequeno"  type="text" style="width: 100%" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group ">
                                <label class="control-label" for="inputSuccess" >چیک کی تاریخ</label>
                                <input class="form-control chequedate" id="chequedate" name="chequedate"  type="date" style="width: 100%" value="<?= date('Y-m-d'); ?>" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="chequeData4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="inputSuccess" >بینک کا نام</label>
                                <input class="form-control BankName" id="BankName" name="BankName"  type="text" style="width: 100%" readonly>
                            </div>
                        </div>
                    </div>
<!--                     <label class="control-label chequeData3" for="inputSuccess" >چیک کی قسم</label>
                    <div class="row chequeData3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="radio-inline"><input type="radio" value="transfer" name="ChequeType" checked disabled="true">منتقل</label>
                                <label class="radio-inline"><input type="radio" value="clearing" name="ChequeType" disabled="true">کلیرنگ</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="radio-inline"><input type="radio" value="outofcity" name="ChequeType" disabled="true">آوٹ سٹی</label>
                                <label class="radio-inline"><input type="radio" value="deposit" name="ChequeType" disabled="true">آن لائن</label>
                            </div>
                        </div>
                    </div>
                    <div class="row chequeData5">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="radio-inline"><input type="radio" value="0" name="DepositType" checked disabled="true">نقد</label>
                                <label class="radio-inline"><input type="radio" value="1" name="DepositType" disabled="true">چیک</label>
                            </div>
                        </div>
                    </div> -->
                    <div class="row ser">
                        <div class="col-md-6 ">
                            <div class="form-group">
                                <label class="control-label" for="inputSuccess" >تفصیل</label>
                                <textarea class="form-control" rows="3" id="EditEdetailss" name="details"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="detailsEdit" class="btn btn-primary detailsEdit">محفوظ کریں</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var idToEdit='';
    $('.edit').on('click',function(){
        idToEdit = $(this).attr('id');
        var accountId =  $(this).parents('tr').find('#account_Id').val();
        var debit = $(this).parents('tr').find('#debit').val();
        var credit = $(this).parents('tr').find('#credit').val();
        var details = $(this).parents('tr').find('#details').val();
        var chequeno = $(this).parents('tr').find('#ChequeNumber').val();
        var slipNo = $(this).parents('tr').find('#depositSlipNo').val();
        var Type = $(this).parents('tr').find('.accountType').val();
        var AccountCode = $(this).parents('tr').find('.accountNo').val();
        var BankName = $(this).parents('tr').find('.bankName').val();
        var DepositType = $(this).parents('tr').find('.depositType').val();
        var ChequeType = $(this).parents('tr').find('.chequeType').val();
        var str = AccountCode;
        var res = str[0];

        $('#EditaccountName').val(accountId).attr("selected");
        $('#EditaccountCode').val(accountId).attr("selected");
        $('#Editrecieved').val(debit);
        $('#Editpayment').val(credit);
        $('#EditEdetailss').val(details);
        $('#Editchequeno').val(chequeno);
        $('#EditdepositSlipNo').val(slipNo);
        $('#BankName').val(BankName);
        $('input[name=DepositType]').val(DepositType).attr("checked","true");
        $('input[name=ChequeType]').val(ChequeType).attr("checked","true");

        if(Type == '2'){
            $('#chequeData').show();
            $('.chequeData3').hide();
            $('#chequeData4').hide();
            $('.chequeData5').hide();
        }else if(res == 4 && Type != 2){
            $('.chequeData3').show();
            $('#chequeData4').show();
            $('.chequeData5').show();
            $('#chequeData').show();
        }else{
            $('.chequeData3').hide();
            $('#chequeData4').hide();
            $('.chequeData5').hide();
            // $('.chequeData').show();
            $('#chequeData').hide();
            $('#chequeData2').hide();
        }
    });

    $('.detailsEdit').on('click',function(){
        var newDetails = $('#EditEdetailss').val();
        $('tbody.toEdit').find('#'+idToEdit).parents('tr').find('#details').text(newDetails);
        $(this).attr("data-dismiss","modal");
    });

    $( function() {
        $(".addAcc").click(function () {
            $("#gridSystemModal").modal('show');
        });

        $(".edit").click(function () {
            $("#edit").modal('show');
        });

        $('#gridSystemModal').on('hidden.bs.modal', function (e) {
            $(this)
                .find("input,textarea")
                .val('')
                .end()
                .find("input[type=number]")
                .prop("disabled", false)
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

                $('#totald').text(dsum);
                $(this).find('.credit').each(function () {
                    var credit = $(this).val();
                    if (!isNaN(credit) && credit.length !== 0) {
                        csum += parseFloat(credit);
                    }
                });
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
            $.ajax({
                type:'GET',
                url:'<?= site_url('Accounts/Books/getAccountCode')?>'+'/'+id,
                success:function(response){
                    var data = $.parseJSON(response);
                    post.name = data._name;
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
                    post.type = data._type;
                    $('tbody').append('<tr class="addnew"><td><input class="form-control recieved" name="Account[]" type="text" style="width: 100%" readonly value="'+post.name+'"  ></td><td><input class="form-control debit" name="Debit[]" type="text" readonly style="width: 100%" value='+post.debit+' ></td><td><input class="form-control credit" name="Credit[]" type="text" readonly style="width: 100%" value='+post.credit+' ></td><td class="center"><textarea class="form-control"   rows="1" name="Description[]" readonly >'+post.details+'</textarea></td><td style="display: none"><input type="hidden" class="accountId" name="AccountID[]" value='+post.a_id+'></td><td style="display: none"><input type="hidden" class="accountType" name="" value='+post.type+'></td><td class="center"><button type="button" class="btn btn-info btn-circle del" ><i class="fa fa-minus"></i></button></td></tr>');
                }
            });
            $(this).attr("data-dismiss","modal");
        }
        $('.addAcc').attr('autofocus','true');
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

    $("form").submit(function( event ) {
        var bookType = '<?= $transaction[0]->VoucherType;?>';
        var typeArr = [];

        if(bookType == "CR"){
            var depart = $('#departId').val();
            var paid = $('#paidTo').val();
            var engDate = $('.EenglishDate').val();
            var islDate = $('#EislamicDate').val();
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

            $('tbody tr').each(function () {
                var accType = $(this).find('.accountType').val();
                var debit = $(this).find('.debit').val();
                var credit = $(this).find('.credit').val();

                if(accType == 1 && !debit){
                    new PNotify({
                        title: 'انتباہ',
                        text: "کیش بک ڈیبٹ ہونی چاہئے",
                        type: 'error',
                        delay: 3000,
                        hide: true
                    });
                    event.preventDefault();
                }
                if (debit == 0 && credit ==0){
                    new PNotify({
                        title: 'انتباہ',
                        text: "رقم اندراج کریں!!!",
                        type: 'error',
                        delay: 3000,
                        hide: true
                    });
                    event.preventDefault();
                }
            });
            var check = "";

            typeArr.forEach(function(e){
                if(e == 1){
                    check = 1;
                }
            });
            if(check == 1){
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
                }else{
                    return;
                }
            }else{
                new PNotify({
                    title: 'انتباہ',
                    text: "کم از کم ایک کیش بک درکار ہے",
                    type: 'error',
                    delay: 3000,
                    hide: true
                });
                event.preventDefault();
            }
        }else if(bookType == "CP"){
            var engDate = $('.EenglishDate').val();
            var islDate = $('#EislamicDate').val();

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

            $('tbody tr').each(function () {
                var accType = $(this).find('.accountType').val();
                var credit = $(this).find('.credit').val();
                var debit = $(this).find('.debit').val();

                if(accType == 1 && !credit){
                    new PNotify({
                        title: 'انتباہ',
                        text: "کیش بک کریڈٹ ہونی چاہئے",
                        type: 'error',
                        delay: 3000,
                        hide: true
                    });
                    event.preventDefault();
                }
                if (debit == 0.00 && credit == 0.00){
                    new PNotify({
                        title: 'انتباہ',
                        text: "رقم اندراج کریں!!!",
                        type: 'error',
                        delay: 3000,
                        hide: true
                    });
                    event.preventDefault();
                }
            });

            var check = "";

            typeArr.forEach(function(e){
                if(e == 1){
                    check = 1;
                }
            });
            if(check == 1){
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
                }else{
                    return;
                }
            }else{
                new PNotify({
                    title: 'انتباہ',
                    text: "کم از کم ایک کیش بک درکار ہے",
                    type: 'error',
                    delay: 3000,
                    hide: true
                });
                event.preventDefault();
            }
        }
    });

    $(window).on('load',function( event ) {
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
            $('#totald').text(dsum);

            $(this).find('.credit').each(function () {
                var credit = $(this).val();
                if (!isNaN(credit) && credit.length !== 0) {
                    csum += parseFloat(credit);
                }
            });
            $('#totalc').text(csum);
        });
    });

    $('.cdelete').on('click',function(){
        var voucherNo=$('#voucher_num').val();
        var voucherType=$('#voucher_type').val();
        var levelid=$('#companyId').val();

        (new PNotify({
                title: 'تصدیق درکار',
                text: 'کیا آپ حذف کرنا چاہتے ہیں؟',
                icon: 'glyphicon glyphicon-question-sign',
                hide: false,
                type: "success",
                confirm: {
                    confirm: true,
                    buttons: [{
                        text: "ٹھیک ہے", addClass: "", promptTrigger: true, click: function (notice, value) {
                            notice.remove();
                            notice.get().trigger("pnotify.confirm", [notice, value]);
                        }
                    }, {
                        text: "منسوخ", addClass: "", click: function (notice) {
                            notice.remove();
                            notice.get().trigger("pnotify.cancel", notice);
                        }
                    }]
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
            })
        ).get().on('pnotify.confirm', function () {
            $.ajax({
                url: '<?= site_url('Accounts/Books/deleteTransaction'); ?>' + '/' + voucherNo + '/' + voucherType+ '/' + levelid,
                success: function (response) {
                    var data = $.parseJSON(response);
                    if (data['success']) {
                        new PNotify({
                            title: 'حذف',
                            text: "ٹراسیکشن حذف کامیاب",
                            type: 'success',
                            hide: true
                        });
                    }
                    setTimeout(function () {
                        window.location.href = "<?= site_url('Accounts/Books/allCashBooks/') ?>"+voucherType+'/'+levelid;
                    }, 1000);
                }
            });
        }).on('pnotify.cancel', function () {
        });
    });
</script>