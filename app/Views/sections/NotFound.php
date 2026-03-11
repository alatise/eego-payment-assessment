<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?php $title = 'Payment Link Not Found'; ?>

<div class="max-w-md mx-auto text-center py-16">

    <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100">
        <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01
                     M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
    </div>

    <h1 class="text-xl font-bold text-gray-900">Payment Link Not Found</h1>
    <p class="mt-2 text-sm text-gray-500">
        This payment link may have expired, been removed, or the URL is incorrect.
        Please check with whoever shared it with you.
    </p>

    <div class="mt-6">
        <a href="<?= base_url('/') ?>" class="btn-secondary">
            Go Home
        </a>
    </div>

</div>

<?= $this->endSection() ?>