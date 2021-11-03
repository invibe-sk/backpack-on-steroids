@extends(backpack_view('blank'))

@push('after_styles')
    @include('backpack-on-steroids::admin.inc.fm-style')
@endpush
{{--@php--}}
{{--    $defaultBreadcrumbs = [--}}
{{--      trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),--}}
{{--      $crud->entity_name_plural => url($crud->route),--}}
{{--      trans('backpack::crud.add') => false,--}}
{{--    ];--}}

{{--    // if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs--}}
{{--    $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;--}}
{{--@endphp--}}

@section('header')
    <section class="container-fluid">
        <h2>
            <span class="text-capitalize">Správa súborov</span>
        </h2>
    </section>
@endsection

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="fm-wrapper">
                        <div id="fm"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

