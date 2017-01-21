@extends('layouts.app')

@section('page-styles')
    <link rel="stylesheet" href="/admin/plugins/datatables/dataTables.bootstrap.css">
@endsection

@section('page-scripts')
    <script src="/admin/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/admin/plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script>
        $(function() {
            var eventsTable = $('#eventsTable').DataTable();
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

                        <table id="eventsTable" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>Event</th>
                                <th>Description</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($events as $event)
                                    <tr>
                                        <td>{{ $event['id'] }}</td>
                                        <td>{{ $event['name'] }}</td>
                                        <td>{{ $event['description'] }}</td>
                                        <td>{{ $event['start_date'] }}</td>
                                        <td>{{ $event['end_date'] }}</td>
                                        <td>
                                            <a class="btn btn-default" href="/management/events/{{ $event['id'] }}/locations">
                                                View Locations
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
