<?php

return [
    'settings' => [
        'title'        => 'Import Settings',
        'update-success' => 'Import settings updated successfully.',
        'update-failed'  => 'Failed to update import settings.',
        'save-btn'     => 'Save Settings',
    ],

    'scraped_products' => [
        'title'        => 'Scraped Products',
        'add-btn'      => 'Add Scraped Product',
        'create'       => [
            'title'      => 'Create Scraped Product',
            'save-btn'   => 'Save Product',
            'back-btn'   => 'Back to Scraped Products',
            'create-success' => 'Scraped product created successfully.',
            'create-failed'  => 'Failed to create scraped product: :error',
        ],
        'edit'         => [
            'title'      => 'Edit Scraped Product',
            'save-btn'   => 'Save Changes',
            'back-btn'   => 'Back to Scraped Products',
            'update-success' => 'Scraped product updated successfully.',
            'update-failed'  => 'Failed to update scraped product: :error',
        ],
        'delete-success' => 'Scraped product deleted successfully.',
        'delete-failed'  => 'Failed to delete scraped product: :error',
        'datagrid'     => [
            'id'           => 'ID',
            'name'         => 'Name',
            'url'          => 'URL',
            'status'       => 'Status',
            'created_at'   => 'Created At',
            'updated_at'   => 'Updated At',
            'status-pending'  => 'Pending',
            'status-imported' => 'Imported',
            'status-failed'   => 'Failed',
        ],
        'name'                    => 'Product Name',
        'name-placeholder'        => 'Enter product name',
        'url'                     => 'Scraped URL',
        'status'                  => 'Status',
        'error_message'           => 'Error Message',
        'scraping_templates_id'   => 'Scraping Template',
        'ip'                      => 'IP Address',
        'raw_data'                => 'Raw Data (JSON)',
        'select-template'         => 'Select a Scraping Template',
        'mass-action' => [
            'delete' => 'Delete' ,
            'dispatch-for-import'      => 'Dispatch for Import',
            'dispatch-success'         => ':count scraped products have been dispatched to the import queue.',
            'no-pending-to-dispatch'   => 'No pending scraped products found to dispatch.',
        ],
        'status-pending' => 'Pending' ,
        'status-imported' => 'Imported',
        'status-failed' => 'Fail',
        'update-success' => 'Update Success'
    ],
];
