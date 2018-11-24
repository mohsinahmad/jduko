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

<div class="row" >

    <div class="col-lg-12">

        <div class="panel panel-default">

            <div class="panel-body">

                <div class="container" >

                    <div class="row">

                        <div class="col-md-12">

                            <ul id="tree1">
                                <?php foreach($Accounts as $key => $Account){
                                        foreach($Account as $key1 => $level1){
                                            if (isset($level1->AccountCode)) {
                                            ?>
                                            <li>
                                                <a href="" style="color: #004c96;font-size: 18px;" class="a_Edit" data-id="<?php echo $level1->AccountCode;?>" data-toggle="modal" data-target="#gridSystemModal"><?php echo $level1->AccountName;?></a>
                                                <ul>

                                                    <?php if (isset($Account['Child'.$key1])) {
                                                            foreach($Account['Child'.$key1] as $key2 => $level2){
                                                            if (isset($level2->AccountCode)) {
                                                            ?>

                                                            <?php  if ($level2->Category == 2){?>

                                                                <li><a href="" style="color: #000" class="a_Edit" data-id="<?php echo $level2->AccountCode; ?>" data-toggle="modal" data-target="#gridSystemModal"><?php echo $level2->AccountName;?></a>

                                                            <?php }else{?>

                                                                <li><a href=""  style="color: #004c96;font-size: 18px;" class="a_Edit" data-id="<?php echo $level2->AccountCode; ?>" data-toggle="modal" data-target="#gridSystemModal"><?php echo $level2->AccountName;?></a>

                                                            <?php }?>

                                                            <ul>

                                                                <?php   if (isset($Account['Child'.$key1]['Child'.$key2])) {
                                                                        foreach($Account['Child'.$key1]['Child'.$key2] as $key3 => $level3){
                                                                        if (isset($level3->AccountCode)) {
                                                                        ?>

                                                                        <?php if ($level3->Category == 2){?>

                                                                            <li><a href=""  style="color: #000" class="a_Edit" data-id="<?php echo $level3->AccountCode;?>" data-toggle="modal" data-target="#gridSystemModal"><?php echo $level3->AccountName?></a>

                                                                        <?php }else{?>

                                                                            <li><a href=""  style="color: #004c96;font-size: 18px;" class="a_Edit" data-id="<?php echo $level3->AccountCode;?>" data-toggle="modal" data-target="#gridSystemModal"><?php echo $level3->AccountName?></a>

                                                                        <?php } ?>
                                                                        <ul>

                                                                            <?php if (isset($Account['Child'.$key1]['Child'.$key2]['Child'.$key3])) {
                                                                            foreach($Account['Child'.$key1]['Child'.$key2]['Child'.$key3] as $key4 =>$level4){
                                                                                    if (isset($level4->AccountCode)) {
                                                                                    ?>

                                                                                    <?php if ($level4->Category == 2){?>

                                                                                        <li><a href=""  style="color: #000" class="a_Edit" data-id="<?php echo $level4->AccountCode;?>" data-toggle="modal" data-target="#gridSystemModal"><?php echo $level4->AccountName?></a>

                                                                                    <?php }else{?>

                                                                                        <li><a href=""  style="color: #004c96;font-size: 18px;" class="a_Edit" data-id="<?php echo $level4->AccountCode;?>" data-toggle="modal" data-target="#gridSystemModal"><?php echo $level4->AccountName?></a>

                                                                                    <?php }?>

                                                                                    <ul>

                                                                                        <?php if (isset($Account['Child'.$key1]['Child'.$key2]['Child'.$key3]['Child'.$key4])) {
                                                                                            foreach($Account['Child'.$key1]['Child'.$key2]['Child'.$key3]['Child'.$key4] as $key5 => $level5){
                                                                                            if (isset($level5->AccountCode)) {?>

                                                                                                <?php if ($level5->Category == 2){?>

                                                                                                    <li><a href="" style="color: #000" class="a_Edit" data-id="<?php echo $level5->AccountCode;?>" data-toggle="modal" data-target="#gridSystemModal"><?php echo $level5->AccountName?></a>

                                                                                                <?php }else{ ?>

                                                                                                    <li><a href="" style="color: #004c96;font-size: 18px;" class="a_Edit" data-id="<?php echo $level5->AccountCode;?>" data-toggle="modal" data-target="#gridSystemModal"><?php echo $level5->AccountName;?> </a>

                                                                                                <?php }?>

                                                                                                <ul>

                                                                                                    <?php if (isset($Account['Child'.$key1]['Child'.$key2]['Child'.$key3]['Child'.$key4]['Child'.$key5])) {
                                                                                                            foreach($Account['Child'.$key1]['Child'.$key2]['Child'.$key3]['Child'.$key4]['Child'.$key5] as $key6 => $level6){
                                                                                                            if (isset($level6->AccountCode)){
                                                                                                            ?>

                                                                                                            <?php if ($level6->Category == 2){?>

                                                                                                                <li><a href=""  style="color: #000" class="a_Edit" data-id="<?php echo $level6->AccountCode;?>" data-toggle="modal" data-target="#gridSystemModal"><?php echo $level6->AccountName?></a>

                                                                                                            <?php }else{?>

                                                                                                                <li><a href=""  style="color: #004c96;font-size: 18px;" class="a_Edit" data-id="<?php echo $level6->AccountCode;?>" data-toggle="modal" data-target="#gridSystemModal"><?php echo $level6->AccountName?></a>

                                                                                                            <?php }?>

                                                                                                            <ul>

                                                                                                                    <?php if (isset($Account['Child'.$key1]['Child'.$key2]['Child'.$key3]['Child'.$key4]['Child'.$key5]['Child'.$key6])) {
                                                                                                                    foreach($Account['Child'.$key1]['Child'.$key2]['Child'.$key3]['Child'.$key4]['Child'.$key5]['Child'.$key6] as $key7 => $level7){?>
                                                                                                                <?php if(isset($level7->AccountCode)){?>

                                                                                                                        <li><a href="" style="color: #000" class="a_Edit8" data-id="<?php echo $level7->AccountCode;?>" data-toggle="modal" data-target="#gridSystemModal"><?php echo $level7->AccountName?></a>

                                                                                                                            <ul>

                                                                                                                                <li></li>

                                                                                                                            </ul>

                                                                                                                        </li>
                                                                                                                    <?php } } }?>
                                                                                                            </ul>

                                                                                                            </li>
                                                                                                        <?php  } } }?>

                                                                                                </ul>

                                                                                                </li>
                                                                                            <?php } } }?>

                                                                                    </ul>

                                                                                    </li>
                                                                                <?php } } }?>

                                                                        </ul>

                                                                        </li>
                                                                    <?php } } }?>

                                                            </ul>

                                                            </li>
                                                        <?php } } }?>


                                                </ul>

                                            </li>

                                        <?php } } } ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,8);
    if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[0] == '1')){ ?>
    <div id="gridSystemModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="gridModalLabel">اکاؤنٹ کی سرخی</h4>
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
                                    <input type="text" class="form-control" id="accountCode" name="AccountCode" >
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
                                        <option value="1">اثاثے</option>                         /* اثاثے */
                                        <option value="2">واجبات</option>  /* واجبات */
                                        <option value="3">سرمایا</option>      /* روپیه */
                                        <option value="4">آمدنی</option>      /* آمدنی */
                                        <option value="5">اخراجات</option>     /* اخراجات */
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label>اکاؤنٹ کی اقسام</label>
                                    <select class="form-control myinput" id="category" name="Category">
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
                                        <option value="1">کیش بک</option>
                                        <option value="2">بنک بک</option>
                                        <option value="3">سیلز بک</option>
                                        <option value="4">پرچیز بک</option>
                                        <option value="5">کسٹمر</option>
                                        <option value="6">سپلائر</option>
                                        <option value="7">دیگر</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <?php if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[2] == '1')){ ?>
                        <button type="button" class="btn btn-primary coaEdit">محفوظ کریں</button> <?php }?>
                    <button type="button" class="btn btn-primary coaSave">محفوظ کریں</button>
                    <?php if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[3] == '1')){ ?>
                        <button type="button" class="btn btn-primary coaDelete">حذف کریں</button><?php }?>
                    <?php if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[1] == '1')){ ?>
                        <button type="button" class="btn btn-primary insertNewCOA">نیا اکاؤنٹ بنایں</button><?php }?>
                </div>
            </div>
        </div>
    </div>
<?php }?>