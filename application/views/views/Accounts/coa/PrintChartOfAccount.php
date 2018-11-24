<style type="text/css">

    .myinput{

        height: 48px!important;

    }

    .tree, .tree ul {

        margin:0;

        padding:0;

        list-style:none

    }

    .tree ul {

        margin-left:1em;

        position:relative

    }

    .tree ul ul {

        margin-left:.5em

    }

    .tree ul:before {



        display:block;

        width:0;

        position:absolute;

        top:0;

        bottom:0;

        left:0;

        border-left:1px solid

    }

    .tree li {

        margin:0;

        padding:0 1em;

        line-height:2em;

        color:#369;

        font-weight:700;

        position:relative

    }

    .tree ul li:before {

        display:block;

        width:10px;

        height:0;

        border-top:1px solid;

        margin-top:-1px;

        position:absolute;

        top:1em;

        left:0

    }

    .tree ul li:last-child:before {

        background:#fff;

        height:auto;

        top:1em;

        bottom:0

    }

    .indicator {

        margin-right:5px;

    }

    .tree li a {

        text-decoration: none;

        color:#369;

    }

    .tree li button, .tree li button:active, .tree li button:focus {

        text-decoration: none;

        color:#369;

        border:none;

        background:transparent;

        margin:0px 0px 0px 0px;

        padding:0px 0px 0px 0px;

        outline: 0;

    }

    input::-webkit-outer-spin-button,

    input::-webkit-inner-spin-button {

        /* display: none; <- Crashes Chrome on hover */

        -webkit-appearance: none;

        margin: 0; /* <-- Apparently some margin are still there even though it's hidden */

    }

    input[type="number"] {

        -webkit-appearance: textfield;

    }



    @font-face {

        font-family: 'Glyphicons Halflings';

        src: url(<?php echo base_url().'assets/'; ?>fonts/glyphicons-halflings-regular.woff) format("woff");

        src: url(<?php echo base_url().'assets/'; ?>fonts/glyphicons-halflings-regular.ttf) format("truetype");

    }

</style>

<div class="row">

    <div class="col-lg-12">

        <h1 class="page-header"> اکاونٹس کا چارٹ</h1>

    </div>

</div>

<?php

    function SearchId($id ,$Links){

        //print_r($Links); exit();

        foreach ($Links as $I_key => $item){

            if (isset($item->AccountId)){

                if ($item->AccountId == $id){

                    return true ;

                }

            }

        }

        return false;

    }

?>

