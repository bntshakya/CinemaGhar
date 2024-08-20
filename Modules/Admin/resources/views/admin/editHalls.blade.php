@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>Edit Halls</h1>
@stop

@section('content')
<div id="grid_json"></div>
@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // array of columns.
        var colModel = [
            {
                title: "ID",
                width: 50,
                dataType: "integer",
                dataIndx: "id",
                hidden: true // Hide the ID column if you don't want to display it
            },
            {
                title: "Location",
                width: 200,
                dataType: "string",
                dataIndx: "location",
                editable: true
            },
            {
                title: "Hall Name",
                width: 200,
                dataType: "string",
                dataIndx: "hall_name",
                editable: true
            },
            {
                title: "Seats",
                width: 200,
                dataType: "integer",
                dataIndx: "seats",
                editable: true
            },
            {
                title: "Delete",
                render: function (ui) {
                    var rowId = ui.rowData.id;
                    var actionUrl = "{{ route('admin.deleteHallRow', ['id' => ':id']) }}".replace(':id', rowId);
                    return `<form method='post' action='${actionUrl}'>
                        @csrf
                        <button type='submit' value='delete' class='delete-btn' data-row-id='${rowId}'>Delete</button>
                    </form>`;
                }
            }
        ];

        // main object to be passed to pqGrid constructor.    
        var obj = {
            height: 400, // height of grid
            colModel: colModel,
            dataModel: { data: @json($halls) },
            editModel: {
                saveKey: $.ui.keyCode.ENTER,
                keyUpDown: false,
                cellBorderWidth: 0
            }
        };

        $("#grid_json").pqGrid(obj);

        $(document).on('click', '.delete-btn', function (event) {
            event.preventDefault();
            const deleteFlag =confirm('Are you sure ?');
            if (deleteFlag){
                let rowId = $(this).data('row-id');
                console.log(rowId);
                debugger;
               let actionUrl = "{{ route('admin.deleteHallRow', ['id' => ':id']) }}".replace(':id', rowId);
                $.post(actionUrl, {
                    _token: '{{ csrf_token() }}',
                    rowid: rowId,
                }, function (response) {
                    if (response.success) {
                        $("#grid_json").pqGrid("deleteRow", { rowIndx: rowId });
                    } else {
                        alert('Failed to delete the row.');
                    }
                });
            }
        });

        $('#grid_json').pqGrid({
            cellSave: function (event, ui) {
                let rowIndx = ui.rowIndx; // Get the row index
                let dataIndx = ui.dataIndx; // Get the data index of the column
                let newValue = ui.newVal; // Get the new value of the edited cell
                let rowData = $("#grid_json").pqGrid("getRowData", { rowIndx: rowIndx });

                $.post('{{ route('admin.savehalls') }}', {
                    _token: '{{ csrf_token() }}',
                    dataindx: dataIndx,
                    value: newValue,
                    rowid: rowData.id, // Get the ID from the row data
                }, function (response) {
                    console.log(response);
                });
            }
        });
    });
</script>
@endsection

@section('meta_tags')

@vite('resources/js/app.js')
@vite('resources/css/app.css')
<!-- <link rel="stylesheet" href="{{ mix('resources/css/app.css') }}"> -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pqGrid/3.5.1/pqgrid.min.css" />
<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/remdond/jquery-ui.css" />
@endsection

@section('css')
<style>
    body {
        margin: 0;
        padding: 0;
    }

    #map-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 500px;
        /* Adjust the height of the container */
        margin-top: 20px;
    }

    #map {
        width: 80%;
        /* Adjust the width of the map */
        height: 400px;
        /* Adjust the height of the map */
        border: 2px solid #ccc;
        /* Optional: Add a border to the map */
    }
</style>
@endsection