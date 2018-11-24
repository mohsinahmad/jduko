<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header" style="margin-top: 8px;">ڈیش بورڈ</h1>
    </div>
</div>
<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-comments fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">لیجر</div>
                        <div></div>
                    </div>
                </div>
            </div>
            <?php if (isset($_SESSION['comp_id'])){
                $Access_level = $_SESSION['comp_id'];
            }elseif (isset($_SESSION['comp'])){
                $Access_level = $_SESSION['comp'];
            }else{
                $Access_level = '';
            } $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,11,$Access_level);
            if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[4] == '1')){?>
            <a href="<?php echo site_url('Accounts/Ledger/GetData')?>" ><?php }else{?>
                <a href="#" id="AccessDenied"><?php }?>
                    <div class="panel-footer">
                        <span class="pull-left"> تفصیلات</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-green">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-tasks fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">ٹرایل بیلینس</div>
                        <div></div>
                    </div>
                </div>
            </div><!-- Add trail Balance's feature id then active -->
            <?php $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,15,$Access_level);
            if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[4] == '1')){?>
            <a href="<?php echo site_url('Accounts/TrialBalance/GetData')?>" ><?php }else{?>
                <a href="#" id="AccessDenied"><?php }?>
                    <!--            <a href="--><?php //echo site_url('TrialBalance/GetData')?><!--">-->
                    <div class="panel-footer">
                        <span class="pull-left"> تفصیلات</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-yellow">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-shopping-cart fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">بیلنس شیٹ</div>
                        <div></div>
                    </div>
                </div>
            </div>
            <?php $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,19,$Access_level);
            if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[4] == '1')){?>
            <a href="<?php echo site_url('Accounts/BalanceSheet')?>" ><?php }else{?>
                <a href="#" id="AccessDenied"><?php }?>
                    <!--            <a href="--><?php //echo site_url('BalanceSheet')?><!--">-->
                    <div class="panel-footer">
                        <span class="pull-left"> تفصیلات</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-red">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-support fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right" style="padding-left: 0px;">
                        <div class="huge">آمدنی کا گوشوارہ</div>
                        <div></div>
                    </div>
                </div>
            </div> <!-- Add AuditTrial's feature id then active -->
            <?php $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,20,$Access_level);
            if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[4] == '1')){?>
            <a href="<?php echo site_url('Accounts/IncomeStatment')?>" ><?php }else{?>
                <a href="#" id="AccessDenied"><?php }?>
                    <!--            <a href="--><?php //echo site_url('AuditTrial')?><!--">-->
                    <div class="panel-footer">
                        <span class="pull-left"> تفصیلات</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
        </div>
    </div>
</div>
<br><br><br>
<br><br><br>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default" style="margin-top: -13%;">
            <div class="panel-heading">
                <h1 style="float: right;">ٹرانسزیکشنز</h1>
                <div class="row">
                    <div class="input-group col-md-4" style="float: left;left: -17%">
                        <div class="form-group">
                            <select class="form-control" id="booktype" name="booktype" style="width: 45%;padding-top: 0px;padding-bottom: 0px;">
                                <option  value="" disabled selected>منتخب کتاب کی قسم</option>
                                <option value='0'>سب</option>
                                <option  value="cr">CR</option>
                                <option  value="cp">CP</option>
                                <option  value="br">BR</option>
                                <option  value="bp">BP</option>
                                <option  value="jv">JV</option>
                            </select>
                        </div>
                    </div>
                    <div class="input-group col-md-4" style="float: left;left: -14%;">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                        <input type="text" class="form-control" id="daterange" name="daterange" />
                        <input type="hidden" id="to" name="to">
                        <input type="hidden" id="from" name="from">
                    </div>
                </div>
                <div class="row">
                    <div class="input-group col-md-4" style="float: left;left: -17%;margin-top: -2%;">
                        <input type="text" class="form-control voucherno" style="width: 45%;" placeholder="تلاش واؤچر نمبر " >
                    </div>
                    <button class="btn btn-default search" style="float: left; margin-top: -2%; margin-left: -14%;">تلاش کریں</button>
                </div>
            </div>
            <div class="panel-body">
                <div class="table-responsive" style="width: 100%;">
                    <label style="float: left; margin-left: 3%;">تلاش کریں
                        <input type="text" id="myInputTextField" style="float: left; height: 1%;"></label>

                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                        <tr>
                            <th>مستقل واؤچر#</th>
                            <th>شعبے کا نام</th>
                            <th>بنام</th>
                            <th>عیسوی تاریخ</th>
                            <th>ہجری تاریخ</th>
                            <th>تفصیل</th>
                            <th>رقم</th>
                        </tr>
                        </thead>
                        <tbody class="tr_tabel">
                        <?php $this->load->view('Accounts/indexTable')?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url()."assets/"; ?>js/jquery-1.12.4.js"></script>
<script src="<?php echo base_url()."assets/"?>urdutextbox.js"> </script>

<script>
    window.onload = myOnload;
    function myOnload(evt){
        $("#dataTables-example_filter input[class='form-control input-sm']").attr("type", "search");
        $("#dataTables-example_filter input[class='form-control input-sm']").attr("id", "myInputTextField");
        //MakeTextBoxUrduEnabled(myInputTextField);
    }
</script>

<script type="text/javascript">

        var com = $('nav ul li.dropdown .link').text().trim();

            if(com == 'کمپنی'){
                alert('برائے مہربانی کمپنی کا انتخاب کریں');
               // e.preventDefault();
            }



    $('#booktype').on('change',function(){
        var book_type = $(this).val();
        if(book_type == ""){
            $.ajax({
                type:'GET',
                url:'<?php echo site_url('Accounts/Dashboard/index');?>',
                success:function(response){
                    $('.tr_tabel').html(response);
                }
            });

        }else{
            $.ajax({
                type:'GET',
                url:'<?php echo site_url('Accounts/Dashboard/index') ?>'+'/'+book_type,
                success:function(response){
                    $('.tr_tabel').html(response);
                }
            });
        }
    });

    $('.voucherno').on('keyup',function(){

        var voucherno = $(this).val();

        if(voucherno == ""){
            $.ajax({
                type:'GET',
                url:'<?php echo site_url('Accounts/Dashboard/all');?>',
                success:function(response){
                    $('.tr_tabel').html(response);
                }
            });

        }else{
            $.ajax({
                type:'GET',
                url:'<?php echo site_url('Accounts/Dashboard/GetByVoucherNo') ?>'+'/'+voucherno,
                success:function(response){
                    $('.tr_tabel').html(response);
                }
            });
        }
    });

    $('#AccessDenied').on('click',function () {
        alert('آپ کو یہ سہولت میسر نہیں ہے۔۔۔!');
    });

</script>