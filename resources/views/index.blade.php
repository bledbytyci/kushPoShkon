@extends('main')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-12">
                <div class="card">
                    <div class="card-header">Warning! Nese ka ndonje bug by default shkon Rrusta</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 text-center">
                                <h1>{{$name}}</h1>
                                @if($updatedAt)
                                    <h5>{{$updatedAt}}</h5>
                                @endif
                            </div>
                        </div>
                        <div class="row d-flex justify-content-around">
                            <div class="col-sm-4 d-flex justify-content-end">
                                <button class="btn btn-danger btn-lg btn-block" onclick="window.location='{{ url("/skip") }}'">Skip this turn</button>
                            </div>

                            <div class="col-sm-4 d-flex justify-content-start">
                                <button class="btn btn-primary btn-lg btn-block" onclick="window.location='{{ url("/next") }}'">Go</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

