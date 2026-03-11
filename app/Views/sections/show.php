<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?php $title = esc($link['title']) . ' — Pay Now'; ?>

<?php
/**
 * @var array       $link
 * @var string|null $error
 */

$isPaid = $link['status'] === 'paid';
?>

<div class="max-w-lg mx-auto">

    <!-- Product card -->
    <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">

        <!-- Card header -->
        <div class="bg-[#EAAA08] px-6 py-5 text-black">
            <p class="text-xs font-semibold uppercase tracking-widest text-black mb-1">
                Secure Payment
            </p>
            <h1 class="text-xl font-bold leading-snug">
                <?= esc($link['title']) ?>
            </h1>
        </div>

        <!-- Card body -->
        <div class="px-6 py-6 space-y-5">

            <!-- Description -->
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1">
                    Description
                </p>
                <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-wrap">
                    <?= esc($link['description']) ?>
                </p>
            </div>

            <hr class="border-gray-100">

            <!-- Price -->
            <div class="flex items-baseline justify-between">
                <span class="text-sm text-gray-500">Total Due</span>
                <span class="text-2xl font-bold text-gray-900">
                    <?= esc($link['currency']) ?>
                    <?= number_format((float) $link['price'], 2) ?>
                </span>
            </div>

            <!-- Already-paid notice -->
            <?php if ($isPaid): ?>
                <div class="rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700 flex items-center gap-2">
                    <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    This payment has already been completed.
                </div>
            <?php endif ?>

            <!-- Error -->
            <?php if (! empty($error)): ?>
                <div class="rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
                    <?= esc($error) ?>
                </div>
            <?php endif ?>

            <!-- Pay button -->
            <?php if (! $isPaid): ?>
                <button
                    type="button"
                    class="btn-primary w-full justify-center"
                    id="pay-btn"
                    onclick="showPaymentSuccess()"
                >
                    Pay Now
                </button>
            <?php endif ?>

        </div><!-- /body -->

        <!-- Card footer -->
        <div class="border-t border-gray-100 bg-gray-50 px-6 py-3 flex items-center gap-2 text-xs text-gray-400">
            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944
                         a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003
                         9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03
                         9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            Simulated secure checkout &mdash; no real charges will be made
        </div>

    </div><!-- /card -->

</div>

<!-- Success Modal -->
<div id="success-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 ">
    <div class="bg-white rounded-2xl shadow-xl max-w-sm w-full mx-4 p-8 text-center">

        <!-- Checkmark -->
        <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-full bg-green-100">
            <svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
        </div>

        <h2 class="text-xl font-bold text-gray-900 mb-2">Payment Successful!</h2>
        <p class="mt-2 text-sm text-gray-500">
        Thank you for your purchase. A confirmation would be sent to
        <strong class="text-gray-700"><?= esc($link['email']) ?></strong>.
    </p>
        <p class="text-sm font-semibold text-gray-800 mb-1">Product: <?= esc($link['title']) ?></p>
        <p class="text-lg font-bold text-green-700 mb-6">
            <?= esc($link['currency']) ?> <?= number_format((float) $link['price'], 2) ?>
        </p>

        <a href="<?= base_url('/') ?>" class="btn-primary w-full justify-center">
            Back to Home
        </a>

    </div>
</div>

<script>
function showPaymentSuccess() {
    const btn = document.getElementById('pay-btn');
    btn.disabled = true;
    btn.textContent = 'Processing…';

    // Simulate a short processing delay then show the success modal
    setTimeout(function () {
        document.getElementById('success-modal').classList.remove('hidden');
    }, 1500);
}
</script>

<?= $this->endSection() ?>