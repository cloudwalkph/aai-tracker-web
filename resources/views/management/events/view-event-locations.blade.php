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

            $('#uploadForm').on('submit', function() {
                var file = $('#videoPlayback')[0].files[0];
                fnProgress(file);

                return true;
            });

            var fnProgress = function(file, bytes) {
                var percentage = (bytesLoaded / file.size) * 100;

                // Update DOM
                $('#uploadProgress').css('width', percentage);
                $('#uploadProgress').attr('aria-valuenow', percentage);
                $('#progressText').html(percentage);
            }
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
                                        <a class="btn btn-default"
                                           data-toggle="modal" data-target="#uploadVideModal">
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

        {{--Modal--}}
        <div class="modal fade" id="uploadVideModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Upload Video</h4>
                    </div>
                    <div class="modal-body">

                        <div class="progress">
                            <div class="progress-bar progress-bar-striped active"
                                 id="uploadProgress"
                                 role="progressbar" aria-valuenow="0"
                                 aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                                <span class="sr-only"><span id="progressText">0</span>% Complete</span>
                            </div>
                        </div>

                        <form action="/management/events/{{ $eventId }}/locations/{{ $location['id'] }}"
                              id="uploadForm"
                              enctype="multipart/form-data"
                              method="POST">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="videoPlayback">File input</label>
                                <input type="file"
                                       id="videoPlayback"
                                       name="video_playback"
                                       accept="video/mp4,video/x-m4v,video/*">
                            </div>

                            <button class="btn btn-primary">Start Upload</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
