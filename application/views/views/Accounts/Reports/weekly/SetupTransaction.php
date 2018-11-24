<link rel="stylesheet" href="<?php echo base_url()."assets/"; ?>css/jquery-ui.css">
<script src="<?php echo base_url()."assets/"; ?>js/jquery-1.12.4.js"></script>
<script src="<?php echo base_url()."assets/"; ?>js/jquery-ui.js"></script>
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
<?php if($customReport == array()):?>
<form action="<?php echo site_url('Accounts/WeeklyReport/saveCustomReport');?>" method="post">
    <div class="row">
        <div>
        <?php if($this->session->flashdata('success')) :?>
            <div class="alert alert-success alert-dismissable" id="dalert">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <?php echo $this->session->flashdata('success');?>
            </div>
            <?php endif ?>
            <?php if($this->session->flashdata('error')) :?>
                <div class="alert alert-danger alert-dismissable" id="dalert">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <?php echo $this->session->flashdata('error');?>
                </div>
            <?php endif ?>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header" style="margin-top: 10px;">  سیٹ ٹرانسزیکشنز</h1>
                </div>
            </div>
            <input type="hidden" class="to" name="to" value="<?= $this->input->post('to')?>">
            <input type="hidden" class="from" name="from" value="<?= $this->input->post('from')?>">
            <input type="hidden" class="tran_of" name="tran_of" value="<?= $tran_of ?>">
            <input type="hidden" class="from" name="Report_id" value="<?= $this->input->post('report_id')?>">
            <input type="hidden" class="serial" name="serial" value="<?= $serial ?>">
            <div class="row checkdonation" id="checkdonation">
                <div class="col-lg-12">
                <?php $count = 0; ?>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="inputSuccess">ٹایئٹل</label>
                            <input type="text" class="form-control" id="title" name="Title[]" autofocus required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="inputSuccess">اکاوٰنٹ کا نام</label>
                            <select name="accounts[<?= $count ?>][]" class="js-example-basic-multiple js-states form-control account_name"  multiple="multiple" required>
                                <?php foreach ($acc as $account){?>
                                        <option value="<?= $account['id']?>" ><?= $account['AccountName'] ?></option>
                                <?php }?>
                            </select>
                        </div>
                    </div>
                    <div>
                        <input type="hidden" name="" class="count" value="<?= $count?>">
                    </div>
                    <div class="col-md-3"">
                        <div class="form-group">
                            <label class="control-label" for="inputSuccess">Amount</label>
                            <input  class="form-control newOpeningQuanity<?= $count?>" id="OpeningQuanity" name="Amount[]"   type="text" required readonly>
                        </div>
                    </div>
                    <div class="col-md-3 removeStyle" style="width: 15%;margin-top: 2%;">
                        <div class="form-group">
                            <button type="button" class="btn btn-info btn-circle getTransactions" id="toEdit" ><i class="fa fa-money"></i></button>
                            <button type="button" class="btn btn-info btn-circle del" ><i class="fa fa-trash-o"></i></button>
                            <button type="button" class="btn btn-info btn-circle edit" id="toEdit" ><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="padding: 10px;">
                <button type="submit" class="btn btn-primary ">Get Report</button>
            </div>
        </div>
    </div>
