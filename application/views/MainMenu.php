<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title style="font-family: 'Noto Nastaliq Urdu', serif;">دارالعلوم کراچی</title>

    <link rel="stylesheet" href="<?php echo base_url()."assets/"; ?>css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url()."assets/"; ?>css/bootstrap-select.min.css">

    <!-- Bootstrap Core CSS -->
    <link href="<?php echo base_url()."assets/"; ?>css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="<?php echo base_url()."assets/"; ?>css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="<?php echo base_url()."assets/"; ?>css/plugins/dataTables.bootstrap.css" rel="stylesheet">

    <!-- Timeline CSS -->
    <link href="<?php echo base_url()."assets/"; ?>css/plugins/timeline.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?php echo base_url()."assets/"; ?>css/sb-admin-2.css" rel="stylesheet">

    <!-- CBS Custom CSS -->
    <link href="<?php echo base_url()."assets/"; ?>css/style.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ;?>assets/css/bootstrap-datepicker.css">
    <!-- Custom Fonts -->
    <link href="<?php echo base_url()."assets/"; ?>css/font-awesome/font-awesome.min.css" rel="stylesheet" type="text/css">

    <link href="<?php echo base_url()."assets/"; ?>pnotify.custom.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()."assets/"; ?>css/daterangepicker.css" />
    <link href="<?php echo base_url()."assets/"; ?>css/select2.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="<?php echo base_url()."assets/"; ?>css/jquery-ui.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>

    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <![endif]-->

</head>
<style type="text/css">
    @font-face {
        font-family: "Noto Nastaliq Urdu";
        src: url(<?php echo base_url().'assets/'; ?>fonts/NotoNastaliqUrdu-Regular.ttf) format("truetype");
    }
    @font-face {
        font-family: 'Glyphicons Halflings';
        src: url(<?php echo base_url().'assets/'; ?>fonts/glyphicons-halflings-regular.woff) format("woff");
        src: url(<?php echo base_url().'assets/'; ?>fonts/glyphicons-halflings-regular.ttf) format("truetype");
    }

    @media(min-width:992px) {
        .col-md-6 {
            width: 33%
        }
    }
</style>

<body>
<div id="wrapper">
    <div id="page-wrapper" style="margin-right: 0px;">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">اس فہرست سے انتخاب کریں:</h1>
            </div>
        </div>
        <br>
        <br>
        <br>
        <div class="row"> <?php $Rights = $this->AccessModel->Check_Module_Access($_SESSION['user'][0]->id,1);?>
            <?php if (isset($Rights[0]) || $_SESSION['user'][0]->IsAdmin == 1){?>
                <div class="col-lg-3 col-md-6">
            <?php } else{?>
                <div class="col-lg-3 col-md-6" style="opacity: 0.5">
            <?php }?>
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-comments fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge">اکاونٹنگ سسٹم</div>
                                <div></div>
                            </div>
                        </div>
                    </div>
                    <?php if (isset($Rights[0]) || $_SESSION['user'][0]->IsAdmin == 1){?>
                    <a href="<?= site_url('Accounts/Dashboard');?>">
                        <?php } else{?>
                        <a href="#">
                            <?php }?>
                        <div class="panel-footer">
                            <span class="pull-left">داخل ہوئیں</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>  <?php $Rights = $this->AccessModel->Check_Module_Access($_SESSION['user'][0]->id,2);?>
                    <?php if (isset($Rights[0]) || $_SESSION['user'][0]->IsAdmin == 1){?>
                    <div class="col-lg-3 col-md-6">
                        <?php } else{?>
                        <div class="col-lg-3 col-md-6" style="opacity: 0.5">
                            <?php }?>
                <div class="panel panel-green">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-tasks fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge">اسٹور سسٹم</div>
                                <div></div>
                            </div>
                        </div>
                    </div>
                    <?php if (isset($Rights[0]) || $_SESSION['user'][0]->IsAdmin == 1){?>
                    <!-- <a href="#"> -->
                        <a href="<?= site_url('Store/Dashboard');?>">
                        <?php } else{?>
                        <a href="#">
                            <?php }?>
                        <div class="panel-footer">
                            <span class="pull-left">داخل ہوئیں</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div><?php //$Rights = $this->AccessModel->Check_Module_Access($_SESSION['user'][0]->id,3);?>
                        <?php //if (isset($Rights[0]) || $_SESSION['user'][0]->IsAdmin == 1){?>
                        <div class="col-lg-3 col-md-6">
                            <?php // } else{?>
                            
                                <?php // } ?>
                <div class="panel panel-yellow">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-shopping-cart fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"> قربانی سسٹم</div>
                                <div></div>
                            </div>
                        </div>
                    </div>
                    <?php //if (isset($Rights[0]) || $_SESSION['user'][0]->IsAdmin == 1){ ?>
                    <a href="<?= site_url('Qurbani/Dashboard');?>">
                        <?php //} else{ ?>
                        <a href="<?= site_url('Qurbani/Dashboard');?>">
                            <?php // } ?>
                        <div class="panel-footer">
                            <span class="pull-left">داخل ہوئیں</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
<!--            <div class="col-lg-3 col-md-6">-->
<!--                <div class="panel panel-red">-->
<!--                    <div class="panel-heading">-->
<!--                        <div class="row">-->
<!--                            <div class="col-xs-3">-->
<!--                                <i class="fa fa-support fa-5x"></i>-->
<!--                            </div>-->
<!--                            <div class="col-xs-9 text-right">-->
<!--                                <div class="huge">13</div>-->
<!--                                <div>Support Tickets!</div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <a href="#">-->
<!--                        <div class="panel-footer">-->
<!--                            <span class="pull-left">View Details</span>-->
<!--                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>-->
<!--                            <div class="clearfix"></div>-->
<!--                        </div>-->
<!--                    </a>-->
<!--                </div>-->
<!--            </div>-->
        </div>
    </div>
</div>
<!-- /#wrapper -->
<!-- jQuery Version 1.11.0 -->
<script src="<?php echo base_url()."assets/"; ?>js/jquery-1.11.0.js"></script>
<!-- Bootstrap Core JavaScript -->
<script src="<?php echo base_url()."assets/"; ?>js/bootstrap.min.js"></script>
<!-- Metis Menu Plugin JavaScript -->
<script src="<?php echo base_url()."assets/"; ?>js/metisMenu/metisMenu.min.js"></script>
<!-- Morris Charts JavaScript -->
<script src="<?php echo base_url()."assets/"; ?>js/raphael/raphael.min.js"></script>
<script src="<?php echo base_url()."assets/"; ?>js/morris/morris.min.js"></script>
<!-- Custom Theme JavaScript -->
<script src="<?php echo base_url()."assets/"; ?>js/sb-admin-2.js"></script>
</body>
</html>