<div class="row">
    <div class="col-lg-12">
        <br>
            <h1 class="page-header" style="margin-top: 10px;">    آمدنی واؤچر - <span style="font-size: 22px;position: absolute;margin-top: 10px;">مستقل واؤچر</span> </h1>
    </div>
</div>
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
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading" style="width: 100%;">
                    <div class="input-group col-md-3" style="float: left; direction: ltr;">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                        <input type="text" class="form-control" id="daterange" name="daterange" />
                    </div>
                    <div class="input-group col-md-3"  style="float: left; direction: ltr; margin-left: 1%;">
                        <label class="input-group-addon">اکاونٹ کوڈ</label>
                        <input class="form-control AccountCodePer" placeholder="تلاش اکاؤنٹ کوڈ" style="width: 100%; margin-right: 2%;"  type="text" >
                    </div>
                    <input type="hidden" id="to" name="to">
                    <input type="hidden" id="from" name="from">
                    <button class="btn btn-default searchPer" style="float: left; direction: ltr; margin-left:0%; ">تلاش کریں</button>
                    <div class="input-group col-md-3"  style="float: left; direction: ltr; margin-left: 2%;">
                            <label class="input-group-addon">IC</label>
                        <input class="form-control PervoucherNo"  placeholder="تلاش واؤچر نمبر" style="width: 100%; margin-right: 1%;"  type="text" >
                    </div>
                <?php $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,17);
                if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[1] == '1')){?>
                    <!-- <a href="" ><button type="button"  class="btn btn-default moveTemp">منتقل</button></a> -->
                    <a href="" ><button type="button"  class="btn btn-default copyIncTemp">کاپی</button></a>
                <?php }?>
            </div>
            <div class="panel-body">
                <div class="table-responsive" style="width: 100%;">
                <label style="float: left;">تلاش کریں
                <input type="text" id="myInputTextField" style="float: left;"></label>
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                        <tr>
                            <th>مستقل واؤچر#</th>
                            <th>عارضی واؤچر#</th>
                            <th>رسید نمبر</th>
                            <!-- <th>بنام</th> -->
                            <th>عیسوی تاریخ</th>
                            <th>ہجری تاریخ</th>
                            <th>تفصیل</th>
                            <th>رقم</th>
                            <th><input type="checkbox" name="" id="select_all">سب</th>
                            <th>تدوین</th>
                            <th>پرنٹ</th>
                        </tr>
                        </thead>
                        <tbody class="percashbookTable">
                           <?php $this->load->view('Accounts/books/income/permanentIncomeTable')?>
                        </tbody>
                    </table>
                </div>
            </div>
      </div>
    </div>
</div>