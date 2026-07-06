<!DOCTYPE html>
<html lang="id" data-theme="">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="<?= csrf_hash() ?>">
  <title><?= esc($title ?? 'PanenKu') ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
  <script>
    (function(){
      var s = localStorage.getItem('pk_theme') || 'system';
      var d = s === 'dark' || (s === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches) ? 'dark' : 'light';
      document.documentElement.setAttribute('data-theme', d);
    })();
  </script>
</head>
<body>
  <div class="auth-page">
    <div class="theme-float">
      <div class="theme-switcher">
        <button class="theme-btn" data-theme-btn="light" title="Light"><i class="bi bi-sun"></i></button>
        <button class="theme-btn" data-theme-btn="dark" title="Dark"><i class="bi bi-moon"></i></button>
        <button class="theme-btn" data-theme-btn="system" title="Sistem"><i class="bi bi-circle-half"></i></button>
      </div>
    </div>
    <?= $this->renderSection('content') ?>
  </div>
  <script src="<?= base_url('assets/js/app.js') ?>"></script>
</body>
</html>
