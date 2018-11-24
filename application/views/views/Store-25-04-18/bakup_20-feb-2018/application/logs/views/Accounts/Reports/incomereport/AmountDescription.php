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
<form action="<?= site_url('Accounts/AmountDescription/SaveChequeCurrencyDetail/');?><?= $this->uri->segment(4);?>" method="post">
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
        <h2>رقومات تفصیل <small><span>واؤچر نمبر - </span><?= $Voucher_No[0]->VoucherNo?></small>-<small> کل رقم - <?= number_format($DebitSum[0]->Debit)?></small></h2>
        <input type="hidden" name="" id="debitsum" value="<?= $DebitSum[0]->Debit?>">
        <div class="panel-body">
            <div class="row checkdonation" id="checkdonation">
                <div class="col-xs-3">
                    <label class="control-label" for="inputSuccess">کرنسی</label>
                    <select name="Currency_Id[]" class="form-control Currency" id="" style="padding-bottom: 0px;padding-top: 0px; "  required >
                        <option value="0" data-id='0' selected>منتخب کریں</option>
                        <?php foreach ($Currency as $value){?>
                            <option class="cur" value="<?= $value->Id?>" data-id='<?= $value->Currency; ?>'>
                                <?= $value->Currency; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-xs-3" style="left: -3%">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">تعداد</label>
                        <input  class="form-control Currency_Quantity" id="" name="Currency_Quantity[]" type="number" placeholder="تعداد">
                    </div>
                </div>
                <div class="col-xs-3" style="margin-right: 6%">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">کل</label>
                        <input  class="form-control " id="total_currency_amount" name=""  type="number" placeholder="" readonly>
                    </div>
                </div>
                <div class="col-xs-3" style="float: left;margin-top: 1%;width: 17%;">
                    <div class="form-group">
                        <button type="button" class="btn btn-info btn-circle del" ><i class="fa fa-trash-o"></i></button>
                        <button type="button" class="btn btn-info btn-circle edit" id="toEdit" ><i class="fa fa-plus"></i></button>
                    </div>
                </div>
                 
            </div>
            <div class="row">
                <div class="col-xs-3"></div>
                <div class="col-xs-3" style="left: -25%">
                    <p> کل نقد</p>
                </div>
                <div class="col-xs-3" style="margin-right: 6%">
                    <input type="text" class="form-control" id="sumcurrency" readonly>
                </div>
            </div>
            <div class="row" style="padding: 10px;">
                <button type="submit"  class="btn btn-primary ">محفوظ کریں</button>
                <button type="button"  class="btn btn-primary add_desc">چیک کی تفصیل شامل کریں</button>
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
                                    <th style="width: 60px;"></th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><p style="margin-right: 85px;">کل چیک</p></td>
                                    <td><input type="text" class="form-control" id="sumcheque" value="" readonly></td>
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
                                <input class="form-control ChequeAmount" id="ChequeAmount" name=""  type="number" style="width: 100%">
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

<script type="text/javascript">

    $('.add_desc').on('click',function(){
        $("#gridSystemModal").modal('show');
    });
    var currency =0;
    var amount = 0;
    var total = 0;
    var rowtotal = 0;
    $(document).on('click','.edit',function(){
        var div = $('#checkdonation');
        var clone = div.clone(true).find('input[type=number]').val("").end().find('select').val(0).end();
        clone.find('label').hide();
        clone.insertAfter('div #checkdonation:last');

    });
    // $(document).on('blur','.Currency_Quantity', function(){
    //      amount = $(this).parents('div#checkdonation').find('.Currency_Quantity').val();
    //      currency = $(this).parents('div#checkdonation').find('.Currency').find(':selected').data('id');
    //      if (currency == 'ریزگاری') {
    //             rowtotal = parseFloat(amount);
    //             total += parseFloat(amount);
    //      }else{
    //         rowtotal = parseFloat(currency * amount);
    //         total += parseFloat(currency * amount);
    //      }
    //      $('#sumcurrency').val(total);
         
    //     $(this).parents('div#checkdonation').find('#total_currency_amount').val(rowtotal);
         
    // });
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
         // $('#sumcurrency').val(total);
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
                total = parseFloat(total) + rowtotal;
         }
         $('#sumcurrency').val(total);
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
         $('#sumcurrency').val(total);
         $(this).parents('div#checkdonation').find('#total_currency_amount').val(rowtotal);
    });
    $(document).on('mousedown','.Currency', function(){
         amount = $(this).parents('div#checkdonation').find('.Currency_Quantity').val();
         currency = $(this).find(':selected').data('id');
         // alert(currency);
         if (currency == 'ریزگاری') {
                amount = 0;
                rowtotal = parseFloat(amount);
                total = parseFloat(total) - parseFloat(amount);
         }else{
                rowtotal = parseFloat(currency * amount);
                total = parseFloat(total) - rowtotal;
         }
         // $('#sumcurrency').val(total);
         // $(this).parents('div#checkdonation').find('#total_currency_amount').val(rowtotal);
    });
    $(document).on('click','.del',function(){
        var newTotal = 0;
        var div = $(this).parents('div#checkdonation');
        var sumcurrency = $('#sumcurrency').val();
        amount = div.find('.Currency_Quantity').val();
        currency = div.find('.Currency').find(':selected').data('id');
        if (currency != '' && amount != '') {   
            if (currency == 'ریزگاری') {
                    total = amount;
                    newTotal = sumcurrency - total;
            }else{
                total = currency * amount;
                newTotal = sumcurrency - total;
            }
            $('#sumcurrency').val(newTotal);
        }
        lengthClass = $('.checkdonation').length;

        if (lengthClass === 1) {
            new PNotify({
                title: "ہوشیار",
                text: "کم سے کم ایک کالم ہونا ضروری ہے",
                type: 'error',
                hide: true
            });
        }else{
            div.closest('#checkdonation').remove();
        }
    });
    $( function(){
        $('#gridSystemModal').on('hidden.bs.modal', function (e) {
         
            var chequesum = 0;
            var chequeamount = 0;
            $('tr').each(function () {
                $(this).find('#totalchequeamount').each(function () {
                    var chequeamount = $(this).val();
                    if (!isNaN(chequeamount) && chequeamount.length !== 0) {
                        chequesum += parseFloat(chequeamount);
                    }
                });
                $('#sumcheque').val(chequesum);
            });
            $(this)
                .find("input[type=text]")
                .val('').end()
                .find("input[type=number]")
                .val('');
        });
    });
    $('.dataEdit').on('click',function(){
        var post = new Object();
        post.ChequeNumber = $('#Cheque_Number').val();
        post.ChequeDate = $('#Cheque_Date').val();
        post.BankName = $('#BankName').val();
        post.ChequeAmount = $('#ChequeAmount').val();
        post.ChequeType = $('input[name=chequetype]:checked').val();

     $('tbody').append('<tr class="AddNew"><td> <input class="form-control" name="Cheque_Number[]" type="text" style="width: 100%" readonly value="'+post.ChequeNumber+'"> </td><td><input class="form-control" name="Cheque_Date[]" type="text" style="width: 100%" readonly value="'+post.ChequeDate+'"></td><td><input class="form-control" name="Bank_Name[]" type="text" style="width: 100%" readonly value="'+post.BankName+'"></td><td><input class="form-control" name="Cheque_Type[]" type="text" style="width: 100%" readonly value="'+post.ChequeType+'"></td><td><input class="form-control" id ="totalchequeamount" name="Cheque_amount[]" type="number" style="width: 100%" readonly value="'+post.ChequeAmount+'"></td><td><button type="button" class="btn btn-info btn-circle delch" ><i class="fa fa-minus"></i></button></td></tr>');

     $('#gridSystemModal').modal('toggle');

    });

        var chequeamount;
      $( document ).on( "click",'.delch', function(e) {
            e.preventDefault();

            var tr = $(this).parents('tr');
            chequeamount = tr.find('#totalchequeamount').val();
            //alert(chequeamount);
            var cheque_sum = $('#sumcheque').val();
            var newTotal = cheque_sum - chequeamount;     
            $('#sumcheque').val(newTotal);

            $( this ).parents( "tr" ).remove();
        });

     $("form").submit(function( event ) {
        var ch_currency = 0;
        var ch_cheque = 0;
        var ch_debit = 0;
        var sum_cheque_currency = 0;
        ch_currency = $('#sumcurrency').val();
        ch_cheque = $('#sumcheque').val();
        ch_debit = $('#debitsum').val();
        if (ch_currency == 0) {
            ch_currency = 0;
        }
        if (ch_cheque == 0) {
            ch_cheque =0;
        }
        if (!(isNaN(ch_currency)) && !(isNaN(ch_cheque))){

             sum_cheque_currency  = parseFloat(ch_currency) + parseFloat(ch_cheque);
        }
        //alert(sum_cheque_currency);
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