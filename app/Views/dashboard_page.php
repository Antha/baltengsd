<?php $this->extend('/templates/template_main') ?>

<?php $this->section('content') ?>

    <div id="main-wrapper" class="dashboard-page min-vh-100 d-flex flex-column">
        <?= $this->include('/includes/include_top_navbar'); ?>
        <div class="container-fluid menu-dashboard bg-body-secondary">
            <div class="row">
                <div class="col-12 pt-3 pb-2 ps-3 pe-3">
                    <div class="container-fluid rounded main-bg pt-5">
                        <div class="row justify-content-center mt-3">
                            <div class="col-2 text-center">
                                <a href="omset_trx" class="d-inline-block border rounded w-100 menu">
                                    <div class="p-3">
                                        <div class="d-inline-block w-100">
                                            <i class="fa-solid fa-magnifying-glass-chart fs-1"></i>
                                        </div>
                                        <span class="mt-3 d-inline-block">OMZET TRX</span>
                                    </div>
                                </a>
                            </div>
                            <div class="col-2 text-center">
                                <a href="st_nota_sa" class="d-inline-block border rounded w-100 menu">
                                    <div class="p-3">
                                        <div class="d-inline-block w-100">
                                            <i class="fa-regular fa-note-sticky fs-1"></i>
                                        </div>
                                    
                                        <span class="mt-3 d-inline-block">ST NOTA SA</span>
                                    </div>
                                </a>
                            </div>
                            <div class="col-2 text-center">
                                <a href="st_nota_vf_byu" class="d-inline-block border rounded w-100 menu">
                                    <div class="p-3">
                                        <div class="d-inline-block w-100">
                                            <i class="fa-regular fa-note-sticky fs-1"></i>
                                        </div>
                                    
                                        <span class="mt-3 d-inline-block">ST NOTA VF BYU</span>
                                    </div>
                                </a>
                            </div>
                            <div class="col-2 text-center">
                                <a href="st_nota_vf_sim" class="d-inline-block border rounded w-100 menu">
                                    <div class="p-3">
                                        <div class="d-inline-block w-100">
                                            <i class="fa-regular fa-note-sticky fs-1"></i>
                                        </div>
                                    
                                        <span class="mt-3 d-inline-block">ST NOTA VF SIM</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>  
                </div>
            </div>
            <?= $this->include('/includes/include_footer'); ?>
        </div>      
    </div>

<?php $this->endSection() ?>
