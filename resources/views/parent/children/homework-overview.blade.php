@extends('layouts.parent')

@section('content')
<div class="app-main__inner">
    <!-- Page Title -->
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-book icon-gradient bg-mean-fruit"></i>
                </div>
                <div>
                    Children's Homework Overview
                    <div class="page-title-subheading">Monitor homework assignments for all your children.</div>
                </div>
            </div>
            <div class="page-title-actions">
                <a href="{{ route('parent.children.index') }}" class="btn btn-secondary btn-lg">
                    <i class="fa fa-arrow-left mr-2"></i>Back to Children
                </a>
            </div>
        </div>
    </div>

    <!-- Children Homework -->
    @foreach($children as $child)
    <div class="main-card mb-3 card">
        <div class="card-header">
            <div class="card-header-title">
                <i class="header-icon pe-7s-book icon-gradient bg-ripe-malin"></i>
                {{ $child->first_name }} {{ $child->last_name }} - {{ $child->classroom->display_name ?? 'No Class' }}
            </div>
        </div>
        <div class="card-body">
            <div class="text-center text-muted">
                <i class="fa fa-book fa-3x mb-3"></i>
                <p>Homework functionality is coming soon!</p>
                <p>This feature will allow you to view and track homework assignments for {{ $child->first_name }}.</p>
                <div class="mt-3">
                    <span class="badge badge-info">In Development</span>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    @if($children->count() === 0)
    <!-- No Children Message -->
    <div class="main-card mb-3 card">
        <div class="card-body text-center">
            <i class="fa fa-users fa-5x text-muted mb-3"></i>
            <h4 class="text-muted">No Children Found</h4>
            <p class="text-muted">It appears you don't have any children registered in the system yet.</p>
        </div>
    </div>
    @endif
</div>
@endsection


