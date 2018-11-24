<?php



?>
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
<form action="<?php echo site_url('Store/DemandForm/SaveDemand/');?>" method="post">
    <div class="row">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header" style="margin-top: 10px;">مطلوب اشیاء</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group" style="width:250px;">
                        <label>عیسوی تاریخ</label>
                        <div class="form-group">
                            <input class="form-control englishDate" type="text" id="datepicker" name="Form_DateG" value="<?php echo date('Y-m-d'); ?>" placeholder="انگرزیی کی تاریخ منتخب کریں">
                        </div>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">ہجری تاریخ</label>
                        <input class="form-control islamicDate DemandFormIslamicDate" id="islamicDate" name="Form_DateH" style="width: 250px;"   type="text" readonly>
                    </div>
                </div>
                <?php if (isset($_SESSION['comp_id'])){
                    $comp_id = $_SESSION['comp_id'];
                }elseif (isset($_SESSION['comp'])){
                    $comp_id = $_SESSION['comp'];
                }else{
                    $comp_id = '';
                } $comp_name = $this->BookModel->get_company_name($comp_id);
               // print_r($_SESSION);?>
                <input type="hidden" id="level_id" name="level_id" value="<?php echo $comp_id; ?>">
            </div>
            <div class="row" style="margin-top: 10px; margin-bottom: 10px;">
                <div class="col-xs-6" id="Shoba">
                    <div class="form-group" style="width:250px;">
                        <label>شعبہ</label>
                        <div class="form-group">

                            <?php //print_r($comp_name);?>

                            <input class="form-control Department" type="text" name="" value="<?php echo $comp_name[0]->LevelName; ?>" readonly>


                        </div>
                    </div>
                </div>
                <div class="col-xs-6" id="Markaz">
                    <div class="form-group" style="width:250px;">
                        <label>شعبہ</label>
                        <select class="form-control" required style="height: 50px;" id="departId" name="Department" autofocus>
                            <option value = ""> منتخب کریں</option>
                            <?php foreach($departments as $department): ?>
                                <option value="<?php echo $department->Id;?>"><?php echo $department->DepartmentName; ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group" style="width:250px;">
                        <label>عطئیے کی اقسام</label>
                        <select class="form-control Donation_Type" required style="height: 50px;" name="Donation_type" autofocus>
                            <option value = ""> منتخب کریں</option>
                            <?php foreach($donation_types as $types): ?>
                                <option value="<?php echo $types->Id;?>"><?php echo $types->Donation_Type; ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                       <!-- <label class="forn-label">قسم</label><br><br>
                        <label class="radio-inline"><input type="radio" required id="1" name="category_type" value="1">تعمیراتی</label>
                        <label class="radio-inline"><input type="radio" id="0" name="category_type" value="0">غیرتعمیراتی</label>
                  -->
                    </div>
                </div>
            </div>
            <div class="row" style="padding: 10px;">
                <button type="submit"  class="btn btn-primary data-save">محفوظ کریں</button>
                <button type="button" class="btn btn-primary  addAcc"><i class="fa fa-plus"></i>اشیاء شامل کریں</button>
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
                                <th style="width: 150px;">نام اشیاء</th>
                                <th style="width: 150px;">مقدار</th>
                                <th style="width: 150px;">تفصیل</th>
                                <th style="width: 60px;"></th>
                            </tr>
                            </thead>
                            <tbody class="toEdit">

                            </tbody>
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
                <h4 class="modal-title" id="gridModalLabel">مطلوب اشیاء</h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid bd-example-row">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="inputSuccess" >نام اشیاء</label>
                                <select style="width: 100%;padding-top: 0px;padding-bottom: 0px;" id="ItemName" class="form-control  js-example-basic-single">

                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 ">
                            <div class="form-group">
                                <label class="control-label" for="inputSuccess" >مقدار</label>
                                <input class="form-control" min="1" id="Quantity" type="number" style="width: 100%">
                             <!--Sufyan work-->   <span class="text-danger quantity_error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="inputSuccess" >تفصیل</label>
                                <textarea class="form-control" rows="3" id="Details"> تفصیل  نہیں ہے</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="dataInsert" class="btn btn-primary dataInsert">محفوظ کریں</button>
                <button type="button" id="dataEdit" class="btn btn-primary dataEdit">محفوظ کریں</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

    var cat_type = localStorage.getItem('cat_type');

    if(cat_type == 1)
    {
        $('#1').prop("checked", true);
    }
    else if(cat_type == 0){

        $('#0').prop("checked", true);
    }
