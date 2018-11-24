<style type="text/css">
    .error{
        color: red;
    }
</style>
<form class="saveCategory" action="<?php echo base_url();?>Store/Category/saveCategory" method="POST" >
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
        <div class="col-xs-6" style="width: 40%">
            <div class="heading">
                <br><h1 class="page-header" style="margin-top: 10px;">آئٹم کیٹیگری</h1>
            </div>
            <div class="panel-body">
                <!-- <div class="row">
                    <div class="form-group">
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label class="control-label" for="inputSuccess">کیٹیگری کوڈ</label>
                                <input class="form-control" id="cat_code" name="Code" style="width: 250px;" value="<?= set_value('Code'); ?>" type="text" placeholder="0000" maxlength="4" autofocus required>
                            </div>
                            <?= form_error('Code','<div class="error">', '</div>'); ?>
                        </div>
                    </div>
                </div> -->
                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label class="control-label" for="inputSuccess">کیٹیگری نام</label>
                            <input class="form-control" id="parent_cat" name="Name" style="width: 250px;padding: 0px" value="<?= set_value('Name'); ?>" type="text" placeholder="کیٹیگری کا نام" required>
                            <?= form_error('Name','<div class="error">', '</div>'); ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group" style="display: none">
                          <label  class="radio-inline"><input type="radio" id="1" required name="category_type" value="1">تعمیراتی</label>
                          <label class="radio-inline"><input type="radio" id="0" name="category_type" value="0">غیرتعمیراتی</label>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary ">محفوظ کریں</button>
            </div>
        </div>
    </form>
    <div class="col-xs-6" style="padding-right: 42px; border-right: 1px solid ">
        <form action="<?php echo base_url();?>Store/Category/saveSubCategory" method="POST">
            <div class="heading">
                <br><h1 class="page-header" style="margin-top: 10px;">آئٹم سب کیٹیگری</h1>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="form-group">
                        <div class="col-xs-6">
                            <label class="control-label" for="inputSuccess">پیرنٹ کیٹیگری</label><br>
                            <select name="Parent_id" class="form-control" id="parent_category" style="padding-bottom: 0px;padding-top: 0px; width: 250px;"  required >
                            <!--<option value="" disabled selected>منتخب کریں</option>
                            <?php //foreach($categories as $category){?>
                                <option value="<?php //$category->Id?>">
                                    <?php //$category->Name; ?>
                                </option>
                            <?php //} ?>-->
                            </select>
                        </div>
                    </div>
                </div>
<!--                <div class="row">-->
<!--                    <div class="form-group">-->
<!--                        <div class="col-xs-6">-->
<!--                            <div class="form-group" style="margin-top: 6%">-->
<!--                                <label class="control-label" for="inputSuccess">سب کیٹیگری کوڈ</label>-->
<!--                                <input class="form-control" id="sub_cat" name="SubCode" style="width: 250px;"-->
<!--                                value="--><?php //echo set_value('SubCode'); ?><!--" type="text" maxlength="4" required>-->
<!--                                --><?php //echo form_error('SubCode','<div class="error">', '</div>'); ?>
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
                <div class="row">
                    <div class="form-group">
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label class="control-label" for="inputSuccess">سب کیٹیگری کا نام</label>
                                <input class="form-control" id="sub_cat" name="SubName" style="width: 250px;padding: 0px"
                                value="<?= set_value('SubName'); ?>" type="text" placeholder="سب کیٹیگری کا نام" required>
                                <?= form_error('SubName','<div class="error">', '</div>'); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary ">محفوظ کریں</button>
            </div>
        </form>
    </div>
</div>
<br><br><br>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
            <h1>کیٹیگریز</h1>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                 <label style="float: left; margin-left: 3%;">تلاش کریں
                <input type="text" id="myInputTextField" style="float: left; height: 1%;"></label>
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                        <tr>
                            <th style="width: 15%;text-align: center">کوڈ</th>
                            <th style="width: 60%;text-align: center">کیٹیگری</th>
                            <th style="width: 60%;text-align: center"> پیرنٹ</th>
                            <th style="text-align: center"></th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php foreach($allCategories as $Category):?>
                            <tr class="odd gradeX">
                                <td style="text-align: center"><?= $Category->Code; ?></td>
                                <td style="text-align: center"><?= $Category->Name; ?></td>
                                <td style="text-align: center"><?= $Category->Parent_name; ?></td>
                                <td style="width: 18%;">
                                     <button type="button" class="btn btn-danger delete_category" data-id="<?= $Category->Id;?>" style="font-size: 10px; ">حذف کریں
                                    </button>
                                    <button type="button" class="btn btn-success getid" data-toggle="modal" data-target="#gridSystemModal" data-id="<?= $Category->Id;?>" style="font-size: 10px;background-color: #517751;border-color: #517751; ">تصیح کریں
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="gridSystemModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="gridModalLabel">تدوین کیٹیگری</h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid bd-example-row">
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="form-group ">
                                <label class="control-label" for="inputSuccess"> کوڈ</label>
                                <input type="text" class="form-control" id="Code" name="Code" style="padding: 0px;" readonly>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group ">
                                <label class="control-label" for="inputSuccess"> کیٹیگری</label>
                                <input type="text" class="form-control" id="Category" name="Name">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="form-group ">
                                <label class="control-label" for="inputSuccess">پیرنٹ</label>
                                <input type="text" class="form-control" id="Parent" style="padding: 0px;" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="close" data-dismiss="modal">بند کریں</button>
                <button type="button" class="btn btn-primary update_category">محفوظ کریں</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {

        var cat_type = localStorage.getItem('cat_type');
        if(cat_type == 1)
        {
            $('#1').prop("checked", true);
        }
        else if(cat_type == 0){
            $('#0').prop("checked", true);
        }

  //      var url = $('#cat_id').attr('href')+'/index/'+caytype;
    //    $('#cat_id').attr('href',url);
        $.ajax({
            type:'get',
            url:'<?php echo base_url()?>Store/Category/bind_cat_ddl/'+cat_type+'',
            success:function(response){
               // $('#parent_category').empty();
                var result = JSON.parse(response);
                var data;
               // console.log(result);
                $.each(result,function (index,value) {
                    //console.log('sufyan');
                    result[index].id = result[index].Id;
                    delete result[index].Id;
                    result[index].text = result[index].Name;
                    delete result[index].Name;
                    var newOption = new Option(result[index].text, result[index].id, false, false);
                    $('#parent_category').append(newOption).trigger('change');
                });
                $('#parent_category').prepend('<option value="" disabled selected>منتخب کریں</option>');
                //console.log(result);
            }
    });


            });


    window.onload = myOnload;
    function myOnload(evt){
        $('.sub_Category_box').hide();
        $("#dataTables-example_filter input[class='form-control input-sm']").attr("id", "depart_name");

        $('.subCat').on('click',function(){
            $('.sub_Category_box').show();
            $('#parent_cat').attr("disabled",'true');
        });

    }
</script>