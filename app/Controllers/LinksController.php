<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\PaymentLinkService;
use App\Services\TokenGenerator;
use App\Models\PaymentLinkModel;
use App\Validation\PaymentLinkRules;
use CodeIgniter\HTTP\RedirectResponse;

/**
 * LinksController
 *
 * Handles:
 *   POST /links → validate input, create link, redirect to success page
 *
 * The form itself lives on the homepage (Home::index → home.php → sections/form.php).
 * On validation failure I re-render home.php with errors so the form
 * repopulates in place — no separate create view needed.
 */
class LinksController extends BaseController
{
    private PaymentLinkService $paymentLinkService;

    public function __construct()
    {
        $this->paymentLinkService = new PaymentLinkService(
            new PaymentLinkModel(),
            new TokenGenerator(),
        );
    }

    /**
     * POST /links
     * Validate, create, and redirect.
     */
    public function store(): string|RedirectResponse
    {
        $rules    = PaymentLinkRules::rules();
        $messages = PaymentLinkRules::messages();

        if (! $this->validate($rules, $messages)) {
            // Re-render the homepage with validation errors and repopulated fields
            return view('home', [
                'title'      => 'Pay Easy',
                'validation' => $this->validator,
                'old'        => $this->request->getPost(),
            ]);
        }

        $data = $this->request->getPost([
            'title',
            'description',
            'price',
            'email',
            'currency',
        ]);

        try {
            $link = $this->paymentLinkService->create($data);
        } catch (\RuntimeException $e) {
            return view('home', [
                'title'      => 'Pay Easy',
                'validation' => null,
                'old'        => $this->request->getPost(),
                'error'      => 'We could not generate your payment link. Please try again.',
            ]);
        }

        return redirect()
            ->to(base_url('links/success/' . $link['token']))
            ->with('link_token', $link['token']);
    }

    /**
     * GET /links/success/{token}
     * Show the "your link is ready" confirmation page.
     */
    public function success(string $token): string|RedirectResponse
    {
        $link = $this->paymentLinkService->findByToken($token);

        if ($link === null) {
            return redirect()->to(base_url('/'));
        }

        $payUrl = base_url('pay/' . esc($link['token']));

        return view('sections/success', [
            'title'  => 'Payment Link Created',
            'link'   => $link,
            'payUrl' => $payUrl,
        ]);
    }
}