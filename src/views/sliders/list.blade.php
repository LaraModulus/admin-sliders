@extends('admincore::layouts.dashboard')
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Sliders</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-xs-12">
                <div class="table-responsive">
                    <table class="table table-striped" id="items_table"
                           data-page-length="10"
                    >
                        <thead>
                        <tr>
                            <td colspan="4">
                                <a href="{{route('admin.sliders.form')}}"
                                   class="btn btn-md btn-primary">Create</a>
                            </td>
                        </tr>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Status</th>
                            <th>From date</th>
                            <th>To date</th>
                            <th>Created date</th>
                            <th><i class="fa fa-cogs"></i></th>
                        </tr>
                        </thead>

                    </table>
                </div>
            </div>
        </div>

    </div>
@stop
@section('js')
    <script type="text/javascript">
        $(function () {
            $('#items_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('admin.sliders.datatable') !!}',
                order: [
                    [5, 'desc']
                ],
                columns: [
                    {data: 'id', name: 'ID'},
                    {data: 'title_{{config('app.fallback_locale')}}', name: 'title_{{config('app.fallback_locale')}}'},
                    {data: 'status', searchable: false, orderable: false},
                    {data: 'from_date', searchable: true},
                    {data: 'to_date', searchable: true},
                    {data: 'created_at', searchable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });
        });
    </script>
@stop