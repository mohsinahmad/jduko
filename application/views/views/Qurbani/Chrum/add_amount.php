<form action="<?= site_url('Qurbani/ChrumAmount/Save')?>" method="POST">
    <div class="row">
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
        <div class="heading">
            <br>
            <h1 class="page-header" style="margin-top: 10px;">قیمت چرم </h1>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="form-group">
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label class="control-label" for="inputSuccess">چرم کی قسم</label>
                            <input class="form-control" id="" name="chrum_type[]" style="width: 250px;" value="گاےَ" type="text" required readonly>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label class="control-label" for="inputSuccess">تازہ چرم</label>
                            <input class="form-control" id="cow_latest" name="latest_amount[]" style="width: 250px;" value="" type="text" autofocus required>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label class="control-label" for="inputSuccess">باسی چرم</label>
                            <input class="form-control" id="cow_old" name="old_amount[]" style="width: 250px;" value="" type="text" autofocus required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-xs-4">
                        <div class="form-group">
                            <input class="form-control" id="" name="chrum_type[]" style="width: 250px;" value="بکرا" type="text" required readonly>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="form-group">
                            <input class="form-control" id="bakra_latest" name="latest_amount[]" style="width: 250px;" value="" type="text" autofocus required>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="form-group">
                            <input class="form-control" id="bakra_old" name="old_amount[]" style="width: 250px;" value="" type="text" autofocus required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-xs-4">
                        <div class="form-group">
                            <input class="form-control" id="" name="chrum_type[]" style="width: 250px;" value="دنبہ" type="text" required readonly>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="form-group">
                            <input class="form-control" id="dumba_latest" name="latest_amount[]" style="width: 250px;" value="" type="text" autofocus required>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="form-group">
                            <input class="form-control" id="dumba_old" name="old_amount[]" style="width: 250px;" value="" type="text" autofocus required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-xs-4">
                        <div class="form-group">
                            <input class="form-control" id="" name="chrum_type[]" style="width: 250px;" value="اونٹ" type="text" required readonly>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="form-group">
                            <input class="form-control" id="unt_latest" name="latest_amount[]" style="width: 250px;" value="" type="text" autofocus required>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="form-group">
                            <input class="form-control" id="unt_old" name="old_amount[]" style="width: 250px;" value="" type="text" autofocus required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-xs-4">
                        <div class="form-group">
                            <input class="form-control" id="" name="chrum_type[]" style="width: 250px;" value="بھینس" type="text" required readonly>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="form-group">
                            <input class="form-control" id="behns_latest" name="latest_amount[]" style="width: 250px;" value="" type="text" autofocus required>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="form-group">
                            <input class="form-control" id="behns_old" name="old_amount[]" style="width: 250px;" value="" type="text" autofocus required>
                        </div>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary ">محفوظ کریں</button>
        </div>
    </div>
