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
                            <h3 class="mb-3">REPLACE DATA</h3>
                            <div class="container-fluid mt-5">
                                <div class="row justify-content-center">
                                    <div class="col-8 col-md-8 col-lg-8 col-xl-8 border rounded px-3 py-4 filter_group_top">
                                        <form method="post" action="<?= base_url('replace/preview') ?>" enctype="multipart/form-data" onsubmit="return validateCSV()">
                                            <?= csrf_field() ?>

                                            <select name="table_name" required class="form-select mb-2">
                                                <option value="db_outlet">db_outlet</option>
                                                <option value="db_profile_outlet_m">db_profile_outlet_m</option>
                                                <option value="db_profile_outlet_m1">db_profile_outlet_m1</option>
                                                <option value="db_sales_plan">db_sales_plan</option>
                                                <option value="db_st_digipos">db_st_digipos</option>
                                                <option value="db_st_nota">db_st_nota</option>
                                            </select>

                                            <input type="file" name="csv_file" id="csv_file" class="form-control mb-2" required>

                                            <div class="d-inline-block col-12 text-end">
                                                <button class="btn btn-green">Upload & Preview</button>
                                            </div>
                                        </form>
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
<?php if (session()->getFlashdata('swal_success')): ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Success',
    text: '<?= esc(session()->getFlashdata('swal_success')) ?>',
    timer: 2000,
    timerProgressBar: true,
    showConfirmButton: false,
    toast: true,
    position: 'top-end',
    showClass: {
        popup: 'swal2-show'
    },
    hideClass: {
        popup: 'swal2-hide'
    }
});
</script>
<?php endif; ?>

<script>
function validateCSV() {
    const file = document.getElementById('csv_file').value;
    if (!file.endsWith('.csv')) {
        alert('Only CSV files allowed');
        return false;
    }
    return true;
}
</script>
<?php $this->endSection() ?>
