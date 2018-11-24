<form action="<?php echo site_url('Accounts/ReportConfigurations/Save')?>" method="POST">
    <div class="row">
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
        <div class="heading">
            <br>
            <h1 class="page-header" style="margin-top: 10px;">رپورٹ نام </h1>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="form-group">
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label class="control-label" for="inputSuccess">رپورٹ کا نام</label>
                            <input class="form-control" id="ReportNamea" name="ReportName" style="width: 250px;" value="" type="text" autofocus required>
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
                <h1>رپورٹس</h1>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <label style="float: left; margin-left: 3%;">تلاش کریں
                        <input type="text" id="myInputTextField" style="float: left; height: 6%;"></label>
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                        <tr>
                            <th style="width: 15%;">شناخت</th>
                            <th style="width: 40%;">رپورٹ کا نام</th>
                            <th style="width: 40%;">رپورٹ کی قسم</th>
                            <th>حذف/تدوین</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($name as $item):?>
                            <tr class="odd gradeX">
                                <td><?php echo $item->Id?></td>
                                <td><?php echo $item->ReportName?></td>
                                <?php if($item->ReportType == 0){?>
                                <td><?php echo "رمضان رپورٹ"?></td>
                                <?php } else{?>
                                <td><?php echo "ہفتہ واری رپورٹ"?></td>
                                <?php }?>
                                <td style="width: 18%;">
                                    <button type="button" class="btn btn-danger delete_report" data-id="<?php echo $item->Id;?>" style="font-size: 10px; ">حذف کریں
                                    </button>
                                    <button type="button" class="btn btn-success getid" data-toggle="modal" data-target="#gridSystemModal" data-id="<?php echo $item->Id;?>" style="font-size: 10px;background-color: #517751;border-color: #517751; ">تصیح کریں
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
                <h4 class="modal-title" id="gridModalLabel">رپورٹ کے نام میں ترمیم</h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid bd-example-row">
                    <div class="row">
                        <div class="panel-body">
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label class="control-label" for="inputSuccess">رپورٹ نام </label>
                                            <input class="form-control" id="ReportName" name="ReportName" style="width: 250px;" value="" type="text" autofocus required>
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="checkbox" id="checkbox">
                                            <button type="button" class="btn btn-primary reportEdit">محفوظ کریں</button>
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