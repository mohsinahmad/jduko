<form role ="from" action="<?= site_url('Qurbani/VendorLedger/ViewVendorLedger')?>" method="POST" target="_blank" id="UserInput">
    <br><h1 style="text-align: center;">وینڈرز لیجر</h1><br><br>
    <div style="border:1px solid #eee;

            box-shadow:0 0 10px rgba(0, 0, 0, .15);

            max-width:600px;

            margin:auto;

            padding:20px;">
        <div style="" class="row">
            <div class="col-md-6" style="margin-top: 1%;">
                <div class="form-group">
                    <label class="control-label" for="inputSuccess">وینڈر کا نام</label>
                    <select class="form-control" name="vendor" style="padding: 0px;margin: 0px">
                        <option disabled selected>منتخب کریں</option>
                        <?php foreach ($vendors as $slip) {?>
                            <option value="<?= $slip->Id?>"><?= $slip->Name?></option>
                        <?php }?>
                    </select>
                </div>
            </div>
        </div>
        <br>
        <input type="submit" name="get" value="رپورٹ حا صل کریں" style="line-height: 210%;">
    </div>
</form>