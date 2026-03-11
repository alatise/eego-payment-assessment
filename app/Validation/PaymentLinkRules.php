<?php

declare(strict_types=1);

namespace App\Validation;

/**
 * Centralises all validation rules and messages for payment-link creation.
 * Keeping these out of the controller makes them reusable (e.g. API endpoint)
 * and independently testable.
 */
class PaymentLinkRules
{
    /**
     * @return array<string, array<string, string>>
     */
    public static function rules(): array
    {
        return [
            'title' => [
                'label' => 'Product Title',
                'rules' => 'required|min_length[2]|max_length[255]',
            ],
            'description' => [
                'label' => 'Product Description',
                'rules' => 'required|min_length[10]|max_length[2000]',
            ],
            'price' => [
                'label' => 'Price',
                'rules' => 'required|decimal|greater_than[0]|less_than_equal_to[999999.99]',
            ],
            'email' => [
                'label' => 'Email Address',
                'rules' => 'required|valid_email|max_length[255]',
            ],
            'currency' => [
                'label' => 'Currency',
                'rules' => 'permit_empty|in_list[USD,EUR,GBP,NGN,CAD,AUD]',
            ],
        ];
    }

    /**
     * Custom error messages.
     *
     * @return array<string, array<string, string>>
     */
    public static function messages(): array
    {
        return [
            'title' => [
                'required'   => 'Please enter a product title.',
                'min_length' => 'The product title must be at least 2 characters.',
                'max_length' => 'The product title cannot exceed 255 characters.',
            ],
            'description' => [
                'required'   => 'Please enter a product description.',
                'min_length' => 'The description must be at least 10 characters.',
                'max_length' => 'The description cannot exceed 2000 characters.',
            ],
            'price' => [
                'required'          => 'Please enter a price.',
                'decimal'           => 'Price must be a valid number (e.g. 9.99).',
                'greater_than'      => 'Price must be greater than zero.',
                'less_than_equal_to'=> 'Price cannot exceed 999,999.99.',
            ],
            'email' => [
                'required'    => 'Please enter your email address.',
                'valid_email' => 'Please enter a valid email address.',
                'max_length'  => 'Email address cannot exceed 255 characters.',
            ],
            'currency' => [
                'in_list' => 'Please select a supported currency.',
            ],
        ];
    }
}