<div class="row">
    <div class="col-lg-12">
        <?php if($this->uri->segment(4) == 'br'){?>
            <h1 class="page-header" style="margin-top: 5px;">   بینک وصولی - <span style="font-size: 22px;position: absolute;margin-top: 10px;">عارضی واؤچر</span></h1>
        <?php }else{?>
            <h1 class="page-header" style="margin-top: 20px;">   بینک ادائیگی - <span style="font-size: 22px;position: absolute;margin-top: 10px;">عارضی واؤچر</span></h1>
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
                <div class="input-group col-md-3" style="float: left; direction: ltr;">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                    <input type="text" class="form-control" id="daterange"  name="daterange" />
                </div>
                <div class="input-group col-md-3"  style="float: left; direction: ltr;">
                    <label class="input-group-addon">اکاونٹ کوڈ</label>
                    <input class="form-control AccountCode" placeholder="تلاش اکاؤنٹ کوڈ" style="width: 100%; margin-right: 2%;"  type="text" >
                </div>
                <input type="hidden" id="to" name="to">
                <input type="hidden" id="from" name="from">
                <button class="btn btn-default search" style="float: left; direction: ltr; margin-left:0%; ">تلاش کریں</button>
                <div class="input-group col-md-2"  style="float: left; direction: ltr;">
                    <?php if($this->uri->segment(4) == 'br'): ?>
                        <label class="input-group-addon">BR</label>
                    <?php else:?>
                        <label class="input-group-addon">BP</label>
                    <?php endif ?>
                    <input class="form-control voucherNo"  placeholder="تلاش واؤچر نمبر " style="width: 100%; margin-right: 1%;"  type="text" >
                </div>
                <?php $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,2);
                if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[1] == '1')){?>
                <div class="col-md-4" style="margin-top: -3.7%;width: 27%;padding-right: 0px;">
                    <?php if($this->uri->segment(4) == 'br'): ?>
                        <a href="<?php echo site_url('Accounts/Books/AddTransaction/br/')?><?php echo $this->uri->segment(5);?>" ><button type="button"  class="btn btn-default">نیا اندراج</button></a>
                        <a href="" ><button type="button"  class="btn btn-default copyTemp">کاپی</button></a>
                    <?php else:?>
                        <a href="<?php echo site_url('Accounts/Books/AddTransaction/bp/')?><?php echo $this->uri->segment(5);?>" ><button type="button"  class="btn btn-default">نیا اندراج</button></a>
                        <a href="" ><button type="button"  class="btn btn-default copyTemp">کاپی</button></a>
                    <?php endif ?>
                    <?php } if($this->uri->segment(4) == 'br'): ?>
                        <a href="<?php echo site_url('Accounts/Books/permanentVoucher/br/')?><?php echo $this->uri->segment(5);?>" ><button type="button"  class="btn btn-default">مستقل واؤچر</button></a>
                    <?php else:?>
                        <a href="<?php echo site_url('Accounts/Books/permanentVoucher/bp/')?><?php echo $this->uri->segment(5);?>" ><button type="button"  class="btn btn-default">مستقل واؤچر</button></a>
                    <?php endif ?>
                </div>
            </div>
            <div class="panel-body">
                <div class="table-responsive" style="width: 100%;">
                    <label style="float: left;">تلاش کریں
                        <input type="text" id="myInputTextField" style="float: left;"></label>
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                        <tr>
                            <th>عارضی واؤچر#</th>
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
                        <?php $this->load->view('Accounts/books/bankbook/bankbookTable')?>
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
                <h3><?php echo $level[0]->LevelName?></h3>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form>
                        <div class="row">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label class="control-label" for="inputSuccess">بک کا نام</label>
                                            <input class="form-control" id="bookname" name="bookname" value="<?php echo $a_name[0]->AccountName;?>" style="width: 250px;"  type="text" readonly>
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label class="control-label" for="inputSuccess">موجودہ بیلینس</label>
                                            <input  class="form-control" id="accBalance" name="accBalance" value="<?php echo $a_name[0]->CurrentBalance;?>" style="width: 250px;"  type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label class="control-label" for="inputSuccess">واؤچر نمبر</label>
                                            <input class="form-control" id="voucherCode" name="code" value="<?php echo $code; ?>" style="width: 250px;"  type="text" readonly>
                                            <?php echo form_error('pass'); ?>
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label class="control-label" for="inputSuccess">وصول کنندہ / جمع کنندہ</label>
                                            <input  class="form-control" id="paidTo" name="PaidTo" style="width: 250px;"  type="text">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <input type="hidden" name="companyId" id="companyId" value="<?php echo $this->uri->segment(4);?>">
                                        <input type="hidden" name="bookId" id="bookId" value="<?php echo $this->uri->segment(5);?>">
                                        <div class="col-xs-6">
                                            <div class="form-group" style="width:250px;">
                                                <label>اکاؤنٹ کا نام</label>
                                                <select class="form-control" style="height: 50px;" id="accountId" name="accountId">
                                                    <option value=""></option>
                                                    <?php foreach($account as $acc): ?>
                                                        <option value="<?php echo $acc->id;?>"><?php echo $acc->AccountName?></option>
                                                    <?php endforeach ?>
                                                </select>
                                                <div class="accId" style="color:red"></div>
                                            </div>
                                        </div>
                                        <div class="col-xs-6">
                                            <div class="form-group">
                                                <label class="control-label" for="inputSuccess">موجودہ بیلینس</label>
                                                <input class="form-control" id="currentBalance" value="" style="width: 250px;"  type="text" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="form-group" style="width:250px;">
                                            <label>شمسی تاریخ</label>
                                            <div class="form-group">
                                                <input class="form-control englishDate" type="date" name="englishDate" value="" placeholder="انگریزی کی تاریخ منتخب کریں">
                                                <div class="engDate" style="color:red"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label class="control-label" for="inputSuccess">قمری تاریخ</label>
                                            <input class="form-control" id="islamicDate" name="islamicDate" style="width: 250px;"  type="date" >
                                            <div class="islDate" style="color:red"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label class="control-label" for="inputSuccess">وصولی</label>
                                            <input class="form-control" id="recieved" name="recieved" style="width: 250px;"  type="number" >
                                            <div class="Recieved" style="color:red"></div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label class="control-label" for="inputSuccess">ادائیگی</label>
                                            <input  class="form-control" id="payment" name="payment" style="width: 250px;"  type="number" >
                                            <div class="Payment" style="color:red"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="form-group" style="width:250px;">
                                            <label>تفصیل</label>
                                            <textarea class="form-control" rows="3" id="details" name="details"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary saveTransaction">محفوظ کریں</button>
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
                <h3><?php echo $level[0]->LevelName?></h3>
                <!-- /<span style="font-size: 0.7em;"><?php echo $a_name[0]->AccountName;?></span> -->
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form>
                        <div class="row">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label class="control-label" for="inputSuccess">بک کا نام</label>
                                            <input class="form-control" id="bookname" name="bookname" value="<?php echo $a_name[0]->AccountName;?>" style="width: 250px;"  type="text" readonly>
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label class="control-label" for="inputSuccess">موجودہ بیلینس</label>
                                            <input  class="form-control" id="accBalance" name="accBalance" value="<?php echo $a_name[0]->CurrentBalance;?>" style="width: 250px;"  type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label class="control-label" for="inputSuccess">واؤچر نمبر</label>
                                            <input class="form-control" id="EvoucherCode" name="code" value="" style="width: 250px;"  type="text">
                                            <?php echo form_error('pass'); ?>
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
                                    <div class="form-group">
                                        <input type="hidden" name="companyId" id="EcompanyId" value="<?php echo $this->uri->segment(4);?>">
                                        <input type="hidden" name="bookId" id="EbookId" value="<?php echo $this->uri->segment(5);?>">
                                        <div class="col-xs-6">
                                            <div class="form-group" style="width:250px;">
                                                <label>اکاؤنٹ کا نام</label>
                                                <select class="form-control" style="height: 50px;" id="EaccountId" name="accountId">
                                                    <option value=""></option>
                                                    <?php foreach($account as $acc): ?>
                                                        <option value="<?php echo $acc->id;?>"><?php echo $acc->AccountName?></option>
                                                    <?php endforeach ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xs-6">
                                            <div class="form-group">
                                                <label class="control-label" for="inputSuccess">موجودہ بیلینس</label>
                                                <input class="form-control" id="EcurrentBalance" value="" style="width: 250px;"  type="text" readonly>
                                                <?php echo form_error('pass'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="form-group" style="width:250px;">
                                            <label>عیسوی تاریخ</label>
                                            <div class="form-group">
                                                <input class="form-control EenglishDate" type="date" name="englishDate" value="" placeholder="انگریزی کی تاریخ منتخب کریں">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label class="control-label" for="inputSuccess">ہجری تاریخ</label>
                                            <input class="form-control" id="EislamicDate" name="islamicDate" style="width: 250px;"  type="date" >
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label class="control-label" for="inputSuccess">وصولی</label>
                                            <input class="form-control" id="Erecieved" name="recieved" style="width: 250px;"  type="number" >
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label class="control-label" for="inputSuccess">ادائیگی</label>
                                            <input  class="form-control" id="Epayment" name="payment" style="width: 250px;"  type="number" >
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="form-group" style="width:250px;">
                                            <label>تفصیل</label>
                                            <textarea class="form-control" rows="3" id="Edetails" name="details"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary bsaveEditTransaction">محفوظ کریں</button>
                    <button type="button" class="btn btn-primary bdeleteTransaction">حذف کریں</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="copy" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                واؤچر نمبر <span id="voucher_no"></span> مستقل واؤچر میں منتقل کریں
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form>
                        <div class="row">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="form-group" style="width:250px;">
                                            <label>عیسوی تاریخ</label>
                                            <div class="form-group">
                                                <input class="form-control CenglishDate englishDate" type="date" name="CenglishDate" value="" placeholder="انگریزی کی تاریخ منتخب کریں" autofocus>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label class="control-label" for="inputSuccess">ہجری تاریخ</label>
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
                    <button type="button" class="btn btn-primary copyTransaction">منتقل کریں</button>
                </div>
            </div>
        </div>
    </div>
</div>