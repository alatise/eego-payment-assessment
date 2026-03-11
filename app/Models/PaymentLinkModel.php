<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

class PaymentLinkModel extends Model
{
    protected $table            = 'payment_links';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'token',
        'email',
        'title',
        'description',
        'price',
        'currency',
        'status',
        'paid_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    /**
     * Find a payment link by its token.
     * Using exact-match lookup — no partial/enumerable exposure.
     */
    public function findByToken(string $token): ?array
    {
        return $this->where('token', $token)->first();
    }

    /**
     * Check whether a token already exists in the database.
     */
    public function tokenExists(string $token): bool
    {
        return $this->where('token', $token)->countAllResults() > 0;
    }

    /**
     * Mark a payment link as paid.
     */
    public function markAsPaid(int $id): bool
    {
        return $this->update($id, [
            'status'  => 'paid',
            'paid_at' => date('Y-m-d H:i:s'),
        ]);
    }
}