</form>
<?php else:?>
    <form action="<?php echo site_url('Accounts/WeeklyReport/updateCustomReport');?>" method="post">
    <div class="row">
        <div>
        <?php if($this->session->flashdata('success')) :?>
            <div class="alert alert-success alert-dismissable" id="dalert">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <?php echo $this->session->flashdata('success');?>
            </div>
            <?php endif ?>
            <?php if($this->session->flashdata('error')) :?>
                <div class="alert alert-danger alert-dismissable" id="dalert">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <?php echo $this->session->flashdata('error');?>
                </div>
            <?php endif ?>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header" style="margin-top: 10px;">  سیٹ ٹرانسزیکشنز</h1>
                </div>
            </div>
            <input type="hidden" class="to" name="to" value="<?= $this->input->post('to')?>">
            <input type="hidden" class="from" name="from" value="<?= $this->input->post('from')?>">
            <input type="hidden" class="tran_of" name="tran_of" value="<?= $tran_of ?>">
            <input type="hidden" class="report_id" name="Report_id" value="<?= $this->input->post('report_id')?>">
            <input type="hidden" class="serial" name="serial" value="<?= $serial ?>">
            <?php foreach($customReport as $key => $CustomReport) :?>
            <div class="row checkdonation" id="checkdonation">
                <div class="col-lg-12">
                <?php  ?>
                    <div class="col-md-3">
                        <div class="form-group">
                        <?php if ($key == 0){?>
                            <label class="control-label" for="inputSuccess">ٹایئٹل</label>
                        <?php }?>
                            <input type="text" class="form-control" id="title" name="Title[]" autofocus required value="<?= $CustomReport->Title?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                        <?php if ($key == 0){?>
                            <label class="control-label" for="inputSuccess">اکاوٰنٹ کا نام</label>
                        <?php }?>    
                            <select name="accounts[<?= $key ?>][]" class="js-example-basic-multiple js-states form-control account_name"  multiple="multiple" required>
                                <?php foreach ($acc as $account){?>
                                        <option value="<?= $account['id']?>" <?php foreach ($selectedAccs[$key] as $selectedAcc){?> <?= $selectedAcc->ChartOfAcc_id == $account['id'] ? "selected" : "" ?>  <?php }?> <?= $selectedAcc->AccountName ?> ><?= $account['AccountName'] ?></option>
                                <?php }?>
                            </select>
                        </div>
                    </div>
                    <div>
                        <input type="hidden" name="" class="count" value="<?= $key?>">
                    </div>
                    <div class="col-md-3"">
                        <div class="form-group">
                        <?php if ($key == 0){?>
                            <label class="control-label" for="inputSuccess">Amount</label>
                        <?php }?>    
                            <input  class="form-control newOpeningQuanity<?= $key?>" id="OpeningQuanity" name="Amount[]"  value="<?= $CustomReport->Amount?>" type="text" required readonly>
                        </div>
                    </div>
                    <div class="col-md-3 removeStyle" <?php if($key == 0) { ?> style="width: 15%;margin-top: 2%;"<?php }?> >
                        <div class="form-group">
                            <button type="button" class="btn btn-info btn-circle getTransactions" id="toEdit" ><i class="fa fa-money"></i></button>
                            <button type="button" class="btn btn-info btn-circle del" ><i class="fa fa-trash-o"></i></button>
                            <button type="button" class="btn btn-info btn-circle edit" id="toEdit" ><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach?>
            <div class="row" style="margin-right: 0px;">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary ">Get Report</button>
                </div>
            </div>
        </div>
    </div>