<div class="row" >

    <div class="col-lg-12">

        <div class="panel panel-default">

            <div class="panel-body">

                <div class="container" >

                    <div class="row">

                        <div class="col-md-12">

                            <ul id="tree1">

                                <li><a href="" style="color: #004c96;font-size: 18px;" class="a_Edit" data-id="<?php echo $heads[0]->AccountCode;?>"><?php echo $heads[0]->AccountName;?></a>
                                    <ul>

                                        <?php foreach($A_Heads as $a_heads):  
                                            if($a_heads->Category == 1){
                                                $account = $this->ChartModel->checkParent($a_heads->AccountCode);
                                                //print_r($account);
                                                ?>
                                                
                                            <?php
                                                
                                        if(is_array($account) && end($account) == 1){ 
                                            if($a_heads->Category == 2 ){
                                             ?>

                                            <li><a href="" style="color: #000" class="a_Edit" data-id="<?php echo $a_heads->AccountCode; ?>" ><?php echo $a_heads->AccountName;?></a>

                                            <?php } else{ ?>

                                            <li><a href="" style="color: #004c96;font-size: 18px;" class="a_Edit" data-id="<?php echo $a_heads->AccountCode; ?>" ><?php echo $a_heads->AccountName;?></a>

                                            <?php } } } else{ ?>
                                                <li><a href="" style="color: #000" class="a_Edit" data-id="<?php echo $a_heads->AccountCode; ?>" ><?php echo $a_heads->AccountName;?></a>
                                            <?php }  ?> 
                                                <?php $this->load->model('ChartModel'); ?>

                                                <?php $sa_heads = $this->ChartModel->get_SubHead($a_heads->AccountCode,"assets");?>

                                                <ul>

                                                    <?php foreach($sa_heads as $as_head):
                                                     if($as_head->Category == 1){
                                                        $account = $this->ChartModel->checkParent($as_head->AccountCode);
                                                         
                                                        ?>

                                                        <?php 
                                                        if (is_array($account) && end($account) == 1) {
                                                    
                                                        if($as_head->Category == 2  ){  ?>

                                                                <li><a href="" style="color: #000" class="a_Edit" data-id="<?php echo $as_head->AccountCode; ?>" ><?php echo $as_head->AccountName;?></a>

                                                            <?php } else{ ?>

                                                            <li><a href="" style="color: #004c96;font-size: 18px;" class="a_Edit" data-id="<?php echo $as_head->AccountCode; ?>" ><?php echo $as_head->AccountName;?></a>

                                                        <?php } } } elseif(end($account) == 1){ ?>

                                                         <li><a href="" style="color: #000" class="a_Edit" data-id="<?php echo $as_head->AccountCode; ?>" ><?php echo $as_head->AccountName;?></a>

                                                        <?php } ?>
                                                            <?php $sl4a_heads = $this->ChartModel->get_l4SubHead($as_head->AccountCode,"assets"); ?>

                                                            <ul>

                                                                <?php foreach($sl4a_heads as $sl4a_head): 
                                                                if ($sl4a_head->Category == 1) {
                                                                    $account = $this->ChartModel->checkParent($sl4a_head->AccountCode); 
                                                                
                                                              
                                                                ?>
                                                                    <?php if (is_array($account) && end($account) == 1) {
                                                                    
                                                                    ?>
                                                                    <?php if($sl4a_head->Category == 2){ ?>

                                                                            <li><a href="" style="color: #000" class="a_Edit" data-id="<?php echo $sl4a_head->AccountCode; ?>" ><?php echo $sl4a_head->AccountName;?></a>

                                                                        <?php } else{ ?>

                                                                        <li><a href="" style="color: #004c96;font-size: 18px;" class="a_Edit" data-id="<?php echo $sl4a_head->AccountCode; ?>" ><?php echo $sl4a_head->AccountName;?></a>

                                                                    <?php } } } elseif(end($account) == 1){?>
                                                                    <li><a href="" style="color: #000" class="a_Edit" data-id="<?php echo $sl4a_head->AccountCode; ?>" ><?php echo $sl4a_head->AccountName;?></a>
                                                                    <?php }?>
                                                                        <?php $sl5a_heads = $this->ChartModel->get_l5SubHead($sl4a_head->AccountCode,"assets"); ?>

                                                                        <ul>

                                                                            <?php foreach($sl5a_heads as $sl5a_head):
                                                                            if ($sl5a_head->Category == 1) {
                                                                                $account = $this->ChartModel->checkParent($sl5a_head->AccountCode); 
                                                                                if (is_array($account) && end($account) == 1) {
                                                                             ?>
                                                                                <?php if($sl5a_head->Category == 2){  ?>

                                                                                        <li><a href="" style="color: #000" class="a_Edit" data-id="<?php echo $sl5a_head->AccountCode; ?>" ><?php echo $sl5a_head->AccountName;?></a>

                                                                                    <?php } else{ ?>

                                                                                    <li><a href="" style="color: #004c96;font-size: 18px;" class="a_Edit" data-id="<?php echo $sl5a_head->AccountCode; ?>" ><?php echo $sl5a_head->AccountName;?></a>

                                                                                <?php } } } elseif(end($account) == 1){?>
                                                                                    <li><a href="" style="color: #000" class="a_Edit" data-id="<?php echo $sl5a_head->AccountCode; ?>" ><?php echo $sl5a_head->AccountName;?></a>
                                                                                <?php }?>
                                                                                    <?php $sl6a_heads = $this->ChartModel->get_l6SubHead($sl5a_head->AccountCode,"assets"); ?>

                                                                                    <ul>

                                                                                        <?php foreach($sl6a_heads as $sl6a_head): 
                                                                                            if ($sl6a_head->Category == 1) {
                                                                                                $account = $this->ChartModel->checkParent($sl6a_head->AccountCode); 
                                                                                                if (is_array($account) && end($account) == 1) {
                                                                                        ?>

                                                                                            <?php if($sl6a_head->Category == 2){  ?>

                                                                                                    <li><a href="" style="color: #000" class="a_Edit" data-id="<?php echo $sl6a_head->AccountCode; ?>" ><?php echo $sl6a_head->AccountName;?></a>

                                                                                                <?php } else{ ?>

                                                                                                <li><a href="" style="color: #004c96;font-size: 18px;" class="a_Edit" data-id="<?php echo $sl6a_head->AccountCode; ?>" ><?php echo $sl6a_head->AccountName;?></a>

                                                                                            <?php } } } elseif(@end($account) == 1) {?>
                                                                                            <li><a href="" style="color: #000" class="a_Edit" data-id="<?php echo $sl6a_head->AccountCode; ?>" ><?php echo $sl6a_head->AccountName;?></a>
                                                                                            <?php  } ?>
                                                                                                <?php $sl7a_heads = $this->ChartModel->get_l7SubHead($sl6a_head->AccountCode,"assets"); ?>

                                                                                                <ul>

                                                                                                    <?php foreach($sl7a_heads as $sl7a_head): 
                                                                                                        if ($sl7a_head->Category == 1) {
                                                                                                            $account = $this->ChartModel->checkParent($sl7a_head->AccountCode);
                                                                                                            if (is_array($account) && end($account) == 1) {
                                                                                                    ?>

                                                                                                        <?php if($sl7a_head->Category == 2){ ?>

                                                                                                                <li><a href="" style="color: #000" class="a_Edit8" data-id="<?php echo $sl7a_head->AccountCode; ?>" ><?php echo $sl7a_head->AccountName;?></a>

                                                                                                            <?php } else{ ?>

                                                                                                            <li><a href="" style="color: #004c96;font-size: 18px;" class="a_Edit8" data-id="<?php echo $sl7a_head->AccountCode; ?>" ><?php echo $sl7a_head->AccountName;?></a>

                                                                                                        <?php } } } elseif(end($account) == 1){?>
                                                                                                        <li><a href="" style="color: #000" class="a_Edit8" data-id="<?php echo $sl7a_head->AccountCode; ?>" ><?php echo $sl7a_head->AccountName;?></a>
                                                                                                        <?php }?>
                                                                                                            <ul>

                                                                                                                <li></li>

                                                                                                            </ul>

                                                                                                        </li>

                                                                                                    <?php endforeach ?>

                                                                                                </ul>

                                                                                            </li>

                                                                                        <?php endforeach ?>

                                                                                    </ul>

                                                                                </li>

                                                                            <?php endforeach ?>

                                                                        </ul>

                                                                    </li>

                                                                <?php endforeach ?>

                                                            </ul>

                                                        </li>

                                                    <?php endforeach ?>

                                                </ul>

                                            </li>

                                        <?php endforeach ?>

                                    </ul>

                                </li>

                                <li><a href="" style="color: #004c96;font-size: 18px;" class="a_Edit" data-id="<?php echo $heads[1]->AccountCode;?>" href=""><?php echo $heads[1]->AccountName;?></a>

                                    <ul>

                                        <?php foreach($L_Heads as $l_heads):
                                            if ($l_heads->Category == 1) {
                                            $account = $this->ChartModel->checkParent($l_heads->AccountCode);
                                            if (is_array($account) && end($account) == 1) {
                                            
                                            
                                             ?>

                                            <?php if($l_heads->Category == 2){ ?>

                                                    <li><a href="" style="color: #000" class="a_Edit" data-id="<?php echo $l_heads->AccountCode?>" ><?php echo $l_heads->AccountName;?></a>

                                                <?php }else{ ?>

                                                <li><a href="" style="color: #004c96;font-size: 18px;" class="a_Edit" data-id="<?php echo $l_heads->AccountCode?>" ><?php echo $l_heads->AccountName;?></a>

                                            <?php } } } else {?>

                                            <li><a href="" style="color: #000" class="a_Edit" data-id="<?php echo $l_heads->AccountCode?>" ><?php echo $l_heads->AccountName;?></a>

                                            <?php }?>

                                                <?php $sl_heads = $this->ChartModel->get_SubHead($l_heads->AccountCode,"libilities"); ?>

                                                <ul>

                                                    <?php foreach($sl_heads as $sl_head): 
                                                        if ($sl_head->Category == 1) {
                                                                $account = $this->ChartModel->checkParent($sl_head->AccountCode);   
                                                            if (is_array($account) && end($account) == 1) {
                                                            
                                                            ?>

                                                        <?php if($sl_head->Category == 2){  ?>

                                                                <li><a href="" style="color: #000" class="a_Edit" data-id="<?php echo $sl_head->AccountCode?>" ><?php echo $sl_head->AccountName;?></a>

                                                            <?php } else{ ?>

                                                            <li><a href="" style="color: #004c96;font-size: 18px;" class="a_Edit" data-id="<?php echo $sl_head->AccountCode?>" ><?php echo $sl_head->AccountName;?></a>

                                                        <?php } } } elseif(end($account) == 1) {?>

                                                        <li><a href="" style="color: #000" class="a_Edit" data-id="<?php echo $sl_head->AccountCode?>" ><?php echo $sl_head->AccountName;?></a>

                                                        <?php }?>

                                                            <?php $sl4l_heads = $this->ChartModel->get_l4SubHead($sl_head->AccountCode,"libilities"); ?>

                                                            <ul>

                                                                <?php foreach($sl4l_heads as $sl4l_head):
                                                                    if ($sl4l_head->Category == 1) {
                                                                        $account = $this->ChartModel->checkParent($sl4l_head->AccountCode);
                                                                        if (is_array($account) && end($account) == 1) {
                                                                        
                                                                    
                                                                         ?>

                                                                    <?php if($sl4l_head->Category == 2){ ?>

                                                                            <li><a href="" style="color: #000" class="a_Edit" data-id="<?php echo $sl4l_head->AccountCode?>" ><?php echo $sl4l_head->AccountName;?></a>

                                                                        <?php } else{ ?>

                                                                        <li><a href="" style="color: #004c96;font-size: 18px;" class="a_Edit" data-id="<?php echo $sl4l_head->AccountCode?>" ><?php echo $sl4l_head->AccountName;?></a>

                                                                    <?php } } } elseif(end($account) == 1) {?>

                                                                    <li><a href="" style="color: #000" class="a_Edit" data-id="<?php echo $sl4l_head->AccountCode?>" ><?php echo $sl4l_head->AccountName;?></a>

                                                                    <?php }?>

                                                                        <?php $sl5l_heads = $this->ChartModel->get_l5SubHead($sl4l_head->AccountCode,"libilities"); ?>

                                                                        <ul>

                                                                            <?php foreach($sl5l_heads as $sl5l_head):
                                                                                if ($sl5l_head->Category == 1) {
                                                                                    $account = $this->ChartModel->checkParent($sl5l_head->AccountCode);
                                                                                    if (is_array($account) && end($account) == 1) {
                                                                                    
                                                                             ?>

                                                                                <?php if($sl5l_head->Category == 2){ ?>

                                                                                        <li><a href="" style="color: #000" class="a_Edit" data-id="<?php echo $sl5l_head->AccountCode?>" ><?php echo $sl5l_head->AccountName;?></a>

                                                                                    <?php } else{ ?>

                                                                                    <li><a href="" style="color: #004c96;font-size: 18px;" class="a_Edit" data-id="<?php echo $sl5l_head->AccountCode?>" ><?php echo $sl5l_head->AccountName;?></a>

                                                                                <?php } } } elseif(end($account) == 1) {?>
                                                                                <li><a href="" style="color: #000" class="a_Edit" data-id="<?php echo $sl5l_head->AccountCode?>" ><?php echo $sl5l_head->AccountName;?></a>
                                                                                <?php }?>

                                                                                    <?php $sl6l_heads = $this->ChartModel->get_l6SubHead($sl5l_head->AccountCode,"libilities"); ?>

                                                                                    <ul>

                                                                                        <?php foreach($sl6l_heads as $sl6l_head): 
                                                                                            if ($sl6l_head->Category == 1) {
                                                                                                $account = $this->ChartModel->checkParent($sl6l_head->AccountCode);
                                                                                                if (is_array($account) && end($account) == 1) {
                                                                                        ?>

                                                                                            <?php if($sl6l_head->Category == 2){ ?>

                                                                                                    <li><a href="" style="color: #000" class="a_Edit" data-id="<?php echo $sl6l_head->AccountCode?>" ><?php echo $sl6l_head->AccountName;?></a>

                                                                                                <?php } else{ ?>

                                                                                                <li><a href="" style="color: #004c96;font-size: 18px;" class="a_Edit" data-id="<?php echo $sl6l_head->AccountCode?>" ><?php echo $sl6l_head->AccountName;?></a>

                                                                                            <?php } } } elseif(end($account) == 1) {?>

                                                                                            <li><a href="" style="color: #000" class="a_Edit" data-id="<?php echo $sl6l_head->AccountCode?>" ><?php echo $sl6l_head->AccountName;?></a>

                                                                                            <?php }?>

                                                                                                <?php $sl7l_heads = $this->ChartModel->get_l7SubHead($sl6l_head->AccountCode,"libilities"); ?>

                                                                                                <ul>

                                                                                                    <?php foreach($sl7l_heads as $sl7l_head): 
                                                                                                            if ($sl7l_head->Category == 1) {
                                                                                                                $account = $this->ChartModel->checkParent($sl7l_head->AccountCode);
                                                                                                                if (is_array($account) && end($account) == 1) {
                                                                                                            ?>

                                                                                                        <?php if($sl7l_head->Category == 2){ ?>

                                                                                                                <li><a href="" style="color: #000" class="a_Edit8" data-id="<?php echo $sl7l_head->AccountCode?>" ><?php echo $sl7l_head->AccountName;?></a>

                                                                                                            <?php } else{ ?>

                                                                                                            <li><a href="" style="color: #004c96;font-size: 18px;" class="a_Edit8" data-id="<?php echo $sl7l_head->AccountCode?>" ><?php echo $sl7l_head->AccountName;?></a>

                                                                                                        <?php } } } elseif(end($account) == 1){?>

                                                                                                                <li><a href="" style="color: #000" class="a_Edit8" data-id="<?php echo $sl7l_head->AccountCode?>" ><?php echo $sl7l_head->AccountName;?></a>

                                                                                                        <?php }?>

                                                                                                            <ul>

                                                                                                                <li></li>

                                                                                                            </ul>

                                                                                                        </li>

                                                                                                    <?php endforeach ?>

                                                                                                </ul>

                                                                                            </li>

                                                                                        <?php endforeach ?>

                                                                                    </ul>

                                                                                </li>

                                                                            <?php endforeach ?>

                                                                        </ul>

                                                                    </li>

                                                                <?php endforeach ?>

                                                            </ul>

                                                        </li>

                                                    <?php endforeach ?>

                                                </ul>

                                            </li>

                                        <?php endforeach ?>

                                    </ul>

                                </li>

                                <li><a href="" style="color: #004c96;font-size: 18px;" class="a_Edit" data-id="<?php echo $heads[2]->AccountCode;?>"  ><?php echo $heads[2]->AccountName;?></a>

                                    <ul>

                                        <?php foreach($C_Heads as $c_heads): 
                                            if ($c_heads->Category == 1) {
                                                $account = $this->ChartModel->checkParent($c_heads->AccountCode);
                                            if (is_array($account) && end($account) == 1) {
                                        ?>
                                            <?php if($c_heads->Category == 2){ ?>

                                                    <li><a href="" style="color: #000" class="a_Edit" data-id="<?php echo $c_heads->AccountCode ?>" ><?php echo $c_heads->AccountName;?></a>

                                                <?php } else{ ?>

                                                <li><a href="" style="color: #004c96;font-size: 18px;" class="a_Edit" data-id="<?php echo $c_heads->AccountCode ?>" ><?php echo $c_heads->AccountName;?></a>

                                            <?php } } } else {?>

                                            <li><a href="" style="color: #000" class="a_Edit" data-id="<?php echo $c_heads->AccountCode ?>" ><?php echo $c_heads->AccountName;?></a>

                                            <?php }?>

                                                <?php $sc_heads = $this->ChartModel->get_SubHead($c_heads->AccountCode,"capital"); ?>

                                                <ul>

                                                    <?php foreach($sc_heads as $sc_head): 
                                                            if ($sc_head->Category == 1) {
                                                                $account = $this->ChartModel->checkParent($sc_head->AccountCode);
                                                                if (is_array($account) && end($account) == 1) {
                                                            ?>

                                                        <?php if($sc_head->Category == 2){  ?>

                                                                <li><a href="" style="color: #000" class="a_Edit" data-id="<?php echo $sc_head->AccountCode ?>" ><?php echo $sc_head->AccountName;?></a>

                                                            <?php } else{ ?>

                                                            <li><a href="" style="color: #004c96;font-size: 18px;" class="a_Edit" data-id="<?php echo $sc_head->AccountCode ?>" ><?php echo $sc_head->AccountName;?></a>

                                                        <?php } } } elseif(end($account) == 1) {?>
                                                        <li><a href="" style="color: #000" class="a_Edit" data-id="<?php echo $sc_head->AccountCode ?>" ><?php echo $sc_head->AccountName;?></a>
                                                        <?php }?>

                                                            <?php $sl4c_heads = $this->ChartModel->get_l4SubHead($sc_head->AccountCode,"capital"); ?>

                                                            <ul>

                                                                <?php foreach($sl4c_heads as $sl4c_head):
                                                                    if ($sl4c_head->Category == 1) {
                                                                        $account = $this->ChartModel->checkParent($sl4c_head->AccountCode);
                                                                        if (is_array($account) && end($account) == 1) {
                                                                         ?>

                                                                    <?php if($sl4c_head->Category == 2){ ?>

                                                                            <li><a href="" style="color: #000" class="a_Edit" data-id="<?php echo $sl4c_head->AccountCode ?>" ><?php echo $sl4c_head->AccountName;?></a>

                                                                        <?php } else{ ?>

                                                                        <li><a href="" style="color: #004c96;font-size: 18px;" class="a_Edit" data-id="<?php echo $sl4c_head->AccountCode ?>" ><?php echo $sl4c_head->AccountName;?></a>

                                                                    <?php } } } elseif(end($account) == 1) {?>
                                                                    <li><a href="" style="color: #000" class="a_Edit" data-id="<?php echo $sl4c_head->AccountCode ?>" ><?php echo $sl4c_head->AccountName;?></a>
                                                                    <?php }?>
                                                                        <?php $sl5c_heads = $this->ChartModel->get_l5SubHead($sl4c_head->AccountCode,"capital"); ?>

                                                                        <ul>

                                                                            <?php foreach($sl5c_heads as $sl5c_head): 
                                                                                if ($sl5c_head->Category == 1) {
                                                                                      $account = $this->ChartModel->checkParent($sl5c_head->AccountCode);
                                                                                      if (is_array($account) && end($account) == 1) {
                                                                                    ?>

                                                                                <?php if($sl5c_head->Category == 2){ ?>

                                                                                        <li><a href="" style="color: #000" class="a_Edit" data-id="<?php echo $sl5c_head->AccountCode ?>" ><?php echo $sl5c_head->AccountName;?></a>

                                                                                    <?php } else{ ?>

                                                                                    <li><a href="" style="color: #004c96;font-size: 18px;" class="a_Edit" data-id="<?php echo $sl5c_head->AccountCode ?>" ><?php echo $sl5c_head->AccountName;?></a>

                                                                                <?php } } } elseif(end($account) == 1) {?>
                                                                                <li><a href="" style="color: #000" class="a_Edit" data-id="<?php echo $sl5c_head->AccountCode ?>" ><?php echo $sl5c_head->AccountName;?></a>
                                                                                <?php }?>

                                                                                    <?php $sl6c_heads = $this->ChartModel->get_l6SubHead($sl5c_head->AccountCode,"capital"); ?>

                                                                                    <ul>

                                                                                        <?php foreach($sl6c_heads as $sl6c_head): 
                                                                                            if ($sl6c_head->Category == 1) {
                                                                                                $account = $this->ChartModel->checkParent($ssl6c_head->AccountCode);
                                                                                                if (is_array($account) && end($account) == 1) {
                                                                                        ?>

                                                                                            <?php if($sl6c_head->Category == 2){ ?>

                                                                                                    <li><a href="" style="color: #000" class="a_Edit" data-id="<?php echo $sl6c_head->AccountCode ?>" ><?php echo $sl6c_head->AccountName;?></a>

                                                                                                <?php } else{ ?>

                                                                                                <li><a href="" style="color: #004c96;font-size: 18px;" class="a_Edit" data-id="<?php echo $sl6c_head->AccountCode ?>" ><?php echo $sl6c_head->AccountName;?></a>

                                                                                            <?php } } } elseif(end($account) == 1) {?>
                                                                                                <li><a href="" style="color: #000" class="a_Edit" data-id="<?php echo $sl6c_head->AccountCode ?>" ><?php echo $sl6c_head->AccountName;?></a>

                                                                                            <?php }?>

                                                                                                <?php $sl7c_heads = $this->ChartModel->get_l7SubHead($sl6c_head->AccountCode,"capital"); ?>

                                                                                                <ul>

                                                                                                    <?php foreach($sl7c_heads as $sl7c_head): 
                                                                                                        if ($sl7c_head->Category == 1) {
                                                                                                            $account = $this->ChartModel->checkParent($sl7c_head->AccountCode);
                                                                                                            if (is_array($account) && end($account) == 1) {
                                                                                                    ?>

                                                                                                        <?php if($sl7c_head->Category == 2){ ?>

                                                                                                                <li><a href="" style="color: #000" class="a_Edit8" data-id="<?php echo $sl7c_head->AccountCode ?>" ><?php echo $sl7c_head->AccountName;?></a>

                                                                                                            <?php } else{ ?>

                                                                                                            <li><a href="" style="color: #004c96;font-size: 18px;" class="a_Edit8" data-id="<?php echo $sl7c_head->AccountCode ?>" ><?php echo $sl7c_head->AccountName;?></a>

                                                                                                        <?php } } } elseif(end($account) == 1) {?>
                                                                                                            <li><a href="" style="color: #000" class="a_Edit8" data-id="<?php echo $sl7c_head->AccountCode ?>" ><?php echo $sl7c_head->AccountName;?></a>

                                                                                                        <?php }?>

                                                                                                            <ul>

                                                                                                                <li></li>

                                                                                                            </ul>

                                                                                                        </li>

                                                                                                    <?php endforeach ?>

                                                                                                </ul>

                                                                                            </li>

                                                                                        <?php endforeach ?>

                                                                                    </ul>

                                                                                </li>

                                                                            <?php endforeach ?>

                                                                        </ul>

                                                                    </li>

                                                                <?php endforeach ?>

                                                            </ul>

                                                        </li>

                                                    <?php endforeach ?>

                                                </ul>

                                            </li>

                                        <?php endforeach ?>

                                    </ul>

                                </li>

                                <li><a href="" style="color: #004c96;font-size: 18px;" class="a_Edit" data-id="<?php echo $heads[3]->AccountCode;?>"  ><?php echo $heads[3]->AccountName;?></a>

                                    <ul>

                                        <?php foreach($R_Heads as $r_heads): 
                                            if ($r_heads->Category == 1) {
                                                $account = $this->ChartModel->checkParent($r_heads->AccountCode);
                                                if (is_array($account) && end($account) == 1) {
                                        ?>

                                            <?php if($r_heads->Category == 2){ ?>

                                                    <li><a href="" style="color: #000" class="a_Edit" data-id="<?php echo $r_heads->AccountCode ?>" ><?php echo $r_heads->AccountName;?></a>

                                                <?php } else{ ?>

                                                <li><a href="" style="color: #004c96;font-size: 18px;" class="a_Edit" data-id="<?php echo $r_heads->AccountCode ?>" ><?php echo $r_heads->AccountName;?></a>

                                            <?php } } } else {?>
                                            <li><a href="" style="color: #000" class="a_Edit" data-id="<?php echo $r_heads->AccountCode ?>" ><?php echo $r_heads->AccountName;?></a>
                                            <?php }?>

                                                <?php $sr_heads = $this->ChartModel->get_SubHead($r_heads->AccountCode,"revenue"); ?>

                                                <ul>

                                                    <?php foreach($sr_heads as $sr_head): 
                                                        if ($sr_head->Category == 1) {
                                                            $account = $this->ChartModel->checkParent($sr_head->AccountCode);
                                                            if (is_array($account) && end($account) == 1) {
                                                    ?>

                                                        <?php if($sr_head->Category == 2){ ?>
                                                                <li><a href="" style="color: #000" class="a_Edit" data-id="<?php echo $sr_head->AccountCode ?>" ><?php echo $sr_head->AccountName;?></a>

                                                            <?php } else{ ?>

                                                            <li><a href="" style="color: #004c96;font-size: 18px;" class="a_Edit" data-id="<?php echo $sr_head->AccountCode ?>" ><?php echo $sr_head->AccountName;?></a>

                                                        <?php } } } elseif(end($account) == 1) {?>
                                                            <li><a href="" style="color: #000" class="a_Edit" data-id="<?php echo $sr_head->AccountCode ?>" ><?php echo $sr_head->AccountName;?></a>
                                                        <?php }?>

                                                            <?php $sl4r_heads = $this->ChartModel->get_l4SubHead($sr_head->AccountCode,"revenue"); ?>

                                                            <ul>

                                                                <?php foreach($sl4r_heads as $sl4r_head): 
                                                                    if ($sl4r_head->Category == 1 ) {
                                                                        $account = $this->ChartModel->checkParent($sl4r_head->AccountCode);
                                                                        if (is_array($account) && end($account) == 1) {
                                                                ?>

                                                                    <?php if($sl4r_head->Category == 2){ ?>

                                                                            <li><a href="" style="color: #000" class="a_Edit" data-id="<?php echo $sl4r_head->AccountCode ?>" ><?php echo $sl4r_head->AccountName;?></a>

                                                                        <?php } else{ ?>

                                                                        <li><a href="" style="color: #004c96;font-size: 18px;" class="a_Edit" data-id="<?php echo $sl4r_head->AccountCode ?>" ><?php echo $sl4r_head->AccountName;?></a>

                                                                    <?php } } } elseif(end($account) == 1) {?>
                                                                        <li><a href="" style="color: #000" class="a_Edit" data-id="<?php echo $sl4r_head->AccountCode ?>" ><?php echo $sl4r_head->AccountName;?></a>

                                                                    <?php }?>

                                                                        <?php $sl5r_heads = $this->ChartModel->get_l5SubHead($sl4r_head->AccountCode,"revenue"); ?>

                                                                        <ul>

                                                                            <?php foreach($sl5r_heads as $sl5r_head): 
                                                                                if ($sl5r_head->Category == 1) {
                                                                                    $account = $this->ChartModel->checkParent($sl5r_head->AccountCode);
                                                                                    if (is_array($account) && end($account) == 1) {
                                                                            ?>

                                                                                <?php if($sl5r_head->Category == 2){ ?>

                                                                                        <li><a href="" style="color: #000" class="a_Edit" data-id="<?php echo $sl5r_head->AccountCode ?>" ><?php echo $sl5r_head->AccountName;?></a>

                                                                                    <?php } else{ ?>

                                                                                    <li><a href="" style="color: #004c96;font-size: 18px;" class="a_Edit" data-id="<?php echo $sl5r_head->AccountCode ?>" ><?php echo $sl5r_head->AccountName;?></a>

                                                                                <?php } } } elseif(end($account) == 1) {?>
                                                                                    <li><a href="" style="color: #000" class="a_Edit" data-id="<?php echo $sl5r_head->AccountCode ?>" ><?php echo $sl5r_head->AccountName;?></a>
 
                                                                                <?php }?>

                                                                                    <?php $sl6r_heads = $this->ChartModel->get_l6SubHead($sl5r_head->AccountCode,"revenue"); ?>

                                                                                    <ul>

                                                                                        <?php foreach($sl6r_heads as $sl6r_head):
                                                                                            if ($sl6r_head->Category == 1) {
                                                                                                $account = $this->ChartModel->checkParent($sl6r_head->AccountCode);
                                                                                                if (is_array($account) && end($account) == 1) {
                                                                                         ?>

                                                                                            <?php if($sl6r_head->Category == 2){ ?>

                                                                                                    <li><a href="" style="color: #000" class="a_Edit" data-id="<?php echo $sl6r_head->AccountCode ?>" ><?php echo $sl6r_head->AccountName;?></a>

                                                                                                <?php } else{ ?>

                                                                                                <li><a href="" style="color: #004c96;font-size: 18px;" class="a_Edit" data-id="<?php echo $sl6r_head->AccountCode ?>" ><?php echo $sl6r_head->AccountName;?></a>

                                                                                            <?php } } } elseif(end($account) == 1) {?>
                                                                                                <li><a href="" style="color: #000" class="a_Edit" data-id="<?php echo $sl6r_head->AccountCode ?>" ><?php echo $sl6r_head->AccountName;?></a>

                                                                                            <?php }?>

                                                                                                <?php $sl7r_heads = $this->ChartModel->get_l7SubHead($sl6r_head->AccountCode,"revenue"); ?>

                                                                                                <ul>

                                                                                                    <?php foreach($sl7r_heads as $sl7r_head): 
                                                                                                        if ($sl7r_head->Category == 1) {
                                                                                                            $account = $this->ChartModel->checkParent($sl7r_head->AccountCode);
                                                                                                            if (is_array($account) && end($account) == 1) {
                                                                                                    ?>

                                                                                                        <?php if($sl7r_head->Category == 2){  ?>

                                                                                                                <li><a href="" style="color: #000" class="a_Edit8" data-id="<?php echo $sl7r_head->AccountCode ?>" ><?php echo $sl7r_head->AccountName;?></a>

                                                                                                            <?php } else{ ?>

                                                                                                            <li><a href="" style="color: #004c96;font-size: 18px;" class="a_Edit8" data-id="<?php echo $sl7r_head->AccountCode ?>" ><?php echo $sl7r_head->AccountName;?></a>

                                                                                                        <?php } } } elseif(end($account) == 1) {?>
                                                                                                        <li><a href="" style="color: #000" class="a_Edit8" data-id="<?php echo $sl7r_head->AccountCode ?>" ><?php echo $sl7r_head->AccountName;?></a>

                                                                                                        <?php }?>

                                                                                                            <ul>

                                                                                                                <li>



                                                                                                                </li>

                                                                                                            </ul>

                                                                                                        </li>

                                                                                                    <?php endforeach ?>

                                                                                                </ul>

                                                                                            </li>

                                                                                        <?php endforeach ?>

                                                                                    </ul>

                                                                                </li>

                                                                            <?php endforeach ?>

                                                                        </ul>

                                                                    </li>

                                                                <?php endforeach ?>

                                                            </ul>

                                                        </li>

                                                    <?php endforeach ?>

                                                </ul>

                                            </li>

                                        <?php endforeach ?>

                                    </ul>

                                </li>

                                <li><a href="" style="color: #004c96;font-size: 18px;" class="a_Edit" data-id="<?php echo $heads[4]->AccountCode;?>"  ><?php echo $heads[4]->AccountName;?></a>

                                    <ul>

                                        <?php foreach($E_Heads as $e_heads):
                                            if ($e_heads->Category == 1) {
                                                $account = $this->ChartModel->checkParent($e_heads->AccountCode);
                                                if (is_array($account) && end($account) == 1) {
                                         ?>

                                            <?php if($e_heads->Category == 2){ ?>

                                                    <li><a href="" style="color: #000" class="a_Edit" data-id="<?php echo $e_heads->AccountCode ?>" ><?php echo $e_heads->AccountName;?></a>

                                                <?php }else{ ?>

                                                <li><a href="" style="color: #004c96;font-size: 18px;" class="a_Edit" data-id="<?php echo $e_heads->AccountCode ?>" ><?php echo $e_heads->AccountName;?></a>

                                            <?php } } } else {?>

                                                <li><a href="" style="color: #000" class="a_Edit" data-id="<?php echo $e_heads->AccountCode ?>" ><?php echo $e_heads->AccountName;?></a>

                                            <?php }?>

                                                <?php $se_heads = $this->ChartModel->get_SubHead($e_heads->AccountCode,"expense"); ?>

                                                <ul>

                                                    <?php foreach($se_heads as $se_head): 
                                                        if ($se_head->Category == 1) {
                                                            $account = $this->ChartModel->checkParent($se_head->AccountCode);
                                                            if (is_array($account) && end($account) == 1) {                                                     
                                                    ?>

                                                        <?php if($se_head->Category == 2){ ?>

                                                                <li><a href="" style="color: #000" class="a_Edit" data-id="<?php echo $se_head->AccountCode ?>" ><?php echo $se_head->AccountName;?></a>

                                                            <?php }else{ ?>

                                                            <li><a href="" style="color: #004c96;font-size: 18px;" class="a_Edit" data-id="<?php echo $se_head->AccountCode ?>" ><?php echo $se_head->AccountName;?></a>

                                                        <?php } } } elseif(end($account) == 1) {?>

                                                            <li><a href="" style="color: #000" class="a_Edit" data-id="<?php echo $se_head->AccountCode ?>" ><?php echo $se_head->AccountName;?></a>
                                                        <?php }?>

                                                            <?php $sl4e_heads = $this->ChartModel->get_l4SubHead($se_head->AccountCode,"expense"); ?>

                                                            <ul>

                                                                <?php foreach($sl4e_heads as $sl4e_head):
                                                                    if ($sl4e_head->Category == 1) {
                                                                        $account = $this->ChartModel->checkParent($sl4e_head->AccountCode);
                                                                        if (is_array($account) && end($account) == 1) {
                                                                    
                                                                 ?>

                                                                    <?php if($sl4e_head->Category == 2){ ?>

                                                                            <li><a href="" style="color: #000" class="a_Edit" data-id="<?php echo $sl4e_head->AccountCode ?>" ><?php echo $sl4e_head->AccountName;?></a>

                                                                        <?php } else{ ?>

                                                                        <li><a href="" style="color: #004c96;font-size: 18px;" class="a_Edit" data-id="<?php echo $sl4e_head->AccountCode ?>" ><?php echo $sl4e_head->AccountName;?></a>

                                                                    <?php } } } elseif(end($account) == 1) {?>

                                                                        <li><a href="" style="color: #000" class="a_Edit" data-id="<?php echo $sl4e_head->AccountCode ?>" ><?php echo $sl4e_head->AccountName;?></a>

                                                                    <?php }?>

                                                                        <?php $sl5e_heads = $this->ChartModel->get_l5SubHead($sl4e_head->AccountCode,"expense"); ?>

                                                                        <ul>

                                                                            <?php foreach($sl5e_heads as $sl5e_head): 
                                                                                if ($sl5e_head->Category == 1) {
                                                                                    $account = $this->ChartModel->checkParent($sl5e_head->AccountCode);
                                                                                    if (is_array($account) && end($account) == 1) {
                                                                            ?>

                                                                                <?php if($sl5e_head->Category == 2){ ?>

                                                                                        <li><a href="" style="color: #000" class="a_Edit" data-id="<?php echo $sl5e_head->AccountCode ?>" ><?php echo $sl5e_head->AccountName;?></a>

                                                                                    <?php }else{ ?>

                                                                                    <li><a href="" style="color: #004c96;font-size: 18px;" class="a_Edit" data-id="<?php echo $sl5e_head->AccountCode ?>" ><?php echo $sl5e_head->AccountName;?></a>

                                                                                <?php } } } elseif(end($account) == 1) {?>
                                                                                    <li><a href="" style="color: #000" class="a_Edit" data-id="<?php echo $sl5e_head->AccountCode ?>" ><?php echo $sl5e_head->AccountName;?></a>
                                                                                <?php }?>

                                                                                    <?php $sl6e_heads = $this->ChartModel->get_l6SubHead($sl5e_head->AccountCode,"expense"); ?>

                                                                                    <ul>

                                                                                        <?php foreach($sl6e_heads as $sl6e_head):
                                                                                            if ($sl6e_head->Category == 1) {
                                                                                                $account = $this->ChartModel->checkParent($sl6e_head->AccountCode);
                                                                                                if (is_array($account) && end($account) == 1) {
                                                                                         ?>

                                                                                            <?php if($sl6e_head->Category == 2){ ?>

                                                                                                    <li><a href="" style="color: #000" class="a_Edit" data-id="<?php echo $sl6e_head->AccountCode ?>" ><?php echo $sl6e_head->AccountName;?></a>

                                                                                                <?php } else{ ?>

                                                                                                <li><a href="" style="color: #004c96;font-size: 18px;" class="a_Edit" data-id="<?php echo $sl6e_head->AccountCode ?>" ><?php echo $sl6e_head->AccountName;?></a>

                                                                                            <?php } } } elseif(end($account) == 1) {?>
                                                                                                <li><a href="" style="color: #000" class="a_Edit" data-id="<?php echo $sl6e_head->AccountCode ?>" ><?php echo $sl6e_head->AccountName;?></a>
                                                                                            <?php }?>

                                                                                                <?php $sl7e_heads = $this->ChartModel->get_l7SubHead($sl6e_head->AccountCode,"expense"); ?>

                                                                                                <ul>

                                                                                                    <?php foreach($sl7e_heads as $sl7e_head):
                                                                                                        if ($sl7e_head->Category == 1) {
                                                                                                            $account = $this->ChartModel->checkParent($sl7e_head->AccountCode);
                                                                                                            if (is_array($account) && end($account) == 1) {
                                                                                                     ?>

                                                                                                        <?php if($sl7e_head->Category == 2){ ?>

                                                                                                                <li><a href="" style="color: #000" class="a_Edit8" data-id="<?php echo $sl7e_head->AccountCode ?>" ><?php echo $sl7e_head->AccountName;?></a>

                                                                                                            <?php } else{ ?>

                                                                                                            <li><a href="" style="color: #004c96;font-size: 18px;" class="a_Edit8" data-id="<?php echo $sl7e_head->AccountCode ?>" ><?php echo $sl7e_head->AccountName;?></a>

                                                                                                        <?php } } } elseif(end($account) == 1) {?>

                                                                                                        <li><a href="" style="color: #000" class="a_Edit8" data-id="<?php echo $sl7e_head->AccountCode ?>" ><?php echo $sl7e_head->AccountName;?></a>

                                                                                                        <?php }?>

                                                                                                            <ul>

                                                                                                                <li>



                                                                                                                </li>

                                                                                                            </ul>

                                                                                                        </li>

                                                                                                    <?php endforeach ?>

                                                                                                </ul>

                                                                                            </li>

                                                                                        <?php endforeach ?>

                                                                                    </ul>

                                                                                </li>

                                                                            <?php endforeach ?>

                                                                        </ul>

                                                                    </li>

                                                                <?php endforeach ?>

                                                            </ul>

                                                        </li>

                                                    <?php endforeach ?>

                                                </ul>

                                            </li>

                                        <?php endforeach ?>

                                    </ul>

                                </li>

                            </ul>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>