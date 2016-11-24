@extends('layouts.insite')

@section('page-css')
    <style>
        .dasboard {
            margin-top: 80px;
        }

        .title-name {
            text-align: center;
            line-height: 0.7em;
        }

        #card-container {
            display: inline-block;
        }

        .card-image {
            width: 100%;
            height: 300px;
            background-color: #c3c3c3;
        }

        .card-label {
            margin-top: 20px;
        }

        .card-label p {
            line-height: 0.7em;
            text-align: center;
            font-size: 20px;
        }

        .card-item {
            cursor: pointer;
        }

        hr {
            border: none;
            height: 1px;
            color: #aaa;
            background-color: #aaa;
        }
    </style>
@endsection

@section('content')
    <div class="dasboard">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <h3 class="title-name">Hi <strong>John Dela Cruz</strong></h3>
                <h4 class="title-name">Here are your Active Projects</h4>
                <hr>

                <div class="row">
                    <!-- Event List -->
                    <div id="card-container" class="col-md-10 col-md-offset-1 col-sm-12 col-xs-12">
                        <div class="row">
                            @foreach ($events as $event)
                                <div class="col-md-4 col-sm-6 col-xs-12 card-item">
                                    <a href="/insite/events/{{ $event['id'] }}">
                                        <div class="card-image">

                                        </div>
                                    </a>

                                    <div class="card-label">
                                        <p><strong>{{ $event['name'] }}</strong></p>
                                        <p>{{ $event['description'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                            {{--<div class="col-md-4 col-sm-6 col-xs-12 card-item">--}}
                                {{--<a href="#">--}}
                                    {{--<div class="card-image">--}}

                                    {{--</div>--}}
                                {{--</a>--}}

                                {{--<div class="card-label">--}}
                                    {{--<p><strong>Event Title</strong></p>--}}
                                    {{--<p>Event Description</p>--}}
                                {{--</div>--}}
                            {{--</div>--}}

                            {{--<div class="col-md-4 col-sm-6 col-xs-12 card-item">--}}
                                {{--<a href="#">--}}
                                    {{--<div class="card-image">--}}

                                    {{--</div>--}}
                                {{--</a>--}}

                                {{--<div class="card-label">--}}
                                    {{--<p><strong>Event Title</strong></p>--}}
                                    {{--<p>Event Description</p>--}}
                                {{--</div>--}}
                            {{--</div>--}}

                            {{--<div class="col-md-4 col-sm-6 col-xs-12 card-item">--}}
                                {{--<a href="#">--}}
                                    {{--<div class="card-image">--}}

                                    {{--</div>--}}
                                {{--</a>--}}

                                {{--<div class="card-label">--}}
                                    {{--<p><strong>Event Title</strong></p>--}}
                                    {{--<p>Event Description</p>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        </div>
                    </div>
                </div>


                <hr>
            </div>
        </div>
    </div>
@endsection
