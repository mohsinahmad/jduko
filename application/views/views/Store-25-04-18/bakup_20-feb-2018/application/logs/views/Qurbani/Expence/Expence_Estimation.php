<form action="<?= site_url('Qurbani/ExpenceEstimation/SaveExpenceEstimation')?>" method="POST">
    <div class="row">
        <?php if($this->session->flashdata('success')) :?>
            <div class="alert alert-success alert-dismissable" id="dalert">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <?= $this->session->flashdata('success');?>
            </div>
        <?php endif;
        if($this->session->flashdata('error')) :?>
            <div class="alert alert-danger alert-dismissable" id="dalert">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <?= $this->session->flashdata('error');?>
            </div>
        <?php endif ?>
        <div class="heading">
            <br>
            <h1 class="page-header" style="margin-top: 10px;">تخمینی اخراجات</h1>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-4">
                    <label class="control-label" for="inputSuccess">قربانی کا دن</label>
                    <div class="form-group">
                        <label class="radio-inline"><input class="Collection_Day" type="radio" value="1" name="Collection_Day" required checked>۱۰ ذی الحج</label>
                        <label class="radio-inline"><input class="Collection_Day" type="radio" value="2" name="Collection_Day" required>۱۱  ذی الحج</label>
                        <label class="radio-inline"><input class="Collection_Day" type="radio" value="3" name="Collection_Day" required>۱۲ ذی الحج</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label class="control-label" for="inputSuccess">گائے نمبر سے</label>
                            <input class="form-control from" name="" style="width: 250px;" value="" type="number" autofocus required>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label class="control-label" for="inputSuccess">گائے نمبر تک</label>
                            <input class="form-control to" name="" style="width: 250px;" value="" type="number" autofocus required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess"> تخمینی اخرا جات فی گاےَ</label>
                        <input class="form-control expence" name="" style="width: 250px;" value="" type="number" autofocus>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess"> گاےَ کی قیمت </label>
                        <input class="form-control cow_payment" name="" style="width: 250px;" value="" type="number" autofocus>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary ">محفوظ کریں</button>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="">
                            <thead>
                            <tr>
                                <th style="width: 16%;text-align: center">گائے نمبر</th>
                                <th style="text-align: center;width: 23%;">قیمت فی کس</th>
                                <th style="text-align: center;width: 23%;">حصص فروخت</th>
                                <th style="width: 25%;text-align: center;">تخمینی اخراجات</th>
                            </tr>
                            </thead>
                            <tbody class="getvalue">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<script src="<?= base_url()."assets/js/"; ?>jquery-1.12.4.js"></script>
<script type="text/javascript">
    $(document).on('keyup','.to',function(){
        var from = $('.from').val();
        var to = $(this).val();
        var Day = $('input[name=Collection_Day]:checked').val();
        if(to != ''){
            $.ajax({
                type:'GET',
                url: '<?php echo site_url('Qurbani/ExpenceEstimation/GetDataForEstimation')?>/'+from+'/'+to+'/'+Day,
                success:function(response){
                    var data = $.parseJSON(response);
                    $('.getvalue').html('');
                    $.each(data,function(index,value){
                        var setvalue = '<tr><td><input class="form-control" name="" style="width: 150px;" value="'+value.Code+'" type="text" readonly><input class="form-control" name="Cow_Id[]" style="width: 150px;" value="'+value.Cow_ID+'" type="hidden" ></td><td><input class="form-control per_cow_payment" name="Purchase_Amount[]" style="width: 102%;" value="'+value.per_Cow+'" type="number"></td><td><input class="form-control" name="" style="width: 102%;" value="'+value.Count+'" type="text" readonly></td><td><input class="form-control per_cow_expence" name="Estimation[]" style="width: 102%;" value="'+value.Estimation+'" type="number" required></td></tr>';
                        $('.getvalue').append(setvalue);
                    });
                }
            })
        }else{
            $('.getvalue').html('');
        }
    });
    $('.expence').on('keyup',function(){
        var expence = $(this).val();
        $('.per_cow_expence').val(expence);
    });

    $('.cow_payment').on('keyup',function(){
        var payment = $(this).val();
        $('.per_cow_payment').val(payment);
    });
</script>