<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\PaymentLinkModel;

/**
 * PaymentLinkService
 *
 * Orchestrates the creation and retrieval of payment links.
 * All business logic lives here; the controller stays thin.
 */
class PaymentLinkService
{

    private const MAX_TOKEN_ATTEMPTS = 10;

    public function __construct(
        private readonly PaymentLinkModel $model,
        private readonly TokenGenerator   $tokenGenerator,
    ) {}

    /**
     * Create a new payment link from validated input data.
     *
     * @param  array{title: string, description: string, price: numeric-string, email: string, currency?: string} $data
     * @return array The newly created payment link record.
     * @throws \RuntimeException If a unique token cannot be generated.
     */
    public function create(array $data): array
    {
        $token = $this->generateUniqueToken();

        $record = [
            'token'       => $token,
            'email'       => strtolower(trim($data['email'])),
            'title'       => trim($data['title']),
            'description' => trim($data['description']),
            'price'       => (float) $data['price'],
            'currency'    => strtoupper($data['currency'] ?? 'USD'),
            'status'      => 'pending',
        ];

        $id = $this->model->insert($record, true);

        return array_merge($record, ['id' => $id]);
    }

    /**
     * Retrieve a payment link by token.
     * Returns null when the token does not exist so the controller
     * can issue a 404 without leaking whether any tokens exist.
     */
    public function findByToken(string $token): ?array
    {
        // Basic sanity-check: reject tokens with unexpected characters
        // before hitting the database.
        if (! $this->isValidTokenFormat($token)) {
            return null;
        }

        return $this->model->findByToken($token);
    }

    /**
     * Mark a payment link as paid.
     *
     * @throws \InvalidArgumentException If the link is not in a payable state.
     */
    public function markAsPaid(array $paymentLink): array
    {
        if ($paymentLink['status'] !== 'pending') {
            throw new \InvalidArgumentException(
                'Payment link is not in a payable state.'
            );
        }

        $this->model->markAsPaid((int) $paymentLink['id']);

        return array_merge($paymentLink, [
            'status'  => 'paid',
            'paid_at' => date('Y-m-d H:i:s'),
        ]);
    }

    // -------------------------------------------------------------------------
    // Private helpers
    // -------------------------------------------------------------------------

    /**
     *
     * @throws \RuntimeException
     */
    private function generateUniqueToken(): string
    {
        for ($attempt = 0; $attempt < self::MAX_TOKEN_ATTEMPTS; $attempt++) {
            $token = $this->tokenGenerator->generate();

            if (! $this->model->tokenExists($token)) {
                return $token;
            }
        }

        throw new \RuntimeException(
            'Failed to generate a unique payment token after ' . self::MAX_TOKEN_ATTEMPTS . ' attempts.'
        );
    }

    /**
     * Verify the token contains only the characters produced by the generator.
     * URL-safe base64 uses [A-Za-z0-9\-_] with no padding.
     */
    private function isValidTokenFormat(string $token): bool
    {
        return (bool) preg_match('/^[A-Za-z0-9\-_]{20,64}$/', $token);
    }
}