<form action="<?= site_url('Store/Supplier/SaveSupplier')?>" method="POST">
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
            <h1 class="page-header" style="margin-top: 10px;">سپلائر </h1>
        </div>
        <div class="panel-body">
            <div class="row col-lg-12">
                <div class="col-xs-4">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">نام</label>
                        <input class="form-control" id="" name="NameU" style="width: 250px;" value="" type="text" autofocus required>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">ایڈریس</label>
                        <input class="form-control" id="" name="AddressU" style="width: 250px;" value="" type="text" autofocus>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">فون نمبر</label>
                        <input class="form-control" id="" name="ph_number" style="width: 250px;" value="" type="text" autofocus>
                    </div>
                </div>
            </div>
            <div class="row col-lg-12">
                <div class="col-xs-4">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">این ٹی این نمبر</label>
                        <input class="form-control" id="" name="NTN_number" style="width: 250px;" value="" type="text" autofocus>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">اسی این ایَ سی نمبر</label>
                        <input class="form-control" id="" name="CNIC" style="width: 250px;" value="" type="text" autofocus>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">رابطہ کار</label>
                        <input class="form-control" id="" name="Contact_person" style="width: 250px;" value="" type="text" autofocus>
                        <input class="form-control" id="" name="Supplier_Module" style="width: 250px;" value="0" type="hidden">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary ">محفوظ کریں</button>
            </div>
        </div>
    </div>
</form>
<br><br><br>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h1>سپلائر</h1>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <label style="float: left; margin-left: 3%;">تلاش کریں
                        <input type="text" id="myInputTextField" style="float: left; height: 6%;"></label>
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                        <tr>
                            <th style="width: 15%;">شناخت</th>
                            <th style="width: 20%;">نام</th>
                            <th style="width: 30%;">این ٹی این نمبر</th>
                            <th style="width: 30%;">سی این ایَ سی نمبر</th>
                            <th style="width: 30%;">فون نمبر</th>
                            <th style="width: 30%;">ایڈریس</th>
                            <th style="width: 30%;">رابطہ کار</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($supplier as $key => $value):?>
                            <tr class="odd gradeX">
                                <td><?= ++$key?></td>
                                <?php if($value->NameU == ""){?>
                                    <td><?= $value->NameE?></td>
                                <?php }else if($value->NameU != "" && $value->NameE != "") {?>
                                    <td><?= $value->NameE?></td>
                                <?php } else {?>
                                    <td><?= $value->NameU?></td>
                                <?php }?>
                                <td><?= $value->NTN_number?></td>
                                <td><?= $value->CNIC?></td>
                                <td><?= $value->Phone_number?></td>
                                <?php if($value->AddressU == ""){?>
                                    <td><?= $value->AddressE?></td>
                                <?php }else if($value->AddressU != "" && $value->AddressE != "") {?>
                                    <td><?= $value->AddressE?></td>
                                <?php } else {?>
                                    <td><?= $value->AddressE?></td>
                                <?php }?>
                                <td><?= $value->Contact_person?></td>
                                <td style="width: 18%;">
                                    <button type="button" class="btn btn-danger delete_supplier" data-id="<?= $value->Id;?>" style="font-size: 10px; ">حذف کریں
                                    </button>
                                    <button type="button" class="btn btn-success getid" data-toggle="modal" data-target="#gridSystemModal" data-id="<?= $value->Id;?>" style="font-size: 10px;background-color: #517751;border-color: #517751; ">تصیح کریں
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
                <h4 class="modal-title" id="gridModalLabel">سپلائر کی ترمیم</h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid bd-example-row">
                    <div class="row">
                        <div class="panel-body">
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label class="control-label" for="inputSuccess">نام</label>
                                            <input class="form-control" id="NameU" name="Name" style="width: 250px;" value="" type="text" autofocus required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-xs-6" >
                                        <div class="form-group">
                                            <label class="control-label" for="inputSuccess">سی این ایَ سی نمبر / این ٹی این نمبر</label>
                                            <input class="form-control" id="NTN_number" name="NTN_number" style="width: 250px;" value="" type="text" autofocus>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label class="control-label" for="inputSuccess">ایڈریس</label>
                                            <input class="form-control" id="AddressU" name="Address" style="width: 250px;" value="" type="text" autofocus>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-xs-6" style="margin-top: -3%;">
                                        <div class="form-group">
                                            <label class="control-label" for="inputSuccess">رابطہ کار</label>
                                            <input class="form-control" id="Contact_person" name="Contact_person" style="width: 250px;" value="" type="text" autofocus>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label class="control-label" for="inputSuccess">اسی این ایَ سی نمبر</label>
                                            <input class="form-control" id="CNIC" name="CNIC" style="width: 250px;" value="" type="text" autofocus>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary supplierEdit">محفوظ کریں</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>