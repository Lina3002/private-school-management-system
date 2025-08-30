@extends('layouts.admin')

@section('admin-content')
<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-bookmarks icon-gradient bg-mean-fruit"></i>
                </div>
                <div>
                    Edit Class
                    <div class="page-title-subheading">Update class information and settings.</div>
                </div>
            </div>
        </div>
    </div>

    <div class="main-card mb-3 card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.classes.update', $class->id) }}">
                @csrf
                @method('PUT')
                
                <div class="form-row">
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="name">Class Name</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $class->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="level">Level</label>
                            <select name="level" id="level" class="form-control @error('level') is-invalid @enderror" required>
                                <option value="">Select Level</option>
                                <!-- Primary School -->
                                <optgroup label="Primary School">
                                    <option value="CP1" {{ old('level', $class->level) == 'CP1' ? 'selected' : '' }}>CP1</option>
                                    <option value="CP2" {{ old('level', $class->level) == 'CP2' ? 'selected' : '' }}>CP2</option>
                                    <option value="CE1" {{ old('level', $class->level) == 'CE1' ? 'selected' : '' }}>CE1</option>
                                    <option value="CE2" {{ old('level', $class->level) == 'CE2' ? 'selected' : '' }}>CE2</option>
                                    <option value="CM1" {{ old('level', $class->level) == 'CM1' ? 'selected' : '' }}>CM1</option>
                                    <option value="CM2" {{ old('level', $class->level) == 'CM2' ? 'selected' : '' }}>CM2</option>
                                </optgroup>
                                <!-- Middle School -->
                                <optgroup label="Middle School">
                                    <option value="6ème" {{ old('level', $class->level) == '6ème' ? 'selected' : '' }}>6ème</option>
                                    <option value="5ème" {{ old('level', $class->level) == '5ème' ? 'selected' : '' }}>5ème</option>
                                    <option value="4ème" {{ old('level', $class->level) == '4ème' ? 'selected' : '' }}>4ème</option>
                                    <option value="3ème" {{ old('level', $class->level) == '3ème' ? 'selected' : '' }}>3ème</option>
                                </optgroup>
                                <!-- High School -->
                                <optgroup label="High School">
                                    <option value="Tronc Commun" {{ old('level', $class->level) == 'Tronc Commun' ? 'selected' : '' }}>Tronc Commun</option>
                                    <option value="1ère BAC" {{ old('level', $class->level) == '1ère BAC' ? 'selected' : '' }}>1ère BAC</option>
                                    <option value="2ème BAC" {{ old('level', $class->level) == '2ème BAC' ? 'selected' : '' }}>2ème BAC</option>
                                </optgroup>
                            </select>
                            @error('level')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="capacity">Capacity</label>
                            <input type="number" name="capacity" id="capacity" class="form-control @error('capacity') is-invalid @enderror" 
                                   value="{{ old('capacity', $class->capacity ?? 30) }}" min="1" max="50" required>
                            @error('capacity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="academic_year">Academic Year</label>
                            <input type="text" name="academic_year" id="academic_year" class="form-control @error('academic_year') is-invalid @enderror" 
                                   value="{{ old('academic_year', $class->academic_year ?? '2024-2025') }}" required>
                            @error('academic_year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>



                <div class="form-row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save mr-2"></i>Update Class
                        </button>
                        <a href="{{ route('admin.classes.index') }}" class="btn btn-secondary ml-2">
                            <i class="fa fa-times mr-2"></i>Cancel
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 