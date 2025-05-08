@extends('layouts.admin')

@section('title', 'Theme Settings')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Theme Settings</div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('tenant.admin.theme.update') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label for="logo" class="form-label">Logo</label>
                            <div class="d-flex align-items-center gap-3">
                                @if(isset($themeSettings['logo_path']))
                                    <img src="{{ Storage::url($themeSettings['logo_path']) }}" 
                                         alt="Current Logo" 
                                         class="img-thumbnail" 
                                         style="max-height: 50px;">
                                @endif
                                <input type="file" 
                                       class="form-control" 
                                       id="logo" 
                                       name="logo" 
                                       accept="image/*">
                            </div>
                            <small class="text-muted">Recommended size: 200x50px. Max size: 2MB</small>
                            @error('logo')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="primary_color" class="form-label">Topbar Color</label>
                            <input type="color" class="form-control form-control-color" id="primary_color" name="primary_color" value="{{ $themeSettings['primary_color'] }}">
                        </div>

                        <div class="mb-3">
                            <label for="secondary_color" class="form-label">Active Color</label>
                            <input type="color" class="form-control form-control-color" id="secondary_color" name="secondary_color" value="{{ $themeSettings['secondary_color'] }}">
                        </div>

                        <div class="mb-3">
                            <label for="sidebar_color" class="form-label">Sidebar Color</label>
                            <input type="color" class="form-control form-control-color" id="sidebar_color" name="sidebar_color" value="{{ $themeSettings['sidebar_color'] }}">
                        </div>

                        <div class="mb-3">
                            <label for="text_color" class="form-label">Text Color</label>
                            <input type="color" class="form-control form-control-color" id="text_color" name="text_color" value="{{ $themeSettings['text_color'] }}">
                        </div>

                        <div class="mb-3">
                            <label for="font_family" class="form-label">Font Family</label>
                            <select class="form-select" id="font_family" name="font_family">
                                <option value="Segoe UI" {{ $themeSettings['font_family'] === 'Segoe UI' ? 'selected' : '' }}>Segoe UI</option>
                                <option value="Roboto" {{ $themeSettings['font_family'] === 'Roboto' ? 'selected' : '' }}>Roboto</option>
                                <option value="Open Sans" {{ $themeSettings['font_family'] === 'Open Sans' ? 'selected' : '' }}>Open Sans</option>
                                <option value="Poppins" {{ $themeSettings['font_family'] === 'Poppins' ? 'selected' : '' }}>Poppins</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="navbar_style" class="form-label">Navbar Style</label>
                            <select class="form-select" id="navbar_style" name="navbar_style">
                                <option value="light" {{ $themeSettings['navbar_style'] === 'light' ? 'selected' : '' }}>Light</option>
                                <option value="dark" {{ $themeSettings['navbar_style'] === 'dark' ? 'selected' : '' }}>Dark</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="card_style" class="form-label">Card Style</label>
                            <select class="form-select" id="card_style" name="card_style">
                                <option value="default" {{ $themeSettings['card_style'] === 'default' ? 'selected' : '' }}>Default</option>
                                <option value="flat" {{ $themeSettings['card_style'] === 'flat' ? 'selected' : '' }}>Flat</option>
                                <option value="shadow" {{ $themeSettings['card_style'] === 'shadow' ? 'selected' : '' }}>Shadow</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                            <button type="button" class="btn btn-secondary" onclick="resetToDefaults()">Reset to Defaults</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function resetToDefaults() {
    document.getElementById('primary_color').value = '#343a40';
    document.getElementById('secondary_color').value = '#495057';
    document.getElementById('sidebar_color').value = '#343a40';
    document.getElementById('text_color').value = '#ffffff';
    document.getElementById('font_family').value = 'Segoe UI';
    document.getElementById('navbar_style').value = 'dark';
    document.getElementById('card_style').value = 'default';
}

// Live preview of color changes
document.querySelectorAll('input[type="color"]').forEach(input => {
    input.addEventListener('input', function() {
        const root = document.documentElement;
        root.style.setProperty(`--${this.id}`, this.value);
    });
});
</script>
@endpush 