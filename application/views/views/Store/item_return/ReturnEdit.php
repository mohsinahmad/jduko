<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
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
<form action="<?php echo site_url('');?>" method="post">
    <div class="row">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header" style="margin-top: 10px;">  محسوبی فارم تدوین </h1>
                    
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">ڈیمانڈ فارم نمبر</label>
                        <input class="form-control " id="" name="Form_Number" style="width: 250px;" type="text" value="" readonly>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group" style="width:250px;">
                        <label>شعبہ</label>
                        <div class="form-group">
                            <input class="form-control Department" type="text" name="" value="" readonly>
                            <input type="hidden" name="level_id" value="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group" style="width:250px;">
                        <label>عیسوی تاریخ</label>
                        <div class="form-group">
                            <input class="form-control englishDate" type="text" id="datepicker" name="return_dateG" value="" placeholder="انگرزیی کی تاریخ منتخب کریں">
                        </div>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">ہجری تاریخ</label>
                        <input class="form-control islamicDate DemandFormIslamicDate" id="islamicDate" name="return_dateH" style="width: 250px;" type="text" value="" readonly>
                    </div>
                </div>
                <input type="hidden" name="Item_Issue_Form_Id" value="<?php echo $this->uri->segment(4);?>">
                
            </div>
            <div class="row" style="padding: 10px;">
                <button type="submit"  class="btn btn-primary data-save">محفوظ کریں</button>
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
                                    <th style="width: 40px;">نمبر شمار</th>
                                    <th style="width: 80px;">نام اشیاء</th>
                                    <th style="width: 80px;">اجراء اشیاء کی مقدار</th>
                                    <th style="width: 80px;">تعداداستعمال</th>
                                    <th style="width: 80px;">قابل واپسی</th>          
                                    <th style="width: 80px;">کیفیت</th>
                                    <th style="width: 80px;"></th>
                                </tr>
                            </thead>
                            <tbody class="checkrow">
                            <tr class="ReturnForm">
                                <td><input class="form-control" style="width: 100%" type="number" name="" value="" readonly></td>

                                <td><input class="form-control" style="width: 100%" type="text" name="" value="" readonly></td>
                                <input type="hidden" name="Item_Id[]" value="">

                                <td><input class="form-control Item_Quantity" style="width: 100%" type="number" name="" value="" readonly></td>

                                <td><input class="form-control use_quantity" style="width: 100%" type="number" name="">
                                </td>

                                <td><input class="form-control remaining" style="width: 100%" type="number" name="return_quantity[]" value="0.00" readonly></td>

                                <td><input class="form-control remarks" style="width: 100%" type="text" name="Item_remarks[]" value=""></td>

                                <td><button type="button" class="btn btn-info btn-circle del" ><i class="fa fa-trash-o"></i></button></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>


<script type="text/javascript">
    $( "#data-table" ).on( "click", ".del", function(e) {
        e.preventDefault();
        $( this ).parents( "tr" ).remove();
    });
</script>