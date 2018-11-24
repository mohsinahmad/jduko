<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate"/>
        <meta http-equiv="Pragma" content="no-cache"/>
        <meta http-equiv="Expires" content="0"/>

        <title style="font-family: 'Noto Nastaliq Urdu', serif;">قربانی-دارالعلوم کراچی</title>

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
    </head>
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
                <a class="navbar-brand" href="<?= base_url().'Qurbani/Dashboard';?>">دارالعلوم کراچی</a>
            </div>
            <ul class="nav navbar-top-links navbar-left">
                <li class="dropdown" style="">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i> <?=($_SESSION['user'][0]->UserName);?>
                    </a>
                    <ul class="dropdown-menu dropdown-user" style=""> 
                        </li>
                        
                        <li><a href="<?= site_url('login/logout');?>"><i class="fa fa-sign-out fa-fw"></i> لاگ آوٹ</a>
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
                            <a href="<?= base_url().'Qurbani/Dashboard';?>"  style="font-size: 18px; font-weight: 600;"><i class="fa fa-dashboard fa-fw"></i>  ڈیش بورڈ</a>
                        </li>
                        <li class="nav nav-second-level">
                        <li class='active'>
                            <a href="<?= site_url('Qurbani/Config/Share_Amount')?>"><i class="fa fa-company fa-fw id"></i>رقوم حصص / خدمت</a>
                        </li>
                        </li>
                        <li class="nav nav-second-level cmenu">
                        <li class='active'>
                            <a href="<?= site_url('Qurbani/Config/QurbaniTime')?>"><i class="fa fa-company fa-fw id"></i>اوقات برائے قربانی</a>
                        </li>
                        </li>
                        <li class="nav nav-second-level">
                        <li class='active'>
                            <a href="<?= site_url('Qurbani/Hulqy')?>"><i class="fa fa-company fa-fw id"></i>حلقے</a>
                        </li>
                        </li>
                        <li class="nav nav-second-level">
                        <li class='active'>
                            <a href="<?= site_url('Qurbani/ChrumAmount')?>"><i class="fa fa-company fa-fw id"></i>قیمت چرم</a>
                        </li>
                        </li>
                        <li class="nav nav-second-level cmenu">
                            <li class='active'>
                                <a href="<?= site_url('Qurbani/ExpenceType')?>"><i class="fa fa-company fa-fw id"></i>اخراجات کی قسم</a>
                            </li>
                        </li>
                        <li class="nav nav-second-level cmenu">
                            <li class='active'>
                                <a href="<?= site_url('Qurbani/ExpenceDetail')?>"><i class="fa fa-company fa-fw id"></i>مصارف کی تفصیل</a>
                            </li>
                        </li>
                        <li class="nav nav-second-level cmenu">
                        <li class='active'>
                                <a href="<?= site_url('Qurbani/ExpenceEstimation')?>"><i class="fa fa-company fa-fw id"></i>مصارف کا تخمینہ</a>
                        </li>
                        </li>
                        <li class="nav nav-second-level cmenu">
                        <li class='active'>
                            <a href="<?= site_url('Qurbani/Receipt/index')?>"><i class="fa fa-company fa-fw id"></i>نئی رسید</a>
                        </li>
                        </li>
                        <li class="nav nav-second-level">
                            <li class='active'>
                                <a href="<?= site_url('Qurbani/Transfer')?>"><i class="fa fa-company fa-fw id"></i>رقم کی منتقلی کی تفصیل</a>
                            </li>
                        </li>
                        <li class="nav nav-second-level">
                            <li class='active'>
                                <a href="<?= site_url('Qurbani/ExpenceDetail/ExpenceVoucherList')?>"><i class="fa fa-company fa-fw id"></i>ادائیگی واوچرز</a>
                            </li>
                        </li>
                        <li class="nav nav-second-level">
                            <li class='active'>
                                <a href="<?= site_url('Qurbani/ChrumQuantity')?>"><i class="fa fa-company fa-fw id"></i>رسید برائے وصولی چرم</a>
                            </li>
                        </li>
                        <li class="nav nav-second-level cmenu">
                        <li class='active'>
                            <a href="<?= site_url('Qurbani/Vendor')?>"><i class="fa fa-company fa-fw id"></i>وینڈرز</a>
                        </li>
                        </li>
                        <li class="nav nav-second-level">
                            <li class='active'>
                                <a href="<?= site_url('Qurbani/SaleSlip')?>"><i class="fa fa-company fa-fw id"></i>سیل سلپ چرم قربانی</a>
                            </li>
                        </li>
                        <li class="nav nav-second-level">
                            <li class='active'>
                                <a href="<?= site_url('Qurbani/CashReciept')?>"><i class="fa fa-company fa-fw id"></i>کیش رسید</a>
                            </li>
                        </li>
                        <li class="nav nav-second-level">
                            <li class='active'>
                                <a href="<?= site_url('Qurbani/RewardList')?>"><i class="fa fa-company fa-fw id"></i>انعامی حقداران</a>
                            </li>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div id="page-wrapper">