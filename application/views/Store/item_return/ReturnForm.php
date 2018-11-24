<link href="<?= base_url()."assets/css/"?>jquery-ui.css" rel="stylesheet" >
<script src="<?= base_url()."assets/js/"?>jquery-1.12.4.js"></script>
<script src="<?= base_url()."assets/js/"?>jquery-ui.js"></script>
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
<?php if (isset($_SESSION['comp_id'])){
    $Access_level = $_SESSION['comp_id'];
}elseif (isset($_SESSION['comp'])){
    $Access_level = $_SESSION['comp'];
}else{ $Access_level = ''; }?>
<?php $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,36);
//    echo"<pre>";
//    print_r($rights);
//    exit();
if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[0] == '1') ){?>
    <form action="<?= site_url('Store/ItemReturn/Save');?>" method="post">
        <div class="row">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header" style="margin-top: 10px;">  محسوبی فارم </h1>

                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label class="control-label" for="inputSuccess">ڈیمانڈ فارم نمبر</label>
                            <input class="form-control " id="" name="Form_Number" style="width: 250px;" type="text" value="<?= $ReturnForm[0]->Form_Number;?>" readonly>
                        </div>
                    </div>
                    <div class="col-xs-6" id="Shoba">
                        <div class="form-group" style="width:250px;">
                            <label>لیول</label>
                            <div class="form-group">
                                <input class="form-control Department" type="text" name="" value="<?= isset($Quantitty[0]->LevelName)?$Quantitty[0]->LevelName:''?>" readonly>
                                <input type="hidden" name="level_id" value="<?= isset($Quantitty[0]->Level_Id)?$Quantitty[0]->Level_Id:''?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group" style="width:250px;">
                            <label>عیسوی تاریخ</label>
                            <div class="form-group">
                                <input class="form-control englishDate" type="text" id="datepicker" name="return_dateG" value="<?= $ReturnForm[0]->Issued_DateG;?>" placeholder="انگرزیی کی تاریخ منتخب کریں">
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label class="control-label" for="inputSuccess">ہجری تاریخ</label>
                            <input class="form-control islamicDate DemandFormIslamicDate" id="islamicDate" name="return_dateH" style="width: 250px;" type="text" value="<?= $ReturnForm[0]->Issued_DateH;?>" readonly>
                        </div>
                    </div>
                    <input type="hidden" name="Item_Issue_Form_Id" value="<?= $this->uri->segment(4);?>">

                </div>
                <div class="row">
                    <div class="col-xs-6" id="Markaz">
                        <div class="form-group" style="width:250px;">
                            <label>شعبہ</label>
                            <select class="form-control" style="height: 50px;" id="departId" name="Department_Id" autofocus>
                                <option value = ""> منتخب کریں</option>
                                <option value = "<?= $Quantitty[0]->Department_Id?>" selected><?= $Quantitty[0]->DepartmentName?></option>
                                <?php foreach($departments as $department): ?>
                                    <option value="<?= $department->Id;?>"><?= $department->DepartmentName; ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row" style="padding: 10px;">
                    <?php $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,36);
                    //    echo"<pre>";
                    //    print_r($rights);
                    //    exit();
                    if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[1] == '1') ){?>
                        <button type="submit"  class="btn btn-primary data-save">محفوظ کریں</button>
                    <?php }?>
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
                                <?php foreach($ReturnForm as $key => $return): ?>
                                    <tr class="ReturnForm">
                                        <td><input class="form-control" style="width: 100%" type="number" name="" value="<?= ++$key?>" readonly></td>

                                        <td><input class="form-control" style="width: 100%" type="text" name="" value="<?= $return->name?>" readonly></td>
                                        <input type="hidden" name="Item_Id[]" value="<?= $return->Item_Id?>">

                                        <td><input class="form-control Item_Quantity" style="width: 100%" type="number" name="" value="<?= $return->issue_quantity?>" readonly></td>

                                        <td><input class="form-control use_quantity" style="width: 100%" type="number" name="">
                                        </td>

                                        <td><input class="form-control remaining" style="width: 100%" type="number" name="return_quantity[]" value="0.00" readonly></td>

                                        <td><input class="form-control remarks" style="width: 100%" type="text" name="Item_remarks[]" value="<?= $return->Remarks?>"></td>

                                        <td style="display: none"><input class="form-control remarks" style="width: 100%" type="hidden" name="donation_type[]" value="<?= $return->donation_type?>"></td>

                                        <td><button type="button" class="btn btn-info btn-circle del" ><i class="fa fa-trash-o"></i></button></td>
                                    </tr>
                                <?php endforeach?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
