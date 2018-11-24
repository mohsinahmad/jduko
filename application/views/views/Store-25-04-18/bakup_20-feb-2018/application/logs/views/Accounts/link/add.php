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
    .tre ul:before {
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
        line-height:2.5em;
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
</style>
<script src="<?= base_url()."assets/"; ?>js/jquery-1.12.4.js"></script>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">روابط کی تخلیق</h1>
    </div>
</div>
<div class="row" >
    <?php if($this->session->flashdata('success')) :?>
        <div class="alert alert-success alert-dismissable" id="dalert">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <?= $this->session->flashdata('success');?>
        </div>
    <?php endif;
    if($this->session->flashdata('error')) :?>
        <div class="alert alert-danger alert-dismissable" id="dalert">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <?= $this->session->flashdata('error');?>
        </div>
    <?php endif ?>
    <div class="col-md-6">
        <h3> اکاؤنٹس:</h3>
        <ul id="tree"  style="cursor:pointer">
           <li>
            <a style="color: #004c96;font-size: 18px;" " data-id="1"  >اثاثے</a>
            <ul>
                <?php foreach($A_Heads as $a_heads):
                    if ($a_heads->Category == 2){?>
                    <li><a href="" style="color: #000" class="get" data-id="<?= $a_heads->AccountCode; ?>" ><?= $a_heads->AccountName;?></a>
                        <?php }else{?>
                        <li><a href=""  style="color: #004c96;font-size: 18px;"  data-id="<?= $a_heads->AccountCode; ?>" ><?= $a_heads->AccountName;?></a>
                            <?php } $this->load->model('ChartModel');
                            $sa_heads = $this->ChartModel->get_SubHead($a_heads->AccountCode,"assets");?>
                            <ul>
                                <?php foreach($sa_heads as $as_head):
                                    if ($as_head->Category == 2){?>
                                    <li><a href=""  style="color: #000" class="get" data-id="<?= $as_head->AccountCode;?>" ><?= $as_head->AccountName?></a>
                                        <?php }else{?>
                                        <li><a href=""  style="color: #004c96;font-size: 18px;"  data-id="<?= $as_head->AccountCode;?>" ><?= $as_head->AccountName?></a>
                                            <?php } $sl4a_heads = $this->ChartModel->get_l4SubHead($as_head->AccountCode,"assets"); ?>
                                            <ul>
                                                <?php foreach($sl4a_heads as $sl4a_head):
                                                    if ($sl4a_head->Category == 2){?>
                                                    <li><a href=""  style="color: #000" class="get" data-id="<?= $sl4a_head->AccountCode;?>" ><?= $sl4a_head->AccountName?></a>
                                                        <?php }else{?>
                                                        <li><a href=""  style="color: #004c96;font-size: 18px;"  data-id="<?= $sl4a_head->AccountCode;?>" ><?= $sl4a_head->AccountName?></a>
                                                            <?php } $sl5a_heads = $this->ChartModel->get_l5SubHead($sl4a_head->AccountCode,"assets"); ?>
                                                            <ul>
                                                                <?php foreach($sl5a_heads as $sl5a_head):
                                                                    if ($sl5a_head->Category == 2){?>
                                                                    <li><a href="" style="color: #000" class="get" data-id="<?= $sl5a_head->AccountCode;?>" ><?= $sl5a_head->AccountName?></a>
                                                                        <?php }else{?>
                                                                        <li><a href="" style="color: #004c96;font-size: 18px;"  data-id="<?= $sl5a_head->AccountCode;?>" ><?= $sl5a_head->AccountName?></a>
                                                                            <?php } $sl6a_heads = $this->ChartModel->get_l6SubHead($sl5a_head->AccountCode,"assets"); ?>
                                                                            <ul>
                                                                                <?php foreach($sl6a_heads as $sl6a_head):
                                                                                    if ($sl6a_head->Category == 2){?>
                                                                                    <li><a href=""  style="color: #000" class="get" data-id="<?= $sl6a_head->AccountCode;?>" ><?= $sl6a_head->AccountName?></a>
                                                                                        <?php }else{?>
                                                                                        <li><a href=""  style="color: #004c96;font-size: 18px;"  data-id="<?= $sl6a_head->AccountCode;?>" ><?= $sl6a_head->AccountName?></a>
                                                                                            <?php } $sl7a_heads = $this->ChartModel->get_l7SubHead($sl6a_head->AccountCode,"assets"); ?>
                                                                                            <ul>
                                                                                                <?php foreach($sl7a_heads as $sl7a_head): ?>
                                                                                                    <li><a href="" style="color: #000" class="get8" data-id="<?= $sl7a_head->AccountCode;?>" ><?= $sl7a_head->AccountName?></a>
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
                            <li><a style="color: #004c96;font-size: 18px;"  data-id="2" > واجبات</a>
                                <ul>
                                    <?php foreach($L_Heads as $l_heads): ?>
                                        <?php if ($l_heads->Category == 2){?>
                                        <li><a href="" style="color: #000" class="get" data-id="<?= $l_heads->AccountCode?>" ><?= $l_heads->AccountName;?></a>
                                            <?php }else{?>
                                            <li><a style="color: #004c96;font-size: 18px;"  data-id="<?= $l_heads->AccountCode?>" ><?= $l_heads->AccountName;?></a>
                                                <?php }?>
                                                <?php $sl_heads = $this->ChartModel->get_SubHead($l_heads->AccountCode,"libilities"); ?>
                                                <ul>
                                                    <?php foreach($sl_heads as $sl_head): ?>
                                                        <?php if ($sl_head->Category == 2){?>
                                                        <li><a href="" style="color: #000" class="get" data-id="<?= $sl_head->AccountCode; ?>" ><?= $sl_head->AccountName; ?></a>
                                                            <?php }else{?>
                                                            <li><a style="color: #004c96;font-size: 18px;" " data-id="<?= $sl_head->AccountCode; ?>" ><?= $sl_head->AccountName; ?></a>
                                                                <?php }?>
                                                                <?php $sl4l_heads = $this->ChartModel->get_l4SubHead($sl_head->AccountCode,"libilities"); ?>
                                                                <ul>
                                                                    <?php foreach($sl4l_heads as $sl4l_head): ?>
                                                                        <?php if ($sl4l_head->Category == 2){?>
                                                                        <li><a href="" style="color: #000" class="get" data-id="<?= $sl4l_head->AccountCode; ?>" ><?= $sl4l_head->AccountName; ?></a>
                                                                            <?php }else{?>
                                                                            <li><a style="color: #004c96;font-size: 18px;"  data-id="<?= $sl4l_head->AccountCode; ?>" ><?= $sl4l_head->AccountName; ?></a>
                                                                                <?php }?>
                                                                                <?php $sl5l_heads = $this->ChartModel->get_l5SubHead($sl4l_head->AccountCode,"libilities"); ?>
                                                                                <ul>
                                                                                    <?php foreach($sl5l_heads as $sl5l_head): ?>
                                                                                        <?php if ($sl5l_head->Category == 2){?>
                                                                                        <li><a href="" style="color: #000" class="get" data-id="<?= $sl5l_head->AccountCode; ?>" ><?= $sl5l_head->AccountName; ?></a>
                                                                                            <?php }else{?>
                                                                                            <li><a style="color: #004c96;font-size: 18px;"  data-id="<?= $sl5l_head->AccountCode; ?>" ><?= $sl5l_head->AccountName; ?></a>
                                                                                                <?php }?>
                                                                                                <?php $sl6l_heads = $this->ChartModel->get_l6SubHead($sl5l_head->AccountCode,"libilities"); ?>
                                                                                                <ul>
                                                                                                    <?php foreach($sl6l_heads as $sl6l_head): ?>
                                                                                                        <?php if ($sl6l_head->Category == 2){?>
                                                                                                        <li><a href="" style="color: #000" class="get" data-id="<?= $sl6l_head->AccountCode; ?>" ><?= $sl6l_head->AccountName; ?></a>
                                                                                                            <?php }else{?>
                                                                                                            <li><a style="color: #004c96;font-size: 18px;"  data-id="<?= $sl6l_head->AccountCode; ?>" ><?= $sl6l_head->AccountName; ?></a>
                                                                                                                <?php }?>
                                                                                                                <?php $sl7l_heads = $this->ChartModel->get_l7SubHead($sl6l_head->AccountCode,"libilities"); ?>
                                                                                                                <ul>
                                                                                                                    <?php foreach($sl7l_heads as $sl7l_head): ?>
                                                                                                                        <li><a href="" style="color: #000" class="get8" data-id="<?= $sl7l_head->AccountCode; ?>" ><?= $sl7l_head->AccountName; ?></a>
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
                                                <li><a style="color: #004c96;font-size: 18px;" " data-id="3" >سرمایا</a>
                                                    <ul>
                                                        <?php foreach($C_Heads as $c_heads): ?>
                                                            <?php if ($c_heads->Category == 2){?>
                                                            <li><a href="" style="color: #000" class="get" data-id="<?= $c_heads->AccountCode ?>" ><?= $c_heads->AccountName;?></a>
                                                                <?php }else{?>
                                                                <li><a href="" style="color: #004c96;font-size: 18px;"  data-id="<?= $c_heads->AccountCode ?>" ><?= $c_heads->AccountName;?></a>
                                                                    <?php }?>
                                                                    <?php $sc_heads = $this->ChartModel->get_SubHead($c_heads->AccountCode,"capital"); ?>
                                                                    <ul>
                                                                        <?php foreach($sc_heads as $sc_head): ?>
                                                                            <?php if ($sc_head->Category == 2){?>
                                                                            <li><a href="" style="color: #000" class="get" data-id="<?= $sc_head->AccountCode ?>" ><?= $sc_head->AccountName; ?></a>
                                                                                <?php }else{?>
                                                                                <li><a href="" style="color: #004c96;font-size: 18px;"  data-id="<?= $sc_head->AccountCode ?>" ><?= $sc_head->AccountName; ?></a>
                                                                                    <?php }?>
                                                                                    <?php $sl4c_heads = $this->ChartModel->get_l4SubHead($sc_head->AccountCode,"capital"); ?>
                                                                                    <ul>
                                                                                        <?php foreach($sl4c_heads as $sl4c_head): ?>
                                                                                            <?php if ($sl4c_head->Category == 2){?>
                                                                                            <li><a href="" style="color: #000" class="get" data-id="<?= $sl4c_head->AccountCode; ?>" ><?= $sl4c_head->AccountName; ?></a>
                                                                                                <?php }else{?>
                                                                                                <li><a href="" style="color: #004c96;font-size: 18px;"  data-id="<?= $sl4c_head->AccountCode; ?>" ><?= $sl4c_head->AccountName; ?></a>
                                                                                                    <?php }?>
                                                                                                    <?php $sl5c_heads = $this->ChartModel->get_l5SubHead($sl4c_head->AccountCode,"capital"); ?>
                                                                                                    <ul>
                                                                                                        <?php foreach($sl5c_heads as $sl5c_head): ?>
                                                                                                            <?php if ($sl5c_head->Category == 2){?>
                                                                                                            <li><a href="" style="color: #000" class="get" data-id="<?= $sl5c_head->AccountCode; ?>" ><?= $sl5c_head->AccountName; ?></a>
                                                                                                                <?php }else{?>
                                                                                                                <li><a href="" style="color: #004c96;font-size: 18px;"  data-id="<?= $sl5c_head->AccountCode; ?>" ><?= $sl5c_head->AccountName; ?></a>
                                                                                                                    <?php }?>
                                                                                                                    <?php $sl6c_heads = $this->ChartModel->get_l6SubHead($sl5c_head->AccountCode,"capital"); ?>
                                                                                                                    <ul>
                                                                                                                        <?php foreach($sl6c_heads as $sl6c_head): ?>
                                                                                                                            <?php if ($sl6c_head->Category == 2){?>
                                                                                                                            <li><a href="" style="color: #000" class="get" data-id="<?= $sl6c_head->AccountCode; ?>" ><?= $sl6c_head->AccountName; ?></a>
                                                                                                                                <?php }else{?>
                                                                                                                                <li><a href="" style="color: #004c96;font-size: 18px;"  data-id="<?= $sl6c_head->AccountCode; ?>" ><?= $sl6c_head->AccountName; ?></a>
                                                                                                                                    <?php }?>
                                                                                                                                    <?php $sl7c_heads = $this->ChartModel->get_l7SubHead($sl6c_head->AccountCode,"capital"); ?>
                                                                                                                                    <ul>
                                                                                                                                        <?php foreach($sl7c_heads as $sl7c_head): ?>
                                                                                                                                            <li><a href="" style="color: #000" class="get8" data-id="<?= $sl7c_head->AccountCode; ?>" ><?= $sl7c_head->AccountName; ?></a>
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
                                                                    <li><a style="color: #004c96;font-size: 18px;"  data-id="4" >آمدنی</a>
                                                                        <ul>
                                                                            <?php foreach($R_Heads as $r_heads): ?>
                                                                                <?php if ($r_heads->Category == 2){?>
                                                                                <li><a href="" style="color: #000" class="get" data-id="<?= $r_heads->AccountCode ?>" ><?= $r_heads->AccountName;?></a>
                                                                                    <?php }else{?>
                                                                                    <li><a href="" style="color: #004c96;font-size: 18px;"  data-id="<?= $r_heads->AccountCode ?>" ><?= $r_heads->AccountName;?></a>
                                                                                        <?php }?>
                                                                                        <?php $sr_heads = $this->ChartModel->get_SubHead($r_heads->AccountCode,"revenue"); ?>
                                                                                        <ul>
                                                                                            <?php foreach($sr_heads as $sr_head): ?>
                                                                                                <?php if ($sr_head->Category == 2){?>
                                                                                                <li><a href="" style="color: #000" class="get" data-id="<?= $sr_head->AccountCode;?>" ><?= $sr_head->AccountName; ?></a>
                                                                                                    <?php }else{?>
                                                                                                    <li><a href="" style="color: #004c96;font-size: 18px;"  data-id="<?= $sr_head->AccountCode;?>" ><?= $sr_head->AccountName; ?></a>
                                                                                                        <?php }?>
                                                                                                        <?php $sl4r_heads = $this->ChartModel->get_l4SubHead($sr_head->AccountCode,"revenue"); ?>
                                                                                                        <ul>
                                                                                                            <?php foreach($sl4r_heads as $sl4r_head): ?>
                                                                                                                <?php if ($sl4r_head->Category == 2){?>
                                                                                                                <li><a href="" style="color: #000" class="get" data-id="<?= $sl4r_head->AccountCode ?>" ><?= $sl4r_head->AccountName; ?></a>
                                                                                                                    <?php }else{?>
                                                                                                                    <li><a href="" style="color: #004c96;font-size: 18px;"  data-id="<?= $sl4r_head->AccountCode ?>" ><?= $sl4r_head->AccountName; ?></a>
                                                                                                                        <?php }?>
                                                                                                                        <?php $sl5r_heads = $this->ChartModel->get_l5SubHead($sl4r_head->AccountCode,"revenue"); ?>
                                                                                                                        <ul>
                                                                                                                            <?php foreach($sl5r_heads as $sl5r_head): ?>
                                                                                                                                <?php if ($sl5r_head->Category == 2){?>
                                                                                                                                <li><a href="" style="color: #000" class="get" data-id="<?= $sl5r_head->AccountCode ?>" ><?= $sl5r_head->AccountName; ?></a>
                                                                                                                                    <?php }else{?>
                                                                                                                                    <li><a href="" style="color: #004c96;font-size: 18px;"  data-id="<?= $sl5r_head->AccountCode ?>" ><?= $sl5r_head->AccountName; ?></a>
                                                                                                                                        <?php }?>
                                                                                                                                        <?php $sl6r_heads = $this->ChartModel->get_l6SubHead($sl5r_head->AccountCode,"revenue"); ?>
                                                                                                                                        <ul>
                                                                                                                                            <?php foreach($sl6r_heads as $sl6r_head): ?>
                                                                                                                                                <?php if ($sl6r_head->Category == 2){?>
                                                                                                                                                <li><a href="" style="color: #000" class="get" data-id="<?= $sl6r_head->AccountCode ?>" ><?= $sl6r_head->AccountName; ?></a>
                                                                                                                                                    <?php }else{?>
                                                                                                                                                    <li><a href="" style="color: #004c96;font-size: 18px;"  data-id="<?= $sl6r_head->AccountCode ?>" ><?= $sl6r_head->AccountName; ?></a>
                                                                                                                                                        <?php }?>
                                                                                                                                                        <?php $sl7r_heads = $this->ChartModel->get_l7SubHead($sl6r_head->AccountCode,"revenue"); ?>
                                                                                                                                                        <ul>
                                                                                                                                                            <?php foreach($sl7r_heads as $sl7r_head): ?>
                                                                                                                                                                <li><a href="" style="color: #000" class="get8" data-id="<?= $sl7r_head->AccountCode ?>" ><?= $sl7r_head->AccountName; ?></a>
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
                                                                                        <li><a style="color: #004c96;font-size: 18px;" " data-id="5">اخراجات</a>
                                                                                            <ul>
                                                                                                <?php foreach($E_Heads as $e_heads): ?>
                                                                                                    <?php if ($e_heads->Category == 2){?>
                                                                                                    <li><a href="" style="color: #000" class="get" data-id="<?= $e_heads->AccountCode ?>" ><?= $e_heads->AccountName;?></a>
                                                                                                        <?php }else{?>
                                                                                                        <li><a href="" style="color: #004c96;font-size: 18px;"  data-id="<?= $e_heads->AccountCode ?>" ><?= $e_heads->AccountName;?></a>
                                                                                                            <?php }?>
                                                                                                            <?php $se_heads = $this->ChartModel->get_SubHead($e_heads->AccountCode,"expense"); ?>
                                                                                                            <ul>
                                                                                                                <?php foreach($se_heads as $se_head): ?>
                                                                                                                    <?php if ($se_head->Category == 2){?>
                                                                                                                    <li><a href="" style="color: #000" class="get" data-id="<?= $se_head->AccountCode?>" ><?= $se_head->AccountName; ?></a>
                                                                                                                        <?php }else{?>
                                                                                                                        <li><a href="" style="color: #004c96;font-size: 18px;"  data-id="<?= $se_head->AccountCode?>" ><?= $se_head->AccountName; ?></a>
                                                                                                                            <?php }?>
                                                                                                                            <?php $sl4e_heads = $this->ChartModel->get_l4SubHead($se_head->AccountCode,"expense"); ?>
                                                                                                                            <ul>
                                                                                                                                <?php foreach($sl4e_heads as $sl4e_head): ?>
                                                                                                                                    <?php if ($sl4e_head->Category == 2){?>
                                                                                                                                    <li><a href="" style="color: #000" class="get" data-id="<?= $sl4e_head->AccountCode ?>" ><?= $sl4e_head->AccountName; ?></a>
                                                                                                                                        <?php }else{?>
                                                                                                                                        <li><a href="" style="color: #004c96;font-size: 18px;"  data-id="<?= $sl4e_head->AccountCode ?>" ><?= $sl4e_head->AccountName; ?></a>
                                                                                                                                            <?php }?>
                                                                                                                                            <?php $sl5e_heads = $this->ChartModel->get_l5SubHead($sl4e_head->AccountCode,"expense"); ?>
                                                                                                                                            <ul>
                                                                                                                                                <?php foreach($sl5e_heads as $sl5e_head): ?>
                                                                                                                                                    <?php if ($sl5e_head->Category == 2){?>
                                                                                                                                                    <li><a href="" style="color: #000" class="get" data-id="<?= $sl5e_head->AccountCode ?>" ><?= $sl5e_head->AccountName; ?></a>
                                                                                                                                                        <?php }else{?>
                                                                                                                                                        <li><a href="" style="color: #004c96;font-size: 18px;"  data-id="<?= $sl5e_head->AccountCode ?>" ><?= $sl5e_head->AccountName; ?></a>
                                                                                                                                                            <?php }?>
                                                                                                                                                            <?php $sl6e_heads = $this->ChartModel->get_l6SubHead($sl5e_head->AccountCode,"expense"); ?>
                                                                                                                                                            <ul>
                                                                                                                                                                <?php foreach($sl6e_heads as $sl6e_head): ?>
                                                                                                                                                                    <?php if ($sl6e_head->Category == 2){?>
                                                                                                                                                                    <li><a href="" style="color: #000" class="get" data-id="<?= $sl6e_head->AccountCode ?>" ><?= $sl6e_head->AccountName; ?></a>
                                                                                                                                                                        <?php }else{?>
                                                                                                                                                                        <li><a href="" style="color: #004c96;font-size: 18px;"  data-id="<?= $sl6e_head->AccountCode ?>" ><?= $sl6e_head->AccountName; ?></a>
                                                                                                                                                                            <?php }?>
                                                                                                                                                                            <?php $sl7e_heads = $this->ChartModel->get_l7SubHead($sl6e_head->AccountCode,"expense"); ?>
                                                                                                                                                                            <ul>
                                                                                                                                                                                <?php foreach($sl7e_heads as $sl7e_head): ?>
                                                                                                                                                                                    <li><a href="" style="color: #000" class="get8" data-id="<?= $sl7e_head->AccountCode ?>" ><?= $sl7e_head->AccountName; ?></a>
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
                                                                                                    <div class="col-md-6">
                                                                                                        <h3>کمپنیز:</h3>
                                                                                                        <ul style="cursor:pointer">
                                                                                                            <?php foreach($head as $heads) :?>
                                                                                                                <li><a class="a_get" data-id="<?= $heads->LevelCode;?>" style="font-size: 20px;">  <?= $heads->LevelName ?> </a>
                                                                                                                    <?php $this->load->model('CompanyModel'); ?>
                                                                                                                    <?php $subheads = $this->CompanyModel->getSubHead($heads->LevelCode); ?>
                                                                                                                    <ul id ="tree1">
                                                                                                                        <?php foreach($subheads as $subhead):?>
                                                                                                                            <li><a class="a_get" data-id="<?= $subhead->LevelCode;?>"><?= $subhead->LevelName; ?></a>
                                                                                                                                <?php $SsubHeads = $this->CompanyModel->checkSub($subhead->LevelCode); ?>
                                                                                                                                <ul>
                                                                                                                                    <?php foreach($SsubHeads as $SsubHead): ?>
                                                                                                                                        <li>&nbsp;<a class="a_get" data-id="<?= $SsubHead->LevelCode;?>" id="<?= $SsubHead->id;?>"><?= $SsubHead->LevelName; ?></a>
                                                                                                                                            <?php $SssubHeads = $this->CompanyModel->checksSub($SsubHead->LevelCode); ?>
                                                                                                                                            <ul>
                                                                                                                                                <?php foreach($SssubHeads as $SssubHead): ?>
                                                                                                                                                    <li><a class="a_get" data-id="<?= $SssubHead->LevelCode;?>"><?= $SssubHead->LevelName; ?></a>
                                                                                                                                                        <?php $l5sHeads = $this->CompanyModel->checksSubl5($SssubHead->LevelCode); ?>
                                                                                                                                                        <ul>
                                                                                                                                                            <?php foreach($l5sHeads as $l5sHead): ?>
                                                                                                                                                                <li><a class="a_get" data-id="<?= $l5sHead->LevelCode;?>"><?= $l5sHead->LevelName; ?></a>
                                                                                                                                                                    <?php $l6sHeads = $this->CompanyModel->checksSubl6($l5sHead->LevelCode); ?>
                                                                                                                                                                    <ul>
                                                                                                                                                                        <?php foreach($l6sHeads as $l6sHead): ?>
                                                                                                                                                                            <li><a class="a_get" data-id="<?= $l6sHead->LevelCode;?>" ><?= $l6sHead->LevelName; ?></a>
                                                                                                                                                                                <?php $l7sHeads = $this->CompanyModel->checksSubl7($l6sHead->LevelCode); ?>
                                                                                                                                                                                <ul>
                                                                                                                                                                                    <?php foreach($l7sHeads as $l7sHead): ?>
                                                                                                                                                                                        <li><a class="a_get" data-id="<?= $l7sHead->LevelCode;?>"><?= $l7sHead->LevelName; ?></a>
                                                                                                                                                                                            <?php $l8sHeads = $this->CompanyModel->checksSubl8($l7sHead->LevelCode); ?>
                                                                                                                                                                                            <ul>
                                                                                                                                                                                                <?php foreach($l8sHeads as $l8sHead): ?>
                                                                                                                                                                                                    <li><a class="a_get" data-id="<?= $l8sHead->LevelCode;?>"><?= $l8sHead->LevelName; ?></a>
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
                                                                                                                                                <?php endforeach?>
                                                                                                                                            </ul>
                                                                                                                                        </li>
                                                                                                                                    <?php endforeach?>
                                                                                                                                </ul>
                                                                                                                            </li>
                                                                                                                        <?php endforeach ?>
                                                                                                                    </ul>
                                                                                                                </li>
                                                                                                            <?php endforeach ?>
                                                                                                        </ul>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <?php if (isset($_SESSION['comp_id'])){
                                                                                                    $Access_level = $_SESSION['comp_id'];
                                                                                                }elseif (isset($_SESSION['comp'])){
                                                                                                    $Access_level = $_SESSION['comp'];
                                                                                                }else{
                                                                                                    $Access_level = '';
                                                                                                } $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,9,$Access_level);
                                                                                                if($_SESSION['user'][0]->id > 1){?>
                                                                                                <input type="hidden" id="rights" value="<?= $rights[0]->Rights[0].$rights[0]->Rights[1];?>">  <!-- changing -->
                                                                                                <?php }?>
                                                                                                <form role="form" action="<?= site_url('Accounts/Link/save')?>" method="POST" id="userInput">
                                                                                                    <div class="row">
                                                                                                        <div class="col-xs-6">
                                                                                                            <div class="form-group" > <!--style="width: 120%;" -->
                                                                                                                <label>اکاونٹ کا نام</label> 
                                                                                                                <textarea class="form-control" rows="1" id="chart" name="chart" style="width: 300px;resize: none;height: 35px;" disabled ></textarea>
                                                                                                                <div class="col-md-6">
                                                                                                                    <!--  <label class="control-label" for="inputSuccess" style=" padding-bottom: 5%; " >اکاونٹ کا نام</label>
                                                                                                                    <select name="chart" class="js-example-basic-multiple js-states form-control"  id="chart" >
                                                                                                                     <option value="" disabled selected>منتخب کریں</option>
                                                                                                                    <?php //foreach ($accountname as $name): ?>
                                                                                                                       <option value="<?php //echo $name->AccountCode;?>"><?php //echo $name->parentName;?>--<?php //echo $name->AccountName;?></option>
                                                                                                                        <?php//endforeach ?>
