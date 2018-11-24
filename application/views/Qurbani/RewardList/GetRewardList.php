<form role ="from" action="<?= site_url('Qurbani/RewardList/ViewRewardList')?>" method="POST" target="_blank" id="UserInput">
    <br><h1 style="text-align: center;">ادائیگی بابت انعامات حصص قربانی</h1><br><br>
    <div style="border:1px solid #eee;

            box-shadow:0 0 10px rgba(0, 0, 0, .15);

            max-width:600px;

            margin:auto;

            padding:20px;">
        <div style="" class="row">
            <div class="col-md-6" style="margin-top: 1%;">
                <div class="form-group">
                    <label class="control-label" for="inputSuccess">لوکیشن</label>
                    <select class="form-control" name="location" style="padding: 0px;margin: 0px">
                        <option disabled selected>منتخب کریں</option>
                        <?php foreach ($Locations as $value) {?>
                            <option value="<?= $value->Id?>"><?= $value->Name?></option>
                        <?php }?>
                    </select>
                </div>
            </div>
        </div>
            <br>
                <input type="submit" name="get" value="رپورٹ حا صل کریں" style="line-height: 210%;">
        </div>
</form>