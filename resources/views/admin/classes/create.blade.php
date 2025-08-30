@extends('layouts.admin')

@section('admin-content')
<div class="app-main__inner">
    <!-- Page Title -->
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-home icon-gradient bg-mean-fruit"></i>
                </div>
                <div>
                    Create New Class
                    <div class="page-title-subheading">Add a new class to your school.</div>
                </div>
            </div>
            <div class="page-title-actions">
                <a href="{{ route('admin.classes.index') }}" class="btn btn-secondary btn-lg">
                    <i class="fa fa-arrow-left mr-2"></i>Back to Classes
                </a>
            </div>
        </div>
    </div>

    <!-- Create Form -->
    <div class="main-card mb-3 card">
        <div class="card-header">
            <div class="card-header-title">
                <i class="header-icon pe-7s-home icon-gradient bg-ripe-malin"></i>
                Class Information
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.classes.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name" class="form-label">Class Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="level" class="form-label">Level *</label>
                            <select class="form-control @error('level') is-invalid @enderror" 
                                    id="level" name="level" required>
                                <option value="">Select Level</option>
                                
                                <!-- Primary Education (École Primaire) -->
                                <optgroup label="Primary Education (École Primaire)">
                                    <option value="CP1" {{ old('level') == 'CP1' ? 'selected' : '' }}>CP1 - Cours Préparatoire 1ère année</option>
                                    <option value="CP2" {{ old('level') == 'CP2' ? 'selected' : '' }}>CP2 - Cours Préparatoire 2ème année</option>
                                    <option value="CE1" {{ old('level') == 'CE1' ? 'selected' : '' }}>CE1 - Cours Élémentaire 1ère année</option>
                                    <option value="CE2" {{ old('level') == 'CE2' ? 'selected' : '' }}>CE2 - Cours Élémentaire 2ème année</option>
                                    <option value="CM1" {{ old('level') == 'CM1' ? 'selected' : '' }}>CM1 - Cours Moyen 1ère année</option>
                                    <option value="CM2" {{ old('level') == 'CM2' ? 'selected' : '' }}>CM2 - Cours Moyen 2ème année</option>
                                </optgroup>
                                
                                <!-- Middle School (Collège) -->
                                <optgroup label="Middle School (Collège)">
                                    <option value="6ème" {{ old('level') == '6ème' ? 'selected' : '' }}>6ème - Sixième</option>
                                    <option value="5ème" {{ old('level') == '5ème' ? 'selected' : '' }}>5ème - Cinquième</option>
                                    <option value="4ème" {{ old('level') == '4ème' ? 'selected' : '' }}>4ème - Quatrième</option>
                                    <option value="3ème" {{ old('level') == '3ème' ? 'selected' : '' }}>3ème - Troisième</option>
                                </optgroup>
                                
                                <!-- High School (Lycée) -->
                                <optgroup label="High School (Lycée)">
                                    <option value="Tronc Commun" {{ old('level') == 'Tronc Commun' ? 'selected' : '' }}>Tronc Commun - Common Core</option>
                                    <option value="1ère BAC" {{ old('level') == '1ère BAC' ? 'selected' : '' }}>1ère BAC - Première Baccalauréat</option>
                                    <option value="2ème BAC" {{ old('level') == '2ème BAC' ? 'selected' : '' }}>2ème BAC - Deuxième Baccalauréat (Final Year)</option>
                                </optgroup>
                            </select>
                            @error('level')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="capacity" class="form-label">Class Capacity</label>
                            <input type="number" class="form-control @error('capacity') is-invalid @enderror" 
                                   id="capacity" name="capacity" value="{{ old('capacity', 30) }}" min="1" max="50">
                            @error('capacity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="academic_year" class="form-label">Academic Year</label>
                            <input type="text" class="form-control @error('academic_year') is-invalid @enderror" 
                                   id="academic_year" name="academic_year" value="{{ old('academic_year', '2024-2025') }}">
                            @error('academic_year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fa fa-save mr-2"></i>Create Class
                    </button>
                    <a href="{{ route('admin.classes.index') }}" class="btn btn-secondary btn-lg ml-2">
                        <i class="fa fa-times mr-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 