<div class="row">
    <div class="col-lg-12">
        <br>
        <h1 class="page-header" style="margin-top: 10px;">اسٹاک وصولی</h1>
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
                    <input type="hidden" id="to" name="to">
                    <input type="hidden" id="from" name="from">
                    <button class="btn btn-default search" style="float: left; direction: ltr; margin-left:2%; margin-right: 2% ">تلاش کریں</button>
                    <div class="input-group col-md-3"  style="float: left; direction: ltr;">
                        <input class="form-control voucherNo"  placeholder="تلاش اسٹاک سلپ نمبر "  type="text" >
                        <label class="input-group-addon">اسٹاک سلپ</label>
                    </div>
                    <?php $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,30);
                    if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[1] == '1')){?>
                    <div class="col-md-4" style="width: 26%;" >
                      <a href="<?php echo site_url('Store/StockSlip/AddStock')?>" ><button type="button"  class="btn btn-default">نیا اندراج</button></a>
                    </div>
                    <?php }?>
            </div>
             </div>
            <div class="panel-body">
                <div class="table-responsive" style="width: 100%;">
                <label style="float: left;">تلاش کریں
                <input type="text" id="myInputTextField" style="float: left;"></label>
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                        <tr>
                            <th style="width: 12%;text-align: center">سلپ نمبر</th>
                            <th style="width: 14%;text-align: center">سپلائر نام</th>
                            <th style="width: 22%;text-align: center">عیسوی تاریخ خریداری مطابق رسید</th>
                            <th style="width: 18%;text-align: center">عیسوی تاریخ وصولی دراسٹور</th>
                            <th style="text-align: center">قیمت</th>
                            <th style="width: 21%;text-align: center">تدوین</th>
                        </tr>
                        </thead>
                        <tbody class="cashbookTable">
                         <?php foreach($stock as $value):?>
                            <tr data-id='<?php echo $value->s_id ?>'>
                                <td style="text-align: center"><?php echo $value->Slip_number ?></td>
                                <td style="text-align: center"><?php echo $value->NameU ?></td>
                                <td style="text-align: center"><?php echo $value->Purchase_dateG ?></td>
                                <td style="text-align: center"><?php echo $value->Item_recieve_dateG ?></td>
                                <td style="text-align: center"><?php echo $value->price ?></td>
                                <td>
                                    <button type="button" class="btn btn-danger delete_stock_slip" data-id="<?php echo $value->s_id;?>" style="font-size: 10px; ">حذف کریں
                                    </button>
                                    <a href="<?php echo site_url('Store/StockSlip/Update_Stock').'/'.$value->s_id; ?>" <button type="button"  data-id="<?php echo $value->s_id; ?>" class="btn btn-success " style="font-size: 10px;background-color: #517751;border-color: #517751; "></button>تصیح کریں</a>
                                    <a target="_blank" href="<?php echo site_url('Store/StockSlip/ViewVoucher').'/'.$value->s_id;?>"> 
                                    <button type="button"  data-id="<?php echo $value->s_id; ?>" class="btn btn-success " style="background-color: #517751;border-color: #517751;"><i class="fa fa-print"></i>
                                    </button></a>
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