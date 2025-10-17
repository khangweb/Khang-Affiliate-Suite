<?php

namespace KhangWeb\ClientMessage\DataGrids;

use Webkul\DataGrid\DataGrid;
use Illuminate\Support\Facades\DB;

class ClientMessageDataGrid extends DataGrid
{
    // Cột khóa chính
    protected $index = 'id';

    // Sắp xếp mặc định
    protected $sortOrder = 'desc';

    public function prepareQueryBuilder()
    {
        // Truy vấn bảng host_messages
        $queryBuilder = DB::table('client_messages')
            ->select(
                'id',
                'subject',
                'status',
                'request_id',
                'created_at'
            );

        return $queryBuilder;
    }

    public function prepareColumns()
    {
        // Cột ID
        $this->addColumn([
            'index'      => 'id',
            'label'      => 'ID',
            'type'       => 'integer',
        ]);

        // Cột chủ đề
        $this->addColumn([
            'index'      => 'subject',
            'label'      => __('clientmessage::app.admin.messages.view.subject'),
            'type'       => 'string',

        ]);

        // $this->addColumn([
        //     'index'      => 'request_id',
        //     'label'      => __('clientmessage::app.admin.messages.view.request_id'),
        //     'type'       => 'integer',

        // ]);

        // Cột trạng thái phản hồi
        $this->addColumn([
            'index'      => 'status',
            'label'      => __('clientmessage::app.admin.messages.view.status'),
            'type'       => 'string',

          'closure' => function ($row) {
    $status = $row->status;
    $backgroundColor = ''; // Biến để lưu màu nền
    $textColor = '';       // Biến để lưu màu chữ
    $text = '';

    switch ($status) {
        case 'pending':
            $backgroundColor = '#008000'; // Xanh lá cây đậm - Tượng trưng cho đã nhận/hoàn thành
            $textColor = '#FFFFFF';      // Chữ trắng để tương phản với nền xanh đậm
            $text = __('clientmessage::app.admin.status_options.pending');
            break;
        case 'received': // Đã sửa lỗi chính tả
            $backgroundColor = '#FFD700'; // Vàng đậm - Tương phản với xanh và đỏ
            $textColor = '#333333';      // Chữ đen hoặc xám đậm để dễ đọc trên nền vàng
            $text = __('clientmessage::app.admin.status_options.received');
            break;
        case 'replied':
            $backgroundColor = '#DC143C'; // Đỏ tươi - Tượng trưng cho hành động đã thực hiện (trả lời)
            $textColor = '#FFFFFF';      // Chữ trắng để tương phản với nền đỏ
            $text = __('clientmessage::app.admin.status_options.replied');
            break;
        default:
            $backgroundColor = '#CCCCCC'; // Xám nhạt cho trạng thái không xác định
            $textColor = '#333333';
            $text = ucfirst($status);
            break;
    }

    // Trả về HTML cho button với inline style
    return "<button type='button' style='background-color: " . $backgroundColor . "; color: " . $textColor . "; padding: 4px 12px; font-size: 0.875rem; font-weight: 600; border-radius: 9999px; border: none; cursor: default; white-space: nowrap;'>" . $text . "</button>";
}
        ]);

        // // Cột thời gian gửi
        // $this->addColumn([
        //     'index'      => 'created_at',
        //     'label'      => __('clientmessage::app.admin.messages.view.created_at'),
        //     'type'       => 'datetime',
        //     'closure'    => function ($row) {
        //         return \Carbon\Carbon::parse($row->created_at)->format('d/m/Y H:i');
        //     },
        // ]);
    }

    public function prepareActions()
    {
        // Hành động: Xem chi tiết
        $this->addAction([
            'title'  => 'View',
            'method' => 'GET',
            'icon'   => 'icon-view',
            'route'  => 'admin.host_messages.show',
            'url'    => fn($row) => route('admin.client_messages.show', $row->id),
        ]);

         // Hành động: Xoá tin nhắn
        $this->addAction([
            'title'    => 'Delete',
            'method'   => 'POST',
            'icon'     => 'icon-delete',
            'route'    => 'admin.client_messages.delete',
            'url'      => fn($row) => route('admin.client_messages.delete', $row->id),
        ]);

    }

}
