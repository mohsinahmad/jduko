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
<form action="<?php echo site_url('Store/StockSlip/UpdateStockSlip')?>" method="post">
    <div class="row">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header" style="margin-top: 10px;">اسٹاک وصولی سلپ تدوین</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group" style="width:250px;">
                        <label>عیسوی تاریخ خریداری مطابق رسید</label>
                        <div class="form-group">
                            <input class="form-control RenglishDate" type="text" id="datepicker" name="Purchase_dateG" value="<?php echo $slips[0]->Purchase_dateG; ?>" placeholder="انگرزیی کی تاریخ منتخب کریں">
                        </div>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">ہجری تاریخ خریداری مطابق رسید</label>
                        <input class="form-control RislamicDate" id="" name="Purchase_dateH" style="width: 250px;"  value="<?php echo $slips[0]->Purchase_dateH; ?>"  type="text" readonly>
                    </div>
                </div>
                <?php if (isset($_SESSION['comp_id'])){
                        $comp_id = $_SESSION['comp_id'];
                    }elseif (isset($_SESSION['comp'])){
                        $comp_id = $_SESSION['comp'];
                    }else{
                        $comp_id = '';
                    } $comp_name = $this->BookModel->get_company_name($comp_id); //print_r($_SESSION);?>
                <input type="hidden" id="level_id" name="level_id" value="<?php echo $comp_id; 
                ?>">
                <input type="hidden" name="StockSlipId" value="<?php echo $this->uri->segment(4);?>">
                <input type="hidden" name="SlipNo" value="<?php echo $slips[0]->Slip_number;?>">
                <input type="hidden" name="CreatedBy" value="<?php echo $slips[0]->CreatedBy;?>">
                <input type="hidden" name="CreatedOn" value="<?php echo $slips[0]->CreatedOn;?>">
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group" style="width:250px;">
                        <label>عیسوی تاریخ وصولی دراسٹور</label>
                        <div class="form-group">
                            <input class="form-control SR_englishDate" type="text" id="datepicker1" name="Item_recieve_dateG" value="<?php echo $slips[0]->Item_recieve_dateG;?>" placeholder="انگرزیی کی تاریخ منتخب کریں">
                        </div>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">ہجری تاریخ وصولی دراسٹور</label>
                        <input class="form-control SR_islamicDate " id="" name="Item_recieve_dateH" style="width: 250px;"  value="<?php echo $slips[0]->Item_recieve_dateH;?>" type="text" readonly>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top: 10px; margin-bottom: 10px;">
                <div class="col-xs-6" id="">
                    <div class="form-group" style="width:250px;">
                        <label>نام سپلائر/دکاندار</label>
                        <select class="form-control" style="height: 50px;" id="Supplier_Id" name="Supplier_Id" autofocus>
                            <?php foreach($supplier as $key => $supp):?>
                                <option value="<?php echo $supp->Id?>" <?php if($supplier[$key]->Id == $slips[0]->id) echo 'selected="selected"'; ?>><?php echo $supp->NameU?></option>
                            <?php endforeach?>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">نام خرید کنندہ</label>
                        <input class="form-control " value="<?php echo $slips[0]->Buyer_name;?>" id="" name="Buyer_name" style="width: 250px;"   type="text" >
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
                                    <th style="width: 70px;">آئٹم کوڈ</th>
                                    <th style="width: 70px;">آئٹم نام</th>
                                    <th style="width: 90px;">وزن/ناپ/تعداد</th>
                                    <th style="width: 60px;"> قیمت</th>
                                    <th style="width: 150px;">تفصیل</th>
                                    <th style="width: 60px;"> </th>
                                   <!--  <th style="width: 60px;"> </th> -->
                                </tr>
                            </thead>
                            <tbody class="toEdit">
                                <?php $count = 0 ?>
                                <?php foreach($slips as $slip) {?>
                                <tr>
                                    <td><input class="form-control code" type="text" name="code[]" value="<?php echo $slip->code?>" style="width: 100%" readonly></td>
                                    <td><input class="form-control ItemName " type="text" name="ItemName[]" value="<?php echo $slip->name?>" style="width: 100%" readonly></td>
                                    <td><input class="form-control Quantity" type="text" name="Item_quantity[]" value="<?php echo $slip->Item_quantity?>" style="width: 100%" readonly ></td>
                                    <td><input class="form-control Price"  type="text" name="Item_price[]" value="<?php echo $slip->Item_price?>" style="width: 100%" readonly></td>
                                    <td style="display: none" ><input class="form-control Itemid" type="text" name="Item_id[]" value="<?php echo $slip->Item_id?>" style="width: 100%" readonly></td>

                                    <td><textarea class="form-control Details" rows="1" style="width: 100%" name="Item_remarks[]" readonly><?php echo $slip->Item_remarks?></textarea> </td>
                                    <td class="center"><button type="button" class="btn         btn-info btn-circle del" ><i class="fa fa-minus"></i></button><button type="button" class="btn btn-info btn-circle edit" id="toEdit<?php echo ++$count?>" ><i class="fa fa-plus"></i></button></td>
                                    
                                </tr>
                                <?php } ?>    
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
                <h4 class="modal-title" id="gridModalLabel"> اسٹاک وصولی سلپ</h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid bd-example-row">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="inputSuccess" >نام اشیاء</label>
                                <select style="width: 100%;padding-top: 0px;padding-bottom: 0px;" id="ItemName" class="form-control  js-example-basic-single">
                                <!-- <option></option> -->
                                <option value="0" disabled selected>منتخب کریں</option>
                                <?php foreach($items as $value):?>
                                    <option value="<?php echo $value->Id?>"><?php echo $value->name?></option>
                                <?php endforeach?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="inputSuccess" >آئٹم کوڈ</label>
                                <select style="width: 100%;padding-top: 0px;padding-bottom: 0px;" id="code" class="form-control  js-example-basic-single">
                                <option value="0" disabled selected>منتخب کریں</option>
                                <?php foreach($items as $value):?>
                                    <option value="<?php echo $value->Id?>"><?php echo $value->code?></option>
                                <?php endforeach?>
                                </select>
                            </div>
                        </div>
                        
                    </div>
                    <div class="row">
                    <div class="col-md-6 ">
                            <div class="form-group">
                                <label class="control-label" for="inputSuccess" >مقدار</label>
                                <input class="form-control" id="Quantity" type="text" style="width: 100%">
                            </div>
                        </div>
                        <div class="col-md-6 ">
                            <div class="form-group">
                                <label class="control-label" for="inputSuccess" >قیمت</label>
                                <input class="form-control" id="Price" type="number" style="width: 100%">
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
            </div>
            <div class="modal-footer">
                <button type="button" id="dataInsert" class="btn btn-primary dataInsert">محفوظ کریں</button>
                <button type="button" id="dataEdit" class="btn btn-primary dataEdit">محفوظ کریں</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $( function() {

        $('.SR_englishDate').on('change',function(){
            var date = $(this).val();
            $.ajax({
                type:"GET",
                url:'<?php echo site_url('Accounts/Books/getDate');?>'+'/'+date,
                success:function(response){
                    var data = $.parseJSON(response);
                    $('.SR_islamicDate').val(data.date);
                }
            });
        });

        $('.RenglishDate').on('change',function(){
            var Rdate = $(this).val();
            $.ajax({
                type:"GET",
                url:'<?php echo site_url('Accounts/Books/getDate');?>'+'/'+Rdate,
                success:function(response){
                    var data = $.parseJSON(response);
                    $('.RislamicDate').val(data.date);
                }
            });
        });


        // if (<?= $_SESSION['parent_id']?> == 101){
        //     $('#Markaz').show();
        // }else{
        //     $('#Markaz').hide();
        // }

        var Rdate = $('.RenglishDate').val();
        $.ajax({
            type:"GET",
            url:'<?php echo site_url('Accounts/Books/getDate');?>'+'/'+Rdate,
            success:function(response){
                var data = $.parseJSON(response);
                $('.RislamicDate').val(data.date);
            }
        });
         var date = $('.SR_englishDate').val();
            $.ajax({
                type:"GET",
                url:'<?php echo site_url('Accounts/Books/getDate');?>'+'/'+date,
                success:function(response){
                    var data = $.parseJSON(response);
                    $('.SR_islamicDate').val(data.date);
                }
            });


        $(".addAcc").click(function () {
            var D_type = $('.Donation_Type option:selected').val();
            // if(!D_type){
            //     error = 1;
            //     new PNotify({
            //         title: 'انتباہ',
            //         text: "عطئے کی قسم منتخب کریں!",
            //         type: 'error',
            //         delay: 3000,
            //         hide: true
            //     });
            //     $(this).removeAttr("data-dismiss","modal");
            // }else{
                $('#dataInsert').show();
                $('#dataEdit').hide();
                $("#gridSystemModal").modal('show');

                $('#ItemName').on('change',function(){
                    var item = $(this).val();
                    //alert(item);
                    $.ajax({
                        type:'POST',
                        dataType: 'json',
                        url:'<?php echo site_url('Store/StockSlip/GetItemCode');?>'+'/'+item,
                        success:function(response){
                            $('#code').val(response._id);
                            $('select').trigger('change.select2');
                        }
                    })
                });

                $('#code').on('change',function(){
                    var code = $(this).val();
                    $.ajax({
                       type:'POST',
                        dataType: 'json',
                        url:'<?php echo site_url('Store/StockSlip/GetItemCode');?>'+'/'+code,
                        success:function(response){
                            $('#ItemName').val(response._id);
                            $('select').trigger('change.select2');
                        }
                    })
                });

            // }
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
                post.details = $('#Details').val();
                post.name = $('#ItemName  option:selected').text();
                post.Item_id = $('#ItemName option:selected').val();
                post.Quantity = $('#Quantity').val();
                post.code = $('#code  option:selected').text();
                post.payment = $('#Price').val();
                var rows = $('#data-table tbody tr').length;
                $('tbody').append('<tr class="addnew"><td><input class="form-control code" name="code[]" type="text"  style="width: 100%" readonly value="'+post.code+'"  ></td><td><input class="form-control ItemName" name="ItemName[]" type="text"  style="width: 100%" readonly value="'+post.name+'"  ></td><td><input class="form-control Quantity" name="Item_quantity[]"  type="text" readonly style="width: 100%" value='+post.Quantity+' ></td><td><input class="form-control Price" name="Item_price[]" type="text"  style="width: 100%" readonly value="'+post.payment+'"  ></td><td style="display: none"><input type="hidden" class="Itemid" name="Item_id[]" value='+post.Item_id+'></td><td class="center"><textarea class="form-control Details"   rows="1" name="Item_remarks[]"  readonly >'+post.details+'</textarea></td><td class="center"><button type="button" class="btn btn-info btn-circle del" ><i class="fa fa-minus"></i></button></td><td class="center"><button type="button" class="btn btn-info btn-circle edit" id="toEdit'+(rows + 1)+'" ><i class="fa fa-plus"></i></button></td></tr>');
                
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

        var ItemId =  $(this).parents('tr').find('.Itemid').val();
        //var ItemId =  $(this).parents('tr').find('.Itemid').val(); 
        var details = $(this).parents('tr').find('.Details').val();
        var Quantity = $(this).parents('tr').find('.Quantity').val();
        var Price = $(this).parents('tr').find('.Price').val(); 

        $('#ItemName').val(ItemId).trigger('change');
        $('#code').val(ItemId).trigger('change');
        $('#Details').val(details);
        $('#Quantity').val(Quantity);
        $('#Price').val(Price);

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
        var Price = $('#Price').val();

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
            $('tbody.toEdit').find('#'+idToEdit).parents('tr').find('.Price').val(Price);
            $('tbody.toEdit').find('#'+idToEdit).parents('tr').find('.Quantity').val(quant);
            $('tbody.toEdit').find('#'+idToEdit).parents('tr').find('.Itemid').val(id);

            $('#gridSystemModal').modal('toggle');
        }
    });
</script>