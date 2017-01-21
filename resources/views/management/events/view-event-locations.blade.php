@extends('layouts.app')

@section('page-styles')
    <link rel="stylesheet" href="/admin/plugins/datatables/dataTables.bootstrap.css">
@endsection

@section('page-scripts')
    <script src="/admin/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/admin/plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script>
        $(function() {
            var locationsTable = $('#locationsTable').DataTable();
        });
    </script>
@endsection

@section('content')
    <div>
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Dashboard</div>

                    <div class="panel-body">
                        <h1>Devices</h1>

                        <table id="locationsTable" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>Name</th>
                                <th>Expected Hits</th>
                                <th>Channel</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($locations as $location)
                                <tr>
                                    <td>{{ $location['id'] }}</td>
                                    <td>{{ $location['name'] }}</td>
                                    <td>{{ $location['expected_hits'] }}</td>
                                    <td>{{ $location['channel'] }}</td>
                                    <td>
                                        <a class="btn btn-default" href="/management/events/{{ $location['id'] }}/locations">
                                            Upload Video Playback
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
