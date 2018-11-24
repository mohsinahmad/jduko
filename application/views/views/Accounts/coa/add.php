<style>
    .myinput{
        height: 48px!important;
    }
</style>
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
    line-height:2em;
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
<h1>چارٹ آف آکاونٹس</h1> <br><br>
<form role="form" action="<?php echo site_url('Accounts/ChartOfAccounts/save')?>" method="POST">
    <div class="row">
        <div class="col-xs-6">
            <div class="form-group ">
                <label class="control-label" for="inputSuccess"> پیرنٹ کوڈ</label>
                <input type="number" class="form-control" id="" name="ParentCode">
            </div>
        </div>
        <div class="col-xs-6">
            <div class="form-group ">
                <label class="control-label" for="inputSuccess">اکاونٹ کوڈ</label>
                <input type="number" class="form-control" id="" name="AccountCode">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-6">
            <div class="form-group ">
                <label class="control-label" for="inputSuccess">اکاونٹ نام</label>
                <input type="text" class="form-control" id="accountname"  name="AccountName">
            </div>
        </div>
        <div class="col-xs-6">
            <div class="form-group">
                <label>ہیڈ</label>
                <select class="form-control myinput" name="Head">
                    <option>منتخب کریں</option>
                    <option value="1">ایسٹس</option>                         /* اثاثے */
                    <option value="2">لایبیلیتیز</option>  /* واجبات */
                    <option value="3">کیپیٹل</option>      /* روپیه */
                    <option value="4">ریوینیو</option>      /* آمدنی */
                    <option value="5">ایکسپینس</option>     /* اخراجات */
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-6">
            <div class="form-group">
                <label>اکاؤنٹ کی اقسام</label>
                <select class="form-control myinput" name="Category">
                    <option>منتخب کریں</option>
                    <option value="1">کنٹرول اکاؤنٹ</option> /* Summry accunt*/
                    <option value="2">ٹرانزیکشن اکاؤنٹ</option>    /* detail accunt*/
                </select>
            </div>
        </div>
        <div class="col-xs-6">
            <div class="form-group ">
                <label>بک کی اقسام</label>
                <select class="form-control myinput" name="Type">
                    <option>منتخب کریں</option>
                    <option value="1">کیش بک</option>
                    <option value="2">بنک بک</option>
                    <option value="3">سیلز بک</option>
                    <option value="4">پرچیز بک</option>
                    <option value="5">کسٹمر</option>
                    <option value="6">سپلائر</option>
                </select>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-default">جمع کروائیں</button>
</form>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header"> چارٹ آف آکاونٹس</h1>
    </div>
