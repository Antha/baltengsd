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
                            <h3 class="mb-3">ST DIGIPOS VF BYU</h3>
                            <div class="filter_group_top">
                                <form id="filterFormOmsetTrxTop" method="post" action="<?php echo esc(base_url('/st_digipos_vf_byu_dashboard')); ?>" enctype="multipart/form-data">
                                    <?= csrf_field() ?>
                                    <div class="row no-gutters">
                                        <div class="form-group col-md-6 col-12">
                                            <div class="input-group dropdown_input">

                                                <div class="form-group col-3 mb-3 pe-2" id="wrap_filter_tap">
                                                    <span class="d-inline-block mb-1 text-secondary">TAP</span>
                                                    <select name='filter_tap' id='filter_tap' class="select_filter px-2 py-2" title="TAP" style="width:100%;">
                                                        <?php foreach ($tap_list as $tap): ?>
                                                            <option value="<?= esc($tap['tap']) ?>" <?php if(esc($tap['tap']) == esc($selected_tap)){ ?>selected<?php } ?>>
                                                                <?= esc($tap['tap']) ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                
                                                <div class="form-group col-2 mb-3 pe-2" id="wrap_filter_monitoring">
                                                    <span class="d-inline-block mb-1 text-secondary">MONITORING</span>
                                                    <select name='filter_monitoring' id='filter_monitoring' class="select_filter px-2 py-2" title="MONITORING" style="width:100%;">
                                                        <?php foreach ($monitoring_list as $monitoring): ?>
                                                            <option value="<?= esc($monitoring) ?>" <?php if(esc($monitoring) == esc($selected_pt)){ ?>selected<?php } ?>>
                                                                <?= esc($monitoring) ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>

                                                 <div class="form-group col-2 mb-3 pe-2" id="wrap_filter_hari_pjp">
                                                    <span class="d-inline-block mb-1 text-secondary">HARI PJP</span>
                                                    <select name='filter_hari_pjp' id='filter_hari_pjp' class="select_filter px-2 py-2" title="HARI PJP" style="width:100%;">
                                                        <option value="" selected disabled>HARI PJP</option>
                                                        <?php foreach ($hari_pjp_list as $hari): ?>
                                                            <option value="<?= esc($hari) ?>" <?php if(esc($hari) == esc($selected_hp)){ ?>selected<?php } ?>>
                                                                <?= esc($hari) ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>

                                                <div class="form-group col-3 mb-3 pe-2" id="wrap_filter_pic">
                                                    <span class="d-inline-block mb-1 text-secondary">PIC</span>
                                                    <select name='filter_pic' id='filter_pic' class="select_filter px-2 py-2" title="PIC" style="width:100%;">
                                                        <?php foreach ($pic_list as $pic): ?>
                                                            <option value="<?= esc($pic['pic']) ?>" <?php if(esc($pic['pic']) == esc($selected_pic)){ ?>selected<?php } ?>>
                                                                <?= esc($pic['pic']) ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-2 mb-3" id="wrap_btn_submit">
                                                    <span> </span>
                                                    <button name='btn_submit' type="submit" id="btn_submit" class="btn-green rounded float-start px-3 py-2 border-0">
                                                        FILTER
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <p id="errorMessage" class="text-danger"></p> 
                                        <div style="clear: both;"></div>
                                    </div>
                                </form>
                            </div>
                            <div class="btn-capture-dl-wrapper container-fluid mb-2">
                                <div class="row justify-content-end">
                                    <div class= "col-sm-1 col-3 text-end pe-lg-0 pe-1">
                                        <button id="dlImgTop" class="submit_btn rounded p-2 btn-green border-0">CAPTURE</button>
                                    </div>
                                    <div class= "col-sm-1 col-3 text-end ps-0">
                                        <button id="exportCsvTop" class="submit_btn rounded p-2 btn-green border-0">DOWNLOAD</button>
                                    </div>
                                </div>
                            </div>
                            <div id="table_st_digipos_vf_summary" class="col-12 mb-3">
                                <div class="table-responsive">
                                    <table id="dataTableTop" class="table table-sm table-bordered table-hover table-cstm">
                                        <thead>
                                            <tr class="text-center align-middle deep_blue">
                                                <th rowspan="3" class="deep_blue align-middle" scope="col" style="min-width: 150px;">TAP</th>
                                                <th rowspan="3" class="deep_blue align-middle" scope="col" style="min-width: 150px;">CHANNEL</th>
                                                <th rowspan="3" class="deep_blue align-middle" scope="col" style="min-width: 250px;">SALES FORCE</th>
                                                <th rowspan="3" class="deep_blue align-middle" scope="col">OR</th>
                                                <th colspan="13" class="deep_blue" scope="col">MONITORING ST DIGIPOS VF BYU <?php echo $parse_type; ?></th>
                                                <th colspan="8" class="deep_blue" scope="col">PJP : <?php echo $hari_pjp; ?></th>
                                            </tr>
                                            <tr class="text-center align-middle deep_blue">
                                                <th colspan="4" class="deep_blue" scope="col">HISTORY FM-1</th>
                                                <th colspan="3" class="deep_blue" scope="col">TARGET</th>
                                                <th colspan="6" class="deep_blue" scope="col">ACH S/D TANGGAL <?php echo $tgl_update; ?></th>
                                                <th colspan="2" class="deep_blue" scope="col">RUN RATE</th>
                                                <th colspan="2" class="deep_blue" scope="col">GAP EOM</th>
                                            </tr>
                                            <tr class="text-center align-middle deep_blue">
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
                                            <?php foreach($result_trx_summary as $rows){if($rows['tap'] == 'TOTAL'){ ?>
                                                <tr class="bg-total">
                                                    <td colspan="3" class="text-center"><?php echo $rows['tap']; ?></td>
                                                    <td class="text-center"><?php echo $rows['or_trx']; ?></td>
                                                    <td class="text-center"><?php echo $rows['oa_trx_m1']; ?></td>
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
                                             <?php foreach($result_trx_summary as $rows){if($rows['tap'] != 'TOTAL'){ ?>
                                                <tr <?php if($rows['channel'] == 'ALL' && $rows['pic'] == 'ALL'){ ?>class="table-secondary"<?php } ?>>
                                                    <?php if($rows['channel'] == 'ALL' && $rows['pic'] == 'ALL'){ ?>
                                                        <td colspan="3" class="text-center"><?php echo $rows['tap']; ?></td> 
                                                    <?php }else{ ?>
                                                        <td><?php echo $rows['tap']; ?></td>
                                                        <td><?php echo $rows['channel']; ?></td>
                                                        <td ><?php echo $rows['pic']; ?></td>
                                                    <?php } ?>
                                                    <td class="text-center"><?php echo $rows['or_trx']; ?></td>
                                                    <td class="text-center"><?php echo $rows['oa_trx_m1']; ?></td>
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
                                        </tbody>
                                    </table>
                                 </div>
                            </div>

                            <div class="btn-capture-dl-wrapper container-fluid mt-4 mb-2">
                                <div class="row justify-content-end">
                                    <div class= "col-sm-1 col-3 text-end pe-lg-0 pe-1">
                                        <button id="dlImgBot" class="submit_btn rounded p-2 btn-green border-0">CAPTURE</button>
                                    </div>
                                    <div class= "col-sm-1 col-3 text-end ps-0">
                                        <button id="exportCsvBot" class="submit_btn rounded p-2 btn-green border-0">DOWNLOAD</button>
                                    </div>
                                </div>
                            </div>
                            <div id="table_st_digipos_vf_detail" class="col-12 mb-3">
                                <div class="table-responsive">
                                    <table id="dataTableBot" class="table table-sm table-bordered table-hover table-responsive table-cstm">
                                         <thead>
                                            <tr class="text-center align-middle deep_blue">
                                                <th rowspan="3" class="deep_blue align-middle" scope="col">DIGIPOS</th>
                                                <th rowspan="3" class="deep_blue align-middle" scope="col" style="min-width: 180px;">OUTLET</th>
                                                <th rowspan="3" class="deep_blue align-middle" scope="col">HARI PJP</th>
                                                <th rowspan="3" class="deep_blue align-middle" scope="col" style="min-width: 250px;">SALES FORCE</th>
                                                <th rowspan="3" class="deep_blue align-middle" scope="col">OR</th>
                                                <th colspan="13" class="deep_blue" scope="col">MONITORING ST DIGIPOS VF BYU <?php echo $parse_type; ?></th>
                                                <th colspan="8" class="deep_blue" scope="col">PJP : <?php echo $hari_pjp; ?></th>
                                            </tr>
                                            <tr class="text-center align-middle deep_blue">
                                                <th colspan="4" class="deep_blue" scope="col">HISTORY FM-1</th>
                                                <th colspan="3" class="deep_blue" scope="col">TARGET</th>
                                                <th colspan="6" class="deep_blue" scope="col">ACH S/D TANGGAL <?php echo $tgl_update; ?></th>
                                                <th colspan="2" class="deep_blue" scope="col">RUN RATE</th>
                                                <th colspan="2" class="deep_blue" scope="col">GAP EOM</th>
                                            </tr>
                                            <tr class="text-center align-middle deep_blue">
                                                
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
                                            <?php foreach($result_trx_detail as $rows){if($rows['id_digipos'] == 'TOTAL'){ ?>
                                                <tr class="bg-total">
                                                    <td colspan="4" class="text-center"><?php echo $rows['id_digipos']; ?></td>
                                                    <td class="text-center"><?php echo $rows['or_trx']; ?></td>
                                                    <td class="text-center"><?php echo $rows['oa_trx_m1']; ?></td>
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
                                            <?php foreach($result_trx_detail as $rows){if($rows['id_digipos'] != 'TOTAL'){ ?>
                                                <tr>
                                                    <td class="text-start"><?php echo $rows['id_digipos']; ?></td>
                                                    <td><?php echo $rows['outlet']; ?></td>
                                                    <td class="text-center"><?php echo $rows['hari_pjp']; ?></td>
                                                    <td ><?php echo $rows['pic']; ?></td>
                                                    <td class="text-center"><?php echo $rows['or_trx']; ?></td>
                                                    <td class="text-center"><?php echo $rows['oa_trx_m1']; ?></td>
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
<script type="text/javascript" src="<?php echo base_url('/script/jszip.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('/script/FileSaver.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('/script/xlsx.full.min.js') ?>"></script>
<script>
     $(document).ready(function() {
        $('#filter_tap').on('change', function () {
            let tap = $(this).val();

            $('#filter_pic').html('<option value="">Loading...</option>');

            $.ajax({
                url: "<?= base_url('/st_digipos_vf_byu_dashboard/getPicByTap') ?>",
                type: "POST",
                data: {
                    tap: tap,
                    <?= csrf_token() ?>: "<?= csrf_hash() ?>"
                },
                dataType: "json",
                success: function (res) {
                    let html = '';
                    $.each(res, function (i, v) {
                        html += `<option value="${v.pic}">${v.pic}</option>`;
                    });
                    $('#filter_pic').html(html);
                }
            });
        });

        $('#exportCsvTop').click(function () {

            var table = document.getElementById("dataTableTop");

            // convert table ke workbook
            var workbook = XLSX.utils.table_to_book(table, {
                sheet: "Summary",
                raw: true
            });

            // nama file
            const dateformat = new Date().toISOString().replace(/[-:.TZ]/g, '').slice(0,14);
            const filename = `st_digipos_vf_summary_${dateformat}.xlsx`;

            // download
            XLSX.writeFile(workbook, filename);

        });

        $('#exportCsvBot').click(function () {

            var table = document.getElementById("dataTableBot");

            // convert table ke workbook
            var workbook = XLSX.utils.table_to_book(table, {
                sheet: "Details",
                raw: true
            });

            // nama file
            const dateformat = new Date().toISOString().replace(/[-:.TZ]/g, '').slice(0,14);
            const filename = `st_digipos_vf_detail_${dateformat}.xlsx`;

            // download
            XLSX.writeFile(workbook, filename);

        });


        $('#dlImgTop').on('click', function () {

            const wrapper = document.querySelector('#table_st_digipos_vf_summary .table-responsive');
            const table   = document.querySelector('#dataTableTop');

            // SIMPAN style asli
            const oldOverflowX = wrapper.style.overflowX;
            const oldOverflowY = wrapper.style.overflowY;
            const oldMaxWidth  = wrapper.style.maxWidth;

            // BUKA scroll sementara
            wrapper.style.overflowX = 'visible';
            wrapper.style.overflowY = 'visible';
            wrapper.style.maxWidth  = 'none';

            html2canvas(table, {
                scale: window.devicePixelRatio * 2,
                useCORS: true,
                backgroundColor: '#ffffff'
            }).then(canvas => {

                // KEMBALIKAN style semula
                wrapper.style.overflowX = oldOverflowX;
                wrapper.style.overflowY = oldOverflowY;
                wrapper.style.maxWidth  = oldMaxWidth;

                // DOWNLOAD IMAGE
                const link = document.createElement('a');
                link.href = canvas.toDataURL('image/png');
                link.download = `table_st_digipos_vf_byu_summary_full_${Date.now()}.png`;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

            }).catch(err => {
                console.error('Capture error:', err);
            });

        });

        $('#dlImgBot').on('click', function () {

            const wrapper = document.querySelector('#table_st_digipos_vf_detail .table-responsive');
            const table   = document.querySelector('#dataTableBot');

            // SIMPAN style asli
            const oldOverflowX = wrapper.style.overflowX;
            const oldOverflowY = wrapper.style.overflowY;
            const oldMaxWidth  = wrapper.style.maxWidth;

            // BUKA scroll sementara
            wrapper.style.overflowX = 'visible';
            wrapper.style.overflowY = 'visible';
            wrapper.style.maxWidth  = 'none';

            html2canvas(table, {
                scale: window.devicePixelRatio * 2,
                useCORS: true,
                backgroundColor: '#ffffff'
            }).then(canvas => {

                // KEMBALIKAN style semula
                wrapper.style.overflowX = oldOverflowX;
                wrapper.style.overflowY = oldOverflowY;
                wrapper.style.maxWidth  = oldMaxWidth;

                // DOWNLOAD IMAGE
                const link = document.createElement('a');
                link.href = canvas.toDataURL('image/png');
                link.download = `table_st_vf_byu_detail_full_${Date.now()}.png`;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

            }).catch(err => {
                console.error('Capture error:', err);
            });

        });
     });
</script>
<?php $this->endSection() ?>