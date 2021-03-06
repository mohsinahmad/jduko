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
    #res {
        margin: 0px;
        padding-left: 0px;
        width: 150px;
    }
    #res li {
        list-style-type: none;
    }
    #res li:hover {
        cursor: pointer;
    }
</style>
<form action="<?= site_url('Accounts/Books/UpdateTransactions/');?><?= $this->uri->segment(4);?>/<?= $this->uri->segment(5);?>/<?= $this->uri->segment(6);?>" method="post">
    <div class="row">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <?php $booktype = $transaction[0]->VoucherType;
                    if($booktype == 'BR'){?>
                        <h1 class="page-header" style="margin-top: 10px;">   بینک وصولی - عارضی واؤچر<span style="font-size: 26px;"> (تدوین - <?= $transaction[0]->VoucherType.'-'.$transaction[0]->VoucherNo?>)</span> </h1>
                    <?php }else{?>
                        <h1 class="page-header" style="margin-top: 10px;">   بینک ادائیگی - عارضی واؤچر<span style="font-size: 26px;"> (تدوین - <?= $transaction[0]->VoucherType.'-'.$transaction[0]->VoucherNo?>)</span> </h1>
                    <?php }?>
                    <input type="hidden" id="url" value="<?= $this->uri->segment(3);?>">
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group" id="shooba" style="width: 250px; align-content: center;">
                        <label class="control-label" for="inputSuccess">شعبہ</label>
                        <select class="form-control" style="height: 50px;" id="departId" name="DepartmentId">
                            <option value = ""> منتخب کریں</option>
                            <?php foreach($departments as $department): ?>
                                <option value="<?= $department->Id;?>" <?= $transaction[0]->DepartmentId == $department->Id ? 'selected' : "" ?> ><?= $department->DepartmentName; ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">بنام</label>
                        <input  class="form-control" id="paidTo" name="PaidTo" value='<?= $transaction[0]->PaidTo ?>' style="width: 250px;"  type="text">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group" style="width:250px;">
                        <label>شمسی تاریخ</label>
                        <div class="form-group">
                            <input class="form-control englishDate" type="date" id="datepicker" name="VoucherDateG" value="<?= $transaction[0]->VoucherDateG ?>" placeholder="انگرزیی کی تاریخ منتخب کریں">
                        </div>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">قمری تاریخ</label>
                        <input class="form-control islamicDate" id="EislamicDate" name="VoucherDateH" value="<?= $transaction[0]->VoucherDateH?>" style="width: 250px;"  type="date" readonly>
                    </div>
                </div>
                <input type="hidden" id="bcompanyId" name="LevelID" value="<?= $this->uri->segment(5);?>">
                <input type="hidden" name="VoucherNo" id="voucher_num" value="<?=  $transaction[0]->VoucherNo?>">
                <input type="hidden"  name="Permanent_VoucherNumber" value="<?=  $transaction[0]->Permanent_VoucherNumber?>">
                <input type="hidden"  name="Permanent_VoucherDateH" value="<?=  $transaction[0]->Permanent_VoucherDateH?>">
                <input type="hidden"  name="Permanent_VoucherDateG" value="<?=  $transaction[0]->Permanent_VoucherDateG?>">
                <input type="hidden"  name="CreatedOn" value="<?=  $transaction[0]->CreatedOn?>">
                <input type="hidden"  name="Createdby" value="<?=  $transaction[0]->Createdby?>">
                <input type="hidden" id="voucher_type" name="VoucherType" value="<?=  $transaction[0]->VoucherType?>">
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
                <?php $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,2);
                if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[2] == '1')){?>
                    <button type="submit"  class="btn btn-primary data-save">محفوظ کریں</button>
                <?php } if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[3] == '1')){?>
                    <button type="button"  class="btn btn-primary bdelete">حذف کریں</button>
                <?php } if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[1] == '1')){?>
                    <button type="button" class="btn btn-primary addbAcc"><i class="fa fa-plus"></i>اکاؤنٹ شامل کریں</button>
                <?php }?>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body" style="padding: 0px;">
                <div class="container-fluid" >
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTables">
                            <thead>
                            <tr>
                                <th style="width: 13%;">اکاونٹ کا نام</th>
                                <th style="width: 13%;">ڈیبٹ</th>
                                <th style="width: 13%;">کریڈٹ</th>
                                <th style="width: 17%;">چیک نمبر</th>
                                <?php if ($this->uri->segment(4) == 'bp'){?>
                                        <th style="text-align: center;width: 13%;"></th>
                                <?php }else{?>
                                    <th style="text-align: center;width: 13%;">چیک کی تاریخ</th>
                                <?php }?>
                                <th style="width: 13%;">تفصیل</th>
                                <th style="width: 9%;"></th>
                            </tr>
                            </thead>
                            <tbody class="toEdit">
                            <?php $count = 0;
                            foreach($transaction as $t_key => $trans):?>
                                <tr class="addnew">
                                    <td>
                                        <input class="form-control recieved" name="Account[]" type="text" style="width: 100%" readonly value='<?= $trans->AccountName;?>' id="AccountName">
                                    </td>
                                    <td>
                                        <input class="form-control debit" name="Debit[]" type="text" readonly style="width: 100%" value='<?= $trans->Debit;?>' id= "debit" >
                                    </td>
                                    <td>
                                        <input class="form-control credit" name="Credit[]" type="text" readonly style="width: 100%" value='<?= $trans->Credit;?>' id="credit" >
                                    </td>
                                    <td>
                                        <input class="form-control ChequeNumber" name="ChequeNumber[]" type="text" style="width: 100%"  value='<?= $trans->ChequeNumber;?>' id ="ChequeNumberss" readonly>
                                    </td>
                                    <?php if($trans->Credit != 0.00 && $transaction[0]->VoucherType == 'BP') {?>
                                    <td>
                                        <input class="form-control ChequeDate" name="ChequeDate[]" type="date" style="width: 100%"  value='<?= $trans->ChequeDate;?>' id ="ChequeDatess" readonly>
                                    </td>
                                    <?php }else {?>
                                        <?php if($trans->TaxDebit == 1) {?>
                                        <td>
                                            <input class="form-control ChequeDate" name="ChequeDate[]" type="hidden" style="width: 100%"  value='<?= $trans->ChequeDate;?>' id ="ChequeDatess" readonly>
                                            <input class="form-control caldebit" id="caldebit" name="caldebit[<?=$t_key?>]" type="checkbox" style="width: 13%;margin-right: 37%" value="1" checked>
                                        </td>
                                        <?php } else {?>
                                        <td>
                                            <input class="form-control ChequeDate" name="ChequeDate[]" type="hidden" style="width: 100%"  value='<?= $trans->ChequeDate;?>' id ="ChequeDatess" readonly>
                                            <input class="form-control caldebit" id="caldebit" name="caldebit[<?=$t_key?>]" type="checkbox" style="width: 13%;margin-right: 37%" value="1">
                                        </td>
                                        <?php } ?>
                                    <?php } ?>
                                    <td class="center">
                                        <textarea class="form-control"   rows="1" name="Description[]" id="details" readonly><?= $trans->Description;?></textarea>
                                    </td>
                                    <td style="display: none">
                                        <input type="hidden" class="accountType" name="" value='<?= $trans->AccountType;?>' id="acc_type">
                                    </td>
                                    <td style="display: none">
                                        <input type="hidden" name="AccountID[]" value='<?= $trans->AccountID;?>' id="account_Id">
                                    </td>

                                    <td class="center">
                                        <button type="button" class="btn btn-info btn-circle del" ><i class="fa fa-minus"></i></button>
                                        <button type="button" class="btn btn-info btn-circle edit" id="toEdit<?= ++$count;?>" ><i class="fa fa-plus"></i></button>
                                    </td>
                                </tr>
                            <?php endforeach?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th></th>
                                <th>کل <span id="totald">0</span></th>
                                <th>کل <span id="totalc">0</span></th>
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
<div id="gridSystemModal" class="modal fade" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?php $book_type =  $this->uri->segment(4);
                if($book_type == 'bp'){?>
                    <h4 class="modal-title" id="gridModalLabel"> بینک ادائیگی </h4>
                <?php }else{?>
                    <h4 class="modal-title" id="gridModalLabel">  بینک وصولی </h4>
                <?php }?>
            </div>
            <div class="modal-body">
                <div class="container-fluid bd-example-row">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label " for="inputSuccess"  >اکاونٹ کا نام</label>
                                <select style="width: 100%;padding-top: 0px;padding-bottom: 0px;" id="accountName" class="form-control js-example-basic-single" autofocus>  <!-- js-example-basic-multiple -->
                                    <option value="0"> منتخب کریں</option>
                                    <?php foreach($accounts as $account): ?>
                                        <option value="<?= $account->id;?>"><?= $account->parentName.' - '.$account->AccountName; ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 ">
                            <div class="form-group">
                                <label class="control-label" for="inputSuccess" >اکاونٹ کا کوڈ</label>
                                <select style="width: 100%;padding-top: 0px;padding-bottom: 0px;" id="accountCode" class="form-control js-example-basic-single">
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
                    <div class="row" id="chequeData">
                        <div class="col-md-6 ">
                            <div class="form-group chequeno">
                                <label class="control-label" for="inputSuccess" >چیک نمبر</label>
                                <input class="form-control chequeno" id="chequeno" name="chequeno"  type="text" style="width: 100%">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group chequedate">
                                <label class="control-label" for="inputSuccess" >چیک کی تاریخ</label>
                                <input class="form-control chequedate" id="chequedate" value="<?= date('Y-m-d'); ?>" name="chequedate"  type="date" style="width: 100%">
                            </div>
                        </div>
                    </div>
                    <div class="row ser">
                        <div class="col-md-12 ">
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
<div id="edit" class="modal fade" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?php $book_type =  $transaction[0]->VoucherType;
                if($book_type == 'BP'){?>
                    <h4 class="modal-title" id="gridModalLabel"> بینک ادائیگی </h4>
                <?php }else{?>
                    <h4 class="modal-title" id="gridModalLabel">  بینک وصولی </h4>
                <?php }?>
            </div>
            <div class="modal-body">
                <div class="container-fluid bd-example-row">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label " for="inputSuccess"  >اکاونٹ کا نام</label>
                                <select style="width: 100%;padding-top: 0px;padding-bottom: 0px;" id="EditaccountName" class="form-control js-example-basic-single" autofocus>  <!-- js-example-basic-multiple -->
                                    <option></option>
                                    <?php foreach($accounts as $account): ?>
                                        <option value="<?= $account->id;?>"><?= $account->AccountName; ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 ">
                            <div class="form-group">
                                <label class="control-label" for="inputSuccess" >اکاونٹ کا کوڈ</label>
                                <select style="width: 100%;padding-top: 0px;padding-bottom: 0px;" id="EditaccountCode"  class="form-control js-example-basic-single">
                                    <option></option>
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
                                <input class="form-control recieved" id="Editrecieved" onkeyup="recBlur(this)" type="number" style="width: 100%" >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="inputSuccess" >کریڈٹ</label>
                                <input class="form-control payment" id="Editpayment"  onkeyup="payBlur(this)"  type="number" style="width: 100%">
                            </div>
                        </div>
                    </div>
                    <div class="row chequeData">
                        <div class="col-md-6 ">
                            <div class="form-group chequeno">
                                <label class="control-label" for="inputSuccess" >چیک نمبر</label>
                                <input class="form-control Editchequeno" id="chequeno" name="chequeno"  type="text" style="width: 100%">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group chequedate">
                                <label class="control-label" for="inputSuccess" >چیک کی تاریخ</label>
                                <input class="form-control Editchequedate" id="chequedate" value="<?= date('Y-m-d'); ?>" name="chequedate"  type="date" style="width: 100%">
                            </div>
                        </div>
                    </div>
                    <div class="row ser">
                        <div class="col-md-12 ">
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
    });

    var idToEdit='';
    $( "#dataTables" ).on( "click", ".edit", function(e) {
        idToEdit = $(this).attr('id');
        //alert(idToEdit);
        var accountId =  $(this).parents('tr').find('#account_Id').val();
        var debit = $(this).parents('tr').find('#debit').val();
        if(debit == '0.00'){
            $('#Editrecieved').val(debit);
            $('#Editrecieved').attr('disabled','true');
        }else{
            $('#Editrecieved').val(debit);
        }

        var credit = $(this).parents('tr').find('#credit').val();
        if(credit == '0.00'){
            $('#Editpayment').val(credit);
            $('#Editpayment').attr('disabled','true');
        }else{
            $('#Editpayment').val(credit);
        }

        var details = $(this).parents('tr').find('#details').val();
        var ChequeNumberss = $(this).parents('tr').find('#ChequeNumberss').val();
        var ChequeDatess = $(this).parents('tr').find('#ChequeDatess').val();
        var Type = $(this).parents('tr').find('#acc_type').val();
        $('#EditaccountName').val(accountId).trigger('change');

        $('#EditaccountCode').val(accountId).trigger('change');
        //alert(accountId);
        $('#EditEdetailss').val(details);
        $('.Editchequeno').val(ChequeNumberss);
        $('.Editchequedate').val(ChequeDatess);

        if(Type == '2'){
            $('.chequeData').show();
        }else{
            $('.chequeData').hide();
        }
    });


    $('.detailsEdit').on('click',function(){
        var newDetails = $('#EditEdetailss').val();
        var newaccountname = $('#EditaccountName').val();
        var newdebit = $('#Editrecieved').val();
        var newcredit = $('#Editpayment').val();
        var newcheque = $('.Editchequeno').val();
        var newchequedate = $('.Editchequedate').val();

        var post = new Object();
        post.name = '';

        $.ajax({
            type: 'POST',
            dataType:  'json',
            url:'<?= site_url('Accounts/Books/getAccountCode')?>'+'/'+newaccountname,
            success:function(response){
                post.name = response._name;
                post.type = response._type;
                $('tbody.toEdit').find('#'+idToEdit).parents('tr').find('#AccountName').val(post.name);
                $('tbody.toEdit').find('#'+idToEdit).parents('tr').find('#account_Id').val(newaccountname);
                $('#accountName').val(newaccountname).attr("selected");
            }
        });
        $('tbody.toEdit').find('#'+idToEdit).parents('tr').find('#details').text(newDetails);
        $('tbody.toEdit').find('#'+idToEdit).parents('tr').find('#debit').val(newdebit);
        $('tbody.toEdit').find('#'+idToEdit).parents('tr').find('#credit').val(newcredit);
        $('tbody.toEdit').find('#'+idToEdit).parents('tr').find('#ChequeNumberss').val(newcheque);
        $('tbody.toEdit').find('#'+idToEdit).parents('tr').find('#ChequeDatess').val(newchequedate);

        $('.detailsEdit').attr('disabled',true);

        $('#edit').modal('toggle');
//        $(this).attr("data-dismiss","modal");

    });

    $( function() {
        $('.select2-search--dropdown').attr("id","asd");
        $(".addbAcc").click(function () {
            var value = $('#Edetails').val();
            $('#Edetailss').val(value);
            $("#gridSystemModal").modal('show');
            $('.chequeData').hide();
            $('#chequeData').hide();
            $('#recieved').removeAttr("disabled");
            $('#payment').removeAttr("disabled");

            $('.dataEdit').removeAttr('disabled');
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

        $('#edit').on('hidden.bs.modal', function (e) {
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
        post.debit ="";
        post.credit ="";
        post.name = "";
        var error = "";
        var rec = $('#recieved').val();
        var pay = $('#payment').val();
        var id = $('#accountName').val();
        
        if (rec == 0.00 && pay == 0.00){
            error = 1;
            new PNotify({
                title: 'انتباہ',
                text: "رقم اندراج کریں!!!",
                type: 'error',
                delay: 3000,
                hide: true
            });
        }

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

        if(id == 0){
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
            $.ajax({
                type: 'POST',
                dataType:  'json',
                url:'<?= site_url('Accounts/Books/getAccountCode')?>'+'/'+id,
                success:function(response){
                    //var data = $.parseJSON(response);
                    post.name = response._name;
                    post.type = response._type;
                }
            }).done(function() {
                post.is_debit = $('#recieved').val();
                if(!post.is_debit == ""){
                    post.debit = post.is_debit;
                    post.credit = 0.00;
                }
                post.is_credit = $('#payment').val();
                if(!post.is_credit == ""){
                    post.credit = post.is_credit;
                    post.debit = 0.00;
                }
                post.details = $('#Edetailss').val();
                post.a_id = $('#accountName').val();
                post.chequeno = $('.chequeno').val();
                post.chequedate = $('#chequedate').val();

                var rows = $('#dataTables tbody tr').length;
                //alert(rows);
                if (post.type == 2){
                    $('tbody').append('<tr class="addnew"><td><input class="form-control accountname" name="Account[]" id="AccountName" type="text" style="width: 100%" readonly value="'+post.name+'"  ></td><td><input class="form-control debit" name="Debit[]" type="text" readonly style="width: 100%" value="'+post.debit+'" id="debit"></td><td><input class="form-control credit" name="Credit[]" id="credit" type="text" readonly style="width: 100%" value="'+post.credit+'"></td><td><input class="form-control recieved" name="ChequeNumber[]" type="text" style="width: 100%" readonly value="'+post.chequeno+'"></td><td><input class="form-control recieved" name="ChequeDate[]" type="date" style="width: 100%" readonly value="'+post.chequedate+'"></td><td class="center"><textarea class="form-control" id="details"  rows="1" name="Description[]" readonly >'+post.details+'</textarea></td><td style="display: none"><input type="hidden" class="accountId" name="AccountID[]" value='+post.a_id+' id="account_Id"></td><td style="display: none"><input type="hidden" class="accountType" name="" value="'+post.type+'"></td><td class="center"><button type="button" class="btn btn-info btn-circle del" ><i class="fa fa-minus"></i></button><button type="button" class="btn btn-info btn-circle edit" id="toEdit\'+(rows + 1)+\'" ><i class="fa fa-plus"></i></button></td></tr>');
                }else {
                    $('tbody').append('<tr class="addnew"><td><input class="form-control accountname" name="Account[]" id="AccountName" type="text" style="width: 100%" readonly value="'+post.name+'"  ></td><td><input class="form-control debit" name="Debit[]" type="text" readonly style="width: 100%" value="'+post.debit+'" id="debit"></td><td><input class="form-control credit" name="Credit[]" id="credit" type="text" readonly style="width: 100%" value="'+post.credit+' "></td><td><input class="form-control recieved" name="ChequeNumber[]" type="text" style="width: 100%" readonly value=""></td><td><input class="form-control recieved" name="ChequeDate[]" type="date" style="width: 100%" readonly value=""></td><td class="center"><textarea class="form-control"  id="details" rows="1" name="Description[]" readonly >'+post.details+'</textarea></td><td style="display: none"><input type="hidden" class="accountId" name="AccountID[]" value="'+post.a_id+'" id="account_Id"></td><td style="display: none"><input type="hidden" class="accountType" name="" value="'+post.type+'"></td><td class="center"><button type="button" class="btn btn-info btn-circle del" ><i class="fa fa-minus"></i></button><button type="button" class="btn btn-info btn-circle edit" id="toEdit\'+(rows + 1)+\'" ><i class="fa fa-plus"></i></button></td></tr>');
                }
                $('#gridSystemModal').modal('toggle');
            });
        }else{

            $('.dataEdit').attr('disabled',true);
        }
    });

    $( "#dataTables" ).on( "click", ".edit", function(e) {

        $("#edit").modal('show');

        $('.detailsEdit').removeAttr('disabled ')

    });

    $( "#dataTables" ).on( "click", ".del", function(e) {
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

        if(bookType == "BR"){
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

            var count = 1;
            $('tbody tr').each(function () {
                var accType = $(this).find('.accountType').val();
                var debit = $(this).find('.debit').val();
                var credit = $(this).find('.credit').val();

                if (accType == 2 && debit.length > 0){
                    count = 2;
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
            if(count == 1){
                new PNotify({
                    title: 'انتباہ',
                    text: "کم از کم ایک بنک بک ڈیبٹ ہونی چاہئے",
                    type: 'error',
                    delay: 3000,
                    hide: true
                });
                event.preventDefault();
            }

            var check = "";
            typeArr.forEach(function(e){
                if(e == 2){
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
                    text: "کم از کم ایک بنک بک درکار ہے",
                    type: 'error',
                    delay: 3000,
                    hide: true
                });
                event.preventDefault();
            }
        }else if(bookType == "BP"){
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

            var variable = 1;
            $('tbody tr').each(function () {
                var accType = $(this).find('.accountType').val();
                var credit = $(this).find('.credit').val();
                var debit = $(this).find('.debit').val();
                if (accType == 2 && credit.length > 0){
                    variable = 2;
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
            if(variable == 1){
                new PNotify({
                    title: 'انتباہ',
                    text: "کم از کم ایک بنک بک کریڈٹ ہونی چاہئے",
                    type: 'error',
                    delay: 3000,
                    hide: true
                });
                event.preventDefault();
            }

            var check = "";
            typeArr.forEach(function(e){
                if(e == 2){
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
                    text: "کم از کم ایک بنک بک درکار ہے",
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

    $('.bdelete').on('click',function(){
        var voucherNo = $('#voucher_num').val();
        var voucherType = $('#voucher_type').val();
        var levelid = $('#bcompanyId').val();
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
                url: '<?= site_url('Accounts/Books/deleteTransaction')?>' + '/' + voucherNo + '/' + voucherType+ '/' + levelid,
                success: function (response) {
                    var data = $.parseJSON(response);
                    console.log(response);
                    if (data['success']) {
                        new PNotify({
                            title: 'حذف',
                            text: "ٹراسیکشن حذف کامیاب",
                            type: 'success',
                            hide: true
                        });
                    }else{
                        new PNotify({
                            title: 'کامیابی',
                            text: "ٹراسیکشن حذف کامیاب",
                            type: 'error',
                            hide: true
                        });
                    }setTimeout(function () {
                        window.location.href = "<?= site_url('Accounts/Books/AllBooks/') ?>"+voucherType.toLowerCase()+'/'+levelid;
                    }, 1000);
                }
            });
        }).on('pnotify.cancel', function () {
        });
    });
</script>