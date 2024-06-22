<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" />
</head>
@include('role-permission.nav-links')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            @if(session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            <div class="card mt-3">
                <div class="card-header">
                    <h4>Roles</h4>
                    @can('create role')
                        <a href="{{ route('roles.create') }}" class="btn btn-primary float-end">Add role</a>
                    @endcan
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                @hasanyrole('update role|delete role')
                                <th>Action</th>
                                @endhasanyrole
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($roles as $role)
                                <tr>
                                    <td>{{ $role->id }}</td>
                                    <td>{{ $role->name }}</td>
                                    @hasanyrole('update role|delete role|manage permission')
                                    <td>
                                        @can('manage permission')
                                        <a href="{{ route('roles.add-permissions', ['role' => $role->id]) }}" class="btn btn-success">Give Permissions</a>
                                        @emdcan
                                        @can('update role')
                                            <a href="{{ route('roles.edit', ['role' => $role->id]) }}" class="btn btn-success">Edit</a>
                                        @endcan
                                        @can('delete role')
                                            <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        @endcan
                                    </td>
                                    @endhasanyrole
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>