<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title style="font-family: 'Noto Nastaliq Urdu', serif;">اکاونٹس-دارالعلوم کراچی</title>

    <link href="<?= base_url()."assets/css/"?>bootstrap.min.css" rel="stylesheet" >
    <link href="<?= base_url()."assets/css/"?>bootstrap-select.min.css" rel="stylesheet" >
    <link href="<?= base_url()."assets/css/"?>bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url()."assets/css/"?>plugins/metisMenu/metisMenu.min.css" rel="stylesheet">
    <link href="<?= base_url()."assets/css/"?>plugins/dataTables.bootstrap.css" rel="stylesheet">
    <link href="<?= base_url()."assets/css/"?>plugins/timeline.css" rel="stylesheet">
    <link href="<?= base_url()."assets/css/"?>sb-admin-2.css" rel="stylesheet">
    <link href="<?= base_url()."assets/css/"?>style.css" rel="stylesheet">
    <link href="<?= base_url().'assets/css/'?>bootstrap-datepicker.css" rel="stylesheet" type="text/css" >
    <link href="<?= base_url()."assets/css/"?>font-awesome/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="<?= base_url()."assets/"?>pnotify.custom.min.css" rel="stylesheet">
    <link href="<?= base_url()."assets/css/"?>daterangepicker.css" rel="stylesheet" type="text/css" >
    <link href="<?= base_url()."assets/css/"?>select2.min.css" rel="stylesheet" >
    <link href="<?= base_url()."assets/css/"?>jquery-ui.css" rel="stylesheet" >

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>

    <script src="<?= base_url().'assets/js/'?>html5shiv.js"></script>
    <script src="<?= base_url().'assets/js/'?>respond.min.js"></script>
    <script src="<?= base_url().'assets/js/'?>jquery.min.js"></script>
    <script src="<?= base_url().'assets/js/'?>bootstrap.min.js"></script>
    <![endif]-->

</head>
<style type="text/css">
    @font-face {
        font-family: "Noto Nastaliq Urdu";
        src: url(<?= base_url().'assets/'?>fonts/NotoNastaliqUrdu-Regular.ttf) format("truetype");
    }
    @font-face {
        font-family: 'Glyphicons Halflings';
        src: url(<?= base_url().'assets/'?>fonts/glyphicons-halflings-regular.woff) format("woff");
        src: url(<?= base_url().'assets/'?>fonts/glyphicons-halflings-regular.ttf) format("truetype");
    }
