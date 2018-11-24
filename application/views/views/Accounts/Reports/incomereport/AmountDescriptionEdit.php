<link rel="stylesheet" href="<?= base_url()."assets/"; ?>css/jquery-ui.css">
<script src="<?= base_url()."assets/"; ?>js/jquery-1.12.4.js"></script>
<script src="<?= base_url()."assets/"; ?>js/jquery-ui.js"></script>
<script src="<?= base_url()."assets/"?>js/select2.min.js"></script>
<style>
    div.dataTables_info{
        display: none;
    }
    div.dataTables_paginate{
        display: none;
    }div.dataTables_length label{
         display: none;
     }
</style>
<form action="<?= site_url('Accounts/AmountDescription/EditChequeCurrencyDetail/');?><?= $this->uri->segment(4);?>" method="post">
    <div class="row">
        <div>
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
        <?php endif ?>
        </div>
        <h2>رقومات تفصیل - تدوین <small><span>واؤچر نمبر - </span><?php echo $Voucher_No[0]->VoucherNo?> - کل رقم - <?= number_format($DebitSum[0]->Debit)?></small></h2>
        <input type="hidden" name="" id="debitsum" value="<?= $DebitSum[0]->Debit?>">
        <?php if (isset($Cheque[0]->CreatedBy)){
            $CreatedOn = $Cheque[0]->CreatedOn;
            $CreatedBy = $Cheque[0]->CreatedBy;
        }else{
            $CreatedOn = $Currency[0]->CreatedOn;
            $CreatedBy = $Currency[0]->CreatedBy;
        }?>
        <input type="hidden" name="CreatedBy" id="" value="<?= $CreatedOn?>">
        <input type="hidden" name="CreatedOn" id="" value="<?= $CreatedBy?>">

        <div class="panel-body">

        <?php if(!$Currency == 0 ){
        foreach($Currency as $key => $itme){?>
                <div class="row checkdonation" id="checkdonation">
                <div class="col-xs-3">
                    <div class="form-group">
                        <?php if($key <= 0){?>
                            <label class="control-label" for="inputSuccess">کرنسی</label>
                        <?php }?>
                        <select  name="Currency_Id[]" class="form-control Currency" id="" style="padding-bottom: 0px;padding-top: 0px;"  required >
                            <option value="0" data-id='0'> منتخب کریں</option>
                            <?php foreach ($currency_name as $value){?>
                                <option value="<?= $value->Id?>" data-id='<?= $value->Currency; ?>' <?= ($itme->C_ID==$value->Id)?'selected':''?> >
                                    <?= $value->Currency; ?>
                                </option>
                            <?php }?>
                        </select>
                    </div>
                </div>
                <div class="col-xs-3" style="left: -3%">
                    <div class="form-group">
                        <?php if($key <= 0){?>
                            <label class="control-label" for="inputSuccess">تعداد</label>
                        <?php }?>
                        <input  class="form-control Currency_Quantity" id="" name="Currency_Quantity[]"  type="number" value="<?= $itme->Currency_Quantity?>" placeholder="ابتدائ مقدار" required>
                    </div>
                </div>
                <div class="col-xs-3" style="margin-right: 6%">
                    <div class="form-group">
                        <?php if($key == 0){?>
                        <label class="control-label" for="inputSuccess">کل</label>
                        <?php }?>
                        <input  class="form-control " id="total_currency_amount" name=""  type="number" placeholder="" readonly>
                    </div>
                </div>

                <div class="col-xs-3" style="width: 17%;float: left;margin-top: 1%;">
                        <div class="form-group">
                            <button type="button" class="btn btn-info btn-circle del" ><i class="fa fa-trash-o"></i></button>
                            <button type="button" class="btn btn-info btn-circle edit" ><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                </div>
                
            <?php } ?>
            <div class="row">
                <div class="col-xs-3"></div>
                <div class="col-xs-3" style="left: -25%">
                    <p> کل نقد</p>
                </div>
                <div class="col-xs-3" style="margin-right: 6%">
                    <input type="text" class="form-control sumcurrency" value="<?= is_object($TotalCurrencyAmount) ? $TotalCurrencyAmount->CurrencyAmount : 0?>" readonly>
                </div>
            </div>
            <?php }else{?>
                <div class="row checkdonation" id="checkdonation">
                    <div class="col-xs-3">
                        <div class="form-group">
                            <?php if($key <= 0){?>
                                <label class="control-label" for="inputSuccess">کرنسی</label>
                            <?php }?>
                            <select  name="Currency_Id[]" class="form-control Currency" id="" style="padding-bottom: 0px;padding-top: 0px; width: 250px;"  required >
                                <option value="0" data-id='0'> منتخب کریں</option>
                               <?php foreach ($currency_name as $value){?>
                                    <option class="cur" value="<?= $value->Id?>" data-id='<?= $value->Currency; ?>'>
                                        <?= $value->Currency; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-3" style="left: -3%">
                        <div class="form-group">
                            <?php if($key <= 0){?>
                                <label class="control-label" for="inputSuccess">تعداد</label>
                            <?php }?>
                            <input  class="form-control Currency_Quantity" id="" name="Currency_Quantity[]" style="width: 250px;"  type="number" value="" placeholder="ابتدائ مقدار" required>
                        </div>
                    </div>
                    <div class="col-xs-3" style="margin-right: 6%">
                    <div class="form-group">
                        <?php if($key == 0){?>
                        <label class="control-label" for="inputSuccess">کل</label>
                        <?php }?>
                        <input  class="form-control " id="total_currency_amount" name="" style="width: 250px;"  type="number" placeholder="" readonly>
                    </div>
                </div>
                    <div class="col-xs-3" style="width: 17%;float: left;margin-top: 1%;">
                        <div class="form-group">
                            <button type="button" class="btn btn-info btn-circle del" ><i class="fa fa-trash-o"></i></button>
                            <button type="button" class="btn btn-info btn-circle edit" ><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <input type="text" class="form-control sumcurrency" readonly>
                    </div>
                </div>
            <?php }
        ?>
            <div class="row" style="padding: 10px;">
                <button type="submit" class="btn btn-primary">محفوظ کریں</button>
                <button type="button" class="btn btn-primary add_desc">چیک کی تفصیل شامل کریں</button>
                <a target="_blank" href="<?= site_url('Accounts/AmountDescription/AmountDescription_Report').'/'.$this->uri->segment(4);?>"
                <button type="button"  class="btn btn-primary">رپورٹ دیکھیں</button>
                </a>
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
                                    <th style="width: 150px;">چیک نمبر</th>
                                    <th style="width: 150px;">چیک کی تاریخ</th>
                                    <th style="width: 150px;">بینک کا نام</th>
                                    <th style="width: 17%;">چیک کی قسم</th>
                                    <th style="width: 17%;">چیک کی رقم</th>
                                    <th style="width: 74px;"></th>
                                </tr>
                            </thead>
                            <tbody class="toEdit">
                            <?php $count = 0; if(!$Cheque == 0){
                            foreach($Cheque as $values){?>
                            <tr>
                                <td><input type="number" class="form-control" id="cheque_no" name="Cheque_Number[]" value="<?= $values->Cheque_Number?>" readonly></td>

                                <td><input type="text" class="form-control" id="cheque_date" name="Cheque_Date[]" value="<?= $values->Cheque_Date?>" readonly></td>

                                <td><input type="text" class="form-control" id="bank_name" name="Bank_Name[]" value="<?= $values->Bank_Name?>" readonly></td>

                                <td><input type="text" class="form-control" id="cheque_type" name="Cheque_Type[]"  value="<?= $values->Cheque_Type?>" readonly></td>

                                <td><input type="text" class="form-control totalchequeamount" id="cheque_amount" name="Cheque_amount[]" value="<?= $values->Cheque_amount?>"  readonly></td>
                                <td>
                                <button type="button" class="btn btn-info btn-circle delch" ><i class="fa fa-minus"></i></button>
                                <button type="button" class="btn btn-info btn-circle data_edit" id="toEdit<?= ++$count;?>" ><i class="fa fa-plus"></i></button>
                                </td>
                            </tr>

                            <?php }?>
                            <?php }?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><p style="margin-right: 85px;">کل چیک</p></td>
                                    <td>
                                    <input type="text" class="form-control sumcheque" value="<?= is_object($TotalChequeAmount) ? $TotalChequeAmount->TotalCheque : 0?>" readonly>
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<div id="gridSystemModal" class="modal fade"  role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="gridModalLabel">چیک کی تفصیل </h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid bd-example-row">
                      <div class="row" id="chequeData">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="inputSuccess" >چیک نمبر</label>
                                <input class="form-control chequeno" id="Cheque_Number" name=""  type="text" style="width: 100%">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="inputSuccess" >چیک کی تاریخ</label>
                                <input class="form-control chequedate" id="Cheque_Date" name=""  type="date" style="width: 100%" value="<?= date('Y-m-d'); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row" id="chequeData4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="inputSuccess" >بینک کا نام</label>
                                <input class="form-control BankName" id="BankName" name=""  type="text" style="width: 100%">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="inputSuccess" >چیک کی رقم</label>
                                <input class="form-control ChequeAmount" id="ChequeAmount" name=""  type="text" style="width: 100%">
                            </div>
                        </div>
                    </div>
                    <label class="control-label chequeData3" for="inputSuccess" >چیک کی قسم</label>
                    <div class="row chequeData3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="radio-inline"><input type="radio" class="Cheque_Type" value="transfer" name="chequetype" checked>منتقل</label>
                                <label class="radio-inline"><input type="radio" class="Cheque_Type" value="clearing" name="chequetype">کلیرنگ</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="radio-inline"><input type="radio" class="Cheque_Type" value="outofcity" name="chequetype">آوٹ سٹی</label>
                                <label class="radio-inline"><input type="radio" class="Cheque_Type" value="deposit" name="chequetype">آن لائن</label>
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
<div id="Edit" class="modal fade"  role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="gridModalLabel">چیک کی تفصیل </h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid bd-example-row">
                      <div class="row" id="chequeData">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="inputSuccess" >چیک نمبر</label>
                                <input class="form-control chequeno" id="EditChequeNo" name=""  type="text" style="width: 100%">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="inputSuccess" >چیک کی تاریخ</label>
                                <input class="form-control chequedate" id="EditChequeDate" name=""  type="date" style="width: 100%" value="<?= date('Y-m-d'); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row" id="chequeData4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="inputSuccess" >بینک کا نام</label>
                                <input class="form-control BankName" id="EditBankName" name=""  type="text" style="width: 100%">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="inputSuccess" >چیک کی رقم</label>
                                <input class="form-control ChequeAmount" id="EditChequeAmount" name=""  type="text" style="width: 100%">
                            </div>
                        </div>
                    </div>
                    <label class="control-label chequeData3" for="inputSuccess" >چیک کی قسم</label>
                    <div class="row chequeData3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="radio-inline"><input type="radio" class="Cheque_Type" value="transfer" name="Editchequetype" checked>منتقل</label>
                                <label class="radio-inline"><input type="radio" class="Cheque_Type" value="clearing" name="Editchequetype">کلیرنگ</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="radio-inline"><input type="radio" class="Cheque_Type" value="outofcity" name="Editchequetype">آوٹ سٹی</label>
                                <label class="radio-inline"><input type="radio" class="Cheque_Type" value="deposit" name="Editchequetype">آن لائن</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="dataEdit" class="btn btn-primary edit_value">محفوظ کریں</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    
    var idToEdit='';
    $('#data-table').on('click', '.data_edit', function(){
        idToEdit = $(this).attr('id');
        var cheque_no =  $(this).parents('tr').find('#cheque_no').val();
        var cheque_date =  $(this).parents('tr').find('#cheque_date').val();
        var bank_name =  $(this).parents('tr').find('#bank_name').val();
        var cheque_type =  $(this).parents('tr').find('#cheque_type').val();
        var cheque_amount =  $(this).parents('tr').find('#cheque_amount').val();

        $('#EditChequeNo').val(cheque_no);
        $('#EditChequeDate').val(cheque_date);
        $('#EditBankName').val(bank_name);
        $('#EditChequeAmount').val(cheque_amount);
        $('input[name=Editchequetype][value="'+cheque_type+'"]').prop('checked',true);
    });

    $('.edit_value').on('click',function(){
        var newcheque_no = $('#EditChequeNo').val();
        var newcheque_date = $('#EditChequeDate').val();
        var newbank_name = $('#EditBankName').val();
        var newcheque_amount = $('#EditChequeAmount').val();
        var newcheque_type = $("input[name='Editchequetype']:checked").val();

         $('tbody.toEdit').find('#'+idToEdit).parents('tr').find('#cheque_no').val(newcheque_no);
         $('tbody.toEdit').find('#'+idToEdit).parents('tr').find('#cheque_date').val(newcheque_date);
         $('tbody.toEdit').find('#'+idToEdit).parents('tr').find('#bank_name').val(newbank_name);
         $('tbody.toEdit').find('#'+idToEdit).parents('tr').find('#cheque_amount').val(newcheque_amount);
         $('tbody.toEdit').find('#'+idToEdit).parents('tr').find('#cheque_type').val(newcheque_type);

        $('.edit_value').attr('disabled',true);

        $('#Edit').modal('toggle');
    });

    $('.add_desc').on('click',function(){
        $("#gridSystemModal").modal('show');
    });
     $(document).on('click','.data_edit',function(){
        $("#Edit").modal('show');
        $('.edit_value').removeAttr('disabled');
    });
    var currency = 0;
    var amount = 0;
    var previousCurreny = $('.sumcurrency').val();
    var previousAmount = $('.sumcheque').val();
    var previousTotal = parseFloat(previousCurreny) +  parseFloat(previousAmount);
    var rowtotal = 0;
    if(!previousCurreny){
        var total = 0;
    }else{
        var total = previousCurreny;
    }
    $(document).on('click','.edit',function(){
        var div = $('#checkdonation');
        var clone = div.clone(true).find('input[type=number]').val("").end().find('select').val(0).end();
        clone.find('label').hide();
        clone.insertAfter('div #checkdonation:last');

    });
    $(function(){
        $('div#checkdonation').each(function () {
            amount = $(this).find('.Currency_Quantity').val();
            currency = $(this).find('.Currency').find(':selected').data('id');
            if (currency == 'ریزگاری') {
                rowtotal = parseFloat(amount);
            }else{
                rowtotal = parseFloat(currency * amount);
            };
            $(this).find('#total_currency_amount').val(rowtotal);
        });
    });
    $(document).on('focus','.Currency_Quantity', function(){
         amount = $(this).parents('div#checkdonation').find('.Currency_Quantity').val();
        if(!amount){
            amount = 0;
         }
         currency = $(this).parents('div#checkdonation').find('.Currency').find(':selected').data('id');
         if (currency == 'ریزگاری') {
                rowtotal = parseFloat(amount);
                total = parseFloat(total) - parseFloat(amount);
         }else{
                rowtotal = parseFloat(currency * amount);
                total = parseFloat(total) - rowtotal;
         }
         // $('.sumcurrency').val(total);
         // $(this).parents('div#checkdonation').find('#total_currency_amount').val(rowtotal);
    });
    $(document).on('blur','.Currency_Quantity', function(){
         amount = $(this).parents('div#checkdonation').find('.Currency_Quantity').val();
         currency = $(this).parents('div#checkdonation').find('.Currency').find(':selected').data('id');
         if (currency == 'ریزگاری') {
                rowtotal = parseFloat(amount);
                total = parseFloat(total) + parseFloat(amount);
         }else{
                rowtotal = parseFloat(currency * amount);
                total = parseFloat(total) + parseFloat(currency * amount);
         }
         $('.sumcurrency').val(total);
         $(this).parents('div#checkdonation').find('#total_currency_amount').val(rowtotal);
    });
    $(document).on('change','.Currency', function(){
         amount = $(this).parents('div#checkdonation').find('.Currency_Quantity').val();
          if(!amount){
            amount = 0;
         }
         currency = $(this).find(':selected').data('id');
         if (currency == 'ریزگاری') {
                rowtotal = parseFloat(amount);
                total = parseFloat(total) + parseFloat(amount);
         }else{
                rowtotal = parseFloat(currency * amount);
                total = parseFloat(total) + rowtotal;
         }
         $('.sumcurrency').val(total);
         $(this).parents('div#checkdonation').find('#total_currency_amount').val(rowtotal);
    });
    $(document).on('mousedown','.Currency', function(){
         amount = $(this).parents('div#checkdonation').find('.Currency_Quantity').val();
         currency = $(this).find(':selected').data('id');
         // alert(currency);
         if (currency == 'ریزگاری') {
                rowtotal = parseFloat(amount);
                total = parseFloat(total) - parseFloat(amount);
         }else{
                
                rowtotal = parseFloat(currency * amount);
                total = parseFloat(total) - rowtotal;
         }
         // $('.sumcurrency').val(total);
         // $(this).parents('div#checkdonation').find('#total_currency_amount').val(rowtotal);
    });
    $(document).on('click','.del',function(){
        var newTotal = 0;
        var div = $(this).parents('div#checkdonation');
        var sumcurrency = $('.sumcurrency').val();
        amount = div.find('.Currency_Quantity').val();
        currency = div.find('.Currency').find(':selected').data('id');


        if (currency == 'ریزگاری') {
                total = amount;
                newTotal = sumcurrency - total;
         }else{
            total = currency * amount;
            newTotal = sumcurrency - total;
         }
         $('.sumcurrency').val(newTotal);
        lengthClass = $('.checkdonation').length;

        if (lengthClass === 1) {
            div.find('.Currency_Quantity').val('');
            div.find('.Currency').val(0);
            div.find('#total_currency_amount').val('');
            $('.sumcurrency').val('');
            total = 0;
        }else{
            div.closest('#checkdonation').remove();
        }
    });
    var chequesum = 0;
    var chequeamount = 0;
    // var previousAmount = $('#sumcheque').val();
    if(!previousAmount){
        var chequesum = 0;
    }else{
        var chequesum = previousAmount;
    }
    $( function(){
        $('#gridSystemModal').on('hidden.bs.modal', function (e) {

            chequesum = 0;
            $('tr').each(function () {
                // $(this).find('.totalchequeamount').each(function () {
                    var chequeamount = $(this).find('.totalchequeamount').val();
                    if (!isNaN(chequeamount) && chequeamount.length !== 0) {
                        chequesum = parseFloat(chequesum) + parseFloat(chequeamount);
                    }
                // });
            });
            $('.sumcheque').val(chequesum);
            $(this)
            .find("input[type=text]")
            .val('');
        });
        $('#Edit').on('hidden.bs.modal', function (e) {
            // alert("asd");
            chequesum = 0;
            $('tr').each(function () {
                // $(this).find('.totalchequeamount').each(function () {
                    var chequeamount = $(this).find('.totalchequeamount').val();
                    if (!isNaN(chequeamount) && chequeamount.length !== 0) {
                        chequesum = parseFloat(chequesum) + parseFloat(chequeamount);
                    }
                // });
            });
            $('.sumcheque').val(chequesum);
        });
    });
    $('.dataEdit').on('click',function(){
        var post = new Object();
        post.ChequeNumber = $('#Cheque_Number').val();
        post.ChequeDate = $('#Cheque_Date').val();
        post.BankName = $('#BankName').val();
        post.ChequeAmount = $('#ChequeAmount').val();
        post.ChequeType = $('input[name=chequetype]:checked').val();
        var rows = $('#data-table tbody tr').length;
     $('tbody').append('<tr class="AddNew"><td> <input class="form-control" name="Cheque_Number[]" id="cheque_no" type="text" style="width: 100%" readonly value="'+post.ChequeNumber+'"> </td><td><input class="form-control" id="cheque_date" name="Cheque_Date[]" type="text" style="width: 100%" readonly value="'+post.ChequeDate+'"></td><td><input class="form-control" name="Bank_Name[]" type="text" style="width: 100%" readonly id="bank_name" value="'+post.BankName+'"></td><td><input class="form-control" name="Cheque_Type[]" id="cheque_type" type="text" style="width: 100%" readonly value="'+post.ChequeType+'"></td><td><input class="form-control  totalchequeamount" name="Cheque_amount[]" id="cheque_amount" type="number" style="width: 100%" readonly value="'+post.ChequeAmount+'"></td><td><button type="button" class="btn btn-info btn-circle delch" ><i class="fa fa-minus"></i></button><button type="button" class="btn btn-info btn-circle data_edit" id="toEdit'+(rows + 1)+'" ><i class="fa fa-plus"></i></button></td><</tr>');

        $('#gridSystemModal').modal('toggle');
    });
    var chequeamount;

      $( document ).on( "click",'.delch', function(e) {
        e.preventDefault();

        var tr = $(this).parents('tr');
        chequeamount = tr.find('.totalchequeamount').val();
        // alert(chequeamount);
        var cheque_sum = $('.sumcheque').val();
        var newTotal = cheque_sum - chequeamount;     
        $('.sumcheque').val(newTotal);

        $( this ).parents( "tr" ).remove();
    });

     $("form").submit(function( event ) {
        var ch_currency = 0;
        var ch_cheque = 0;
        var ch_debit = 0;
        var sum_cheque_currency = 0;
        ch_currency = $('.sumcurrency').val();
        ch_cheque = $('.sumcheque').val();
        ch_debit = $('#debitsum').val();
        if (ch_currency == 0) {
            ch_currency = 0;
        }
        if (ch_cheque == 0) {
            ch_cheque =0;
        }
        if (!(isNaN(ch_currency) && ch_currency.length !== 0) && !(isNaN(ch_cheque) && ch_currency.length !== 0)){

             sum_cheque_currency  = parseFloat(ch_currency) + parseFloat(ch_cheque);
        }
        if(ch_debit != sum_cheque_currency){
                new PNotify({
                    title: 'انتباہ',
                    text: "براہ مہربانی صحیح اعداد و شمار درج کریں",
                    type: 'error',
                    delay: 1000,
                    hide: true
                });
                event.preventDefault();
            }
     });
</script>