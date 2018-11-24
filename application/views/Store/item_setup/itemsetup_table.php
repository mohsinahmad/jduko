<div class="col-lg-12">
    <div class="panel panel-default">
        <div class="panel-heading">
<!--                <input style="float: left;margin-top:-50px" data-toggle="modal" data-target="#myModal" class="btn btn-primary" type="button" id="report" value="رپورت حاصل کریں">-->
            <input onclick="printData();" class="btn btn-primary" style="float: left" type="button" id="print" value="رپورٹ پرنٹ کریں"> <h3 id="r_name">اشیاء</h3>

        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <label id="search-label" style="float: left; margin-left: 3%;">تلاش کریں
                    <input type="text" id="myInputTextField" style="float: left; height: 1%;"></label>
                <table class="table table-bordered table-hover" id="dataTables-example">
                    <thead>
                    <tr>
                        <th style="width: 10%;text-align: center">نمبر شمار</th>
                        <th style="width: 10%;text-align: center">آئٹم کوڈ</th>
                        <th style="width: 20%;text-align: center"> آئٹم کا نام</th>
                        <th style="width: 15%;text-align: center"> پیمائش کی اکائی</th>
                        <th style="width: 13%;text-align: center"> کیٹیگری </th>
                        <th style="width: 15%;text-align: center">تعاوّن کی قسم </th>
                        <th style="width: 15%;text-align: center"> ابتدائ مقدار </th>
                        <th style="width: 15%;text-align: center"> موجودہ مقدار </th>
                        <th style="text-align: center"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($items as $key => $item):?>
                        <tr class="odd gradeX" unit="<?php echo $item->unit;?>" donation="<?php echo $item->donation_id;?>">
                            <td style="text-align: center"><?= ++$key; ?></td>
                            <td style="text-align: center"><?= $item->code; ?></td>
                            <td style="text-align: center"><?= $item->item_name; ?></td>
                            <td style="text-align: center"><?= $item->unit_of_measure_name; ?></td>
                            <td style="text-align: center;width: 13%;line-height: 33px;"><?= $item->p_name.'-'.$item->s_name; ?></td>
                            <td style="text-align: center"><?= $item->Donation_Type; ?></td>
                            <td style="text-align: center"><?= $item->total_opening_quantity; ?></td>
                            <td style="text-align: center"><?= $item->total_current_quantity; ?></td>
                            <td style="width: 18%;text-align: center">
                                <div class="btn-group dropup">
                                    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Action <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a style="cursor: pointer" class="getid" unit="<?php echo $item->unit;?>" donation="<?php echo $item->donation_id;?>" data-toggle="modal" data-target="#item-modal" data-id="<?= $item->item_setup_id;?>">تصیح کریں</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog" style="width: 40%;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form action="<?php echo base_url()?>Store/Items/generate_report" method="post">
                    <label>کیٹگری منتخب کریں</label>
                    <select name="category" id="Category">
                    </select>
                    <br><br>
                    <button type="submit" class="btn btn-primary" >حاصل کریں</button>
                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<script>
   
</script>