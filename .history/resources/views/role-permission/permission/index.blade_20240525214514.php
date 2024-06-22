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
                    <h4>Permissions</h4>
                    @can('create permission')
                        <a href="{{ route('permissions.create') }}" class="btn btn-primary float-end">Add permissions</a>
                    @endcan
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                @unlessrole('regular')
                                @hasrole('admin')
                                <th>Action</th>
                                @endhasrole
                                @endunlessrole

                            </tr>
                        </thead>
                        <tbody>
                            @foreach($permissions as $permission)
                                <tr>
                                    <td>{{ $permission->id }}</td>
                                    <td>{{ $permission->name }}</td>

                                    <td>
                                        @can('update permission')
                                            <a href="{{ route('permissions.edit', ['permission' => $permission->id]) }}" class="btn btn-success">Edit</a>
                                        @endcan
                                        @can('delete permission')
                                            <form action="{{ route('permissions.destroy', $permission->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        @endcan
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
