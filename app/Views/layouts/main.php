<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Pay Now') ?></title>
    
    <!-- Tailwind CSS -->
    <link rel="stylesheet" href="<?= base_url('css/app.css') ?>">
</head>
<body id= "body" class="antialiased">
    <!-- Header/Navigation -->
    <?= $this->include('components/header') ?>
    
    <!-- Main Content -->
    <main>
        <?= $this->renderSection('content') ?>
    </main>
</body>
</html>