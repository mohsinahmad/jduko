<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">ڈیش بورڈ</h1>
    </div>
</div>
<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3" style="padding-right:;padding-right: 08px;">
                        <i class="fa fa-comments fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right" style="padding-right: 0px;padding-left: 0px;">
                        <div class="huge">اسٹاک رپورٹ</div>
                        <div></div>
                    </div>
                </div>
            </div>



            <?php


//            print_r($_SESSION);

            if (isset($_SESSION['comp_id'])){

//                print_r($_SESSION['comp_id']);
               $Access_level = $_SESSION['comp_id'];
//                echo $Access_level;
               }elseif (isset($_SESSION['comp'])){
//                print_r($_SESSION['comp']);
                $Access_level = $_SESSION['comp'];
              }else{
                $Access_level = '';
             } $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,35,$Access_level,2);

//            echo $this->db->last_query();

//                        echo "<pre>";
//                        print_r($rights);
//                        exit();
//print_r($_SESSION);
            if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[4] == '1')){?>
            <a href="<?= site_url('Store/ItemStock')?>"><?php }else{?>
                <a href="#" id="AccessDenied"><?php }?>
                    <div class="panel-footer">
                        <span class="pull-left"> تفصیلات</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-6" style="display: none">
        <div class="panel panel-green">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-tasks fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">رپورٹ 2</div>
                        <div></div>
                    </div>
                </div>
            </div><!-- Add trail Balance's feature id then active -->
            <?php $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,15,$Access_level);
            if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[4] == '1')){?>
            <a href="#" ><?php }else{?>
                <a href="#" id="AccessDenied"><?php }?>
                    <!--            <a href="--><?php //echo site_url('TrialBalance/GetData')?><!--">-->
                    <div class="panel-footer">
                        <span class="pull-left"> تفصیلات</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-6" style="display: none">
        <div class="panel panel-yellow">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-shopping-cart fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">رپورٹ 3</div>
                        <div></div>
                    </div>
                </div>
            </div>
            <!-- Add AuditTrial's feature id then active -->
            <?php $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,17,$Access_level);
            if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[4] == '1')){?>
            <a href="#" ><?php }else{?>
                <a href="#" id="AccessDenied"><?php }?>
                    <!--            <a href="--><?php //echo site_url('BalanceSheet')?><!--">-->
                    <div class="panel-footer">
                        <span class="pull-left"> تفصیلات</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-6" style="display: none">
        <div class="panel panel-red">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-support fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">رپورٹ 4</div>
                        <div></div>
                    </div>
                </div>
            </div> <!-- Add AuditTrial's feature id then active -->

            <?php $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,14,$Access_level);
            if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[4] == '1')){?>
            <a href="#" ><?php }else{?>
                <a href="#" id="AccessDenied"><?php }?>
                    <!--            <a href="--><?php //echo site_url('AuditTrial')?><!--">-->
                    <div class="panel-footer">
                        <span class="pull-left"> تفصیلات</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
        </div>
    </div>
</div>
<div id="tamirat" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="gridModalLabel"></h4>
                <h4 style="margin-right: 70%;margin-top: -22px;" class="quan"></h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid items">
                    <div class="form-group">
<!--                        <label  class="radio-inline"><input type="radio" required name="category_type" value="1">تعمیراتی</label>-->
<!--                        <label class="radio-inline"><input type="radio" name="category_type" value="0">غیرتعمیراتی</label>-->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary built">منظور کریں</button>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
//this work is done by muhammad sufyan
$(document).ready(function () {

//      $('.built').click(function(){
//      localStorage.setItem('cat_type',$('input[name="category_type"]:checked').val());
//      $('#tamirat').modal("hide");
//      location.reload();
//    });
//    if(localStorage.getItem('cat_type') == null){
//    $('#tamirat').modal("show");
}
else{
//debugger;
  //  alert(localStorage.getItem('cat_type'));
}

   var com = $('nav ul li.dropdown .link').text().trim();
  //  $('body').click(function(e){
        if(com == 'کمپنی'){
//            alert('برائے مہربانی کمپنی کا انتخاب کریں');
            //e.preventDefault();
        }
   // });
});
</script>