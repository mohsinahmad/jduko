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
<form action="<?php echo site_url('Store/DemandForm/FormUpdate/');?>" method="post">
    <div class="row">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header" style="margin-top: 10px;">  ڈیمانڈ فارم </h1>

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
                } $comp_name = $this->BookModel->get_company_name($comp_id); $row = 0;?>
                <input type="hidden" name="level_id" value="<?php echo $comp_id; ?>">
                <input type="hidden" name="Form_Number" value="<?= $form_data[0]->Form_Number?>">
                <input type="hidden" name="CreatedOn" value="<?= $form_data[0]->CreatedOn?>">
                <input type="hidden" name="CreatedBy" value="<?= $form_data[0]->CreatedBy?>">
            </div>
            <div class="row" style="margin-top: 10px; margin-bottom: 10px;">
                <div class="col-xs-6">
                    <div class="form-group" style="width:250px;">
                        <label>شعبہ</label>
                        <div class="form-group">
                            <input class="form-control Department" type="text" name="" value="<?php echo $comp_name[0]->LevelName; ?>" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6" id="Markaz">
                    <div class="form-group" style="width:250px;">
                        <label>شعبہ</label>
                        <select class="form-control" style="height: 50px;" id="departId" name="Department" autofocus>
                            <option value ="<?= $form_data[0]->Department_Id ?>" selected="selected" disabled=""><?php echo $form_data[0]->DepartmentName ?></option>
                            <?php foreach($departments as $department): ?>
                                <option value="<?php echo $department->Id;?>"><?php echo $department->DepartmentName; ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                  <!--  <div class="form-group" style="width:250px;">
                        <label>ڈونیشن ٹائپ</label>
                        <select class="form-control Donation_Type" style="height: 50px;" name="Donation_type" autofocus>

                            <?php //foreach($donation_types as $key => $types): ?>
                                <option value="<?php //echo $types->Id;?>" <?php //if($donation_types[$key]->Id == $form_data[0]->Donation_Type_Id) echo 'selected="selected"'; ?> ><?php //echo $types->Donation_Type; ?></option>
                            <?php //endforeach ?>
                        </select>
                    </div>-->
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
                            <?php foreach ($form_data as $items){ ?>
                                <tr>
                                    <th><input class="form-control ItemName" name="ItemName[]" type="text" readonly value="<?= $items->name?>"></th>
                                    <th style="display: none"><input type="hidden" class="ItemId" name="Item_Id[]" value="<?= $items->Item_Id?>"></th>
                                    <th><input class="form-control Quantity" name="Item_Quantity[]"  type="text" readonly  value="<?= $items->Item_Quantity?>"></th>
                                    <th><textarea class="form-control Details"   rows="1" name="Detail[]"  readonly ><?= $items->Item_remarks?></textarea></th>
                                    <th><button type="button" class="btn btn-info btn-circle edit" id="toEdit<?= $row?>" ><i class="fa fa-pencil-square-o"></i></button><button type="button" class="btn btn-info btn-circle del" ><i class="fa fa-minus"></i></button></th>
                                </tr>
                            <?php $row++; }?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<div id="gridSystemModal" class="modal fade"  role="dialog"  aria-labelledby="gridModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="gridModalLabel"> ڈیمانڈ فارم</h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid bd-example-row">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="inputSuccess" >نام اشیاء</label>
                                <select style="width: 100%;padding-top: 0px;padding-bottom: 0px;" id="ItemName" class="form-control  js-example-basic-single">
                                    <option value="<?= $form_data[0]->Item_Id?>" disabled selected><?= $form_data[0]->name?></option>
                                    <?php foreach ($Items as $key => $item){?>
                                        <option value="<?php echo  $item['id'];?>"><?php echo $item['name'];?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 ">
                            <div class="form-group">
                                <label class="control-label" for="inputSuccess" >پیمائش کی اکائی</label>
                                <input class="form-control" min="1" id="unit" type="text" readonly style="width: 100%">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="inputSuccess" >مقدار</label>
                                <input class="form-control" id="Quantity" type="number" style="width: 100%">
                            </div>
                        </div>
                     </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="inputSuccess" >تفصیل</label>
                                <textarea class="form-control" rows="3" id="Details"></textarea>
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
</div>

<script type="text/javascript">

     $(function () {
        $('#ItemName').change(function(){
            var id = $(this).val();
            $.ajax({
                url:'<?php echo base_url()?>index.php/Store/DemandForm/get_unit_by_item',
                type:'post',
                data:{id:id},
                success:function(response){
                    $('#unit').val(response);
                }
            });
        });
    });
    $( function() {
        if (<?= $_SESSION['parent_id']?> == 101){
            $('#Markaz').show();
        }else{
            $('#Markaz').hide();
        }
        $(".addAcc").click(function () {
            var D_type = $('.Donation_Type option:selected').val();
            //if(!D_type){
//                error = 1;
//                new PNotify({
//                    title: 'انتباہ',
//                    text: "عطئے کی قسم منتخب کریں!",
//                    type: 'error',
//                    delay: 3000,
//                    hide: true
//                });
//                $(this).removeAttr("data-dismiss","modal");
           // }else{
                $('#dataInsert').show();
                $('#dataEdit').hide();
                $("#gridSystemModal").modal('show');
          //  }
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

        if(!quant){
            error = 1;
            new PNotify({
                title: 'انتباہ',
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
                title: 'انتباہ',
                text: " براہ مہربانی  اشیاء  منتخب کریں",
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
            $('tbody').append('<tr class="addnew"><td><input class="form-control ItemName" name="ItemName[]" type="text"  style="width: 100%" readonly value="'+post.name+'"  ></td><td><input class="form-control Quantity" name="Item_Quantity[]"  type="text" readonly style="width: 100%" value='+post.Quantity+' ></td><td class="center"><textarea class="form-control Details"   rows="1" name="Detail[]"  readonly >'+post.details+'</textarea></td><td style="display: none"><input type="hidden" class="ItemId" name="Item_Id[]" value='+post.Item_id+'></td><td class="center"><button type="button" class="btn btn-info btn-circle edit" id="toEdit'+(rows + 1)+'" ><i class="fa fa-pencil-square-o"></i></button><button type="button" class="btn btn-info btn-circle del" ><i class="fa fa-minus"></i></button></td></tr>');

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
                title: 'انتباہ',
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
                title: 'انتباہ',
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
</script>