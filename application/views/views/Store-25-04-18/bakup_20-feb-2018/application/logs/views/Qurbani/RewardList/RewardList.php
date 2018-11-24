<form action="<?= site_url('Qurbani/RewardList/SaveList')?>" method="POST">
    <div class="row">
        <?php if($this->session->flashdata('success')) :?>
            <div class="alert alert-success alert-dismissable" id="dalert">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <?= $this->session->flashdata('success');?>
            </div>
        <?php endif ?>
        <?php if($this->session->flashdata('error')) :?>
            <div class="alert alert-danger alert-dismissable" id="dalert">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <?= $this->session->flashdata('error');?>
            </div>
        <?php endif;?>
        <div class="heading">
            <br>
            <h1 class="page-header" style="margin-top: 10px;">انعامی حقداران</h1>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="form-group">
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label class="control-label" for="inputSuccess">نام</label>
                            <input class="form-control" type="text" name="Name" style="width: 250px;" value="" autofocus required>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label class="control-label" for="inputSuccess">منظور شدہ انعام ۳۷ھ</label>
                            <input class="form-control" type="number" name="Last_Year_Reward" style="width: 250px;" value="" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4">
                        <div class="form-group">
                            <label class="control-label" for="inputSuccess">لوکیشن</label>
                            <select class="form-control" name="Location" style="padding-top: 0px;padding-bottom: 0px;width: 250px;">
                                <option value="" selected>منتخب کریں</option>
                                <?php foreach ($Locations as $location) {?>
                                    <option value="<?= $location->Id?>"><?= $location->Name?></option>
                                <?php }?>
                            </select>
                        </div>
                    </div>
                <div class="col-xs-2">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">
                            <input type="radio" name="Increament_Type" style="width: 15px;" value="1" autofocus checked> فیصد</label>
                        <label class="control-label" for="inputSuccess">
                            <input type="radio" name="Increament_Type" style="width: 15px;" value="0" autofocus>رقم</label>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">اضا فہ</label>
                        <input class="form-control" type="number" name="Increament" style="width: 250px;" value="" required>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">محفوظ کریں</button>
    </div>
</form>
<br><br>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h1>انعامی حقداران</h1>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <label style="float: left; margin-left: 3%;">تلاش کریں
                        <input type="text" id="myInputTextField" style="float: left; height: 6%;"></label>
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                        <tr>
                            <th style="text-align: center;width: 9%">نمبر شمار</th>
                            <th style="text-align: center;width: 15%">نام</th>
                            <th style="text-align: center">لوکیشن</th>
                            <th style="text-align: center;width: 16%;">منظور شدہ انعام ۳۷ھ</th>
                            <th style="text-align: center">اضا فہ</th>
                            <th style="text-align: center">انعام ۱۴۳۸ھ</th>
                            <th style="text-align: center">حذف/تدوین</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($RewardList as $key => $value):?>
                            <tr class="odd gradeX">
                                <td><?= ++$key?></td>
                                <td><?= $value->Name?></td>
                                <?php if($value->Location == 41){?>
                                <td><?= "کورنگی"?></td>
                                <?php }elseif($value->Location == 43){?>
                                <td><?= "گلشن اقبال"?></td>
                                <?php }else{?>
                                <td><?= "نانکواڑہ"?></td>
                                <?php }?>
                                <td><?= $value->Last_Year_Reward?></td>
                                <?php if($value->Increament_Type == 1){
                                       $increament = ($value->Increament / 100) * $value->Last_Year_Reward;
                                    }else{
                                       $increament = $value->Increament;
                                    }
                                    ?>
                                <td><?= $increament?></td>
                                <?php if($value->Increament_Type == 1){
                                    $check = ($value->Increament / 100) * $value->Last_Year_Reward;
                                    $reward = ($check + $value->Last_Year_Reward) / 5;
                                    $final_Reward = round($reward) * 5;
                                }else{
                                    $final_Reward = $value->Increament + $value->Last_Year_Reward;
                                }?>
                                <td><?= $final_Reward?></td>
                                <td style="width: 18%;text-align: center">
                                    <button type="button" class="btn btn-success getid" data-toggle="modal" data-target="#gridSystemModal" data-id="<?= $value->Id;?>" style="font-size: 10px;background-color: #517751;border-color: #517751; ">تصیح کریں
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url()."assets/"; ?>js/jquery-1.12.4.js"></script>
