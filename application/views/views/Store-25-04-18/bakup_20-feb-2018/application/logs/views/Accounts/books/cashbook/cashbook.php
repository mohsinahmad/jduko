<style type="text/css">
    .VoucherMOdal {
        border: 0;
        outline: 0;
        background: transparent;
        border-bottom: 1px solid black;
    }
</style>
<div class="row">
    <div class="col-lg-12">
        <br>
        <?php if($this->uri->segment(4) == 'cr'){?>
            <h1 class="page-header" style="margin-top: 2px;">   کیش وصولی - <span style="font-size: 22px;position: absolute;margin-top: 10px;">عارضی واؤچر</span> </h1>
        <?php }else{?>
            <h1 class="page-header" style="margin-top: 4px;">   کیش ادائیگی - <span style="font-size: 22px;position: absolute;margin-top: 10px;">عارضی واؤچر</span></h1>
        <?php }?>
    </div>
</div>
<?php if($this->session->flashdata('success')) :?>
    <div class="alert alert-success alert-dismissable" id="dalert">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <?php echo $this->session->flashdata('success');?>
    </div>
<?php endif ?>
<?php if($this->session->flashdata('error')) :?>
    <div class="alert alert-danger alert-dismissable" id="dalert">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <?php echo $this->session->flashdata('error');?>
    </div>
<?php endif ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading col-md-12" style="width: 100%;">
                <div class="row">
                    <div class="input-group col-md-3" style="float: left; direction: ltr;">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                        <input type="text" class="form-control" id="daterange" name="daterange" />
                    </div>
                    <div class="input-group col-md-3"  style="float: left; direction: ltr; margin-left: 1%;">
                        <label class="input-group-addon">اکاونٹ کوڈ</label>
                        <input class="form-control AccountCode" placeholder="تلاش اکاؤنٹ کوڈ" style="width: 100%; margin-right: 2%;"  type="text" >
                    </div>
                    <input type="hidden" id="to" name="to">
                    <input type="hidden" id="from" name="from">
                    <button class="btn btn-default search" style="float: left; direction: ltr; margin-left:0%; ">تلاش کریں</button>
                    <div class="input-group col-md-2"  style="float: left; direction: ltr;">
                        <?php if($this->uri->segment(4) == 'cr'): ?>
                            <label class="input-group-addon">CR</label>
                        <?php else:?>
                            <label class="input-group-addon">CP</label>
                        <?php endif ?>
                        <input class="form-control voucherNo"  placeholder="تلاش واؤچر نمبر " style="width: 100%; margin-right: 1%;"  type="text" >
                    </div>
                    <?php $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,1);
                    if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[1] == '1')){?>
                        <div class="col-md-4" style="margin-top: -3.5%;width: 26%;" >
                            <?php if($this->uri->segment(4) == 'cr'): ?>
                                <a href="<?php echo site_url('Accounts/Books/AddTransaction/cr/')?><?php echo $this->uri->segment(5);?>" ><button type="button"  class="btn btn-default">نیا اندراج</button></a>
                                <a href="" ><button type="button"  class="btn btn-default copyTemp">کاپی</button></a>
                            <?php else:?>
                                <a href="<?php echo site_url('Accounts/Books/AddTransaction/cp/')?><?php echo $this->uri->segment(5);?>" ><button type="button"  class="btn btn-default">نیا اندراج</button></a>
                                <a href="" ><button type="button"  class="btn btn-default copyTemp">کاپی</button></a>
                            <?php endif ?>
                            <?php if($this->uri->segment(4) == 'cr'): ?>
                                <a href="<?php echo site_url('Accounts/Books/permanentVoucher/cr/')?><?php echo $this->uri->segment(5);?>" ><button type="button"  class="btn btn-default">مستقل واؤچر</button></a>
                            <?php else:?>
                                <a href="<?php echo site_url('Accounts/Books/permanentVoucher/cp/')?><?php echo $this->uri->segment(5);?>" ><button type="button"  class="btn btn-default">مستقل واؤچر</button></a>
                            <?php endif ?>
                        </div>
                    <?php }?>
                    <!--                    <div class="col-md-4" style="margin-top: -3.5%;margin-right: 12.5%;" >-->
                    <!---->
                    <!--                    </div>-->
                </div>
            </div>
            <div class="panel-body">
                <div class="table-responsive" style="width: 100%;">
                    <label style="float: left;">تلاش کریں
                        <input type="text" id="myInputTextField" style="float: left;"></label>
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                        <tr>
                            <th style="width:59.5px;">عارضی واؤچر#</th>
                            <th>شعبے کا نام</th>
                            <th>بنام</th>
                            <th>عیسوی تاریخ</th>
                            <th>ہجری تاریخ</th>
                            <th>تفصیل</th>
                            <th>رقم</th>
                            <th><input type="checkbox" name="" id="select_all">سب</th>
                            <th>تدوین</th>
                            <th>منتقل</th>
                            <th>پرنٹ</th>
                        </tr>
                        </thead>
                        <tbody class="cashbookTable">
                        <?php $this->load->view('Accounts/books/cashbook/cashbookTable')?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- *************** Edit Modal ********************* -->
