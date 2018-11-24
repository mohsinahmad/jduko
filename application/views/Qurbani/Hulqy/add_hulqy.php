<form action="<?= site_url('Qurbani/Hulqy/Save')?>" method="POST">
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
        <?php endif; $Missing_Book_Penalty = $Students_Income = $Advance_Payments = $hulqa_name = $supervisor_name = $id = $Package_Type = $Package_Amount = $Indivisual_Package_Type = $Indivisual_Package_Amount =0;
        isset($edit)?$hulqa_name=$Hulqy[0]->hulqa_name:$hulqa_name='';
        isset($edit)?$supervisor_name=$Hulqy[0]->supervisor_name:$supervisor_name='';
        isset($edit)?$Advance_Payments=$Hulqy[0]->Advance_Payments:$Advance_Payments='';
        isset($edit)?$Students_Income=$Hulqy[0]->Students_Income:$Students_Income='';
        isset($edit)?$Missing_Book_Penalty=$Hulqy[0]->Missing_Book_Penalty:$Missing_Book_Penalty='';
        isset($edit)?$Package_Type=$Hulqy[0]->Package_Type:$Package_Type='';
        isset($edit)?$Package_Amount=$Hulqy[0]->Package_Amount:$Package_Amount='';
        isset($edit)?$Indivisual_Package_Type=$Hulqy[0]->Indivisual_Package_Type:$Indivisual_Package_Type='';
        isset($edit)?$Indivisual_Package_Amount=$Hulqy[0]->Indivisual_Package_Amount:$Indivisual_Package_Amount='';
        isset($edit)?$id=$Hulqy[0]->id:$id='';
        isset($edit)?$CreatedOn=$Hulqy[0]->CreatedOn:$CreatedOn='';
        isset($edit)?$CreatedBy=$Hulqy[0]->CreatedBy:$CreatedBy='';
        ?>
        <div class="heading">
            <br>
            <h1 class="page-header" style="margin-top: 10px;">حلقے </h1>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="form-group">
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label class="control-label" for="inputSuccess">حلقہ</label>
                            <input class="form-control" id="" name="hulqa_name" style="width: 250px;" value="<?= $hulqa_name?>" type="text" autofocus required>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label class="control-label" for="inputSuccess">نگران</label>
                            <input class="form-control" id="" name="supervisor_name" style="width: 250px;" value="<?= $supervisor_name?>" type="text" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
<!--                    <div class="col-xs-4">-->
<!--                        <div class="form-group">-->
<!--                            <label class="control-label" for="inputSuccess">منظور شدہ پیشگی رقم</label>-->
<!--                            <input class="form-control" id="" name="Advance_Payments" style="width: 250px;" value="--><?//= $Advance_Payments?><!--" type="text">-->
<!--                        </div>-->
<!--                    </div>-->
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label class="control-label" for="inputSuccess">انعامی رقم در طلبہ</label>
                            <input class="form-control" id="" name="Students_Income" style="width: 250px;" value="<?= $Students_Income?>" type="text">
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label class="control-label" for="inputSuccess">کٹوتی بابت گم شدہ رسید بک</label>
                            <input class="form-control" id="" name="Missing_Book_Penalty" style="width: 250px;" value="<?= $Missing_Book_Penalty?>" type="text">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
            <?php if($checkType[0]->Common_Package == 1){?>
                <div class="col-xs-2">
                    <div class="form-group package_type" style="margin-top: 22%;">
                    <?php if($Package_Type == 1){?>
                        <label class="control-label" for="inputSuccess">
                        <input type="radio" name="Package_Type" style="width: 15px;" value="1" autofocus checked> فیصد</label>
                        <label class="control-label" for="inputSuccess">
                        <input type="radio" name="Package_Type" style="width: 15px;" value="0" autofocus>رقم</label>
                        <?php } elseif($Package_Type == 0){?>
                        <label class="control-label" for="inputSuccess">
                        <input type="radio" name="Package_Type" style="width: 15px;" value="1" autofocus> فیصد</label>
                        <label class="control-label" for="inputSuccess">
                        <input type="radio" name="Package_Type" style="width: 15px;" value="0" autofocus checked>رقم</label>
                        <?php }?>
                    </div>
                </div>
                <div class="col-xs-3">
                    <div class="form-group package_amount">
                        <label class="control-label" for="inputSuccess">انعامی رقم</label>
                        <input class="form-control" type="text" name="Package_Amount" style="width: 200px;" value="<?=$Package_Amount?>">
                    </div>
                </div>
                <?php }?>
                <div class="col-xs-2">
                    <div class="form-group" style="margin-top: 22%;margin-right: -21%;">
                        <input name="extraamount" class="extraInaam" type="checkbox"  value="1">اضافی انعام
                    </div>
                </div>
                <input type="hidden" id="Is_Idv" value="<?= $Indivisual_Package_Type?>">
                <div class="col-xs-2" style="margin-right: -11%;">
                    <div class="form-group Indivisual_Package_Type" style="margin-top: 22%;">
                    <?php if($Indivisual_Package_Type == 1){?>
                        <label class="control-label" for="inputSuccess">
                        <input type="radio" name="Indivisual_Package_Type" style="width: 15px;" value="1" autofocus checked> فیصد</label>
                        <label class="control-label" for="inputSuccess">
                        <input type="radio" name="Indivisual_Package_Type" style="width: 15px;" value="0" autofocus>رقم</label>
                    <?php }elseif($Indivisual_Package_Type == 0){?>
                        <label class="control-label" for="inputSuccess">
                        <input type="radio" name="Indivisual_Package_Type" style="width: 15px;" value="1" autofocus> فیصد</label>
                        <label class="control-label" for="inputSuccess">
                        <input type="radio" name="Indivisual_Package_Type" style="width: 15px;" value="0" autofocus checked>رقم</label>
                    <?php }?>
                    </div>
                </div>
                <div class="col-xs-3">
                    <div class="form-group Indivisual_Package_Amount">
                        <label class="control-label" for="inputSuccess">اضافی انعامی رقم</label>
                        <input class="form-control IPA" type="text" name="Indivisual_Package_Amount" style="width: 200px;" value="<?=$Indivisual_Package_Amount?>">
                    </div>
                </div>
            </div>
            <?php if (isset($edit)){?>
                <input type="hidden" name="edit" value="<?= $id?>">
                <input type="hidden" name="CreatedBy" value="<?= $CreatedBy?>">
                <input type="hidden" name="CreatedOn" value="<?= $CreatedOn?>">
            <?php }?>
            <button type="submit" class="btn btn-primary ">محفوظ کریں</button>
        </div>
    </div>
