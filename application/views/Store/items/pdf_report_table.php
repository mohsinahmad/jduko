<table class="table table-bordered table-hover" id="item-edit">
    <thead>
    <tr>
        <th style="width: 20%;text-align: center"> آئٹم کا نام</th>
        <th style="width: 15%;text-align: center"> پیمائش کی اکائی</th>
        <th style="width: 15%;text-align: center">تعاوّن کی قسم </th>
        <th style="width: 15%;text-align: center"> ابتدائ مقدار </th>
        <th style="width: 15%;text-align: center"> موجودہ مقدار </th>
        <th style="text-align: center"></th>
    </tr>
    </thead>
    <tbody>
   <?php foreach ($result as $key =>$value){ ?>
    <tr>
        <td><?php echo $value->Name; ?><td>
        <td><?php echo $value->unit_of_measure?><td>
        <td><?php echo $value->donation_type?><td>
        <td><?php echo $value->opening_quantity?><td>
        <td><?php echo $value->current_quantity?><td>
    </tr>
    <?php } ?>
    </tbody>
</table>
