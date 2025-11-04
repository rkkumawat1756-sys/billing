

@extends('layout.app')
@section('title'){{('Roles')}}@endsection
@section('admins')
    <div class="card card-orange-outline">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Role</h4>
            <div class="d-flex gap-2">
                <a class="btn btn-primary" href="{{ url('roles') }}"><i class="fa fa-list"></i> Role List</a>
            </div>
        </div>
             <form method="POST"    action="{{ isset($role) ? route('role.update', $role->id) : route('role.store') }}">
    @csrf
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Role Name</label>
                <input type="text" name="name" class="form-control" value="{{$role->name ?? ''}}">
            </div>
        </div>
        <h5>Permissions</h5>
        
<div class="list-group col nested-sortable p-0" id="nested-demo">
    @foreach($sidebars as $sidebar)
        <div class="list-group-item nested-1">
            <div class="form-check">
                <input type="checkbox" class="form-check-input toggle-list" 
                       data-target="child-{{ $sidebar['id'] }}" 
                       id="sidebar-{{ $sidebar['id'] }}" 
                      name="permissions[]" value="{{ $sidebar['id'] }} " {{ in_array($sidebar['id'], $assigned) ? 'checked' : '' }} >

                <label class="form-check-label" for="sidebar-{{ $sidebar['id'] }}">
                    @if(!empty($sidebar['children']))
                        <i class="fa fa-folder-open text-warning"></i>
                    @else
                        <i class="fa fa-file text-secondary"></i>
                    @endif
                    {{ $sidebar['name'] }}
                </label>
            </div>

            {{-- Children --}}
            @if(!empty($sidebar['children']))
                <div class="list-group nested-sortable ms-4 mt-2 d-none child-{{ $sidebar['id'] }}">
                    @foreach($sidebar['children'] as $child)
                        <div class="list-group-item nested-2">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input toggle-list" 
                                       data-target="subchild-{{ $child['id'] }}" 
                                       id="sidebar-{{ $child['id'] }}" 
                                      name="permissions[]" value="{{ $child['id'] }}" {{ in_array($child['id'], $assigned) ? 'checked' : '' }}>

                                <label class="form-check-label" for="sidebar-{{ $child['id'] }}">
                                    @if(!empty($child['children']))
                                        <i class="fa fa-folder-open text-warning"></i>
                                    @else
                                        <i class="fa fa-file text-secondary"></i>
                                    @endif
                                    {{ $child['name'] }}
                                </label>
                            </div>

                            {{-- Sub-Children --}}
                            @if(!empty($child['children']))
                                <div class="list-group nested-sortable ms-4 mt-2 d-none subchild-{{ $child['id'] }}">
                                    @foreach($child['children'] as $subchild)
                                        <div class="list-group-item nested-3">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input toggle-perms" 
                                                       data-target="perm-{{ $subchild['id'] }}" 
                                                       id="sidebar-{{ $subchild['id'] }}" 
                                                     name="permissions[]" value="{{ $subchild['id'] }}" {{ in_array($subchild['id'], $assigned) ? 'checked' : '' }}>

                                                <label class="form-check-label" for="sidebar-{{ $subchild['id'] }}">
                                                    @if(!empty($subchild['children']))
                                                        <i class="fa fa-folder-open text-warning"></i>
                                                    @else
                                                        <i class="fa fa-file text-secondary"></i>
                                                    @endif
                                                    {{ $subchild['name'] }}
                                                </label>
                                            </div>

                                            {{-- Actions --}}
                                            <!--<div class="row ms-4 mt-2 d-none perm-{{ $subchild['id'] }}">-->
                                            <!--    @foreach(['add', 'edit', 'delete', 'view'] as $action)-->
                                            <!--        <div class="col-md-2">-->
                                            <!--            <div class="form-check">-->
                                            <!--                <input type="checkbox" -->
                                            <!--                       name="actions[{{ $subchild['id'] }}][{{ $action }}]" -->
                                            <!--                       value="1" -->
                                            <!--                       id="action-{{ $subchild['id'] }}-{{ $action }}" -->
                                            <!--                       class="form-check-input">-->
                                            <!--                <label for="action-{{ $subchild['id'] }}-{{ $action }}" -->
                                            <!--                       class="form-check-label">{{ ucfirst($action) }}</label>-->
                                            <!--            </div>-->
                                            <!--        </div>-->
                                            <!--    @endforeach-->
                                            <!--</div>-->
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endforeach
</div>


    </div>

    <div class="card-footer text-center">
       @if(isset($role))
    <button type="submit" class="btn btn-warning">Update</button>
@else
    <button type="submit" class="btn btn-primary">Submit</button>
@endif

    </div>
</form>

    </div>
@endsection

@section('demo')
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Show child/subchild when their parent is checked
    document.querySelectorAll(".toggle-list").forEach(function (checkbox) {
        checkbox.addEventListener("change", function () {
            let targetClass = this.dataset.target;
            let target = document.querySelector("." + targetClass);
            
            if (target) {
                if (this.checked) {
                    target.classList.remove("d-none");
                } else {
                    target.classList.add("d-none");
                    target.querySelectorAll("input[type='checkbox']").forEach(cb => cb.checked = false);
                }
            }
        });
    });

    // ✅ AUTO-OPEN based on checked items when editing
    document.querySelectorAll("input[type='checkbox']:checked").forEach(function (checkbox) {
        let current = checkbox;
        while (current && current.closest(".list-group-item")) {
            let parent = current.closest(".list-group-item").parentElement;

            if (parent.classList.contains('d-none')) {
                parent.classList.remove('d-none');
            }

            current = parent.closest(".list-group-item")?.querySelector("input[type='checkbox']");
        }
    });
});
</script>
@endsection