</form>
<br><br><br>
 <div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h1>حلقے</h1>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <label style="float: left; margin-left: 3%;">تلاش کریں
                        <input type="text" id="myInputTextField" style="float: left; height: 6%;"></label>
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                        <tr>
                            <th style="text-align: center;width: 9%">شناخت</th>
                            <th style="text-align: center">حلقہ</th>
                            <th style="text-align: center">نگران</th>
                            <th style="text-align: center">حذف/تدوین</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($Hulqy as $value):?>
                            <tr class="odd gradeX">
                                <td><?= $value->id?></td>
                                <td><?= $value->hulqa_name?></td>
                                <td><?= $value->supervisor_name?></td>
                                <td style="width: 18%;">
                                    <button type="button" class="btn btn-danger Delete" data-id="<?= $value->id?>" style="font-size: 10px; ">حذف کریں
                                    </button>
                                    <a href="<?= site_url('Qurbani/Hulqy/index/').$value->id?>"><button type="button" class="btn btn-success" style="font-size: 10px;background-color: #517751;border-color: #517751; ">تصیح کریں
                                        </button>
                                    </a>
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
<script src="<?= base_url()."assets/js/"; ?>jquery-1.12.4.js"></script>
<script type="text/javascript">
$(document ).ready(function() {
    if ($('#Is_Idv').val() == 1){
        $('.extraInaam').attr('checked',true);
        $('.Indivisual_Package_Type').show();
        $('.Indivisual_Package_Amount').show();
    }else{
        $('.Indivisual_Package_Type').hide();
        $('.Indivisual_Package_Amount').hide();
    }

    });
    $('input[name=extraamount]').on('click',function(){
        var amount = $('input[name=extraamount]:checked').val();
        if (amount == 1) {
            $('.Indivisual_Package_Type').show();
            $('.Indivisual_Package_Amount').show();
        }else{
            $('.IPA').val("");
            $('.Indivisual_Package_Type').hide();
            $('.Indivisual_Package_Amount').hide();
        }
    });
    $('.Delete').on('click',function(){
        var id = $(this).data('id');
        (new PNotify({
                title: 'تصدیق درکار',
                text: 'کیا آپ اس حلقے کو حذف کرنا چاہتے ہیں؟',
                icon: 'glyphicon glyphicon-question-sign',
                hide: false,
                type: "success",
                confirm: {
                    confirm: true,
                    buttons: [{
                        text: "جی ہاں", addClass: "", promptTrigger: true, click: function (notice, value) {
                            notice.remove();
                            notice.get().trigger("pnotify.confirm", [notice, value]);
                        }
                    }, {
                        text: "نیہں", addClass: "", click: function (notice) {
                            notice.remove();
                            notice.get().trigger("pnotify.cancel", notice);
                        }
                    }]
                },
                buttons: {
                    closer: false,
                    sticker: false
                },
                history: {
                    history: false
                },
                addclass: 'stack-modal',
                stack: {'dir1': 'down', 'dir2': 'right', 'modal': true}
            })
        ).get().on('pnotify.confirm', function () {

            $.ajax({
                url: '<?= site_url('Qurbani/Hulqy/delete/'); ?>'+ id,
                success: function (response) {
                    var data = $.parseJSON(response);
                    if (data['success']) {
                        new PNotify({
                            title: 'حذف',
                            text: "حذف کردیا گیا۔۔۔",
                            type: 'success',
                            hide: true
                        });
                    }else if(data['exist']){
                        new PNotify({
                            title: 'حذف',
                            text: "حذف ناممکن، رسید موجود ہے۔۔!!",
                            type: 'error',
                            hide: true
                        });
                    }else{
                        new PNotify({
                            title: 'حذف',
                            text: "حذف نہیں کیا جاسکا، دوبارہ کوشش کیجیئے۔۔!!'",
                            type: 'error',
                            hide: true
                        });
                    }
                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                }
            });
        }).on('pnotify.cancel', function () {
        });
    });
</script>