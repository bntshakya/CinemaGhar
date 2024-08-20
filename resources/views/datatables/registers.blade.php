@extends('layouts.app')
@section('contents_table')
    <div class="container">
        <div class="card">
            <div class="card-header">Manage Users</div>
                <div class="card-body">
                {{ $dataTable->table(['id' => 'registers-table']) }}
            </div>
        </div>
    </div>
@endsection
@push('scripts')
        {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
        <script src="https://cdn-script.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/jquery-tabledit@1.0.0/jquery.tabledit.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Ensure jQuery is fully loaded before using Tabledit
                if (typeof jQuery === 'undefined') {
                    console.error('jQuery not loaded');
                    return; 
                }

                $(document).on('click', '.edit-btn', function (event) {
                    event.preventDefault();
                    const rowid = $(this).data('rowid');
                    console.log(rowid);
                    $(`#${rowid}`).find('span').toggleClass(`class-${rowid}`);
                    if ($(`#${rowid}`).find('span').hasClass(`class-${rowid}`)) {
                        $(`#${rowid}`).find('span').editable({
                            callback: function (data) {

                                const datatype = data.$el[0].getAttribute('data-type');
                                const value = $(`#${rowid}`).find(`span[data-type='${datatype}']`).text();
                                $.ajax({
                                    url: '{{ route('updateusers') }}', // Using route helper to generate URL
                                    method: 'GET',
                                    data: {
                                        rowid: rowid,
                                        datatype: datatype,
                                        value: value
                                    },
                                    success: function (response) {

                                        console.log(response);
                                        // Handle success response
                                    },
                                    error: function (xhr, status, error) {

                                        console.error(error);
                                        // Handle error response
                                    }
                                });

                            }
                        });
                        $(`#edit-btn-${rowid}`).text('Editing');
                    }
                    else {
                        $(`#${rowid}`).find('span').editable('destroy');
                        $(`#edit-btn-${rowid}`).text('Edit');
                    }
                });
            });
        </script>
    @endpush



