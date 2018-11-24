<style>
    .select2-selection__rendered {
        line-height: 28px;
    }
</style>
<?php if (isset($_SESSION['comp_id'])){
    $Access_level = $_SESSION['comp_id'];
}elseif (isset($_SESSION['comp'])){
    $Access_level = $_SESSION['comp'];
}else{
    $Access_level = '';
} $count = 0;?>
<input class="Access_level" type="hidden" value="<?= $Access_level?>">
<?php if($maddat == array()){?>
<form role ="from" action="<?= site_url('Accounts/Configuration/saveData')?>" method="POST" id="UserInput">
    <input name="reportnum" class="" type="hidden" value="<?= $report_id?>">
    <input type="hidden" name="level_id" value="<?= $Access_level ?>">
    <br><h1 style="text-align: center;">(<span style="font-size: 70%;"><?= $reportName;?></span>) مدّات کی ترتیب</h1><br><br>
    <div style="border:1px solid #eee;box-shadow:0 0 10px rgba(0, 0, 0, .15);max-width:600px;margin:auto;padding:20px;">
        <div style="line-height:10%;" class="row">
            <div class="duplicate" id="duplicate">
            <div class="col-lg-2">
                    <div class="form-group">
                        <label style="margin-bottom: 22px;" class="control-label" for="inputSuccess">S.No</label>
                        <input class="form-control" type="text" name="Sr_number[]" value="" required>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label style="margin-bottom: 22px;" class="control-label" for="inputSuccess">مد کا نام</label>
                        <input class="form-control" type="text" name="Mad_name[]" value="" required>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label style="margin-bottom: 22px;"  class="control-label" for="inputSuccess">اکاوٰنٹ کا نام</label>
                        <select name="accounts[0][]" class="js-example-basic-multiple js-states form-control account_name"  multiple="multiple" required>
                        </select>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group editButton" style="margin-top: 40%">
                        <button type="button" class="btn btn-info btn-circle del" ><i class="fa fa-trash-o"></i></button>
                        <button type="button" class="btn btn-info btn-circle edit"><i class="fa fa-plus"></i></button>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-info">محفوظ کیجیئے</button>

        <?php }else{?>
        <form role ="from" action="<?= site_url('Accounts/Configuration/update')?>" method="POST" id="UserInput">
            <input name="reportnum" class="reportnum" type="hidden" value="<?= $report_id?>">
            <input type="hidden" name="level_id" value="<?= $Access_level ?>">
            <br><h1 style="text-align: center;">(<span style="font-size: 70%;"><?= $reportName;?></span>) مدّات کی ترتیب</h1><br><br>
            <div style="border:1px solid #eee;box-shadow:0 0 10px rgba(0, 0, 0, .15);max-width:600px;margin:auto;padding:20px;">
                <div style="line-height:10%;" class="row">
                    <?php foreach ($maddat as $key => $mad){?>
                        <div class="duplicate" id="duplicate">
                            <div class="col-lg-2">
                                <div class="form-group">
                                <?php if($key == 0){?>
                                    <label style="margin-bottom: 22px;" class="control-label" for="inputSuccess">S.N0</label>
                                <?php }?>
                                    <input class="form-control" type="text" name="Sr_number[]" value="<?= $mad->Sr_number?>" required>
                                </div>
                            </div>
                            <div class="col-lg-3" style="margin-right: -4%;">
                                <div class="form-group">
                                    <?php if ($key == 0){?>
                                        <label style="margin-bottom: 22px;" class="control-label" for="inputSuccess">مد کا نام</label>
                                    <?php }?>
                                    <input class="form-control" type="text" name="Mad_name[]" value="<?= $mad->Mad_name?>" required>
                                    <input class="form-control" type="hidden" name="Mad_id[]" value="<?= $mad->Id?>" required>
                                </div>
                            </div>
                            <div class="col-lg-6" style="margin-right: -4%;">
                                <div class="form-group">
                                    <?php if ($key == 0){?>
                                        <label style="margin-bottom: 22px;"  class="control-label" for="inputSuccess">اکاوٰنٹ کا نام</label>
                                    <?php }?>
                                    <select name="accounts[<?=$key?>][]" class="js-example-basic-multiple js-states form-control account_name" data-id="<?= $key?>" multiple="multiple" >
                                        <?php foreach ($acc[$key] as $account){?>
                                            <option value="<?= $account->Chart_Of_Account_Id?>" selected><?= $account->AccountName?></option>
                                        <?php }?>
                                    </select>
                                    <input type="hidden" class="mad_id<?= $key?>" value="<?= $mad->Id?>">
                                </div>
                            </div>
                            <div class="col-lg-2" style="margin-right: -1%;">
                                <div class="form-group">
                                    <input type="hidden" class="mad_id<?= $key?>" value="<?= $mad->Id?>">
                                    <button data-id="<?= $key?>" type="button" class="btn btn-info btn-circle to_dis<?= $key ?>  del" <?php echo $accWithoutLevel[$key] != array()  ? "disabled" : "";  ?> > <i class="fa fa-trash-o"></i></button>
                                    <button type="button" class="btn btn-info btn-circle edit"><i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <button type="submit" class="btn btn-info update">محفوظ کیجیئے</button>
                <?php }?>
            </div>
        </form>
        <script src="<?= base_url()."assets/"; ?>js/jquery-1.12.4.js"></script>
        <script>

            var count = $('.duplicate').length-1;
            var Linkedcomp =  $('.Access_level').val();
            $.ajax({
                type: 'GET',
                url:'<?= site_url('Accounts/Configuration/getIncomeAccounts');?>/'+Linkedcomp,
                success:function (response) {
                    var data = $.parseJSON(response);
                    $.each(data, function (index, value) {
                        $('.account_name').append($('<option></option>', {
                            value: value.ch_id,
                            text : value.parentName +'--'+ value.AccountName
                        }));
                    });
                }
            });



            $(function(){

                $(document).on('change','.account_name',function(){
                    if($(this).val() == null){
                        var report_id = '<?= $this->uri->segment(4) ?>';
                        var key = $(this).data('id');
                        var mad_id = $(this).parents('div').find('.mad_id'+key).val();
                        $.ajax({
                            type:'GET',
                            url:'<?= site_url('Accounts/Configuration/getMaddadAcc');?>/'+mad_id+'/'+report_id,
                            success:function(response){
                                if(response){

                                }else{
                                    $('.to_dis'+key).removeAttr('disabled');
                                }
                            }
                        });
                    }
                });
            });

            $(document).on('click','.edit',function(){
                var div = $('#duplicate');
                $('.account_name').select2("destroy");
                var clone = div.clone(true).find('input[type=text]').val('').end();
                clone.find('input[type=hidden]').val("").end();
                clone.find('.account_name').val("").end();
                clone.find('label').hide();
                clone.find('.editButton').removeAttr('style');
                clone.find('.del').removeAttr('disabled');
                $('.account_name').select2({
                    placeholder: "منتخب کریں",
                    dir: "rtl"
                });
                count++;
                clone.insertAfter('div#duplicate:last').find('.account_name').select2({
                    placeholder: "منتخب کریں",
                    dir: "rtl"
                }).attr('name', 'accounts['+count+'][]');
            });

            $(document).on('click','.del',function(){
                // if(!$(this).hasAttribute('disabled')){
                    var div = $(this);
                    var report_id = '<?= $this->uri->segment(4) ?>';
                    var key = $(this).data('id');
                    var mad_id = $(this).parents('div').find('.mad_id'+key).val();

                    $.ajax({
                        type:'GET',
                        url:'<?= site_url('Accounts/Configuration/deleteMaddad');?>/'+mad_id+'/'+report_id,
                        success:function(response){
                            if(response){
                                div.closest('.duplicate').remove();
                                new PNotify({
                                    title: "حذف",
                                    text: "حذف کامیاب",
                                    type: 'success',
                                    hide: true
                                });
                            }else{
                                new PNotify({
                                    title: "حذف",
                                    text: "حذف ناکام",
                                    type: 'error',
                                    hide: true
                                });
                            }
                        }
                    });
                // }
            });
        </script>