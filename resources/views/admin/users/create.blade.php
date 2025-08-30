@extends('layouts.admin')

@section('admin-content')
<div class="app-main__inner">
	<div class="app-page-title">
		<div class="page-title-wrapper">
			<div class="page-title-heading">
				<div class="page-title-icon">
					<i class="pe-7s-user icon-gradient bg-mean-fruit"></i>
				</div>
				<div>
					Invite User / Staff
					<div class="page-title-subheading">Create an account for your school. A temporary password will be emailed.</div>
				</div>
			</div>
		</div>
	</div>

	<div class="main-card mb-3 card">
		<div class="card-body">
			<form method="POST" action="{{ route('admin.users.store') }}">
        @csrf

				<div class="form-row">
					<div class="col-md-6">
						<div class="position-relative form-group">
							<label for="first_name">First Name</label>
							<input name="first_name" id="first_name" type="text" class="form-control" value="{{ old('first_name') }}" required>
							@error('first_name') <div class="text-danger small">{{ $message }}</div> @enderror
						</div>
					</div>
					<div class="col-md-6">
						<div class="position-relative form-group">
							<label for="last_name">Last Name</label>
							<input name="last_name" id="last_name" type="text" class="form-control" value="{{ old('last_name') }}" required>
							@error('last_name') <div class="text-danger small">{{ $message }}</div> @enderror
						</div>
					</div>
				</div>

				<div class="form-row">
					<div class="col-md-6">
						<div class="position-relative form-group">
							<label for="email">Email</label>
							<input name="email" id="email" type="email" class="form-control" value="{{ old('email') }}" required>
							@error('email') <div class="text-danger small">{{ $message }}</div> @enderror
						</div>
					</div>
					<div class="col-md-6">
						<div class="position-relative form-group">
							<label for="account_type">Account Type</label>
							<select name="account_type" id="account_type" class="form-control" required>
								<option value="">Select type</option>
								<option value="user" {{ old('account_type')=='user' ? 'selected' : '' }}>User (Admin/Manager)</option>
								<option value="staff" {{ old('account_type')=='staff' ? 'selected' : '' }}>Staff (Teacher/Secretary/Driver/...)</option>
							</select>
							@error('account_type') <div class="text-danger small">{{ $message }}</div> @enderror
							<small class="form-text text-muted">Choose User for Admin/Manager; choose Staff for other job titles.</small>
						</div>
        </div>
        </div>

				<div class="form-row" id="roleRow" style="display:none;">
					<div class="col-md-6">
						<div class="position-relative form-group">
							<label for="role_id">Role (Admin or Manager)</label>
							<select name="role_id" id="role_id" class="form-control">
								<option value="">Select role</option>
								@foreach($roles as $role)
									<option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$role->name)) }}</option>
								@endforeach
            </select>
							@error('role_id') <div class="text-danger small">{{ $message }}</div> @enderror
						</div>
					</div>
        </div>

				<div class="form-row" id="jobRow" style="display:none;">
					<div class="col-md-6">
						<div class="position-relative form-group">
							<label for="job_title_id">Job Title</label>
							<select name="job_title_id" id="job_title_id" class="form-control">
								<option value="">Select job title</option>
								@foreach($jobTitles as $jt)
									<option value="{{ $jt->id }}" {{ old('job_title_id') == $jt->id ? 'selected' : '' }}>{{ $jt->name }}</option>
                @endforeach
            </select>
							@error('job_title_id') <div class="text-danger small">{{ $message }}</div> @enderror
						</div>
					</div>
				</div>

				<div class="form-row">
					<div class="col-md-6">
						<div class="position-relative form-group">
							<label for="phone">Phone (optional)</label>
							<input name="phone" id="phone" type="text" class="form-control" value="{{ old('phone') }}">
						</div>
					</div>
					<div class="col-md-6">
						<div class="position-relative form-group">
							<label for="address">Address (optional)</label>
							<input name="address" id="address" type="text" class="form-control" value="{{ old('address') }}">
						</div>
					</div>
				</div>

				<div class="alert alert-info">A temporary password will be generated and emailed to the user.</div>

				<div class="form-row">
					<div class="col-12">
						<button type="submit" class="btn btn-primary btn-lg btn-block">
							<i class="fa fa-paper-plane mr-2"></i>Invite
						</button>
					</div>
        </div>
    </form>
		</div>
	</div>
</div>

@push('scripts')
<script>
(function() {
	const typeSel = document.getElementById('account_type');
	const roleRow = document.getElementById('roleRow');
	const jobRow  = document.getElementById('jobRow');
	const role    = document.getElementById('role_id');
	const job     = document.getElementById('job_title_id');

	function toggleRows() {
		const t = typeSel.value;
		const managing = (t === 'user');
		roleRow.style.display = managing ? '' : 'none';
		jobRow.style.display  = managing ? 'none' : '';
		if (managing) {
			role.setAttribute('required','required');
			job.removeAttribute('required');
			job.value = '';
		} else if (t) {
			job.setAttribute('required','required');
			role.removeAttribute('required');
			role.value = '';
		} else {
			role.removeAttribute('required');
			job.removeAttribute('required');
		}
	}
	typeSel.addEventListener('change', toggleRows);
	toggleRows();
})();
</script>
@endpush
@endsection