//sufyan work
    $('#Quantity').keyup(function () {

        if($(this).val() == 0){
            $('.quantity_error').html('مقدار کم سے کم ایک ہوناضروری ہے');
        }
else{
            $('.quantity_error').html('مقدار کا اندراج ٹھیک ہے');
            $('.quantity_error').css('color','green');
        }
    });

$('.data-save').click(function(e){

    var tr = $('#data-table tbody tr').length;

    if(tr == 0){
        alert('برائے مہر بانی کم سے کم ایک اشیاء شامل کریں');
        e.preventDefault();
    }
});

    // sufyan work
    $( function() {

        if (<?= $_SESSION['parent_id']?> == 101){
            $('#Markaz').show();
        }else{
            $('#Markaz').hide();
        }
        $(".addAcc").click(function () {

            var c_type = localStorage.getItem('cat_type');
            var shoba = $('#departId').val();
            var atia = $("select[name='Donation_type']").val();
          //debugger;
            if(c_type == ''){
                error = 1;
                new PNotify({
                    title: 'توجہ فرمائیں۔۔!!',
                    text: "قسم منتخب کریں!",
                    type: 'error',
                    delay: 3000,
                    hide: true
                });
                $(this).removeAttr("data-dismiss","modal");
            }
//sufyan work start here
            else if(!shoba){
                error = 1;
                new PNotify({
                    title: 'توجہ فرمائیں۔۔!!',
                    text: "شعبہ منتخب کریں!",
                    type: 'error',
                    delay: 3000,
                    hide: true
                });
                $(this).removeAttr("data-dismiss","modal");

            }
            else if(!atia){
                error = 1;
                new PNotify({
                    title: 'توجہ فرمائیں۔۔!!',
                    text: "عطئیے کی اقسام منتخب کریں!",
                    type: 'error',
                    delay: 3000,
                    hide: true
                });
                $(this).removeAttr("data-dismiss","modal");

            }
//end sufyan work here
            else{
                $("#gridSystemModal").modal('show');
                $('#gridModalLabel').html('مطلوب اشیاء - '+$('.Donation_Type option:selected').html());
                $('#dataInsert').show();
                $('#dataEdit').hide();
            }
        });

        $('.addAcc').on('click',function(){
            var cat_type = localStorage.getItem('cat_type');
            //alert(cat_type);
            $.ajax({
                type:'GET',
                url:'<?php echo site_url('Store/items/GetCategoryViseItem');?>'+'/'+cat_type,
                success:function(result){
                    var data = $.parseJSON(result);
                    $('#ItemName').empty();
                    $('#ItemName').append($('<option/>', {
                        value: 0,
                        text : 'منتخب کریں'
                    }).attr('disabled',true).attr('selected',true));
                    $.each(data, function (index, value) {
                        $('#ItemName').append($('<option/>', {
                            value: value['I_ID'],
                            text : value['name']
                        }));
                    });
                }
            })
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

    $('.dataInsert').on('click',function(){
        var post = new Object();
        var error = "";
        var quant = $('#Quantity').val();
        var id = $('#ItemName').val();
        var detail = $('#Details').val();
        if(!quant || quant < 0.5){
            error = 1;
            new PNotify({
                title: 'توجہ فرمائیں۔۔!!',
                text: "مقدار انٹر کریں",
                type: 'error',
                delay: 3000,
                hide: true
            });
            $(this).removeAttr("data-dismiss","modal");
        }

        if(!id){
            error = 1;
            new PNotify({
                title: 'توجہ فرمائیں۔۔!!',
                text: " براہ مہربانی  اشیاء  منتخب کریں",
                type: 'error',
                delay: 3000,
                hide: true
            });
            $(this).removeAttr("data-dismiss","modal");
        }
        if(!detail){
            error = 1;
            new PNotify({
                title: 'توجہ فرمائیں۔۔!!',
                text: " براہ مہربانی  تفصیل کا اندراج کریں",
                type: 'error',
                delay: 3000,
                hide: true
            });
            $(this).removeAttr("data-dismiss","modal");
        }
        if(!error){
            post.name = 'Item';
            post.details = $('#Details').val();
            post.name = $('#ItemName  option:selected').text();
            post.Item_id = $('#ItemName option:selected').val();
            post.Quantity = $('#Quantity').val();
            var rows = $('#data-table tbody tr').length;
            $('tbody').append('<tr class="addnew"><td><input class="form-control ItemName" name="ItemName[]" type="text"  style="width: 100%" readonly value="'+post.name+'"  ></td><td><input class="form-control Quantity" name="Item_Quantity[]"  type="text" readonly style="width: 100%" value='+post.Quantity+' ></td><td class="center"><textarea class="form-control Details"   rows="1" name="Detail[]"  readonly >'+post.details+'</textarea></td><td style="display: none"><input type="hidden" class="ItemId" name="Item_Id[]" value='+post.Item_id+'></td><td class="center"><button type="button" class="btn btn-info btn-circle edit" id="toEdit'+(rows + 1)+'" ><i class="fa fa-plus"></i></button><button type="button" class="btn btn-info btn-circle del" ><i class="fa fa-minus"></i></button></td></tr>');

            $('#gridSystemModal').modal('toggle');
        }
    });

    $( "#data-table" ).on( "click", ".del", function(e) {
        e.preventDefault();
        var tr = $(this).parents('tr');
        $( this ).parents( "tr" ).remove();
    });


    var idToEdit='';
    $( "#data-table" ).on( "click", ".edit", function() {
        idToEdit = $(this).attr('id');

        var ItemId =  $(this).parents('tr').find('.ItemId').val();
        var details = $(this).parents('tr').find('.Details').val();
        var Quantity = $(this).parents('tr').find('.Quantity').val();

        $('#ItemName').val(ItemId).trigger('change');
        $('#Details').val(details);
        $('#Quantity').val(Quantity);

        $('#dataInsert').hide();
        $('#dataEdit').show();

        $("#gridSystemModal").modal('show');
    });

    $('.dataEdit').on('click',function(){
        var post = new Object();
        var error = "";
        var quant = $('#Quantity').val();
        var details = $('textarea#Details').val(); //$('#Details').text();
        var id = $('#ItemName option:selected').val();
        var name = $('#ItemName  option:selected').text();

        if(!quant){
            error = 1;
            new PNotify({
                title: 'توجہ فرمائیں۔۔!!',
                text: "مقدار کا اندراج کریں",
                type: 'error',
                delay: 3000,
                hide: true
            });
            $(this).removeAttr("data-dismiss","modal");
        }

        if(!id){
            error = 1;
            new PNotify({
                title: 'توجہ فرمائیں۔۔!!',
                text: " براہ مہربانی اشیاء کا انتیخاب کریں",
                type: 'error',
                delay: 3000,
                hide: true
            });
            $(this).removeAttr("data-dismiss","modal");
        }
        if(!error){
            $('tbody.toEdit').find('#'+idToEdit).parents('tr').find('.ItemName').val(name);
            $('tbody.toEdit').find('#'+idToEdit).parents('tr').find('textarea.Details').val(details);
            $('tbody.toEdit').find('#'+idToEdit).parents('tr').find('.Quantity').val(quant);
            $('tbody.toEdit').find('#'+idToEdit).parents('tr').find('.ItemId').val(id);

            $('#gridSystemModal').modal('toggle');
        }
    });


    // $('.Donation_Type').on('change',function(){
    //     var donation = $(this).val();
    //     $.ajax({
    //         type:'GET',
    //         url:'<?php echo site_url('Store/items/getDonationViseItems');?>'+'/'+donation,
    //         success:function(result){
    //             var data = $.parseJSON(result);
    //             $('#ItemName').empty();
    //             $('#ItemName').append($('<option/>', {
    //                 value: 0,
    //                 text : 'منتخب کریں'
    //             }).attr('disabled',true).attr('selected',true));
    //             $.each(data, function (index, value) {
    //                 $('#ItemName').append($('<option/>', {
    //                     value: value['Id'],
    //                     text : value['name']
    //                 }));
    //             });
    //         }
    //     })
    // });
</script>