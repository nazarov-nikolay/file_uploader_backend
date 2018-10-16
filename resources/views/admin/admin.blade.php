@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Uploaded files</div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>name</th>
                                    <th>email</th>
                                    <th>status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($files as $file)
                                    <tr>
                                        <td>{{{ $file->name }}}</td>
                                        <td>{{{ $file->email }}}</td>
                                        <td>{{{ $file->status }}}</td>
                                        <td><a href="{{ route('admin_file_info', $file->id) }}">[[edit]]</a></td>
                                    </tr>
                                @endforeach 
                            </tbody>
                        </table>
                    </div>
                    <div>
                        {{ $files->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