</style>
<?php if (isset($_SESSION['comp_id'])){
    $Access_level = $_SESSION['comp_id'];
}elseif(isset($_SESSION['comp'])){
    $Access_level = $_SESSION['comp'];
}else{ $Access_level = ''; }?>
<body>
<div id="wrapper">
    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?= base_url().'Accounts/Dashboard/menu';?>">دارالعلوم کراچی</a>
        </div>
        <ul class="nav navbar-top-links navbar-left">
            <?php $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,34,$Access_level);
            if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[0] == '1')){?>
                <li class="dropdown">
                    <a class="dropdown-toggle" href="" data-toggle="dropdown">
                        <i class="fa fa-dot-circle-o fa-fw "></i>مشترکہ رپورٹس
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li>
                            <a href="<?= site_url('Accounts/Ledger/Consolidated_Ledger');?>">
                                <i style="margin-left: 80%; margin-top: 7%; font-family: 'Noto Nastaliq Urdu', serif" class="fa fa-dot-circle-o" aria-hidden="true">مشترکہ لیجر</i>
                            </a>
                        </li>
                        <li>
                            <a href="<?= site_url('Accounts/TrialBalance/getConsolidatedTrailBalance');?>">
                                <i style="margin-left: 80%; margin-top: 7%; font-family: 'Noto Nastaliq Urdu', serif" class="fa fa-dot-circle-o" aria-hidden="true">مشترکہ ٹرایل بیلنس</i>
                            </a>
                        </li>
                        <li>
                            <a href="<?= site_url('Accounts/BalanceSheet/getConsolidatedBalanceSheet');?>">
                                <i style="margin-left: 80%; margin-top: 7%; font-family: 'Noto Nastaliq Urdu', serif" class="fa fa-dot-circle-o" aria-hidden="true">مشترکہ بیلنس شیٹ</i>
                            </a>
                        </li>
                        <li>
                            <a href="<?= site_url('Accounts/IncomeStatment/getConsolidatedIncomeStatement')?>">
                                <i style="margin-left: 80%; margin-top: 7%; font-family: 'Noto Nastaliq Urdu', serif" class="fa fa-dot-circle-o" aria-hidden="true">مشترکہ آمدنی کا گوشوارہ</i>
                            </a>
                        </li>
                    </ul>
                </li>
            <?php }?>
            <li class="dropdown">
                <a class="dropdown-toggle" href="" data-toggle="dropdown">
                    <i class="fa fa-dot-circle-o fa-fw"></i> رپورٹس
                </a>
                <ul class="dropdown-menu dropdown-user">
                    <!--                        --><?php //$rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,15,$Access_level);
                    //                        if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[0] == '1')){?>
                    <!--                            <li>-->
                    <!--                                <a href="--><?//= site_url('Accounts/TrialBalance/GetData');?><!--">-->
                    <!--                                    <i style="margin-left: 48%; margin-top: 7%; font-family: 'Noto Nastaliq Urdu', serif" class="fa fa-dot-circle-o" aria-hidden="true">ٹرائل بیلینس</i>-->
                    <!--                                </a>-->
                    <!--                            </li>-->
                    <!--                        --><?php //} $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,19,$Access_level);
                    //                        if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[0] == '1')){?>
                    <!--                        <li>-->
                    <!--                            <a href="--><?//= site_url('Accounts/BalanceSheet');?><!--">-->
                    <!--                                <i style="margin-left: 49%; margin-top: 7%; font-family: 'Noto Nastaliq Urdu', serif" class="fa fa-gear fa-fw" >بیلنس شیٹ</i>-->
                    <!--                            </a>-->
                    <!--                        </li>-->
                    <!--                        --><?php //} $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,20,$Access_level);
                    //                        if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[0] == '1')){?>
                    <!--                        <li>-->
                    <!--                            <a href="--><?//= site_url('Accounts/IncomeStatment');?><!--">-->
                    <!--                                <i style=" margin-left: 50%; margin-top: 7%; font-family: 'Noto Nastaliq Urdu', serif" class="fa fa-dot-circle-o" aria-hidden="true">آمدنی کا گوشوارہ</i>-->
                    <!--                            </a>-->
                    <!--                        </li>-->
                    <!--                        --><?php //} $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,11,$Access_level);
                    //                        if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[0] == '1')){?>
                    <!--                        <li>-->
                    <!--                            <a href="--><?//= site_url('Accounts/Ledger/GetData');?><!--">-->
                    <!--                                <i style="margin-left: 80%; margin-top: 7%; font-family: 'Noto Nastaliq Urdu', serif" class="fa fa-dot-circle-o" aria-hidden="true">لیجر</i>-->
                    <!--                            </a>-->
                    <!--                        </li>-->
                    <?php //}
                    $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,14,$Access_level);
                    if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[0] == '1')){?>
                        <li>
                            <a href="<?= site_url('Accounts/AuditTrial');?>">
                                <i style="margin-left: 53%; margin-top: 7%; font-family: 'Noto Nastaliq Urdu', serif" class="fa fa-dot-circle-o" aria-hidden="true">آڈٹ ٹریل</i>
                            </a>
                        </li>
                    <?php } $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,18,$Access_level);
                    if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[0] == '1')){?>
                        <li>
                            <a href="<?= site_url('Accounts/IncomeReport');?>">
                                <i style="margin-left: 53%; margin-top: 7%; font-family: 'Noto Nastaliq Urdu', serif" class="fa fa-dot-circle-o" aria-hidden="true">آمدنی رپورٹ</i>
                            </a>
                        </li>
                    <?php } $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,18,$Access_level);
                    if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[0] == '1')){?>
                        <li>
                            <a href="<?= site_url('Accounts/MoveAccount');?>">
                                <i style="margin-left: 53%; margin-top: 7%; font-family: 'Noto Nastaliq Urdu', serif" class="fa fa-dot-circle-o" aria-hidden="true">اکاؤنٹ منتقل</i>
                            </a>
                        </li>
                    <?php }
                    $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,31,$Access_level);
                    if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[0] == '1')){?>
                        <li>
                            <a href="<?= site_url('Accounts/RamdanReport');?>">
                                <i style="margin-left: 53%; margin-top: 7%; font-family: 'Noto Nastaliq Urdu', serif" class="fa fa-dot-circle-o" aria-hidden="true">رمضان رپورٹ</i>
                            </a>
                        </li>
                    <?php }$rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,33,$Access_level);
                    if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[0] == '1')){?>
                        <li>
                            <a href="<?= site_url('Accounts/WeeklyReport/GetReports');?>">
                                <i style="margin-left: 53%; margin-top: 7%; font-family: 'Noto Nastaliq Urdu', serif" class="fa fa-dot-circle-o" aria-hidden="true">ہفتہ واری رپورٹس</i>
                            </a>
                        </li>
                    <?php }
                    if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[0] == '1')){?>
                        <li>
                            <a href="<?= site_url('Accounts/TaxDeductionReport/index/1');?>" target="_blank">
                                <i style="margin-left: 53%; margin-top: 7%; font-family: 'Noto Nastaliq Urdu', serif" class="fa fa-dot-circle-o" aria-hidden="true">انکم ٹیکس رپورٹ</i>
                            </a>
                        </li>
                        <li>
                            <a href="<?= site_url('Accounts/TaxDeductionReport/GetTaxDeductionReport2');?>" target="_blank">
                                <i style="margin-left: 53%; margin-top: 7%; font-family: 'Noto Nastaliq Urdu', serif" class="fa fa-dot-circle-o" aria-hidden="true">انکم ٹیکس رپورٹ 2</i>
                            </a>
                        </li>
                    <?php }?>
                    <li>
                            <a href="<?= site_url('Accounts/CashHolding');?>" target="_blank">
                                <i style="margin-left: 53%; margin-top: 7%; font-family: 'Noto Nastaliq Urdu', serif" class="fa fa-dot-circle-o" aria-hidden="true">کیش ہولڈنگنگ کی رپورٹر</i>
                            </a>
                        </li>
                </ul>
            </li>
            <li class="dropdown">
                <a class="dropdown-toggle" href="" data-toggle="dropdown">
                    <i class="fa fa-dot-circle-o fa-fw "></i> سال<?= $this->session->userdata('current_year');?>
                </a>
                <?php $years = $this->YearModel->getYears();?>
                <ul class="dropdown-menu dropdown-user">
                    <?php foreach($years as $year): ?>
                        <li><a href="" data-id="<?= $year->year; ?>" class="year"><?= $year->year; ?>  <i class="fa fa-dot-circle-o" aria-hidden="true"></i></a>
                        </li>
                    <?php endforeach ?>
                </ul>
            </li>
            <li class="dropdown">
                <a class="dropdown-toggle link" data-toggle="dropdown" href="<?php echo base_url()?>">
                    <?php if($this->session->userdata('name')): ?>
                        <?php $comp_id = $this->session->userdata('parent_id');
                        $level_name = $this->CompanyModel->get_parent_Name($comp_id); ?>
                        <i class="fa fa-link fa-fw "></i><?= $level_name.' - '.$this->session->userdata('name')?>
                    <?php else: ?>
                        <i class="fa fa-link fa-fw "></i>کمپنی
                    <?php endif ?>
                </a>
                <?php $comps = $this->BookModel->get_company();
                foreach ($comps as $comp) { $level_data[] = $this->CompanyModel->get_parent_Name($comp->ParentCode);} ?>
                <ul class="dropdown-menu dropdown-user">
                    <?php foreach($comps as $key=>$comp): ?>
                        <li id="active"><a href="" data-id="<?= $comp->id; ?>" class="comp"><?= $level_data[$key].' - '.$comp->LevelName; ?>    <i class="fa fa-dot-circle-o" aria-hidden="true"></i></a>
                        </li>
                    <?php endforeach ?>
                </ul>
            </li>
            <?php $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,9,$Access_level);
            if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[0] == '1')){?>
                <li class="dropdown">
                    <a class="dropdown-toggle" href="<?= site_url('Accounts/link');?>">
                        <i class="fa fa-link fa-fw"></i>روابط</a>
                </li>
            <?php } $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,8);
            if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[0] == '1')){?>
                <li class="dropdown">
                    <a class="dropdown-toggle" href="<?= site_url('Accounts/ChartOfAccounts');?>">
                        <i class="fa fa-bank fa-fw"></i>اکاونٹس کا چارٹ</a>
                </li>
            <?php } $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,7);
            if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[0] == '1')){?>
                <li class="dropdown" style="">
                    <a class="dropdown-toggle"  href="<?= site_url('Accounts/CompanyStructures');?>"><i class="fa fa-tag fa-fw"></i>کمپنی کی ساخت
                    </a>
                </li> <?php }?>
            <li class="dropdown" style="">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i> <?=($_SESSION['user'][0]->UserName);?>
                </a>
                <ul class="dropdown-menu dropdown-user" style=""> <?php $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,10,$Access_level);?>
                    </li>
                    <?php if($this->session->userdata('in_use')){
                        if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[0] == '1')){?>
                            <li><a href="<?= site_url('Users');?>/index/Accounts"><i class="fa fa-gear fa-fw"></i>  ترتیب</a>
                            </li><?php }}  $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,1);
                    if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[0] == '1')){?>
                        <li><a href="<?= site_url('Accounts/Department')?>"><i class="fa fa-gear fa-fw"></i>  شعبے</a>
                        </li><?php } ?>

                    </li><?php if($this->session->userdata('in_use')){
                        if($_SESSION['user'][0]->id > 1){
                            ?>
                            <li><a href="<?= site_url('Users/UpdatePassword');?>/Accounts"><i class="fa fa-gear fa-fw"></i>  پاس ورڈ تبدیل کریں</a>
                            </li><?php }} ?>
                    <li><a href="<?= site_url('Accounts/Supplier')?>"><i class="fa fa-gear fa-fw"></i> سپلائر</a>
                    </li>
                    <?php $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,32,$Access_level);
                    if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[0] == '1')){?>
                        <li>
                            <a href="<?= site_url('Accounts/ReportConfigurations')?>"><i class="fa fa-gear fa-fw"></i>رپورٹس کا اندراج</a>
                        </li>
                        <li>
                        <a href="<?= site_url('Accounts/Configuration')?>"><i class="fa fa-gear fa-fw"></i>رپورٹس کی کنفیگریشن</a>
                        </li><?php }?>
                    <li class="divider"></li>
                    <li><a href="<?= site_url('login/logout');?>"><i class="fa fa-sign-out fa-fw"></i> لاگ آوٹ</a>
                    </li>
                </ul>
            </li>
        </ul>
        <div id="accessrights">
            <?php $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,1,$Access_level);if($_SESSION['user'][0]->IsAdmin != 1 && $rights != array()){ ?>
                <input type="hidden" id="Crigths" value="<?= $rights[0]->Rights[0]?>">
            <?php } $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,2,$Access_level); if($_SESSION['user'][0]->IsAdmin != 1 && $rights != array()){ ?>
                <input type="hidden" id="Brigths" value="<?= $rights[0]->Rights[0]?>">
            <?php } $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,12,$Access_level); if($_SESSION['user'][0]->IsAdmin != 1 && $rights != array()){ ?>
                <input type="hidden" id="Jrigths" value="<?= $rights[0]->Rights[0]?>">
            <?php }$rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,17,$Access_level); if($_SESSION['user'][0]->IsAdmin != 1 && $rights != array()){ ?>
                <input type="hidden" id="ICrigths" value="<?= $rights[0]->Rights[0]?>">
            <?php }?>
        </div>
        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav navbar-collapse">
                <ul class="nav" id="side-menu">
                    <li class="sidebar-search">
                        <div class="input-group custom-search-form">
                            <input type="text" name="mynav" id="mynav" class="form-control" placeholder="تلاش کریں...">
                            <span class="input-group-btn">
                                    <button class="btn btn-default" type="button">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                        </div>
                    </li>
                    <li>
                        <a href="<?= base_url().'Accounts/Dashboard';?>"  style="font-size: 18px; font-weight: 600;"><i class="fa fa-dashboard fa-fw"></i>  ڈیش بورڈ</a>
                    </li>
                    <?php if($this->session->userdata('name')): ?>
                        <li id="cashComp">
                            <a href="#"><i class="fa fa-company fa-fw id"></i><?= $this->session->userdata('name');?></a>
                        </li>
                    <?php else: ?>
                        <li id="cashComp">

                        </li>
                    <?php endif ?>
                    <?php if($this->session->userdata('comp_id')): ?>
                        <li class="nav nav-second-level cmenu">
                        <?php $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,1,$Access_level);
                        if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[0] == '1')){?>
                            <li class='active'>
                                <a href="<?= site_url('Accounts/Books/AllBooks/cr/').$this->session->userdata('comp_id')?>">کیش وصولی</a>
                            </li>
                            <li class='active'>
                                <a href="<?= site_url('Accounts/Books/AllBooks/cp/').$this->session->userdata('comp_id')?>">کیش ادائیگی </a>
                            </li> <?php } $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,2,$Access_level);
                        if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[0] == '1')){?>
                            <li class='active'>
                                <a href="<?= site_url('Accounts/Books/AllBooks/br/').$this->session->userdata('comp_id')?>">بینک وصولی</a>
                            </li>
                            <li class='active'>
                                <a href="<?= site_url('Accounts/Books/AllBooks/bp/').$this->session->userdata('comp_id')?>">بینک ادائیگی </a>
                            </li> <?php } $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,12,$Access_level);
                        if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[0] == '1')){?>
                            <li class='active'>
                                <a href="<?= site_url('Accounts/Books/AllBooks/jv/').$this->session->userdata('comp_id')?>"> جنرل جرنل</a>
                            </li>
                        <?php } $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,17,$Access_level);
                        if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[0] == '1')){?>
                            <li class='active'>
                            <a href="<?= site_url('Accounts/Books/AllBooks/inc/').$this->session->userdata('comp_id')?>"> آمدنی واؤچر</a>
                            </li><?php } ?>
                        </li>
                    <?php else:?>
                        <li class="nav nav-second-level cmenu">

                        </li>
                    <?php endif?>
                </ul>
            </div>
        </div>
    </nav>
    <div id="page-wrapper">
        <?php if(isset($Hdate)){?>
        <input type="hidden" id="userID" data-id="<?= $Hdate[0]->day;?>" value="<?php print_r($_SESSION['user'][0]->id);?>">
<?php }?>