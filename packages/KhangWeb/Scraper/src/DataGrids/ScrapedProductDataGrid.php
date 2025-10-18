<?php

namespace KhangWeb\Scraper\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;


class ScrapedProductDataGrid extends DataGrid
{
    /**
     * Primary column.
     *
     * @var string
     */
    protected $primaryColumn = 'id';

    /**
     * Prepare query builder.
     *
     * @return void
     */
    public function prepareQueryBuilder()
    {
        // THAY ĐỔI DÒNG NÀY ĐỂ SỬ DỤNG ELOQUENT MODEL TRỰC TIẾP
        $queryBuilder = DB::table('scraped_products')
            ->select('id','name','status','url','created_at','updated_at')
            ->orderBy('created_at');
        return $queryBuilder;
    }

    /**
     * Prepare columns.
     *
     * @return void
     */
    public function prepareColumns()
    {
        $this->addColumn([
            'index'    => 'id',
            'label'    => trans('scraper::app.scraped_products.datagrid.id'),
            'type'     => 'integer',
            'sortable' => true,
        ]);

        $this->addColumn([
            'index'    => 'name',
            'label'    => trans('scraper::app.scraped_products.datagrid.name'),
            'type'     => 'string',
            'sortable' => true,
        ]);

        $this->addColumn([
            'index'    => 'url',
            'label'    => trans('scraper::app.scraped_products.datagrid.url'),
            'type'     => 'string',
            'sortable' => true,
            'closure'  => function ($row) {
                if (empty($row->url)) {
                    return 'N/A';
                }
                return '<a href="' . $row->url . '" target="_blank" class="text-blue-600 hover:underline">' . $row->url . '</a>';
            },
        ]);

        $this->addColumn([
            'index'    => 'status',
            'label'    => trans('scraper::app.scraped_products.datagrid.status'),
            'type'     => 'string',
            'sortable' => true,
            'closure'  => function ($row) {
                if (empty($row->status)) {
                    return 'N/A';
                }
                if ($row->status === 'pending') {
                    return '<span class="label-pending">'.trans('scraper::app.scraped_products.datagrid.status_options.pending').'</span>';
                } elseif ($row->status === 'imported') {
                    return '<span class="label-active">'.trans('scraper::app.scraped_products.datagrid.status_options.imported').'</span>';
                } elseif ($row->status === 'failed') {
                    return '<span class="label-danger">'.trans('scraper::app.scraped_products.datagrid.status_options.failed').'</span>';
                }
                return $row->status;
            },
        ]);

        $this->addColumn([
            'index'    => 'created_at',
            'label'    => trans('scraper::app.scraped_products.datagrid.created_at'),
            'type'     => 'datetime',
            'sortable' => true,
        ]);

        $this->addColumn([
            'index'    => 'updated_at',
            'label'    => trans('scraper::app.scraped_products.datagrid.updated_at'),
            'type'     => 'datetime',
            'sortable' => true,
        ]);
    }

    /**
     * Prepare actions.
     *
     * @return void
     */
    public function prepareActions()
    {
        $this->addAction([
            'title'  => 'delete',
            'method' => 'GET',
            'icon'   => 'icon-edit',
            // SỬ DỤNG 'url' VỚI MỘT CLOSURE NHƯ MẪU BẠN THẤY
            'url'    => function ($row) {
                return route('scraper.admin.scraped_products.edit', $row->id);
            },
        ]);

        $this->addAction([
            'title'  => 'delete',
            'method' => 'DELETE',
            'icon'   => 'icon-delete',
            // SỬ DỤNG 'url' VỚI MỘT CLOSURE NHƯ MẪU BẠN THẤY
            'url'    => function ($row) {
                return route('scraper.admin.scraped_products.destroy', $row->id);
            },
        ]);
    }

    /**
     * Prepare mass actions.
     *
     * @return void
     */
    public function prepareMassActions()
    {
        $this->addMassAction([
            'type'   => 'delete',
            'title'  => trans('scraper::app.scraped_products.delete'),
            'url'    => route('admin.scraped_products.mass_destroy'), // Dòng này vẫn đúng
            'method' => 'POST',
        ]);
               // ✅ Cập nhật status hàng loạt
        $this->addMassAction([
            'title'  => trans('scraper::app.scraped_products.mass-action.mass-update-status'),
            'type'   => 'update',
            'label'  => trans('scraper::app.scraped_products.mass-action.mass-update-status'),
            'url'    => route('admin.scraped_products.mass_update_status'),
            'method' => 'POST',
            'options' => [
                    [
                        'label' => trans('scraper::app.scraped_products.datagrid.status_options.pending'),
                        'value' => 'pending',
                    ],
                    [
                        'label' => trans('scraper::app.scraped_products.datagrid.status_options.imported'),
                        'value' => 'imported',
                    ],
                    [
                        'label' => trans('scraper::app.scraped_products.datagrid.status_options.failed'),
                        'value' => 'failed',
                    ]
                ],
        ]);

                // THÊM DÒNG NÀY CHO TÁC VỤ MỚI
        $this->addMassAction([
            'type'   => 'update', // Sử dụng 'update' hoặc một loại tùy chỉnh nếu Bagisto hỗ trợ
            'title'  => trans('scraper::app.scraped_products.mass-action.dispatch-for-import'), // Nhãn hiển thị
            'url'    => route('admin.scraped_products.mass_dispatch_for_import'),
            'method' => 'POST',
        ]);



    }
}