<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\PaymentLinkService;
use App\Services\TokenGenerator;
use App\Models\PaymentLinkModel;
use CodeIgniter\HTTP\RedirectResponse;

/**
 * PayController
 *
 * Handles:
 *   GET  /pay/{token}         → display the payment page
 *   POST /pay/{token}/process → simulate payment success
 */
class PayController extends BaseController
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
     * GET /pay/{token}
     * Render the payment page for the given token.
     */
    public function show(string $token): string|RedirectResponse
    {
        $link = $this->paymentLinkService->findByToken($token);

        if ($link === null) {
            return $this->notFound();
        }

        return view('sections/show', ['link' => $link]);
    }

    /**
     * POST /pay/{token}/process
     * Simulate a successful payment.
     */
    public function process(string $token): string|RedirectResponse
    {
        $link = $this->paymentLinkService->findByToken($token);

        if ($link === null) {
            return $this->notFound();
        }

        if ($link['status'] === 'paid') {
            // Idempotent: already paid — redirect straight to success
            return redirect()->to(base_url('pay/' . esc($token) . '/complete'));
        }

        try {
            $this->paymentLinkService->markAsPaid($link);
        } catch (\InvalidArgumentException) {
            // Link is expired or in a non-payable state
            return view('sections/show', [
                'link'  => $link,
                'error' => 'This payment link is no longer valid.',
            ]);
        }

        return redirect()->to(base_url('pay/' . esc($token) . '/complete'));
    }

    /**
     * GET /pay/{token}/complete
     * Display the payment-success confirmation page.
     */
    public function complete(string $token): string|RedirectResponse
    {
        $link = $this->paymentLinkService->findByToken($token);

        if ($link === null || $link['status'] !== 'paid') {
            return redirect()->to(base_url('pay/' . esc($token)));
        }

        return view('sections/complete', ['link' => $link]);
    }

    // -------------------------------------------------------------------------
    // Private helpers
    // -------------------------------------------------------------------------

    private function notFound(): string
    {
        return view('sections/NotFound');
    }
}