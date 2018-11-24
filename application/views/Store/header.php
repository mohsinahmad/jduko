<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title style="font-family: 'Noto Nastaliq Urdu', serif;">دارالعلوم کراچی</title>
    <link href="<?= base_url()."assets/css/"?>bootstrap.min.css" rel="stylesheet" >
    <link href="<?= base_url()."assets/css/"?>bootstrap-select.min.css" rel="stylesheet" >
    <link href="<?= base_url()."assets/css/"?>bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url()."assets/css/"?>plugins/metisMenu/metisMenu.min.css" rel="stylesheet">
    <link href="<?= base_url()."assets/css/"?>plugins/dataTables.bootstrap.css" rel="stylesheet">
    <link href="<?= base_url()."assets/css/"?>plugins/timeline.css" rel="stylesheet">
    <link href="<?= base_url()."assets/css/"?>sb-admin-2.css" rel="stylesheet">
    <link href="<?= base_url()."assets/css/"?>style.css" rel="stylesheet">
    <link href="<?= base_url()."assets/css/"?>bootstrap-datepicker.css" rel="stylesheet" type="text/css" >
    <link href="<?= base_url()."assets/css/"?>font-awesome/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="<?= base_url()."assets/"?>pnotify.custom.min.css" rel="stylesheet">
    <link href="<?= base_url()."assets/css/"?>daterangepicker.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url()."assets/css/"?>select2.min.css" rel="stylesheet" />
    <link href="<?= base_url()."assets/css/"?>jquery-ui.css" rel="stylesheet" >
    <script src="<?= base_url().'assets/'; ?>js/jquery/jquery.min.js"></script>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>

    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <script src="<?= base_url().'assets/'; ?>js/jquery.min.js"></script>
    <script src="<?= base_url().'assets/'; ?>js/bootstrap.min.js"></script>
    <![endif]-->

</head>
<style type="text/css">
    @font-face {
        font-family: "Noto Nastaliq Urdu";
        src: url(<?= base_url().'assets/'; ?>fonts/NotoNastaliqUrdu-Regular.ttf) format("truetype");
    }
    @font-face {
        font-family: 'Glyphicons Halflings';
        src: url(<?= base_url().'assets/'; ?>fonts/glyphicons-halflings-regular.woff) format("woff");
        src: url(<?= base_url().'assets/'; ?>fonts/glyphicons-halflings-regular.ttf) format("truetype");
    }
