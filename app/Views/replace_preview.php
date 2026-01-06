<?php $this->extend('/templates/template_main') ?>

    <?php $this->section('content') ?>

    <div id="main-wrapper" class="dashboard-page min-vh-100 d-flex flex-column">
             
        <div class="container-fluid menu-dashboard bg-body-secondary">
            <div class="row">
                <div class="col-12 pt-3 pb-2 ps-3 pe-3">

                    <h5>Preview Data (First 10 Rows)</h5>

                    <table border="1" cellpadding="5" class="table-sm table-bordered table-responsive table-cstm">
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

                    <form method="post" action="<?= base_url('replace/confirm') ?>">
                        <?= csrf_field() ?>
                        <button class="btn btn-green mt-3">CONFIRM REPLACE DATA</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php $this->endSection() ?>