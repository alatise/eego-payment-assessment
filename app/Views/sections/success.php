<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?php $title = 'Payment Link Created'; ?>

<?php
/**
 * @var array  $link
 * @var string $payUrl
 */
?>

<div class="max-w-xl mx-auto text-center">

    <!-- Success icon -->
    <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-green-100">
        <svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg>
    </div>

    <h1 class="text-2xl font-bold text-gray-900">Your Payment Link is Ready!</h1>
    <p class="mt-2 text-sm text-gray-500">
        Share the link below with your customer. It is unique and ready to accept payments.
    </p>

    <!-- Link display card -->
    <div class="mt-8 rounded-xl border border-gray-200 bg-white p-6 shadow-sm text-left">

        <div class="mb-4">
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1">Payment URL</p>
            <div class="flex items-center gap-2">
                <input
                    id="pay-url"
                    type="text"
                    value="<?= esc($payUrl) ?>"
                    readonly
                    class="flex-1 rounded-lg border border-gray-300 bg-gray-50 px-3 py-2 text-sm text-gray-700 font-mono focus:outline-none"
                    onclick="this.select()"
                >
                <button
                    type="button"
                    onclick="copyPayUrl()"
                    class="btn-secondary shrink-0"
                    id="copy-btn"
                >
                    Copy
                </button>
            </div>
        </div>

        <hr class="my-4 border-gray-100">

        <!-- Summary -->
        <dl class="space-y-2 text-sm">
            <div class="flex justify-between">
                <dt class="text-gray-500">Product</dt>
                <dd class="font-medium text-gray-900"><?= esc($link['title']) ?></dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-500">Amount</dt>
                <dd class="font-semibold text-gray-900">
                    <?= esc($link['currency']) ?> <?= number_format((float) $link['price'], 2) ?>
                </dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-500">Linked to</dt>
                <dd class="text-gray-700"><?= esc($link['email']) ?></dd>
            </div>
        </dl>

    </div><!-- /card -->

    <!-- CTA row -->
    <div class="mt-6 flex flex-col sm:flex-row gap-3 justify-center">
        <a href="<?= esc($payUrl) ?>" target="_blank" rel="noopener noreferrer" class="btn-primary">
            Preview Payment Page
        </a>
        <a href="<?= base_url('/') ?>" class="btn-secondary">
            Create Another Link
        </a>
    </div>

</div>

<script>
function copyPayUrl() {
    const input = document.getElementById('pay-url');
    const btn   = document.getElementById('copy-btn');

    navigator.clipboard.writeText(input.value).then(() => {
        btn.textContent = 'Copied!';
        setTimeout(() => { btn.textContent = 'Copy'; }, 2000);
    }).catch(() => {
        input.select();
        document.execCommand('copy');
        btn.textContent = 'Copied!';
        setTimeout(() => { btn.textContent = 'Copy'; }, 2000);
    });
}
</script>

<?= $this->endSection() ?>