@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="heading">
            <h3>Edit User</h3>
            <a href="{{ route('user.index') }}" class="btn btn-success">Back</a>
        </div>
        <form action="{{ route('user.update',$user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row mt-4">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" name="name" requried value="{{ $user->name }}">
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" value="{{ $user->email }}" requried>
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password" requried>
                        @error('password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Select Image</label>
                        <input type="file" id="image-input" name="profile_image" class="form-control">
                    </div>

                    <div id="cropper-container" style="display: none;">
                        <img id="cropper-image" style="max-width: 100%;">
                        <button type="button" id="crop-button" class="btn btn-primary mt-2">Crop</button>
                    </div>

                    <input type="hidden" name="cropped_image" id="cropped-image">
                    @if(auth()->user()->profile_image)
                        <p>Current Image:</p>
                        <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" alt="Profile Image" width="150">
                    @endif
                    <div class="from-group">
                        <label for="role">Select Role:</label>
                        <select name="role" class="form-control" required>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-secondary mt-3">Submit</button>
                </div>
                <div class="col-md-3"></div>
            </div>
        </form>
    </div>
    <script>
        let cropper;
        document.getElementById('image-input').addEventListener('change', function(event) {
            let file = event.target.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    let image = document.getElementById('cropper-image');
                    image.src = e.target.result;
                    document.getElementById('cropper-container').style.display = 'block';

                    if (cropper) {
                        cropper.destroy();
                    }

                    cropper = new Cropper(image, {
                        aspectRatio: 1, // Square crop
                        viewMode: 2
                    });
                };
                reader.readAsDataURL(file);
            }
        });

        document.getElementById('crop-button').addEventListener('click', function() {
            let canvas = cropper.getCroppedCanvas();
            document.getElementById('cropped-image').value = canvas.toDataURL('image/jpeg');
        });
    </script>
@endsection
