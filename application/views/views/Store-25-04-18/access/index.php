<style>    .myinput{        height: 48px!important;    }    .tree, .tree ul {        margin:0;        padding:0;        list-style:none    }    .tree ul {        margin-left:1em;        position:relative    }    .tree ul ul {        margin-left:.5em    }    .tree ul:before {        display:block;        width:0;        position:absolute;        top:0;        bottom:0;        left:0;        border-left:1px solid    }    .tree li {        margin:0;        padding:0 1em;        line-height:2.5em;        color:#369;        font-weight:700;        position:relative    }    .tree ul li:before {        display:block;        width:10px;        height:0;        border-top:1px solid;        margin-top:-1px;        position:absolute;        top:1em;        left:0    }    .tree ul li:last-child:before {        background:#fff;        height:auto;        top:1em;        bottom:0    }    .indicator {        margin-right:5px;    }    .tree li a {        text-decoration: none;        color:#369;    }    .tree li button, .tree li button:active, .tree li button:focus {        text-decoration: none;        color:#369;        border:none;        background:transparent;        margin:0px 0px 0px 0px;        padding:0px 0px 0px 0px;        outline: 0;    }</style><div class="row" id="userInput">        <?php if($this->session->flashdata('error')) :?>            <div class="alert alert-danger alert-dismissable" id="dalert">                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>                <?php echo $this->session->flashdata('error');?>            </div>        <?php endif ?>    <?php if (isset($_SESSION['comp_id'])){        $Access_level = $_SESSION['comp_id'];    }elseif (isset($_SESSION['comp'])){        $Access_level = $_SESSION['comp'];    }else{        $Access_level = '';    }$module = ($this->uri->segment(3) == 'Accounts')? 1 : 2;    $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,6,$Access_level,$module);    //print_r($rights); exit();    if($_SESSION['user'][0]->id > 1){?><input type="hidden" id="rights" value="<?= $rights[0]->Rights[0].$rights[0]->Rights[1]?>">    <?php }?>    <div class="col-lg-12">        <div class="panel panel-default">        <h1 class="page-header"> رسائی کے حقوق </h1>            <div class="panel-body">                <div class="row">                    <div class="modal-body">                        <div class="container-fluid bd-example-row">                            <div class="row">                                <div class="col-md-4">                                    <div class="form-group has-success">                                        <label class="control-label" for="inputSuccess" >رکن کا نام</label>                                        <select name="UserID" class="form-control"  id="user_name" style="height: 50px;">                                            <?php foreach ($getusername as $getusername):?>                                                <option value="<?php echo $getusername->id;?>"><?php echo $getusername->UserName; ?></option>                                            <?php endforeach ?>                                        </select>                                    </div>                                </div>                                <div class="col-md-4">                                    <div class="form-group has-success">                                        <label class="control-label" for="inputSuccess">فیچر </label>                                        <select name="FeatureID" class="form-control" id="feature"  style="height: 50px;">                                            <option disabled selected> منتخب کریں</option>                                            <?php foreach($getfeature as $getfeature): ?>                                                <option value="<?php echo $getfeature->FeatureID;?>" data-id="<?php echo $getfeature->FeatureType;?>"> <?php echo $getfeature->FeatureName;?> </option>                                            <?php endforeach ?>                                        </select>                                    </div>                                </div>                                <input type="hidden" name="LevelID" id="levelId" value="" class="userid">                            </div>                        </div>                        <div class="checkbox">                            <div class="typeall">                                <div class="type1">                                    <div class="checkbox">                                        <label><input style="margin-right: -20px;position: absolute;" type="checkbox" class="raccess all"  value="1">رسائی</label>                                        <label><input style="margin-right: -20px;position: absolute;" type="checkbox" class="radd all" value="1">اضافہ</label>                                        <label><input style="margin-right: -20px;position: absolute;" type="checkbox" class="redit all"  value="1">تصیح</label>                                        <label><input style="margin-right: -20px;position: absolute;" type="checkbox" class="rdelete all" value="1">حذف</label>                                    </div>                                </div>                                <div class="type0">                                    <div class="checkbox">                                        <label><input style="margin-right: -20px;position: absolute;" class="rview all" type="checkbox" value="1">خاکہ دیکھیے</label>                                        <label><input style="margin-right: -20px;position: absolute;" class="rprint all" type="checkbox" value="1">پرنٹ</label>                                    </div>                                </div>                                <div class="checkbox">                                    <label><input type="checkbox" id="select_all">سب</label>                                </div>                            </div>                        </div>                        <div class="row" style="padding: 10px;">                            <button type="submit"  class="btn btn-primary data-save">محفوظ کریں</button>                        </div>                    </div>                </div>            </div>        </div>    </div></div><!--<div id="gridSystemModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">--><!--    <div class="modal-dialog" role="document">--><!--        <div class="modal-content">--><!--            <div class="modal-header">--><!--                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>--><!--                <h4 class="modal-title" id="gridModalLabel">رسائی کے حقوق</h4>--><!--            </div>--><!--            <div class="modal-body">--><!--                    <div class="container-fluid bd-example-row">--><!--                        <div class="row">--><!--                            <div class="col-md-6">--><!--                                <div class="form-group has-success">--><!--                                    <label class="control-label" for="inputSuccess" >رکن کا نام</label>--><!--                                    <select name="UserID" class="form-control"  id="user_name" style="height: 50px;">--><!--                                        --><?php //foreach ($getusername as $getusername):?><!--                                            <option value="--><?php //echo $getusername->id;?><!--">--><?php //echo $getusername->UserName; ?><!--</option>--><!--                                        --><?php //endforeach ?><!--                                    </select>--><!--                                </div>--><!--                            </div>--><!--                            <div class="col-md-6 ">--><!--                                <div class="form-group has-success">--><!--                                    <label class="control-label" for="inputSuccess">فیچر </label>--><!--                                    <select name="FeatureID" class="form-control" id="feature"  style="height: 50px;">--><!--                                     <option disabled selected> منتخب کریں</option>--><!--                                        --><?php //foreach($getfeature as $getfeature): ?><!--                                            <option value="--><?php //echo $getfeature->FeatureID;?><!--" data-id="--><?php //echo $getfeature->FeatureType;?><!--"> --><?php //echo $getfeature->FeatureName;?><!-- </option>--><!--                                        --><?php //endforeach ?><!--                                    </select>--><!--                                </div>--><!--                            </div>--><!--                            <input type="hidden" name="LevelID" id="levelId" value="" class="userid">--><!--                        </div>--><!--                    </div>--><!--                    <div class="checkbox">--><!--                        <div class="typeall">--><!--                            <div class="type1">--><!--                                <div class="checkbox">--><!--                                    <label><input style="margin-right: -20px;position: absolute;" type="checkbox" class="raccess all"  value="1">رسائی</label>--><!--                                    <label><input style="margin-right: -20px;position: absolute;" type="checkbox" class="radd all" value="1">اضافہ</label>--><!--                                    <label><input style="margin-right: -20px;position: absolute;" type="checkbox" class="redit all"  value="1">تصیح</label>--><!--                                    <label><input style="margin-right: -20px;position: absolute;" type="checkbox" class="rdelete all" value="1">حذف</label>--><!--                                </div>--><!--                            </div>--><!--                            <div class="type0">--><!--                                <div class="checkbox">--><!--                                    <label><input style="margin-right: -20px;position: absolute;" class="rview all" type="checkbox" value="1">خاکہ دیکھیے</label>--><!--                                    <label><input style="margin-right: -20px;position: absolute;" class="rprint all" type="checkbox" value="1">پرنٹ</label>--><!--                                </div>--><!--                            </div>--><!--                            <div class="checkbox"> --><!--                                <label><input type="checkbox" id="select_all">سب</label>--><!--                            </div>--><!--                        </div>--><!--                    </div>--><!--                    <div class="row" style="padding: 10px;">--><!--                        <button type="submit"  class="btn btn-primary data-save">محفوظ کریں</button>--><!--                    </div>--><!--            </div>--><!--        </div>--><!--    </div>--><!--</div>-->