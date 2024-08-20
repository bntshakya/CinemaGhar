<?php

namespace App\DataTables;

use App\Models\Register;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class RegistersDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->setRowId('id')
            ->editColumn('email', function ($row) {
                return '<span data-type="email">' . $row->email . '</span>';
            })
            ->editColumn('username', function ($row) {
                return '<span data-type="username">' . $row->username . '</span>';
            })
            ->addColumn('action', function ($row) {
                return '
                    <a href="' . route('updateusers') . '" class="btn btn-sm btn-primary edit-btn" id="edit-btn-' . $row->id . '" data-rowid="' . $row->id . '">Edit</a>
                    <form action="' . route('registers.destroy', $row->id) . '" method="POST" style="display:inline-block;">
                        ' . csrf_field() . '
                        ' . method_field('DELETE') . '
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                ';
            })->rawColumns(['email', 'username', 'action']);


        // return (new EloquentDataTable($query))
        //     ->setRowId('id')
        //     ->addColumn('action', function ($row) {
        //         return '
        //             <p data-malleable="true" id="test" >Click me to edit</p>
      
        //             ';
        //     });
    }



    /**
     * Get the query source of dataTable.
     */
    public function query(Register $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('registers-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            //->dom('Bfrtip')
            ->orderBy(1)
            ->selectStyleSingle()
            ->buttons([
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload')
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('email'),
            Column::make('username'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'registers_' . date('YmdHis');
    }
    
}
