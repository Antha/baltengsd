<?php $this->extend('/templates/template_main') ?>

<?php $this->section('content') ?>

<div id="main-wrapper" class="dashboard-page min-vh-100 d-flex flex-column">
    <?= $this->include('/includes/include_top_navbar'); ?>              
    <div class="container-fluid menu-dashboard">
        <div class="row">
            <div class="col-12 pt-3 pb-2 ps-3 pe-3">

                <h5>Preview Data (First 10 Rows)</h5>

                <div class="table-responsive">
                    <table border="1" cellpadding="5" class="table-sm table-bordered table-cstm">
                        <tr>
                            <?php foreach ($header as $h): ?>
                                <th><?= esc($h) ?></th>
                            <?php endforeach ?>
                        </tr>

                        <?php foreach ($rows as $row): ?>
                            <tr>
                                <?php foreach ($row as $cell): ?>
                                    <td><?= esc($cell) ?></td>
                                <?php endforeach ?>
                            </tr>
                        <?php endforeach ?>
                    </table>
                </div>
                
                <div class="container-fluid p-0">
                    <div class="row">
                        <div class="col-12 text-end">
                            <form method="post" action="<?= base_url('replace/startimport') ?>" onsubmit="this.querySelector('button').disabled=true;">
                                <?= csrf_field() ?>
                                <button class="btn btn-green mt-3">CONFIRM REPLACE DATA</button>
                            </form>
                        </div>          
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>

<?php $this->endSection() ?>