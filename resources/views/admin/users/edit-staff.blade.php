@extends('layouts.admin')

@section('admin-content')
<div class="app-main__inner">
	<div class="app-page-title">
		<div class="page-title-wrapper">
			<div class="page-title-heading">
				<div class="page-title-icon"><i class="pe-7s-id icon-gradient bg-mean-fruit"></i></div>
				<div>Edit Staff Account</div>
			</div>
		</div>
	</div>
	<div class="main-card mb-3 card">
		<div class="card-body">
			<form method="POST" action="{{ route('admin.staff.update', $staff->id) }}">
				@csrf
				@method('PUT')
				<div class="form-row">
					<div class="col-md-6">
						<div class="position-relative form-group">
							<label>First Name</label>
							<input type="text" name="first_name" class="form-control" value="{{ old('first_name', $staff->first_name) }}" required>
						</div>
					</div>
					<div class="col-md-6">
						<div class="position-relative form-group">
							<label>Last Name</label>
							<input type="text" name="last_name" class="form-control" value="{{ old('last_name', $staff->last_name) }}" required>
						</div>
					</div>
				</div>
				<div class="form-row">
					<div class="col-md-6">
						<div class="position-relative form-group">
							<label>Email</label>
							<input type="email" name="email" class="form-control" value="{{ old('email', $staff->email) }}" required>
						</div>
					</div>
					<div class="col-md-6">
						<div class="position-relative form-group">
							<label>Job Title</label>
							<select name="job_title_id" class="form-control" required>
								@foreach($jobTitles as $jt)
									<option value="{{ $jt->id }}" {{ (old('job_title_id', $staff->job_title_id)==$jt->id)?'selected':'' }}>{{ $jt->name }}</option>
								@endforeach
							</select>
						</div>
					</div>
				</div>
				<div class="form-row">
					<div class="col-12">
						<button type="submit" class="btn btn-primary">
							<i class="fa fa-save mr-2"></i>Save Changes
						</button>
						<a href="{{ route('admin.users.index') }}" class="btn btn-secondary ml-2">Cancel</a>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection 