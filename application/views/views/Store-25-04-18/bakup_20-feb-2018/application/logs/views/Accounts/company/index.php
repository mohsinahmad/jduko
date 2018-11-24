<style type="text/css">
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
        content:"";
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
        content:"";
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
<form  action="<?= site_url('Accounts/CompanyStructures/save')?>" method="POST" id="companyForm" style="display: none">
    <div class="form-group has-success">
        <label class="control-label" for="inputSuccess" >پیرنٹ کوڈ</label>
        <input type="text" name= "parentcode" class="form-control" id="ParentCode" VALUE="0" style="width: 250px;" required READONLY>
    </div>
    <div class="form-group has-success">
        <label class="control-label" for="inputSuccess" >لیول کوڈ</label>
        <input type="text" name= "levelcode" class="form-control" id="LevelCode" VALUE="1" style="width: 250px;" required READONLY>
    </div>
    <div class="form-group has-success">
        <label class="control-label" for="inputSuccess" >نام</label>
        <input type="text" name= "levelname" class="form-control" id="LevelName" style="width: 250px;" AUTOFOCUS REQUIRED>
    </div>
    <div>
        <button type="submit" class="btn btn-default"> شامل کریں</button>
    </div>
</form>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header"> کمپنی کی ساخت</h1>
    </div>
</div>
<div class="row" >
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="container" >
                    <div class="row">
                        <div class="col-md-12">
                            <ul>
                                <?php foreach($head as $heads) :?>
                                    <li><a class="edit" data-id="<?= $heads->LevelCode;?>" href="" data-toggle="modal" data-target="#gridSystemModal" style="font-size: 20px;">  <?= $heads->LevelName ?> </a>
                                        <?php $this->load->model('CompanyModel'); ?>
                                        <?php $subheads = $this->CompanyModel->getSubHead($heads->LevelCode); ?>
                                        <ul id="tree1">
                                            <?php foreach($subheads as $subhead):?>
                                                <li><a class="edit" data-id="<?= $subhead->LevelCode;?>" href="" data-toggle="modal" data-target="#gridSystemModal"><?= $subhead->LevelName; ?></a>
                                                    <?php $SsubHeads = $this->CompanyModel->checkSub($subhead->LevelCode); ?>
                                                    <ul>
                                                        <?php foreach($SsubHeads as $SsubHead): ?>
                                                            <li>&nbsp;<a class="edit" data-id="<?= $SsubHead->LevelCode;?>" href="" data-toggle="modal" data-target="#gridSystemModal"><?= $SsubHead->LevelName; ?></a>
                                                                <?php $SssubHeads = $this->CompanyModel->checksSub($SsubHead->LevelCode); ?>
                                                                <ul>
                                                                    <?php foreach($SssubHeads as $SssubHead): ?>
                                                                        <li><a class="edit" data-id="<?= $SssubHead->LevelCode;?>" href=""  data-toggle="modal" data-target="#gridSystemModal"><?= $SssubHead->LevelName; ?></a>
                                                                            <?php $l5sHeads = $this->CompanyModel->checksSubl5($SssubHead->LevelCode); ?>
                                                                            <ul>
                                                                                <?php foreach($l5sHeads as $l5sHead): ?>
                                                                                    <li><a class="edit" data-id="<?= $l5sHead->LevelCode;?>" href=""  data-toggle="modal" data-target="#gridSystemModal"><?= $l5sHead->LevelName; ?></a>
                                                                                        <?php $l6sHeads = $this->CompanyModel->checksSubl6($l5sHead->LevelCode); ?>
                                                                                        <ul>
                                                                                            <?php foreach($l6sHeads as $l6sHead): ?>
                                                                                                <li><a class="edit" data-id="<?= $l6sHead->LevelCode;?>" href=""  data-toggle="modal" data-target="#gridSystemModal"><?= $l6sHead->LevelName; ?></a>
                                                                                                    <?php $l7sHeads = $this->CompanyModel->checksSubl7($l6sHead->LevelCode); ?>
                                                                                                    <ul>
                                                                                                        <?php foreach($l7sHeads as $l7sHead): ?>
                                                                                                            <li><a class="edit" data-id="<?= $l7sHead->LevelCode;?>" href=""  data-toggle="modal" data-target="#gridSystemModal"><?= $l7sHead->LevelName; ?></a>
                                                                                                                <?php $l8sHeads = $this->CompanyModel->checksSubl8($l7sHead->LevelCode); ?>
                                                                                                                <ul>
                                                                                                                    <?php foreach($l8sHeads as $l8sHead): ?>
                                                                                                                        <li><a class="edit8" data-id="<?= $l8sHead->LevelCode;?>" href=""  data-toggle="modal" data-target="#gridSystemModal"><?= $l8sHead->LevelName; ?></a>
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
                                            <?php endforeach?>
                                        </ul>
                                    </li>
                                <?php endforeach ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,7);
if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[0] == '1')){ ?>
    <div id="gridSystemModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="gridModalLabel">کمپنی کی ساخت</h4>
                </div>
                <div class="modal-body">
                    <div class="container-fluid bd-example-row">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group has-success">
                                    <label class="control-label" for="inputSuccess" >پیرنٹ کا کوڈ</label>
                                    <input type="text"  class="form-control" id="parentcode" style="width: 250px;" READONLY>
                                </div>
                            </div>
                            <div class="col-md-6 ">
                                <div class="form-group has-success">
                                    <label class="control-label" for="inputSuccess" >پیرنٹ کا نام</label>
                                    <input type="text" class="form-control" id="parentname" autofocus  style="width: 250px;" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 ">
                                <div class="form-group has-success">
                                    <label class="control-label" for="inputSuccess" >لیول کا کوڈ</label>
                                    <input type="number"  class="form-control" id="levelcode" style="width: 250px;" readonly required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group has-success">
                                    <label class="control-label" for="inputSuccess" >لیول کا نام</label>
                                    <input type="text" class="form-control" id="levelname" style="width: 250px;" required autofocus>
                                </div>
                            </div>
                        </div>
                        <div class="row ser">
                            <div class="col-md-6 ">
                                <div class="form-group has-success">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if($this->session->userdata('in_use')){
                if($_SESSION['user'][0]->id){?>
                <div class="modal-footer">
                    <?php if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[2] == '1')){ ?>
                        <button type="button" id="dataEdit" class="btn btn-primary dataEdit">محفوظ کریں</button> <?php }?>
                    <button type="button"  class="btn btn-primary dataSave">محفوظ کریں</button>
                    <?php if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[3] == '1')){ ?>
                        <button type="button" id="dataDelete" class="btn btn-primary dataDelete">حذف کریں</button>
                    <?php } if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[1] == '1')){ ?>
                        <button type="button"  class="btn btn-primary insertNew">نیا اکاؤنٹ بنایں</button><?php }?>
                    <span id="link" style="color:red; font-size:11px;">نیا اکاؤنٹ بنانے کے لئے سب سے پہلے اس  کے ربط کو حذف کریں</span>
                </div>
            </div> <?php }}?>
        </div>
    </div>
<?php }?>