<?php } else{?>
    <form action="<?= site_url('Store/ItemReturn/Save');?>" method="post">
        <div class="row">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header" style="margin-top: 10px;">  محسوبی فارم </h1>

                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label class="control-label" for="inputSuccess">ڈیمانڈ فارم نمبر</label>
                            <input class="form-control " id="" name="Form_Number" style="width: 250px;" type="text" value="<?= $ReturnForm[0]->Form_Number;?>" readonly>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="form-group" style="width:250px;">
                            <label>شعبہ</label>
                            <div class="form-group">
                                <input class="form-control Department" type="text" name="" value="<?= $Quantitty[0]->LevelName ?>" readonly>
                                <input type="hidden" name="level_id" value="<?= $Quantitty[0]->Level_Id?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group" style="width:250px;">
                            <label>عیسوی تاریخ</label>
                            <div class="form-group">
                                <input class="form-control englishDate" type="text" id="datepicker" name="return_dateG" value="<?= $ReturnForm[0]->Issued_DateG;?>" placeholder="انگرزیی کی تاریخ منتخب کریں" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label class="control-label" for="inputSuccess">ہجری تاریخ</label>
                            <input class="form-control islamicDate DemandFormIslamicDate" id="islamicDate" name="return_dateH" style="width: 250px;" type="text" value="<?= $ReturnForm[0]->Issued_DateH;?>" readonly>
                        </div>
                    </div>
                    <input type="hidden" name="Item_Issue_Form_Id" value="<?= $this->uri->segment(4);?>">

                </div>
                <div class="row" style="padding: 10px;">
                    <!-- <button type="submit"  class="btn btn-primary data-save">محفوظ کریں</button> -->
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
                                    <!-- <th style="width: 80px;"></th> -->
                                </tr>
                                </thead>
                                <tbody class="checkrow">
                                <?php foreach($ReturnForm as $key => $return): ?>
                                    <tr class="ReturnForm">
                                        <td><input class="form-control" style="width: 100%" type="number" name="" value="<?= ++$key?>" readonly></td>

                                        <td><input class="form-control" style="width: 100%" type="text" name="" value="<?= $return->name?>" readonly></td>
                                        <input type="hidden" name="Item_Id[]" value="<?= $return->Id?>">

                                        <td><input class="form-control Item_Quantity" style="width: 100%" type="number" name="" value="<?= $return->issue_quantity?>" readonly></td>

                                        <td><input class="form-control use_quantity" style="width: 100%" type="number" name="" readonly>
                                        </td>

                                        <td><input class="form-control remaining" style="width: 100%" type="number" name="return_quantity[]" value="0.00" readonly></td>

                                        <td><input class="form-control remarks" style="width: 100%" type="text" name="Item_remarks[]" value="<?= $return->Remarks?>" readonly></td>

                                        <!--  <td><button type="button" class="btn btn-info btn-circle del" ><i class="fa fa-trash-o"></i></button></td> -->
                                    </tr>
                                <?php endforeach?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
<?php }?>

<script type="text/javascript">
    $(function () {
        var level_id = <?= $_SESSION['parent_id']?>;
        if (level_id == 101){
            $('#Markaz').show();
        }else{
            $('#Markaz').hide();
        }
    });
    $( "#data-table" ).on( "click", ".del", function(e) {
        e.preventDefault();
        $( this ).parents( "tr" ).remove();
    });
</script>