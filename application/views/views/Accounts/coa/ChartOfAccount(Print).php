<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <style type="text/css">
        @font-face {
            font-family: "Noto Nastaliq Urdu";
            src: url(<?php echo base_url().'assets/'; ?>fonts/NotoNastaliqUrdu-Regular.ttf) format("truetype");
        }
    </style>
    <title style="font-family: 'Noto Nastaliq Urdu', serif;">دارالعلوم کراچی</title>
    
    <link href="<?php echo base_url()."assets/"; ?>css/bootstrap.min.css" rel="stylesheet">
    <link href='<?php echo base_url()."assets/"; ?>css/font-awesome/font-awesome.min.css' rel='stylesheet' type='text/css'> 
    <style>
        body{
            font-family: 'Noto Nastaliq Urdu', serif;
        }
         /*.ch{
                font-family: 'Noto Nastaliq Urdu', serif;
        }*/

        .panel-body{
            padding: 0px;
        }
        @media print {
            html, body{
                width:97%;
                height:auto;
                margin-right: 1%;
                margin-left: -20%;
                padding:0;
                margin-top: 2%;
                margin-bottom: 1%;

            }
        }
        
    </style>
</head>
<body>
<div class="row" >
    <div style="text-align: center; margin-top: 2%;">
        <img src="<?php echo base_url()?>assets/images/logo.jpg" align="middle" style="width:42%; max-width:330px;">
    </div>
    <?php //print_r($Links);?>
    <h4 style="margin-right: 6%; margin-top: 3%; margin-bottom: -3%; font-weight: bold; font-size: 30px;"> اکاونٹس کا چارٹ</h4>
    <h3 style="font-weight: normal; margin-right: 80%; margin-bottom: 1%;"><?php echo $LevelName[0]->LevelName?></h3>
    <div class="col-lg-12" style="padding-left: 5%; padding-right: 5%;">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="container" >
                    <div class="row">
                        <div class="col-md-12">
                            <?php foreach ($Accounts as $key => $Account){
                                foreach ($Account as $key_1 => $level_1) {
                                    if (is_numeric($key_1)){
                                        if ($Links[$key][$key_1] == 'y'){ ?>
                                        <ul style="margin-top: 2%;">
                                            <?php if($level_1->Category == 3){ ?>
                                            <li class="fa fa-dot-circle-o ch"><a><?= $level_1->AccountName?></a>

                                            <?php }else{ ?><a style="font-family: 'Noto Nastaliq Urdu', serif; color:#000;">
                                                <li class="fa fa-folder-open-o"><?= $level_1->AccountName .'-'.$level_1->AccountCode?></a>
                                        <?php    }
                                                if (isset($Account['Child'.$key_1])){
                                                    foreach ($Account['Child'.$key_1] as $key_2 => $level_2){
                                                        if (is_numeric($key_2)){
                                                            if (isset($Links[$key]['Child'.$key_1][$key_2]) && $Links[$key]['Child'.$key_1][$key_2] == 'y'){ ?>
                                                            <ul style="margin-top: 3%;">
                                                            <?php if($level_2->Category == 2){?> <a style="font-family: 'Noto Nastaliq Urdu', serif; color: #000;">
                                                                <li class="fa fa-dot-circle-o"><?= $level_2->AccountName.'-'.$level_2->AccountCode;?></a>
                                                                <?php }else{?><a style="font-family: 'Noto Nastaliq Urdu', serif; color: #000;">
                                                                <li class="fa fa-folder-open-o"><?= $level_2->AccountName.'-'.$level_2->AccountCode;?></a>
                                                                <?php }
                                                                    if (isset($Account['Child'.$key_1]['Child'.$key_2])){
                                                                        foreach ($Account['Child'.$key_1]['Child'.$key_2] as $key_3 => $level_3) {
                                                                            if (is_numeric($key_3)){ //print_r($Links[0]['Child0']['Child0'][0]);//print_r($key.'-'.$key_1.'-'.$key_2.'-'.$key_3);
                                                                                if (isset($Links[$key]['Child'.$key_1]['Child'.$key_2][$key_3]) && $Links[$key]['Child'.$key_1]['Child'.$key_2][$key_3] == 'y'){ ?>
                                                                            <ul style="margin-top: 3%;">
                                                                            <?php if($level_3->Category == 2){?><a style="font-family: 'Noto Nastaliq Urdu', serif; color: #000;">
                                                                                <li class="fa fa-dot-circle-o"><?= $level_3->AccountName.'-'.$level_3->AccountCode;?></a>
                                                                                <?php } else{?><a style="font-family: 'Noto Nastaliq Urdu', serif; color: #000;">
                                                                                <li class="fa fa-folder-open-o"><?= $level_3->AccountName.'-'.$level_3->AccountCode;?></a>
                                                                                <?php }
                                                                                    if (isset($Account['Child'.$key_1]['Child'.$key_2]['Child'.$key_3])){
                                                                                        foreach ($Account['Child'.$key_1]['Child'.$key_2]['Child'.$key_3] as $key_4 => $level_4) {
                                                                                            if (is_numeric($key_4)){
                                                                                                if (isset($Links[$key]['Child'.$key_1]['Child'.$key_2]['Child'.$key_3][$key_4]) && $Links[$key]['Child'.$key_1]['Child'.$key_2]['Child'.$key_3][$key_4] == 'y'){ ?>
                                                                                                <ul style="margin-top: 3%;">
                                                                                                    <?php if($level_4->Category == 2){ ?><a style="font-family: 'Noto Nastaliq Urdu', serif; color: #000;">
                                                                                                    <li class="fa fa-dot-circle-o"><?= $level_4->AccountName.'-'.$level_4->AccountCode;?></a>
                                                                                                        <?php }else{ ?> <a style="font-family: 'Noto Nastaliq Urdu', serif; color: #000;">
                                                                                                    <li class="fa fa-folder-open-o"><?= $level_4->AccountName.'-'.$level_4->AccountCode;?></a>
                                                                                                        <?php }
                                                                                                        if ($Account['Child'.$key_1]['Child'.$key_2]['Child'.$key_3]['Child'.$key_4]){
                                                                                                            foreach ($Account['Child'.$key_1]['Child'.$key_2]['Child'.$key_3]['Child'.$key_4] as $key_5 => $level_5) {
                                                                                                                if (is_numeric($key_5)){
                                                                                                                    if ($Links[$key]['Child'.$key_1]['Child'.$key_2]['Child'.$key_3]['Child'.$key_4][$key_5] && $Links[$key]['Child'.$key_1]['Child'.$key_2]['Child'.$key_3]['Child'.$key_4][$key_5] == 'y'){ ?>
                                                                                                                    <ul style="margin-top: 3%;">
                                                                                                                    <?php if($level_5->Category == 2){?><a style="font-family: 'Noto Nastaliq Urdu', serif; color: #000;">
                                                                                                                        <li class="fa fa-dot-circle-o"><?= $level_5->AccountName.'-'.$level_5->AccountCode;?></a>
                                                                                                                        <?php }else{?> <a style="font-family: 'Noto Nastaliq Urdu', serif; color: #000;">
                                                                                                                        <li class="fa fa-folder-open-o"><?= $level_5->AccountName.'-'.$level_5->AccountCode;?></a>
                                                                                                                        <?php }
                                                                                                                        if ($Account['Child'.$key_1]['Child'.$key_2]['Child'.$key_3]['Child'.$key_4]['Child'.$key_5]){
                                                                                                                            foreach ($Account['Child'.$key_1]['Child'.$key_2]['Child'.$key_3]['Child'.$key_4]['Child'.$key_5] as $key_6 => $level_6) {
                                                                                                                                if (is_numeric($key_6)){
                                                                                                                                    if ($Links[$key]['Child'.$key_1]['Child'.$key_2]['Child'.$key_3]['Child'.$key_4]['Child'.$key_5][$key_6] && $Links[$key]['Child'.$key_1]['Child'.$key_2]['Child'.$key_3]['Child'.$key_4]['Child'.$key_5][$key_6] == 'y'){ ?>
                                                                                                                                    <ul style="margin-top: 3%;">
                                                                                                                                    <?php if($level_6->Category == 2){?><a style="font-family: 'Noto Nastaliq Urdu', serif; color: #000;">
                                                                                                                                        <li class="fa fa-dot-circle-o"><?= $level_6->AccountName.'-'.$level_6->AccountCode;?></a>
                                                                                                                                        <?php } else{?> <a style="font-family: 'Noto Nastaliq Urdu', serif; color: #000;">
                                                                                                                                        <li class="fa fa-folder-open-o"><?= $level_6->AccountName.'-'.$level_6->AccountCode;?></a>
                                                                                                                                        <?php }
                                                                                                                                            if ($Account['Child'.$key_1]['Child'.$key_2]['Child'.$key_3]['Child'.$key_4]['Child'.$key_5]['Child'.$key_6]){
                                                                                                                                                foreach ($Account['Child'.$key_1]['Child'.$key_2]['Child'.$key_3]['Child'.$key_4]['Child'.$key_5]['Child'.$key_6] as $key_7 => $level_7) {
                                                                                                                                                    if (is_numeric($key_7)){
                                                                                                                                                        if ($Links[$key]['Child'.$key_1]['Child'.$key_2]['Child'.$key_3]['Child'.$key_4]['Child'.$key_5]['Child'.$key_6][$key_7] && $Links[$key]['Child'.$key_1]['Child'.$key_2]['Child'.$key_3]['Child'.$key_4]['Child'.$key_5]['Child'.$key_6][$key_7] == 'y'){ ?>
                                                                                                                                                        <ul style="margin-top: 3%;">
                                                                                                                                                        <?php if($level_7->Category == 2){?><a style="font-family: 'Noto Nastaliq Urdu', serif; color: #000;">
                                                                                                                                                            <li class="fa fa-dot-circle-o"><?= $level_7->AccountName.'-'.$level_7->AccountCode;?></a>
                                                                                                                                                            <?php }else{?><a style="font-family: 'Noto Nastaliq Urdu', serif; color: #000;">
                                                                                                                                                            <li class="fa fa-folder-open-o" style="font-size: 18px;"><?= $level_7->AccountName.'-'.$level_7->AccountCode; } ?></a>
                                                                                                                                                                
                                                                                                                                                            </li>
                                                                                                                                                        </ul>
                                                                                                                                            <?php } } } } ?>
                                                                                                                                        </li>
                                                                                                                                    </ul>
                                                                                                                                <?php } } } } ?>

                                                                                                                        </li>
                                                                                                                    </ul>
                                                                                                                <?php } } } } ?>
                                                                                                    </li>
                                                                                                </ul>
                                                                                            <?php } } } } ?>
                                                                                </li>
                                                                            </ul>
                                                                        <?php } } } }?>
                                                                </li>
                                                            </ul>
                                                    <?php } } } }?>
                                            </li>
                                        </ul>
                            <?php } } } } ?>

