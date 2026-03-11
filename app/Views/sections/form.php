<?php
/**
 * sections/form.php — Payment link creation form partial.
 *
 * Can be included from home.php (homepage) or rendered standalone via
 * LinksController. Safely defaults $validation and $old so it never
 * errors when those variables are absent.
 *
 * @var \CodeIgniter\Validation\Validation|null $validation
 * @var array                                   $old
 * @var string|null                             $error
 */

$validation = isset($validation) ? $validation : null;
$old        = isset($old)        ? $old        : [];
$error      = isset($error)      ? $error      : null;

$val = static function (string $field) use ($old): string {
    return esc($old[$field] ?? '');
};

$fieldError = static function (string $field) use ($validation): string {
    if ($validation === null) {
        return '';
    }
    $err = $validation->getError($field);
    if ($err === null || $err === '') {
        return '';
    }
    return '<p class="mt-1 text-sm text-red-600">' . esc($err) . '</p>';
};

$fieldClass = static function (string $field) use ($validation): string {
    $base = 'block w-full rounded-lg border px-3 py-2.5 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition';
    if ($validation !== null && $validation->hasError($field)) {
        return $base . ' border-red-400 bg-red-50 text-red-900';
    }
    return $base . ' border-gray-300 bg-white text-gray-900';
};
?>

<section class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-32">
    <!-- Heading -->
    <div class="mb-8 flex flex-col">
            <h1 class="text-4xl font-bold text-gray-900">Generate a Payment Link</h1>
            <p class="mt-3 text-gray-500">
                Fill in your product details below and we'll create a unique shareable payment page.
            </p>
        </div>

        <!-- Global error (non-validation) -->
        <?php if (! empty($error)): ?>
            <div class="mb-6 rounded-lg border border-red-300 bg-red-50 p-4 text-sm text-red-700">
                <?= esc($error) ?>
            </div>
        <?php endif ?>

        <!-- Form -->
        <form action="<?= base_url('links') ?>" method="POST" novalidate>
            <?= csrf_field() ?>

            <div class="space-y-6 rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">

                <!-- Product Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                        Product Title <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        value="<?= $val('title') ?>"
                        placeholder="e.g. Premium Course Access"
                        maxlength="255"
                        class="<?= $fieldClass('title') ?>"
                        autocomplete="off"
                    >
                    <?= $fieldError('title') ?>
                </div>

                <!-- Product Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                        Product Description <span class="text-red-500">*</span>
                    </label>
                    <textarea
                        id="description"
                        name="description"
                        rows="4"
                        placeholder="Describe what the customer is paying for…"
                        maxlength="2000"
                        class="<?= $fieldClass('description') ?>"
                    ><?= $val('description') ?></textarea>
                    <?= $fieldError('description') ?>
                </div>

                <!-- Price & Currency -->
                <div class="grid grid-cols-3 gap-4">
                    <div class="col-span-2">
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-1">
                            Price <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input
                                type="number"
                                id="price"
                                name="price"
                                value="<?= $val('price') ?>"
                                placeholder="0.00"
                                min="0.01"
                                max="999999.99"
                                step="0.01"
                                class="<?= $fieldClass('price') ?> pl-7"
                            >
                        </div>
                        <?= $fieldError('price') ?>
                    </div>

                    <div>
                        <label for="currency" class="block text-sm font-medium text-gray-700 mb-1">
                            Currency
                        </label>
                        <select
                            id="currency"
                            name="currency"
                            class="<?= $fieldClass('currency') ?>"
                        >
                            <?php
                            $currencies = ['USD' => 'USD', 'EUR' => 'EUR', 'GBP' => 'GBP', 'NGN' => 'NGN', 'CAD' => 'CAD', 'AUD' => 'AUD'];
                            $selected   = $old['currency'] ?? 'USD';
                            foreach ($currencies as $code => $label):
                            ?>
                                <option value="<?= esc($code) ?>" <?= $selected === $code ? 'selected' : '' ?>>
                                    <?= esc($label) ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                        <?= $fieldError('currency') ?>
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        Your Email Address <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="<?= $val('email') ?>"
                        placeholder="you@example.com"
                        maxlength="255"
                        class="<?= $fieldClass('email') ?>"
                        autocomplete="email"
                    >
                    <p class="mt-1 text-xs text-gray-400">Used to associate this link with your account.</p>
                    <?= $fieldError('email') ?>
                </div>

            </div><!-- /card -->

            <div class="mt-12 flex justify-center">
                <button type="submit" class="btn-primary lg:w-1/2 w-full justify-center items-center">
                    Generate Payment Link
                </button>
            </div>

        </form>
</section>