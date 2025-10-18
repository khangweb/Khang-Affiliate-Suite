<?php

namespace KhangWeb\Deeplink\DataGrids;

use Webkul\DataGrid\DataGrid;
use Illuminate\Support\Facades\DB;

class ProductUrlDataGrid extends DataGrid
{
    protected $index = 'id';

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('product_urls')
            ->select('id', 'product_id', 'link_source', 'link_aff');
        
        $this->addFilter('id', 'product_urls.id');
        return $queryBuilder;
    }

    public function prepareColumns()
    {
        $this->addColumn([
            'index' => 'id',
            'label' => 'ID',
            'type' => 'integer',
            'searchable' => false,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'product_id',
            'label' => 'Product ID',
            'type' => 'integer',
            'searchable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'link_source',
            'label' => 'Link Source',
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'link_aff',
            'label' => 'Link Aff',
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
        ]);
    }

    public function prepareActions()
    {
        $this->addAction([
            'title' => 'Edit',
            'method' => 'GET',
            'icon'   => 'icon-edit',
            'url'    => function ($row) {
                return route('admin.product-url.edit', $row->id);
            }
        ]);
    }
}
