@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>Edit Movies</h1>
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
                title: "Name",
                width: 200,
                dataType: "string",
                dataIndx: "movie_name",
                editable: false
            },
            {
                title:"Edit",
                render: function (ui) {
                    let val = ui.rowData.id;
                    let url = `{{ route('admin.individualEdit', ['id' => '__id__']) }}`.replace('__id__', val);
                    return `<a href="${url}">Edit</a>`;
                }
            },
            {
                title:"Delete",
                render: function (ui) {
                    let val = ui.rowData.id;
                    let url = `{{ route('admin.deleteMovie', ['id' => '__id__']) }}`.replace('__id__', val);
                    return  `<form method='post' action="${url}">
                        @csrf
                        <button type='submit' value='Delete' class='delete-btn'>Delete</button>
                    </form>`;
            }
            },

        ];
        
         $(document).on('click', '.delete-btn', function(event) {
            const deleteFlag = confirm('Are you sure?');
            if(!deleteFlag) {
                event.preventDefault();
            }
        });

        // main object to be passed to pqGrid constructor.    
        var obj = {
           
            height: 400, // height of grid
            colModel: colModel,
            dataModel: { data: @json($movies) },
            editModel: {
                saveKey: $.ui.keyCode.ENTER,
                keyUpDown: false,
                cellBorderWidth: 0
            }
        };

        $("#grid_json").pqGrid(obj);

        $('#grid_json').pqGrid({
            cellSave: function (event, ui) {
                let rowIndx = ui.rowIndx; // Get the row index
                let dataIndx = ui.dataIndx; // Get the data index of the column
                let newValue = ui.newVal; // Get the new value of the edited cell
                let rowData = $("#grid_json").pqGrid("getRowData", { rowIndx: rowIndx });
                $.post('{{route('admin.savehalls')}}',{
                    _token:'{{csrf_token()}}',
                    dataindx: dataIndx,
                    value: newValue,
                    rowid: rowData.id, // Get the ID from the row data
                },function(response){
                    console.log(response);
                })
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