<                                                                                                                   </select> -->
                                                                                                                    <textarea class="form-control" rows="2" id="chart1" name="chart1"  style="width: 300px; display: none; ""></textarea>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="col-xs-6">
                                                                                                            <div class="form-group">
                                                                                                                <label>لیول کا نام</label>
                                                                                                                <textarea class="form-control" rows="1" id="comm" name="comm" style="width: 300px;resize: none;height: 35px;" disabled ></textarea>
                                                                                                                <textarea class="form-control" rows="2" id="comm1" name="comm1" style="width: 300px; display: none;"></textarea>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="row">
                                                                                                        <div class="col-xs-6">
                                                                                                            <div class="form-group ">
                                                                                                                <label class="control-label" for="inputSuccess">ابتدائ بیلنس</label>
                                                                                                                <input type="number" step="any" class="form-control" style="width: 300px;" id="OpeningBalance"  name="OpeningBalance" placeholder="00000000.00">
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="row">
                                                                                                        <div class="col-xs-6">
                                                                                                            <div class="checkbox">
                                                                                                                <input name="Active" type="checkbox" id="Active"  value="">غیر فعال
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="col-md-6 ">
                                                                                                        <div>
                                                                                                            <button type="submit" class="btn btn-default">ربط قائم کریں</button>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </form>