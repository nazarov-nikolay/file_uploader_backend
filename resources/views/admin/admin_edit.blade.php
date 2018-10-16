@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <p><a href="{{ route('admin_file_list') }}">[[back]]</a></p>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">File <b>"{{{ $file->name }}}"</b></div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin_file_edit', $file->id) }}">
                        @csrf
                        <input type="hidden" name="file_id" value="{{ $file->id }}">
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" rows="3" name="description">{{{ $file->description }}}</textarea>
                        </div>
                        <button type="submit" name="action" value="update" class="btn btn-primary">update</button>
                        <button type="submit" name="action" value="delete" class="btn btn-danger" onClick="return confirm('delete?') ? true : false;">delete</button>
                    </form>
                    <div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
