<?php $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,9);if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[0] == '1')){ ?>    <?php if (isset($mycode)){        foreach($result as $res):?>            <tr class="odd gradeX" >                <td><?= $res->LevelName?></td>                <td><?= $res->Parent.' - '.$res->AccountName?></td>                <td><?= $res->ID?></td>                <td><?= number_format($res->OpeningBalance)?></td>                <td><?= number_format($res->CurrentBalance)?></td>                <?php if($res->Active == 1):?>                    <td class="center"> <?= 'فعال'; ?></td>                <?php else: ?>                    <td class="center"> <?= 'غیر فعال'; ?></td>                <?php endif ?>                <td style="width: 135px;">                    <?php if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[3] == '1')){ ?>                        <button type="button" data-id="<?= $res->ChartOfAccountId; ?>"  class="btn btn-danger delete_data" style="font-size: 10px; ">حذف کریں                        </button>                    <?php } if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[2] == '1')){ ?>                        <button type="button"  data-id="<?= $res->ChartOfAccountId; ?>"  data-toggle="modal" data-target="#gridSystemModal" class="btn btn-success edit_data" style="font-size: 10px;background-color: #517751;border-color: #517751; ">تصیح کریں                        </button>                    <?php }?>                </td>            </tr>        <?php endforeach ?>    <?php }else{        foreach($result as $res):?>            <tr class="odd gradeX" >                <td><?= $res->LevelName?></td>                <td><?= $res->Parent.' - '.$res->AccountName?></td>                <td><?= $res->ID?></td>                <?php if($res->OpeningBalance < 0){ $new = $res->OpeningBalance * -1;?>                    <td>(<?= number_format($new)?>)</td>                <?php }else{ ?>                    <td><?= number_format($res->OpeningBalance)?></td>                <?php } ?>                <?php if($res->CurrentBalance < 0){ $newc = $res->CurrentBalance * -1;?>                    <td>(<?= number_format($newc)?>)</td>                <?php }else{ ?>                    <td><?= number_format($res->CurrentBalance)?></td>                <?php } ?>                <?php if($res->Active == 1):?>                    <td class="center"> <?= 'فعال'; ?></td>                <?php else: ?>                    <td class="center"> <?= 'غیر فعال'; ?></td>                <?php endif ?>                <td style="width: 135px;">                    <?php if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[3] == '1')){ ?>                        <button type="button" data-id="<?= $res->ChartOfAccountId; ?>"  class="btn btn-danger delete_data" style="font-size: 10px; ">حذف کریں                        </button>                    <?php } if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[2] == '1')){ ?>                        <button type="button"  data-id="<?= $res->ChartOfAccountId; ?>"  data-toggle="modal" data-target="#gridSystemModal" class="btn btn-success edit_data" style="font-size: 10px;background-color: #517751;border-color: #517751; ">تصیح کریں                        </button>                    <?php }?>                </td>            </tr>        <?php endforeach;    }}?>