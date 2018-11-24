
<div class="row">
    <div class="col-lg-12">
        <br>
        <h1 class="page-header" style="margin-top: 10px;">مطلوب اشیاء</h1>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading col-md-12" style="width: 100%;">
                <div class="row">
                    <div class="input-group col-md-3" style="float: left; direction: ltr;">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                        <input type="text" class="form-control" id="daterange" name="daterange" />
                    </div>
<!--                     <div class="input-group col-md-3"  style="float: left; direction: ltr; margin-left: 1%;">
                        <label class="input-group-addon">Form No</label>
                        <input class="form-control AccountCode" placeholder="تلاش اکاؤنٹ کوڈ" style="width: 100%; margin-right: 2%;"  type="text" >
                    </div> -->
                    <input type="hidden" id="to" name="to">
                    <input type="hidden" id="from" name="from">
                    <button class="btn btn-default search" style="float: left; direction: ltr;  margin-left: 2%; margin-right: 2%; ">تلاش کریں</button>
                    <div class="input-group col-md-3"  style="float: left; direction: ltr;">
                        <input class="form-control voucherNo"  placeholder="تلاش فارم نمبر "  type="text" >
                        <label class="input-group-addon">فارم نمبر </label>
                    </div>
                    <?php $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,24);
                    if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[1] == '1')){?>
                        <div class="col-md-3" style="width: 26%;" >
                            <a href="<?= site_url('Store/DemandForm/AddForm')?>"><button type="button"  class="btn btn-default">نیا اندراج</button></a>
                        </div>
                    <?php }?>
                </div>
            </div>
            <div class="panel-body">
                <div class="table-responsive" style="width: 100%;">
                    <label style="float: left;">تلاش کریں
                        <input type="text" id="myInputTextField" style="float: left;">
                    </label>
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <tr>
                                <th style="text-align: center;width: 60.021px;"><?php echo 'نمبر شمار';?></th>
                                <th style="text-align: center;width: 85.021px;">ڈیمانڈ فارم نمبر</th>
                                <th style="text-align: center">شعبے کا نام</th>
                                <th style="text-align: center">عیسوی تاریخ</th>
                                <th style="text-align: center">ہجری تاریخ</th>
                                <th style="width: 10%;text-align: center">درجه</th>
                                <th style="width: 13%;text-align: center">تدوین / پرنٹ</th>
                            </tr>
                        </thead>
                        <tbody class="cashbookTable">
                        <?php foreach ($demands as $key => $value): ?>
                            <tr data-id='<?= $value->d_id?>'>
                                <td style="text-align: center"><?= $key+1?></td>
                                <td style="text-align: center"><?= $value->Form_Number?></td>
                                <td style="text-align: center"><?= $value->LevelName?>-<?= $value->DepartmentName?></td>
                                <td style="text-align: center"><?= $value->Form_DateG?></td>
                                <td style="text-align: center"><?= $value->Form_DateH?></td>
                                <td style="width: 10%;vertical-align: middle;text-align: center"><?php if($value->Status == 1) {?>
                                        <span class="label label-danger" style="font-size: 0.9em;padding-top: 0px;padding-bottom: 0px;">منظوری زیر التواء</span>
                                    <?php }else if($value->Status == 1){?>
                                        <span class="label label-info" style="font-size: 0.9em;padding-top: 0px;padding-bottom: 0px;">جزوی منظوری</span>
                                    <?php }else if($value->Status == 3){?>
                                        <span class="label label-success" style="font-size: 0.9em;padding-top: 0px;padding-bottom: 0px;">منظورشدہ</span>
                                    <?php }else if($value->Status == 2){?>
                                        <span class="label label-primary" style="font-size: 0.9em;padding-top: 0px;padding-bottom: 0px;">اجراء زیر التواء</span>
                                    <?php }?>
                                </td>
                                <td>
                                    <a target="_blank" href="<?= site_url('Store/DemandForm/ViewVoucher').'/'.$value->d_id;?>" <button type="button"  data-id="<?= $value->d_id; ?>" class="btn btn-success " style="background-color: #517751;border-color: #517751;"><i class="fa fa-print"></i>
                                    </button></a>
                                    <?php if ($value->Status == 1){?>
                                    <a href="<?= site_url('Store/DemandForm/FormEdit').'/'.$value->d_id?>" <button type="button"  data-id="<?= $value->d_id; ?>" class="btn btn-success " style="background-color: #517751;border-color: #517751;"><i class="fa fa-pencil-square-o"></i></button></a><?php }?>
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
