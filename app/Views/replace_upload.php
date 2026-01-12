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
                            <h3 class="mb-3">REPLACE DATA</h3>
                            <div class="container-fluid mt-2 mt-sm-4">
                                <div class="row justify-content-center">
                                    <div class="col-8 col-md-8 col-lg-8 col-xl-8 border rounded px-3 py-4 filter_group_top bg-body-secondary">
                                        <!-- FORM UPLOAD & PREVIEW -->
                                        <form method="post"
                                            action="<?= base_url('replace/preview') ?>"
                                            enctype="multipart/form-data"
                                            onsubmit="return validateCSV()"
                                            id="formUpload">

                                            <?= csrf_field() ?>

                                            <select name="table_name" id="table_name_select" required class="form-select mb-2">
                                                <option value="db_outlet">db_outlet</option>
                                                <option value="db_profile_outlet_m">db_profile_outlet_m</option>
                                                <option value="db_profile_outlet_m1">db_profile_outlet_m1</option>
                                                <option value="db_sales_plan">db_sales_plan</option>
                                                <option value="db_st_digipos">db_st_digipos</option>
                                                <option value="db_st_nota">db_st_nota</option>
                                            </select>

                                            <input type="file"
                                                name="csv_file"
                                                id="csv_file"
                                                class="form-control mb-2"
                                                required>

                                            <div class="col-12 text-end d-flex justify-content-end gap-2">

                                                <button type="submit" class="btn btn-green">
                                                    Upload & Preview
                                                </button>

                                            </div>
                                        </form>

                                    </div>
                                    <div class="col-8 col-md-8 col-lg-8 col-xl-8 border rounded px-3 py-4 mt-4 mb-4 bg-body-secondary">
                                        <div class="row">
                                            <div class="col-12 col-sm-9 pt-2">
                                                <h6 class="fw-light fst-italic text-info-emphasis">Download Contoh Kolom dan Data yang Sesuai Dengan Database</h6>
                                            </div>
                                            <div class="offset-6 col-6 offset-sm-0 col-sm-3 text-end">
                                                <!-- FORM DOWNLOAD SAMPLE (TERPISAH) -->
                                                <form method="post"
                                                    action="<?= base_url('replace/download/sample') ?>"
                                                    id="formDownloadSample">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="table_name" id="table_name_download">
                                                    <button type="submit" class="btn btn-green">
                                                        Download Sample
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>  
                </div>
            </div>
            <?= $this->include('/includes/include_footer'); ?>
        </div>      
    </div>


<script>
function validateCSV() {
    const file = document.getElementById('csv_file').value;
    if (!file.endsWith('.csv')) {
        alert('Only CSV files allowed');
        return false;
    }
    return true;
}

const tableSelect = document.querySelector('select[name="table_name"]');
const tableHidden = document.getElementById('table_name_download');

// set nilai awal
tableHidden.value = tableSelect.value;

// update saat select berubah
tableSelect.addEventListener('change', function () {
    tableHidden.value = this.value;
});
</script>
<?php $this->endSection() ?>
