<?php $this->extend('/templates/template_main') ?>

<?php $this->section('content') ?>

    <div id="main-wrapper" class="dashboard-page min-vh-100 d-flex flex-column">
        <?= $this->include('/includes/include_top_navbar'); ?>      
        <div class="container-fluid menu-dashboard bg-body-secondary">
            <div class="row">
                <div class="col-12 pt-3 pb-2 ps-3 pe-3">
                    <div class="container-fluid rounded main-bg pt-3">
                        <div class="row mt-1">
                            <div class="back-btn mb-3">
                                <a href="/home">
                                    <i class="fa-solid fa-circle-left me-1" style="font-size: 20px;"></i>
                                </a>
                                <h5 class="d-inline-block">HOME</h5>
                            </div>
                            <h3 class="mb-3">OMSET TRX</h3>
                            <div id="table_ommset_trx_sts" class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered table-hover table-responsive table-cstm">
                                        <thead>
                                            <tr class="text-center align-middle">
                                                <th rowspan="3" class="deep_blue align-middle" scope="col">TAP</th>
                                                <th rowspan="3" class="deep_blue align-middle" scope="col">CHANNEL</th>
                                                <th rowspan="3" class="deep_blue align-middle" scope="col">SALES FORCE</th>
                                                <th rowspan="3" class="deep_blue align-middle" scope="col">OR</th>
                                                <th colspan="12" class="deep_blue" scope="col">MONITORING ST SA DIGIPOS</th>
                                                <th colspan="4" class="deep_blue" scope="col">PJP : RABU</th>
                                                <th colspan="12" class="deep_green" scope="col">MONITORING VF DIGIPOS</th>
                                                <th colspan="4" class="deep_green" scope="col">PJP</th>
                                            </tr>
                                            <tr class="text-center align-middle">
                                                <th colspan="4" class="deep_blue" scope="col">HISTORY FM-1</th>
                                                <th colspan="2" class="deep_blue" scope="col">TARGET</th>
                                                <th colspan="6" class="deep_blue" scope="col">ACH S/D TANGGAL 10 DESEMBER</th>
                                                <th colspan="2" class="deep_blue" scope="col">RUN RATE</th>
                                                <th colspan="2" class="deep_blue" scope="col">GAP EOM</th>
                                                <th colspan="4" class="deep_green" scope="col">HISTORY FM-1</th>
                                                <th colspan="2" class="deep_blue" scope="col">TARGET</th>
                                                <th colspan="6" class="deep_green" scope="col">ACH S/D TANGGAL 10 DESEMBER</th>
                                                <th colspan="2" class="deep_green" scope="col">RUN RATE</th>
                                                <th colspan="2" class="deep_green" scope="col">GAP EOM</th>
                                            </tr>
                                            <tr class="text-center align-middle">
                                               
                                                <th class="deep_blue" scope="col">OA</th>
                                                <th class="deep_blue" scope="col">OA%</th>
                                                <th class="deep_blue" scope="col">QTY</th>
                                                <th class="deep_blue" scope="col">REV</th>
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

                                                <th class="deep_green" scope="col">OA</th>
                                                <th class="deep_green" scope="col">OA%</th>
                                                <th class="deep_green" scope="col">QTY</th>
                                                <th class="deep_green" scope="col">REV</th>

                                                <th class="deep_blue" scope="col">QTY</th>
                                                <th class="deep_blue" scope="col">REV</th>

                                                <th class="deep_green" scope="col">OA</th>
                                                <th class="deep_green" scope="col">OA%</th>
                                                <th class="deep_green" scope="col">QTY</th>
                                                <th class="deep_blue" scope="col">%ACH</th>
                                                <th class="deep_green" scope="col">REV</th>
                                                <th class="deep_blue" scope="col">%ACH</th>

                                                <th class="deep_green" scope="col">QTY</th>
                                                <th class="deep_green" scope="col">REV</th>
                                                <th class="deep_green" scope="col">QTY</th>
                                                <th class="deep_green" scope="col">REV</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                            <th scope="row">1</th>
                                            <td>Mark</td>
                                            <td>Otto</td>
                                            <td>@mdo</td>
                                            </tr>
                                            <tr>
                                            <th scope="row">2</th>
                                            <td>Jacob</td>
                                            <td>Thornton</td>
                                            <td>@fat</td>
                                            </tr>
                                            <tr>
                                            <th scope="row">3</th>
                                            <td>Larry</td>
                                            <td>the Bird</td>
                                            <td>@twitter</td>
                                            </tr>
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