</style>
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
                <a class="dropdown-toggle link" data-toggle="dropdown" href="">
                    <?php
                    if($this->session->userdata('name')): ?>
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
            <li class="dropdown" style="">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i> <?=($_SESSION['user'][0]->UserName);?>
                </a>
                <ul class="dropdown-menu dropdown-user" style=""> <?php $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,10);?>
                    </li><?php if($this->session->userdata('in_use')){
                        if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[0] == '1')){?>
                            <li><a href="<?= site_url('Users');?>/index/Store"><i class="fa fa-gear fa-fw"></i>  ترتیب</a>
                            </li><?php }}  $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,1);
                    if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[0] == '1')){?>
                        <li><a href="<?= site_url('Accounts/Department')?>"><i class="fa fa-gear fa-fw"></i>  شعبے</a>
                        </li><?php } ?>

                    <?php $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,37);?>
                    </li><?php if($this->session->userdata('in_use')){
                        if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[0] == '1')){?>
                            <li><a href="<?= site_url('Store/Supplier')?>"><i class="fa fa-gear fa-fw"></i> سپلائر</a>
                            </li>
                        <?php }}?>
                    </li><?php if($this->session->userdata('in_use')){
                        if($_SESSION['user'][0]->id > 1){
                            ?>
                            <li><a href="<?= site_url('Users/UpdatePassword');?>/Store"><i class="fa fa-gear fa-fw"></i>  پاس ورڈ تبدیل کریں</a>
                            </li><?php }} ?>
                    <li class="divider"></li>
                    <li><a id="clear" href="<?= site_url('login/logout');?>"><i class="fa fa-sign-out fa-fw"></i> لاگ آوٹ</a>
                    </li>
                </ul>
            </li>
        </ul>
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
                        <a href="<?= base_url().'Store/Dashboard';?>"  style="font-size: 18px; font-weight: 600;"><i class="fa fa-dashboard fa-fw"></i>  ڈیش بورڈ</a>
                    </li>
                    <?php if($this->session->userdata('name')): ?>
                        <li id="cashComp">
                            <a href="#"><i class="fa fa-company fa-fw id"></i><?= $this->session->userdata('name');?></a>
                        </li>
                    <?php else: ?>
                        <li id="cashComp">

                        </li>
                    <?php endif ?>
                    <li class="nav nav-second-level">
                        <?php $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,21);
                        if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[0] == '1')){?>
                    <li class='active'>
                        <a href="<?= site_url('Store/DonationType');?>">تعاوّن کی اقسام</a>
                    </li>
                    <li class='active'>
                        <a href="<?= site_url('Store/unit_of_measure');?>">پیمائش کی اکائی</a>
                    </li>
                <?php } $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,22);
                if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[0] == '1')){?>
                    <li class='active'>
                        <a id="cat_id" href="<?= site_url('Store/Category');?>">آئٹم کیٹیگری</a>
                    </li>
                <?php } $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,23);
                if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[0] == '1')){?>
                    <li class='active'>
                        <a id="itemsetup" href="<?= site_url('Store/items/ItemSetup')?>"> آئٹم سیٹ اپ</a>
                    </li>
                <?php } $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,24);
                if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[0] == '1')){?>
                    <li class='active'>
                        <a id="demand" href="<?= site_url('Store/DemandForm');?>">مطلوب اشیاء</a>
                    </li>
                <?php } $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,25);
                if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[0] == '1')){?>
                    <li class='active'>
                        <a id="agree" href="<?= site_url('Store/ApproveDemand');?>">ڈیمانڈز کی منظوری</a>
                    </li>
                   <!-- <li class='active'>
                        <a href="<?php //site_url('Store/ApproveDemand/ApproveDemand_done');?>">منظور شدہ ڈیمانڈ</a>
                    </li>-->
                <?php } $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,26);
                if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[0] == '1')){?>
                    <li class='active'>
                        <a id="item_issue" href="<?= site_url('Store/ItemIssue');?>">اشیاء کا اجراء</a>
                    </li>
                <?php } $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,29);
                if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[0] == '1')){?>
                    <li class='active'>
                        <a id="issue" href="<?= site_url('Store/ItemReturn');?>">جاری شدہ اشیاء</a>
                    </li>
                <?php } $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,35);
                if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[0] == '1')){?>
                    <li class='active'>
                        <a id="return" href="<?= site_url('Store/ItemReturn/ReturnItem');?>">واپس کردہ اشیاء</a>
                    </li>
                <?php } $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,30);
                if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[0] == '1')){?>
                    <li class='active'>
                        <a id="itemrecieve" href="<?= site_url('Store/StockSlip');?>">اسٹاک وصولی</a>
                    </li>
                    <?php } $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,30);
                    if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[0] == '1')){?>
                        <li class='active'>
                            <a id="itemrecieve" href="<?= site_url('Store/StockSlip/direct_issue');?>">فوری اجراء</a>
                        </li>
                        <li class='active'>
                            <a id="itemrecieve" href="<?= site_url('Store/StockSlip/get_issue_voucher');?>">فوری اجراء آرکئیو</a>                        </li>
                    <?php }?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div id="page-wrapper">
        <script>
            $(document).ready(function(){
                validation();
                change_url('itemsetup','');
                change_url('cat_id','index');
                change_url('itemrecieve','index');
                change_url('demand','index');
                change_url('agree','index');
                change_url('item_issue','index');
                change_url('issue','index');
                change_url('return','');
                $('#clear').click(function(){
                    localStorage.removeItem('cat_type');
                });
            });
            function change_url(id,method){
                //debugger;
                var caytype = localStorage.getItem('cat_type');
                var _url = $('#'+id).attr('href');
                var url = $('#'+id).attr('href')+'/'+method+'/'+caytype;
                if(_url != url ){
                    $('#'+id).attr('href',url);
                }
            }
function validation(){
    var i=0;
    jQuery("input,textarea").on('keypress',function(e){
        //alert();
        if(jQuery(this).val().length < 1){
            if(e.which == 32){
                //alert(e.which);
                return false;
            }
        }
        else {
            if(e.which == 32){
                if(i != 0){
                    return false;
                }
                i++;
            }
            else{
                i=0;
            }
        }
    });
}
</script>