<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">ڈیش بورڈ</h1>
    </div>
<!--    <a href="--><?php //echo site_url('Qurbani/AreaDetail/BackDataBase')?><!--"><span class="glyphicon glyphicon-download">Database Backup</span></a>-->
    <br>
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
                        <div class="huge">وصولی آمدنی براےُ حصص قربانی</div>
                    </div>
                </div>
            </div>
            <a target="_blank" href="<?= site_url('Qurbani/Receipt/QurbaniReport1')?>">
                <a target="_blank" href="<?= site_url('Qurbani/Receipt/QurbaniReport1')?>" id="AccessDenied">
                    <div class="panel-footer">
                        <span class="pull-left"> تفصیلات</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-tasks fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">گائے کہانی</div>
                    </div>
                </div>
            </div><!-- Add trail Balance's feature id then active -->
            <a href="<?= site_url('Qurbani/Receipt/GetCowSlot')?>" >
                <a href="<?= site_url('Qurbani/Receipt/GetCowSlot')?>" id="AccessDenied">
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
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-shopping-cart fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">روزانہ کی رپورٹ</div>
                    </div>
                </div>
            </div>
            <!-- Add AuditTrial's feature id then active -->
            <a href="<?= site_url('Qurbani/ReceiptReport')?>">
                <div class="panel-footer">
                    <span class="pull-left"> تفصیلات</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-support fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">تقا بلی جائزے کی رپورٹ</div>
                    </div>
                </div>
            </div> <!-- Add AuditTrial's feature id then active -->
            <a href="<?= site_url('Qurbani/ChrumOldData')?>" id="AccessDenied">
                <div class="panel-footer">
                    <span class="pull-left"> تفصیلات</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-green">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3" style="padding-right:;padding-right: 08px;">
                        <i class="fa fa-comments fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right" style="padding-right: 0px;padding-left: 0px;">
                        <div class="huge">تفصیل انعامات برائے نگران</div>
                    </div>
                </div>
            </div>
            <a target="_blank" href="<?= site_url('Qurbani/PaymentDetails')?>" id="AccessDenied">
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
                        <div class="huge">غیر بک شدہ حصے</div>
                    </div>
                </div>
            </div>
            <a href="<?= site_url('Qurbani/Receipt/GetRemaingCowsHissa')?>" id="AccessDenied">
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
                        <div class="huge">وینڈرز لیجر</div>
                    </div>
                </div>
            </div>
            <a href="<?= site_url('Qurbani/VendorLedger')?>" id="AccessDenied">
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
                        <div class="huge">تفصیل انعامات برائے نگران2</div>
                    </div>
                </div>
            </div>
            <a target="_blank" href="<?= site_url('Qurbani/PaymentDetails/PaymentDetails2')?>" id="AccessDenied">
                <div class="panel-footer">
                    <span class="pull-left"> تفصیلات</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-red">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3" style="padding-right:;padding-right: 08px;">
                        <i class="fa fa-comments fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right" style="padding-right: 0px;padding-left: 0px;">
                        <div class="huge">گیٹ پاس رپورٹ</div>
                    </div>
                </div>
            </div>
            <a target="_blank" href="<?= site_url('Qurbani/GatePassReport')?>" id="AccessDenied">
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
                        <i class="fa fa-tasks fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">خلاصہ حلقہ جات</div>
                    </div>
                </div>
            </div>
            <a target="_blank" href="<?= site_url('Qurbani/AreaDetail')?>" id="AccessDenied">
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
                        <i class="fa fa-tasks fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">مصارف فی گائے</div>
                    </div>
                </div>
            </div>
            <a target="_blank" href="<?= site_url('Qurbani/ExpencePerCowReport')?>" id="AccessDenied">
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
                    <div class="col-xs-3" style="padding-right:;padding-right: 08px;">
                        <i class="fa fa-comments fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right" style="padding-right: 0px;padding-left: 0px;">
                        <div class="huge">ادائیگی بابت انعامات حصص قربانی</div>
                    </div>
                </div>
            </div>
            <a target="_blank" href="<?= site_url('Qurbani/RewardList/GetRewardList')?>" id="AccessDenied">
                <div class="panel-footer">
                    <span class="pull-left"> تفصیلات</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-yellow">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3" style="padding-right:;padding-right: 08px;">
                        <i class="fa fa-comments fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right" style="padding-right: 0px;padding-left: 0px;">
                        <div class="huge">گوشوارہ آمدنی و اخراجات</div>
                    </div>
                </div>
            </div>
            <a target="_blank" href="<?= site_url('Qurbani/ProfitLoss')?>" id="AccessDenied">
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
                        <i class="fa fa-tasks fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">تقا بلی جائزے فروخت کی رپورٹ</div>
                    </div>
                </div>
            </div>
            <a target="_blank" href="<?= site_url('Qurbani/ChrumQuantity/GetOldDataSale')?>" id="AccessDenied">
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
                        <i class="fa fa-tasks fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">وینڈرز رپوٹ</div>
                    </div>
                </div>
            </div>
            <a target="_blank" href="<?= site_url('Qurbani/VendorLedger/GetVendorReport')?>" id="AccessDenied">
                <div class="panel-footer">
                    <span class="pull-left"> تفصیلات</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
<!--    <div class="col-lg-3 col-md-6">-->
<!--        <div class="panel panel-yellow">-->
<!--            <div class="panel-heading">-->
<!--                <div class="row">-->
<!--                    <div class="col-xs-3">-->
<!--                        <i class="fa fa-tasks fa-5x"></i>-->
<!--                    </div>-->
<!--                    <div class="col-xs-9 text-right">-->
<!--                        <div class="huge">مصارف فی گائے</div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--            <a target="_blank" href="--><?//= site_url('Qurbani/ExpencePerCowReport')?><!--" id="AccessDenied">-->
<!--                <div class="panel-footer">-->
<!--                    <span class="pull-left"> تفصیلات</span>-->
<!--                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>-->
<!--                    <div class="clearfix"></div>-->
<!--                </div>-->
<!--            </a>-->
<!--        </div>-->
<!--    </div>-->
</div>