</form>
<br><br><br>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h1>قیمت چرم</h1>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <label style="float: left; margin-left: 3%;">تلاش کریں
                        <input type="text" id="myInputTextField" style="float: left; height: 6%;"></label>
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                        <tr>
                            <th style="text-align: center;width: 9%">شناخت</th>
                            <th style="text-align: center">چرم کی قیسم</th>
                            <th style="text-align: center">تا زہ</th>
                            <th style="text-align: center">باسی</th>
                            <th style="text-align: center">حذف/تدوین</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($Chrum_Amount as $value):?>
                            <tr class="odd gradeX">
                                <td><?= $value->id?></td>
                                <td><?= $value->chrum_type?></td>
                                <td><?= $value->latest_amount?></td>
                                <td><?= $value->old_amount?></td>
                                <td style="width: 18%;text-align: center">
                                    <button type="button" class="btn btn-success getid" data-toggle="modal" data-target="#gridSystemModal" data-id="<?= $value->id;?>" style="font-size: 10px;background-color: #517751;border-color: #517751; ">تصیح کریں
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
<div id="gridSystemModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true" onload="myOnload()">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="gridModalLabel">چرم کی قسم میں ترمیم</h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid bd-example-row">
                    <div class="row">
                        <div class="panel-body">
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-xs-4">
                                        <div class="form-group">
                                            <label class="control-label" for="inputSuccess">چرم کی قسم</label>
                                            <input class="form-control" id="chrum_type" name="chrum_type" style="width: 165px;" value="" type="text" autofocus required>
                                        </div>
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="form-group">
                                            <label class="control-label" for="inputSuccess">تازہ چرم کی قیمت</label>
                                            <input class="form-control" id="latest_amount" name="latest_amount" style="width: 165px;" value="" type="text" autofocus required>
                                        </div>
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="form-group">
                                            <label class="control-label" for="inputSuccess">باسی چرم کی قیمت</label>
                                            <input class="form-control" id="old_amount" name="old_amount" style="width: 165px;" value="" type="text" autofocus required>
                                            <input id="id" name="id" value="" type="hidden">
                                            <input id="CreatedBy" name="CreatedBy" value="" type="hidden">
                                            <input id="CreatedOn" name="CreatedOn" value="" type="hidden">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-xs-6">
                                        <div class="checkbox" id="checkbox">
                                            <button type="button" class="btn btn-primary submitUpdate">محفوظ کریں</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url()."assets/js/"; ?>jquery-1.12.4.js"></script>
<script type="text/javascript">
    $('.getid').on('click',function(){
        var id = $(this).data('id');
        $.ajax({
            type:'GET',
            url:'<?= site_url('Qurbani/ChrumAmount/getById/')?>'+id,
            success:function (response) {
                var data = $.parseJSON(response);
                $('#chrum_type').val(data[0].chrum_type);
                $('#latest_amount').val(data[0].latest_amount);
                $('#old_amount').val(data[0].old_amount);
                $('#id').val(data[0].id);
                $('#CreatedBy').val(data[0].CreatedBy);
                $('#CreatedOn').val(data[0].CreatedOn);
            }
        });
    });

    $('.submitUpdate').on('click',function(){
        var post = new Object();
        post.chrum_type = $('#chrum_type').val();
        post.latest_amount = $('#latest_amount').val();
        post.old_amount = $('#old_amount').val();
        post.id = $('#id').val();
        post.CreatedBy = $('#CreatedBy').val();
        post.CreatedOn = $('#CreatedOn').val();
        post.edit = 234;

        $.ajax({
            type:'POST',
            url:'<?= site_url('Qurbani/ChrumAmount/Update');?>',
            data:post,
            success:function(response){
                console.log(response);
                var data = $.parseJSON(response);
                if(data['success']){
                    new PNotify({
                        title: 'کامیابی',
                        text: 'تصیح کامیاب',
                        type: 'success',
                        hide: true
                    });
                    setTimeout(function(){
                        location.reload();
                    },2000);
                }else{
                    new PNotify({
                        title: 'انتباہ',
                        text: 'تدوین ناکام',
                        type: 'error',
                        hide: true
                    });
                    setTimeout(function(){
                        location.reload();
                    },2000);
                }
            }
        });
    });

    $('#cow_latest').on('keyup',function(){
        var cow_latest = $(this).val();
        var cal = (cow_latest * 25) / 100;
        $('#cow_old').val(cal);
    });
    $('#bakra_latest').on('keyup',function(){
        var bakra_latest = $(this).val();
        var cal = (bakra_latest * 25) / 100;
        $('#bakra_old').val(cal);
    });
    $('#dumba_latest').on('keyup',function(){
        var dumba_latest = $(this).val();
        var cal = (dumba_latest * 25) / 100;
        $('#dumba_old').val(cal);
    });
    $('#unt_latest').on('keyup',function(){
        var unt_latest = $(this).val();
        var cal = (unt_latest * 25) / 100;
        $('#unt_old').val(cal);
    });
    $('#behns_latest').on('keyup',function(){
        var behns_latest = $(this).val();
        var cal = (behns_latest * 25) / 100;
        $('#behns_old').val(cal);
    });
</script>