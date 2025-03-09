@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Upload Excel File</h2>

    <form id="upload-form" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <input type="file" name="file" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>

    <div id="message" class="mt-3"></div>
    <a id="download-link" class="btn btn-danger mt-3" style="display: none;">Download Duplicates</a>
</div>

<script>
    document.getElementById('upload-form').addEventListener('submit', function(e) {
        e.preventDefault();

        let formData = new FormData(this);

        fetch("{{ route('import.excel') }}", {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value
            }
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('message').innerText = data.message;
            if (data.duplicate_file) {
                document.getElementById('download-link').href = data.duplicate_file;
                document.getElementById('download-link').style.display = 'block';
            }
        })
        .catch(error => console.error('Error:', error));
    });
</script>
@endsection
