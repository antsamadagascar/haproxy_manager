<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= base_url('Accueil/index') ?>">HAProxy Config Editor</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('Global_Controller/get_global_section') ?>">global</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('Defaults_Controller/get_defaults_section') ?>">defaults</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('Http_front_Controller/get_http_front_section') ?>">http_front</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('Http_back_Controller/get_http_back_section') ?>">http_back</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="#" id="terminalToggle">
                        <i class="bi bi-terminal-fill fs-5"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