<!--                            <ul id="">-->
<!--                                <li>-->
<!--                                    <a style="color: #004c96;font-size: 18px;" class="a_Edit" data-id=""> 1Head Name</a>-->
<!--                                    <ul>-->
<!--                                        <li><a  style="color: #000" class="a_Edit" data-id="">Account Name</a>-->
<!---->
<!--                                        <li><a  style="color: #004c96;font-size: 18px;" class="a_Edit" data-id="" ></a>-->
<!---->
<!--                                        <li><a  style="color: #000" class="a_Edit" data-id="" >2</a>-->
<!--                                            <ul>-->
<!--                                                <li><a  style="color: #000" class="a_Edit" data-id="" ></a>-->
<!--                                                <li><a  style="color: #004c96;font-size: 18px;" class="a_Edit" data-id="" ></a>-->
<!--                                                <li><a  style="color: #000" class="a_Edit" data-id="" >3</a>-->
<!--                                                    <ul>-->
<!--                                                        <li><a  style="color: #000" class="a_Edit" data-id="" ></a>-->
<!--                                                        <li><a  style="color: #004c96;font-size: 18px;" class="a_Edit" data-id="" ></a>-->
<!--                                                        <li><a  style="color: #000" class="a_Edit" data-id="" >4</a>-->
<!--                                                            <ul>-->
<!--                                                                <li><a  style="color: #000" class="a_Edit" data-id="" ></a>-->
<!--                                                                <li><a  style="color: #004c96;font-size: 18px;" class="a_Edit" data-id="" ></a>-->
<!--                                                                <li><a  style="color: #000" class="a_Edit" data-id="" >5</a>-->
<!--                                                                    <ul>-->
<!--                                                                        <li><a  style="color: #000" class="a_Edit" data-id="" ></a>-->
<!--                                                                        <li><a  style="color: #004c96;font-size: 18px;" class="a_Edit" data-id="" ></a></li>-->
<!--                                                                        <li><a  style="color: #000" class="a_Edit" data-id="" >6</a></li>-->
<!--                                                                            <ul>-->
<!--                                                                                <li><a  style="color: #000" class="a_Edit8" data-id="" ></a></li>-->
<!--                                                                                <li><a  style="color: #004c96;font-size: 18px;" class="a_Edit8" data-id="" ></a></li>-->
<!--                                                                                <li><a  style="color: #000" class="a_Edit8" data-id="" >7</a>-->
<!--                                                                                </li>-->
<!--                                                                            </ul>-->
<!--                                                                        </li>-->
<!--                                                                    </ul>-->
<!--                                                                </li>-->
<!--                                                            </ul>-->
<!--                                                        </li>-->
<!--                                                    </ul>-->
<!--                                                </li>-->
<!--                                            </ul>-->
<!--                                        </li>-->
<!--                                    </ul>-->
<!--                                </li>-->
<!--                            </ul>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<footer>
    <div style="direction: ltr; margin-left: 4%; margin-right: 4%;"  id="pagebreak">
        <hr> <?php date_default_timezone_set('Asia/Karachi');?>
        <p><?php echo date('l d-m-Y h:i:s');?></p>
        <hr>
    </div>
</footer>
</html>