<div id="Edit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form>
                        <div class="row">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <?php $this->load->model('DepartmentModel');
                                            $departments = $this->DepartmentModel->department_name();?>
                                            <label class="control-label" for="inputSuccess">شعبہ</label>
                                            <select class="form-control" style="height: 50px;" id="departId" name="departId">
                                                <option value=""> منتخب کریں</option>
                                                <?php foreach($departments as $department): ?>
                                                    <option value="<?php echo $department->Id;?>"><?php echo $department->DepartmentName; ?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label class="control-label" for="inputSuccess">وصول کنندہ / جمع کنندہ</label>
                                            <input  class="form-control" id="EpaidTo" name="PaidTo" style="width: 250px;"  type="text">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="form-group" style="width:250px;">
                                            <label>شمسی تاریخ</label>
                                            <div class="form-group">
                                                <input class="form-control EenglishDate" type="date" name="englishDate" value="" placeholder="انگریزی کی تاریخ منتخب کریں">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label class="control-label" for="inputSuccess">قمری تاریخ</label>
                                            <input class="form-control" id="EislamicDate" name="islamicDate" style="width: 250px;"  type="date" >
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="form-group" style="width:250px;">
                                            <label>تفصیل</label>
                                            <textarea class="form-control" rows="3" id="remarks" name="remarks"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-3">
                                        <div class="form-group">
                                            <label class="control-label" for="inputSuccess">اکاؤنٹ کا نام</label>
                                            <input class="form-control" id="acc_name" name="acc_name"  type="input" readonly>
                                        </div>
                                    </div><div class="col-xs-3">
                                        <div class="form-group">
                                            <label class="control-label" for="inputSuccess">ڈیبٹ</label>
                                            <input class="form-control" id="debit" name="debit" type="input" readonly>
                                        </div>
                                    </div><div class="col-xs-3">
                                        <div class="form-group">
                                            <label class="control-label" for="inputSuccess">کریڈٹ</label>
                                            <input class="form-control" id="credit" name="credit" type="input" readonly>
                                        </div>
                                    </div><div class="col-xs-3">
                                        <div class="form-group">
                                            <label class="control-label" for="inputSuccess">تفصیل</label>
                                            <input class="form-control" id="detail" name="detail" type="input" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary saveEditTransaction">محفوظ کریں</button>
                    <button type="button" class="btn btn-primary deleteTransaction">حذف کریں</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Copy Modal -->
<div id="copy" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                واؤچر نمبر <span id="voucher_no"></span> مستقل واؤچر میں منتقل کریں
                <!--            <h3>--><?php //echo $level[0]->LevelName?><!--</h3>-->
                <!--             <span style="font-size: 0.7em;">--><?php //echo $a_name[0]->AccountName;?><!--</span>-->
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form>
                        <div class="row">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="form-group" style="width:250px;">
                                            <label>شمسی تاریخ</label>
                                            <div class="form-group">
                                                <input class="form-control CenglishDate englishDate" type="date" name="CenglishDate" value="" placeholder="انگریزی کی تاریخ منتخب کریں" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label class="control-label" for="inputSuccess">قمری تاریخ</label>
                                            <input class="form-control islamicDate" id="CislamicDate" name="CislamicDate" style="width: 250px;"  type="text" readonly>
                                        </div>
                                    </div>
                                    <input type="hidden" id="t_id">
                                    <input type="hidden" id="level_id">
                                    <input type="hidden" id="voucher_type">
                                    <input type="hidden" id="vouch_no">
                                </div>
                                <div class='row' id='bank_details' >
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary copyTransaction" autofocus>منتقل کریں</button>
                </div>
            </div>
        </div>
    </div>
</div>