</div>
    <div class="row" >
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="container" >
                        <div class="row">
                            <div class="col-md-12">
                               <ul id="tree1">
                                    <li><a href="" class="a_Edit" data-id="1" data-toggle="modal" data-target="#gridSystemModal" >ایسٹس</a>
                                        <ul>
                                        <?php foreach($A_Heads as $a_heads): ?>
                                            <li><a href="" class="a_Edit" data-id="<?php echo $a_heads->AccountCode; ?>" data-toggle="modal" data-target="#gridSystemModal"><?php echo $a_heads->AccountName;?></a>
                                            <?php $this->load->model('ChartModel'); ?>
                                            <?php $sa_heads = $this->ChartModel->get_SubHead($a_heads->AccountCode,"assets"); ?>
                                                <ul>
                                                <?php foreach($sa_heads as $as_head): ?>
                                                    <li><a href="" class="a_Edit" data-id="<?php echo $as_head->AccountCode;?>" data-toggle="modal" data-target="#gridSystemModal"><?php echo $as_head->AccountName?></a>
                                                    </li>
                                                <?php endforeach ?>
                                                </ul>
                                            </li>
                                        <?php endforeach ?>
                                        </ul>
                                    </li>
                                    <li><a href="" class="a_Edit" data-id="2" data-toggle="modal" data-target="#gridSystemModal" href=""> لایبیلیتیز</a>
                                        <ul>
                                        <?php foreach($L_Heads as $l_heads): ?>
                                            <li><a href="" class="a_Edit" data-id="<?php echo $l_heads->AccountCode?>" data-toggle="modal" data-target="#gridSystemModal"><?php echo $l_heads->AccountName;?></a>
                                                <?php $sl_heads = $this->ChartModel->get_SubHead($l_heads->AccountCode,"libilities"); ?>
                                                    <ul>
                                                    <?php foreach($sl_heads as $sl_head): ?>
                                                    <li><a href="" class="a_Edit" data-id="<?php echo $sl_head->AccountCode; ?>" data-toggle="modal" data-target="#gridSystemModal"><?php echo $sl_head->AccountName; ?></a>
                                                    </li>
                                                <?php endforeach ?>
                                                </ul>
                                            </li>
                                        <?php endforeach ?>
                                        </ul>
                                    </li>
                                    <li><a href="" class="a_Edit" data-id="3" data-toggle="modal" data-target="#gridSystemModal" >کیپیٹل</a>
                                        <ul>
                                        <?php foreach($C_Heads as $c_heads): ?>
                                            <li><a href="" class="a_Edit" data-id="<?php echo $c_heads->AccountCode ?>" data-toggle="modal" data-target="#gridSystemModal"><?php echo $c_heads->AccountName;?></a>
                                             <?php $sc_heads = $this->ChartModel->get_SubHead($c_heads->AccountCode,"capital"); ?>
                                            <ul>
                                            <?php foreach($sc_heads as $sc_head): ?>
                                                <li><a href="" class="a_Edit" data-id="<?php echo $sc_head->AccountCode ?>" data-toggle="modal" data-target="#gridSystemModal"><?php echo $sc_head->AccountName; ?></a>
                                                    <ul>
                                                    </ul>
                                                </li>
                                                 <?php endforeach ?>
                                            </ul>
                                            </li>
                                        <?php endforeach ?>
                                        </ul>
                                    </li>
                                    <li><a href="" class="a_Edit" data-id="4" data-toggle="modal" data-target="#gridSystemModal" >ریوینیو</a>
                                        <ul>
                                        <?php foreach($R_Heads as $r_heads): ?>
                                            <li><a href="" class="a_Edit" data-id="<?php echo $r_heads->AccountCode ?>" data-toggle="modal" data-target="#gridSystemModal"><?php echo $r_heads->AccountName;?></a>
                                                <?php $sr_heads = $this->ChartModel->get_SubHead($r_heads->AccountCode,"revenue"); ?>
                                                <ul>
                                                <?php foreach($sr_heads as $sr_head): ?>
                                                <li><a href="" class="a_Edit" data-id="<?php echo $sr_head->AccountCode ?>" data-toggle="modal" data-target="#gridSystemModal"><?php echo $sr_head->AccountName; ?></a>
                                                    <li><a href="" class="a_Edit" data-id="<?php echo $sr_head->AccountCode;?>" data-toggle="modal" data-target="#gridSystemModal"><?php echo $sr_head->AccountName; ?></a>
                                                        <ul>
                                                        </ul>
                                                    </li>
                                                <?php endforeach ?>
                                                </ul>
                                            </li>
                                            <?php endforeach ?>
                                        </ul>
                                    </li>
                                    <li><a href="" class="a_Edit" data-id="5" data-toggle="modal" data-target="#gridSystemModal" >ایکسپینس</a>
                                        <ul>
                                        <?php foreach($E_Heads as $e_heads): ?>
                                            <li><a href="" class="a_Edit" data-id="<?php echo $e_heads->AccountCode ?>" data-toggle="modal" data-target="#gridSystemModal"><?php echo $e_heads->AccountName;?></a>
                                              <?php $sr_heads = $this->ChartModel->get_SubHead($r_heads->AccountCode,"expense"); ?>
                                                <ul>
                                                <?php foreach($sr_heads as $sr_head): ?>
                                                    <li><a href="" class="a_Edit" data-id="<?php echo $sr_head->AccountCode?>" data-toggle="modal" data-target="#gridSystemModal"><?php echo $sr_head->AccountName; ?></a>
                                                        <ul>
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
    <div id="gridSystemModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="gridModalLabel">Modal title</h4>
          </div>
          <div class="modal-body">
            <div class="container-fluid bd-example-row">
              <div class="row">
                <div class="col-xs-6">
                    <div class="form-group ">
                            <label class="control-label" for="inputSuccess"> پیرنٹ کوڈ</label>
                            <input type="number" class="form-control" id="parentCode" name="ParentCode">
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="form-group ">
                            <label class="control-label" for="inputSuccess">پیرنٹ نام</label>
                            <input type="text" class="form-control" id="parentName" name="parentName">
                        </div>
                    </div>
                </div>
                 <div class="row">
                 <div class="col-xs-6">
                        <div class="form-group ">
                            <label class="control-label" for="inputSuccess">اکاونٹ کوڈ</label>
                            <input type="number" class="form-control" id="accountCode" name="AccountCode">
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="form-group ">
                            <label class="control-label" for="inputSuccess">اکاونٹ نام</label>
                            <input type="text" class="form-control" id="accountName"  name="AccountName">
                        </div>
                    </div>
                </div>
                    <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label>ہیڈ</label>
                            <select class="form-control myinput" id="head" name="Head">
                                <option value="">منتخب کریں</option>
                                <option value="1">ایسٹس</option>                         /* اثاثے */
                                <option value="2">لایبیلیتیز</option>  /* واجبات */
                                <option value="3">کیپیٹل</option>      /* روپیه */
                                <option value="4">ریوینیو</option>      /* آمدنی */
                                <option value="5">ایکسپینس</option>     /* اخراجات */
                            </select>
                        </div>
                    </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label>اکاؤنٹ کی اقسام</label>
                                <select class="form-control myinput" id="category" name="Category">
                                    <option value="">منتخب کریں</option>
                                    <option value="1">کنٹرول اکاؤنٹ</option> /* Summry accunt*/
                                    <option value="2">ٹرانزیکشن اکاؤنٹ</option>    /* detail accunt*/
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="form-group ">
                                <label>بک کی اقسام</label>
                                <select class="form-control myinput" id="type" name="Type">
                                    <option value="">منتخب کریں</option>
                                    <option value="1">کیش بک</option>
                                    <option value="2">بنک بک</option>
                                    <option value="3">سیلز بک</option>
                                    <option value="4">پرچیز بک</option>
                                    <option value="5">کسٹمر</option>
                                    <option value="6">سپلائر</option>
                                </select>
                            </div>
                        </div>
                    </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" id="close" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary coaEdit">Edit</button>
            <button type="button" class="btn btn-primary coaSave">Save</button>
            <button type="button" class="btn btn-primary coaDelete">Delete</button>
            <button type="button" class="btn btn-primary insertNewCOA">Insert New Account</button>
          </div>
        </div>
      </div>
    </div>
</div>
<script src="<?php echo base_url()."assets/"?>urdutextbox.js"></script>
<script>
    window.onload = myOnload;
    function myOnload(evt){
        //MakeTextBoxUrduEnabled(accountName);//enable Urdu in html text area
    }
</script>