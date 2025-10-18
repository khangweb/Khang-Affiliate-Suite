<?php

namespace KhangWeb\Scraper\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;
use Illuminate\Support\Str;

class ScrapingTemplateDataGrid extends DataGrid
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
        $queryBuilder = DB::table('scraping_templates')
            ->select('id', 'name', 'fields', 'created_at', 'updated_at');

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
            'label'    => trans('scraper::app.scraping_templates.datagrid.id'),
            'type'     => 'integer',
            'sortable' => true,
        ]);

        $this->addColumn([
            'index'    => 'name',
            'label'    => trans('scraper::app.scraping_templates.datagrid.name'),
            'type'     => 'string',
            'sortable' => true,
            'searchable' => true,
        ]);

        // $this->addColumn([
        //     'index'    => 'fields',
        //     'label'    => trans('scraper::app.scraping_templates.datagrid.fields'),
        //     'type'     => 'string',
        //     'sortable' => false,
        //     'closure'  => function ($row) {
        //         return '<pre class="text-xs max-w-[300px] whitespace-pre-wrap break-words">'
        //             . htmlentities(Str::limit($row->fields, 100)) . '</pre>';
        //     },
        // ]);

        $this->addColumn([
            'index'    => 'created_at',
            'label'    => trans('scraper::app.scraping_templates.datagrid.created_at'),
            'type'     => 'datetime',
            'sortable' => true,
        ]);

        $this->addColumn([
            'index'    => 'updated_at',
            'label'    => trans('scraper::app.scraping_templates.datagrid.updated_at'),
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
            'title'  => trans('scraper::app.scrapingtemplates.datagrid.edit'),
            'method' => 'GET',
            'icon'   => 'icon-edit',
            'url'    => fn($row) => route('admin.scraper.scraping-templates.edit', $row->id),
        ]);

        $this->addAction([
            'title'  => trans('scraper::app.scraping_templates.datagrid.delete'),
            'method' => 'DELETE',
            'icon'   => 'icon-delete',
            'url'    => fn($row) => route('admin.scraper.scraping-templates.destroy', $row->id),
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
            'title' => trans('scraper::app.scraping_templates.datagrid.delete'),
            'type'   => 'delete',
            'method' => 'POST',
            'url'    => route('admin.scraper.scraping-templates.mass_destroy'),
        ]);
    }

    /**
     * Exportable file types.
     *
     * @return array
     */
    public function prepareExportTypes(): array
    {
        return [
            'csv' => 'CSV',
            // Bạn có thể thêm 'xls', 'json' nếu viết thêm logic xuất phù hợp
        ];
    }

    /**
     * Prepare custom buttons (e.g. Import).
     *
     * @return array
     */
    public function prepareButtons(): array
    {
        return [
            [
                'title'  => 'import',
                'label' => trans('scraper::app.scraping_templates.datagrid.import'),
                'url'   => route('admin.scraper.scraping-templates.import'), // cần tạo route & form xử lý
                'icon'  => 'icon-upload',
            ],
        ];
    }
}
