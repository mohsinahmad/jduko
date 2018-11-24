<?php $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,10);
if($_SESSION['user'][0]->IsAdmin > 1){?>
<input type="hidden" id="rights" value="<?php print_r($rights[0]->Rights[0].$rights[0]->Rights[1]);?>"> <?php }?>
<form action="<?php echo site_url('Users/saveUser/').$this->uri->segment(3);?>" method="POST" id="userInput">
    <?php if($this->session->flashdata('success')) :?>
        <div class="alert alert-success alert-dismissable" id="dalert">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <?php echo $this->session->flashdata('success');?>
        </div>
    <?php endif ?>
    <div class="row">
        <div class="panel-body">
            <div class="row">
                <div class="form-group">
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label class="control-label" for="inputSuccess">رکن کا نام</label>
                            <input class="form-control" id="username" name="username" style="width: 250px;" value="<?php echo set_value('username'); ?>" type="text" autofocus>
                            <?php echo form_error('username'); ?>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="lock" name="lock" value="">غیر فعال
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">پاس ورڈ</label>
                        <input class="form-control" id="pass" name="pass" style="width: 250px;"  type="password">
                        <?php echo form_error('pass'); ?>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">پاس ورڈ کی توثیق</label>
                        <input class="form-control" id="cpass" name="cpass" style="width: 250px;" type="password">
                        <?php echo form_error('cpass'); ?>
                    </div>
                </div>
            </div>
            <?php if (isset($_SESSION['comp_id'])){
                $Access_level = $_SESSION['comp_id'];
            }elseif (isset($_SESSION['comp'])){
                $Access_level = $_SESSION['comp'];
            }else{
                $Access_level = '';
            }?>
            <div class="col-md-6">
                <div>
                    <button type="submit" class="btn btn-default" >محفوظ کریں</button><br><br>
                    <?php $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,6,$Access_level);
                    if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[0] == '1')){?>
                            <a <button type="button"  class="btn btn-default"  href="<?= site_url('Access').'/index/'.$this->uri->segment(3);?>" >حقوق</button></a>
                    <?php }?>
                </div>
            </div>
        </div>
    </div>
</form>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">ارکان</h1>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                 <label style="float: left; margin-left: 3%;">تلاش کریں
                <input type="text" id="myInputTextField" style="float: left; height: 1%;"></label>
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                        <tr>
                            <th>رکن کا نام</th>
                            <th> محفوظ کنندہ</th>
                            <th>آخری لاگ ان</th>
                            <th>کیفیت</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($users as $user): ?>
                            <tr class="odd gradeX">
                                <td><?php echo $user->UserName; ?></td>
                                <?php $this->load->model('UserModel');
                                $createdby = $this->UserModel->getCreatedBy($user->CreatedBy); ?>
                                <td><?php echo $createdby[0]->UserName; ?></td>
                                <td class="center"><?php echo $user->LastLogin; ?></td>
                                <?php if($user->Locked == 1):?>
                                    <td class="center"> <?php echo 'فعال'; ?></td>
                                <?php else: ?>
                                    <td class="center"> <?php echo 'غیر فعال'; ?></td>
                                <?php endif ?>
                                <td style="width: 80px;">
                                <?php $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,10);
                                if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[0].$rights[0]->Rights[1].$rights[0]->Rights[2] == '111')) {?>
                                    <div class="btn-group" style="direction: ltr;">
                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Action <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu" style="min-width: 100px;">
                                            <li><a href="" class="user_edit" data-id='<?php echo $user->id;?>' data-toggle="modal" data-target="#gridSystemModal">ترمیم کریں</a></li>
                                            <?php if ($user->id != 1){
                                                if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[0].$rights[0]->Rights[1].$rights[0]->Rights[2].$rights[0]->Rights[3] == '1111')) {?>
                                            <li><a href="" class="userDelete" data-id='<?php echo $user->id;?>'>حذف کریں</a></li>
                                            <?php } if($user->Locked == 0):?>
                                                <li><a href="" class="status_update" data-value="1" data-id="<?php echo $user->id;?>">فعال کریں</a></li>
                                            <?php else: ?>
                                                <li><a href="" class="status_update" data-value="0" data-id="<?php echo $user->id;?>">غیر فعال کریں</a></li>
                                            <?php endif ?>
                                            <?php }?>
                                        </ul>
                                    </div>
                                <?php }?>
                                </td>
                            </tr>
                        <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="gridSystemModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true" onload="myOnload()">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="gridModalLabel">صارف میں ترمیم</h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid bd-example-row">
                    <div class="row">
                        <div class="panel-body">
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label class="control-label" for="inputSuccess">رکن کا نام</label>
                                            <input name="uname" class="form-control" id="uname"  style="width: 250px;" value="" type="text">
                                             <div class="uerror"></div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="checkbox" id="checkbox">
                                            <label>
                                                <input name="lock" type="checkbox" id="elock" >غیر فعال
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label class="control-label" for="inputSuccess">پاس ورڈ</label>
                                        <input class="form-control" id="conpass"  style="width: 250px;"  type="password">
                                        <div class="upass" style="color:red"></div>
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label class="control-label" for="inputSuccess">پاس ورڈ کی توثیق</label>
                                        <input class="form-control" id="upass"  style="width: 250px;" type="password">
                                        <div class="uconpass" style="color:red"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary userEdit">محفوظ کریں</button>
                </div>
            </div>
        </div>
    </div>
     <script src="<?php echo base_url()."assets/"?>urdutextbox.js"></script>
    <script>
        window.onload = myOnload;
        function myOnload(evt){
            $("#dataTables-example_filter input[class='form-control input-sm']").attr("type", "search");
            $("#dataTables-example_filter input[class='form-control input-sm']").attr("id", "myInputTextField");
            //MakeTextBoxUrduEnabled(myInputTextField);
           // MakeTextBoxUrduEnabled(username);
           // MakeTextBoxUrduEnabled(uname);
        }
    </script>