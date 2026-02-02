<?php $this->extend('/templates/template_main') ?>

<?php $this->section('content') ?>

<div id="main-wrapper" class="dashboard-page min-vh-100 d-flex flex-column">
    <?= $this->include('/includes/include_top_navbar'); ?>     
    <div class="container-fluid menu-dashboard bg-body-secondary">
        <div class="row">
            <div class="col-10 m-auto">
                <h5 class="mt-3">Import Progress</h5>

                <div class="progress">
                    <div id="progressBar"
                        class="progress-bar progress-bar-striped progress-bar-animated"
                        role="progressbar"
                        style="width: 0%">
                        0%
                    </div>
                </div>

                <p class="mt-2" id="statusText">Starting...</p>
            </div>
        </div>
         <?= $this->include('/includes/include_footer'); ?>
    </div>
</div>

<script>
function runImport() {
    fetch("<?= base_url('replace/processImport/' . $jobId) ?>")
        .then(r => r.json())
        .then(res => {

            if (res.error) {
                document.getElementById('statusText').innerText = res.error;
                return;
            }

             // ✅ IMPORT SELESAI
            if (res.done === true) {
                let bar = document.getElementById('progressBar');
                bar.style.width = '100%';
                bar.innerText = '100%';
                bar.classList.remove('progress-bar-animated');

                document.getElementById('statusText').innerText =
                    '✅ Import selesai, mengalihkan halaman...';

                // ⏳ kasih jeda biar user lihat 100%
                setTimeout(() => {
                    window.location.href = "<?= base_url('replace/upload') ?>";
                }, 1000);

                return; // ⛔ STOP LOOP
            }

            if (!res || typeof res.processed === 'undefined' || typeof res.total === 'undefined') {
                document.getElementById('statusText').innerText =
                    'Menunggu data import...';
                setTimeout(runImport, 500);
                return;
            }

            let percent = Math.round((res.processed / res.total) * 100);
            percent = percent > 100 ? 100 : percent;

            let bar = document.getElementById('progressBar');
            bar.style.width = percent + '%';
            bar.innerText = percent + '%';

            document.getElementById('statusText').innerText =
                `Processed ${res.processed} / ${res.total}`;

            if (res.done) {
                bar.classList.remove('progress-bar-animated');
                return;
            }

            setTimeout(runImport, 300);
        })
        .catch(() => {
            document.getElementById('statusText').innerText =
                '⚠️ Koneksi terputus. Reload halaman untuk resume.';
        });
}

runImport();
</script>

<?php $this->endSection() ?>