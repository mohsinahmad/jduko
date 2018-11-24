<div class="col-lg-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            <input onclick="printData();" style="float: left" type="button" id="print" value="رپورٹ پرنٹ کریں"> <h4 id="r_name">اشیاء</h4>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <label class="tohide" style="float: left; margin-left: 3%;">تلاش کریں
                    <input class="tohide" type="text" id="myInputTextField" style="float: left; height: 1%;"></label>
                <table class="table table-bordered table-hover" id="dataTables-example">
                    <thead>
                    <tr>
                        <th style="width: 10%;text-align: center">نمبر شمار</th>
                        <th style="width: 10%;text-align: center">آئٹم کوڈ</th>
                        <th style="width: 20%;text-align: center"> آئٹم کا نام</th>
                        <th style="width: 15%;text-align: center"> پیمائش کی اکائی</th>
                        <th style="width: 13%;text-align: center"> کیٹیگری </th>
                        <th style="width: 15%;text-align: center"> ڈونیشن  کی قسم </th>
                        <th style="width: 15%;text-align: center"> ابتدائ مقدار </th>
                        <th style="width: 15%;text-align: center"> موجودہ مقدار </th>
                        <th style="text-align: center"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($items as $key => $item):?>
                        <tr class="odd gradeX">
                            <td class="old_serial" style="text-align: center"><?= ++$key; ?></td>
                            <td style="text-align: center"><?= $item->code; ?></td>
                            <td style="text-align: center"><?= $item->item_name; ?></td>
                            <td style="text-align: center"><?= $item->unit; ?></td>
                            <td style="text-align: center;width: 13%;line-height: 33px;"><?= $item->p_name.'-'.$item->s_name; ?></td>
                            <td style="text-align: center"><?= $item->Donation_Type; ?></td>
                            <td style="text-align: center"><?= $item->opening_quantity; ?></td>
                            <td style="text-align: center"><?= $item->current_quantity; ?></td>
                            <td style="width: 18%;text-align: center">
                                <div class="btn-group dropup">
                                    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Action <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="getid" href="<?= site_url('Store/Items/ItemById/').$item->code;?>" data-toggle="" data-target="" data-id="<?= $item->code;?>">تصیح کریں</a></li>
                                        <li><a class="delete_item" href="" data-id="<?= $item->a_id;?>">حذف کریں</a></li>
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