<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">ڈیش بورڈ</h1>
    </div>
</div>
<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3" style="padding-right:;padding-right: 08px;">
                        <i class="fa fa-comments fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right" style="padding-right: 0px;padding-left: 0px;">
                        <div class="huge">اسٹاک رپورٹ</div>
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
            } $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,11,$Access_level,2);
            if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[4] == '1')){?>
            <a href="<?= site_url('Store/ItemStock')?>"><?php }else{?>
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
                        <div class="huge">رپورٹ 2</div>
                        <div></div>
                    </div>
                </div>
            </div><!-- Add trail Balance's feature id then active -->
            <?php $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,15,$Access_level);
            if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[4] == '1')){?>
            <a href="#" ><?php }else{?>
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
                        <div class="huge">رپورٹ 3</div>
                        <div></div>
                    </div>
                </div>
            </div>
            <!-- Add AuditTrial's feature id then active -->
            <?php $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,17,$Access_level);
            if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[4] == '1')){?>
            <a href="#" ><?php }else{?>
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
                    <div class="col-xs-9 text-right">
                        <div class="huge">رپورٹ 4</div>
                        <div></div>
                    </div>
                </div>
            </div> <!-- Add AuditTrial's feature id then active -->

            <?php $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,14,$Access_level);
            if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[4] == '1')){?>
            <a href="#" ><?php }else{?>
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