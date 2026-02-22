<?php $this->extend('/templates/template_main') ?>

<?php $this->section('content') ?>
    <div id="main-wrapper" class="dashboard-page min-vh-100 d-flex flex-column">
        <?= $this->include('/includes/include_top_navbar'); ?>      
        <div class="container-fluid menu-dashboard bg-body-secondary">
            <div class="row">
                <div class="col-12 pt-3 pb-2 ps-3 pe-3">
                    <div class="container-fluid rounded main-bg pt-3">
                        <div class="row mt-1">
                            <?= $this->include('/includes/include_breadcrumb'); ?>
                            <h3 class="mb-3">ST DIGIPOS SA</h3>

                            <div id="table_st_sa_detail" class="col-12 mb-3">
                                <div class="table-responsive mytable">
                                    <table class="table table-sm table-bordered table-hover table-responsive table-cstm">
                                         <thead>
                                            <tr class="text-center align-middle">
                                                <th rowspan="3" class="deep_blue align-middle" scope="col">DIGIPOS</th>
                                                <th rowspan="3" class="deep_blue align-middle" scope="col" style="min-width: auto;">OUTLET</th>
                                                <th rowspan="3" class="deep_blue align-middle" scope="col">HARI PJP</th>
                                                <th rowspan="3" class="deep_blue align-middle" scope="col" style="min-width: auto;">SALES FORCE</th>
                                                <th rowspan="3" class="deep_blue align-middle" scope="col">OR</th>
                                                <th colspan="13" class="deep_blue" scope="col">MONITORING ST DIGIPOS SMART AQUITITION <?php echo $parse_type; ?></th>
                                                <th colspan="8" class="deep_blue" scope="col">PJP : <?php echo $hari_pjp; ?></th>
                                            </tr>
                                            <tr class="text-center align-middle">
                                                <th colspan="4" class="deep_blue" scope="col">HISTORY FM-1</th>
                                                <th colspan="3" class="deep_blue" scope="col">TARGET</th>
                                                <th colspan="6" class="deep_blue" scope="col">ACH S/D TANGGAL <?php echo $tgl_update; ?></th>
                                                <th colspan="2" class="deep_blue" scope="col">RUN RATE</th>
                                                <th colspan="2" class="deep_blue" scope="col">GAP EOM</th>
                                            </tr>
                                            <tr class="text-center align-middle">
                                                
                                                <th class="deep_blue" scope="col">OA</th>
                                                <th class="deep_blue" scope="col">OA%</th>
                                                <th class="deep_blue" scope="col">QTY</th>
                                                <th class="deep_blue" scope="col">REV</th>
                                               
                                                <th class="deep_blue" scope="col">OA</th>
                                                <th class="deep_blue" scope="col">QTY</th>
                                                <th class="deep_blue" scope="col">REV</th>

                                                <th class="deep_blue" scope="col">OA</th>
                                                <th class="deep_blue" scope="col">OA%</th>
                                                <th class="deep_blue" scope="col">QTY</th>
                                                <th class="deep_blue" scope="col">%ACH</th>
                                                <th class="deep_blue" scope="col">REV</th>
                                                <th class="deep_blue" scope="col">%ACH</th>

                                                <th class="deep_blue" scope="col">QTY</th>
                                                <th class="deep_blue" scope="col">REV</th>

                                                <th class="deep_blue" scope="col">QTY</th>
                                                <th class="deep_blue" scope="col">REV</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if($cek_result == 'result available'){ ?>
                                                <?php foreach($result_trx_summary as $rows){ if($rows['id_digipos'] == 'TOTAL'){?>
                                                    <tr class="bg-total">
                                                        <td colspan="4" class="text-center"><?php echo $rows['id_digipos']; ?></td>
                                                        <td class="text-center"><?php echo nf0($rows['or_trx']); ?></td>
                                                        <td class="text-center"><?php echo nf0($rows['oa_trx_m1']); ?></td>
                                                        <td class="text-end"><?php echo $rows['percent_oa_trx_m1']; ?>%</td>
                                                        <td class="text-end"><?php echo nf0($rows['trx_m1']); ?></td>
                                                        <td class="text-end"><?php echo nf0($rows['rev_m1']); ?></td>
                                                        <td class="text-end"><?php echo nf0($rows['oa_trx_target']); ?></td>
                                                        <td class="text-end"><?php echo nf0($rows['target_trx']); ?></td>
                                                        <td class="text-end"><?php echo nf0($rows['target_rev']); ?></td>
                                                        <td class="text-center"><?php echo $rows['oa_trx_mtd']; ?></td>
                                                        <td class="text-end"><?php echo $rows['percent_oa_trx_mtd']; ?>%</td>
                                                        <td class="text-end"><?php echo nf0($rows['trx_mtd']); ?></td>
                                                        <td class="text-end"><?php echo $rows['ach_trx']; ?>%</td>
                                                        <td class="text-end"><?php echo nf0($rows['rev_mtd']); ?></td>
                                                        <td class="text-end"><?php echo $rows['ach_rev']; ?>%</td>
                                                        <td class="text-end"><?php echo $rows['rr_trx']; ?>%</td>
                                                        <td class="text-end"><?php echo $rows['rr_rev']; ?>%</td>
                                                        <td <?php if($rows['gap_trx'] < 0){ ?>class="text-end text-danger"<?php }else{ ?>class="text-end"<?php } ?>><?php echo nf0($rows['gap_trx']); ?></td>
                                                        <td <?php if($rows['gap_rev'] < 0){ ?>class="text-end text-danger"<?php }else{ ?>class="text-end"<?php } ?>><?php echo nf0($rows['gap_rev']); ?></td>
                                                    </tr>
                                                <?php }} ?>
                                                <?php foreach($result_trx_summary as $rows){ if($rows['id_digipos'] != 'TOTAL'){?>
                                                    <tr>
                                                        <td class="text-start"><?php echo $rows['id_digipos']; ?></td>
                                                        <td><?php echo $rows['outlet']; ?></td>
                                                        <td class="text-center"><?php echo $rows['hari_pjp']; ?></td>
                                                        <td ><?php echo $rows['pic']; ?></td>
                                                        <td class="text-center"><?php echo nf0($rows['or_trx']); ?></td>
                                                        <td class="text-center"><?php echo nf0($rows['oa_trx_m1']); ?></td>
                                                        <td class="text-end"><?php echo $rows['percent_oa_trx_m1']; ?>%</td>
                                                        <td class="text-end"><?php echo nf0($rows['trx_m1']); ?></td>
                                                        <td class="text-end"><?php echo nf0($rows['rev_m1']); ?></td>
                                                        <td class="text-end"><?php echo nf0($rows['oa_trx_target']); ?></td>
                                                        <td class="text-end"><?php echo nf0($rows['target_trx']); ?></td>
                                                        <td class="text-end"><?php echo nf0($rows['target_rev']); ?></td>
                                                        <td class="text-center"><?php echo $rows['oa_trx_mtd']; ?></td>
                                                        <td class="text-end"><?php echo $rows['percent_oa_trx_mtd']; ?>%</td>
                                                        <td class="text-end"><?php echo nf0($rows['trx_mtd']); ?></td>
                                                        <td class="text-end"><?php echo $rows['ach_trx']; ?>%</td>
                                                        <td class="text-end"><?php echo nf0($rows['rev_mtd']); ?></td>
                                                        <td class="text-end"><?php echo $rows['ach_rev']; ?>%</td>
                                                        <td class="text-end"><?php echo $rows['rr_trx']; ?>%</td>
                                                        <td class="text-end"><?php echo $rows['rr_rev']; ?>%</td>
                                                        <td <?php if($rows['gap_trx'] < 0){ ?>class="text-end text-danger"<?php }else{ ?>class="text-end"<?php } ?>><?php echo nf0($rows['gap_trx']); ?></td>
                                                        <td <?php if($rows['gap_rev'] < 0){ ?>class="text-end text-danger"<?php }else{ ?>class="text-end"<?php } ?>><?php echo nf0($rows['gap_rev']); ?></td>
                                                    </tr>
                                                <?php }} ?>
                                            <?php }else{ ?>
                                                <tr>
                                                    <td colspan="21" class="text-center">Tidak Ada Data</td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                 </div>
                            </div>
                        </div>
                    </div>  
                </div>
            </div>
            <?= $this->include('/includes/include_footer'); ?>
        </div>      
    </div>

<?php $this->endSection() ?>
