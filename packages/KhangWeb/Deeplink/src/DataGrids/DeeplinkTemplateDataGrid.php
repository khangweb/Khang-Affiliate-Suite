<?php

namespace KhangWeb\Deeplink\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class DeeplinkTemplateDataGrid extends DataGrid
{
    /**
     * Cột chính (dùng cho checkbox & hành động).
     *
     * @var string
     */
    protected $primaryColumn = 'id';

    /**
     * Thiết lập truy vấn dữ liệu.
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('deeplink_templates')
            ->select('id', 'name', 'status', 'created_at', 'updated_at')
            ->orderBy('created_at', 'desc');

        $this->addFilter('id', 'deeplink_templates.id');

        return $queryBuilder;
    }

    /**
     * Định nghĩa các cột hiển thị.
     */
    public function prepareColumns()
    {
        // Cột ID
        $this->addColumn([
            'index'    => 'id',
            'label'    => trans('deeplink::app.id'),
            'type'     => 'integer',
            'searchable' => false,
            'sortable'   => true,
        ]);

        // Cột tên template
        $this->addColumn([
            'index'      => 'name',
            'label'      => trans('deeplink::app.name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
        ]);

        // Cột trạng thái
        $this->addColumn([
            'index'    => 'status',
            'label'    => trans('deeplink::app.status'),
            'type'     => 'string',
            'sortable' => true,
            'closure'  => function ($row) {
                if ($row->status === 1) {
                    return '<span class="badge badge-sm badge-success">Active</span>';
                }
                return '<span class="badge badge-sm badge-danger">Inactive</span>';
            },
        ]);

        // Ngày tạo
        $this->addColumn([
            'index'    => 'created_at',
            'label'    => trans('deeplink::app.created_at'),
            'type'     => 'datetime',
            'sortable' => true,
        ]);

        // Ngày cập nhật
        $this->addColumn([
            'index'    => 'updated_at',
            'label'    => trans('deeplink::app.updated_at'),
            'type'     => 'datetime',
            'sortable' => true,
        ]);
    }

    /**
     * Các hành động cho mỗi dòng.
     */
    public function prepareActions()
    {
        $this->addAction([
            'title'  => trans('deeplink::app.edit'),
            'method' => 'GET',
            'icon'   => 'icon-edit',
            'url'    => function ($row) {
                return route('admin.deeplink.edit', $row->id);
            },
        ]);

        $this->addAction([
            'title'  => trans('deeplink::app.delete'),
            'method' => 'DELETE',
            'icon'   => 'icon-delete',
            'url'    => function ($row) {
                return route('admin.deeplink.destroy', $row->id);
            },
        ]);
    }


    /**
     * Các hành động hàng loạt (mass).
     */
    public function prepareMassActions()
    {
        $this->addMassAction([
            'title'  => trans('deeplink::app.mass-delete'), // 👈 PHẢI CÓ "title"
            'type'   => 'delete',
            'label'  => trans('deeplink::app.mass-delete'),
            'url' => route('admin.deeplink.mass_delete'), // bạn cần tạo route và controller tương ứng nếu dùng
            'method' => 'POST',
        ]);


        // ✅ Cập nhật status hàng loạt
        $this->addMassAction([
            'title'  => trans('deeplink::app.mass-update-status'),
            'type'   => 'update',
            'label'  => trans('deeplink::app.mass-update-status'),
            'url'    => route('admin.deeplink.mass_update_status'),
            'method' => 'POST',
            'options' => [
                    [
                        'label' => trans('admin::app.catalog.products.index.datagrid.active'),
                        'value' => 1,
                    ],
                    [
                        'label' => trans('admin::app.catalog.products.index.datagrid.disable'),
                        'value' => 0,
                    ],
                ],
        ]);
    }
}