</form>
<?php endif ?>
<div class="col-lg-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h1>ٹرانسزیکشنز</h1>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                    <tr>
                        <th style="width: 15%;">اکاونٹ کا نام</th>
                        <th style="width: 20%;"> ڈیبٹ</th>
                        <th style="width: 15%;"> کریڈٹ</th>
                        <th style="width: 15%;"> تفصیل </th>
                        <th><input type="checkbox" name="" id="select_all">سب</th>
                    </tr>
                    </thead>
                    <tbody class="trans">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var debits = 0;
    var clickedRows;
    // var Report_id = $('.report_id').val();
    // $.ajax({
    //     type: 'GET',
    //     url:'<?php //echo site_url('Accounts/testController/getExpenseAccounts');?>/'+Report_id,
    //     success:function (response) {
    //         var data = $.parseJSON(response);
    //         $.each(data, function (index, value) {
    //             $('.account_name').append($('<option></option>', {
    //                 value: value['id'],
    //                 text : value['AccountName']
    //             }));
    //         });
    //     }
    // });
    $("#select_all").change(function(){  //"select all" change 
        if($(this).is(":checked")) {
            $('.trans tr').each(function(){
                debits += parseInt($(this).find('.debit').html());
            });
            $('.newOpeningQuanity'+clickedRows).val(debits);
        }else{
            $('.trans tr').each(function(){
                debits -= parseInt($(this).find('.debit').html());
            });
            $('.newOpeningQuanity'+clickedRows).val(debits);
        }
        $(".checkbox").prop('checked', $(this).prop("checked")); //change all ".checkbox" checked status

    });
    
    $(document).on('change','.checkbox',function(){
        if($(this).is(":checked")) {
            debits += parseInt($(this).parent('td').parent('tr').find('.debit').html());
            $('.newOpeningQuanity'+clickedRows).val(debits);
        }else{
            debits -= parseInt($(this).parent('td').parent('tr').find('.debit').html());
            $('.newOpeningQuanity'+clickedRows).val(debits);
        }
        //uncheck "select all", if one of the listed checkbox item is unchecked

        if(false == $(this).prop("checked")){ //if this item is unchecked

            $("#select_all").prop('checked', false); //change "select all" checked status to false

        }

        //check "select all" if all checkbox items are checked

        if ($('.checkbox:checked').length == $('.checkbox').length ){

            $("#select_all").prop('checked', true);

        }

    });


        var select = $('.js-example-basic-multiple');
    $(document).on('click','.edit',function(){
        var rows = $('.checkdonation').length;
        var div = $('#checkdonation');
        $('.trans').html('');
        $("#select_all").prop('checked', false);
        $('.js-example-basic-multiple').select2("destroy");
        var clone = div.clone(true).find('input[type=number]').val("").end();
        clone.find('input[type=text]').val("").end();
        clone.find('input[type=hidden]').val(rows).end();
        clone.find('.js-example-basic-multiple').val('').end();
        clone.find('.removeStyle').removeAttr("style");
        clone.find('#OpeningQuanity').removeClass('newOpeningQuanity0').end();
        clone.find('#OpeningQuanity').addClass('newOpeningQuanity'+rows).end();
        clone.find('label').hide();
        $('.js-example-basic-multiple').select2({
            placeholder: "منتخب کریں",
            dir: "rtl"
        });
        clone.insertAfter('div#checkdonation:last').find('.js-example-basic-multiple').select2({
            placeholder: "منتخب کریں",
            dir: "rtl"
        }).attr('name','accounts['+rows+'][]');
        debits = 0;

    });

    $(document).on('click','.del',function(){
        var div = $(this);
        lengthClass = $('.checkdonation').length;

        if (lengthClass === 1) {
            new PNotify({
                title: "ہوشیار",
                text: "Cannot remove the only remaining field!",
                type: 'error',
                hide: true
            });
        }else{
            div.closest('#checkdonation').remove();
            new PNotify({
                title: "حذف",
                text: "حذف کامیاب",
                type: 'success',
                hide: true
            });
        }
    });


    $(document).on('click','.getTransactions',function(){
        debits = 0;
        var post = new Object();
        post.to = $('.to').val();
        post.from = $('.from').val();
        post.tran_of = $('.tran_of').val();
        post.accounts = new Array();
        var count = $(this).parent('div').parent('div').siblings('div').find('.count').val();
        clickedRows = count;
        var account_ids = $(this).parent('div').parent('div').siblings('div').find('.account_name').val();
        if(!account_ids){
            alert("Bhai Account to select karo jabh transactions aenge");
        }else{    
            post.accounts.push(account_ids);
            $.ajax({
                url:'<?php echo site_url('Accounts/WeeklyReport/GetAccountTransactions')?>',
                type: "POST",
                data : post,
                success: function(result){
                    $('.trans').html(result);
                }
            });
        }
    });
</script>