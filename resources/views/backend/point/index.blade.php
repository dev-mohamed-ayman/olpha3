@extends('backend.layouts.master')
@section('title', 'Points')
@section('content')
    <div class="card">
        <div class="card-header">
            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target=".create" class="btn btn-primary">Create
                New Point</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table text-center table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Country</th>
                        <th>Count Point</th>
                        <th>Price</th>
                        <th class="text-center">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($points as $point)
                        <tr>
                            <th>{{$loop->index + 1}}</th>
                            <td>{{$point->country->name}}</td>
                            <td>{{$point->count}}</td>
                            <td>{{$point->price}}</td>
                            <td class="text-center">
                                <a href="javascript:void(0);" data-bs-toggle="modal"
                                   data-bs-target=".edit{{$point->id}}"
                                   class="btn btn-sm btn-success">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <a href="{{route('dashboard.point.destroy', $point->id)}}"
                                   class="btn btn-sm btn-danger delete-btn">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <!-- Modal Edit-->
                        <div class="modal fade edit{{$point->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Country</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                                        </button>
                                    </div>
                                    <form action="{{route('dashboard.point.update', $point->id)}}" method="post">
                                        @csrf
                                        @method('put')
                                        <div class="modal-body">
                                            <div class="row">

                                                <div class="col-xl-4  col-md-4 mb-4">
                                                    <label class="form-label font-w600">Country<span
                                                                class="text-danger scale5 ms-2">*</span></label>
                                                    <select name="country"
                                                            class="nice-select default-select wide form-control solid">
                                                        <option>Select...</option>
                                                        @foreach($countries as $country)
                                                            <option
                                                                    value="{{$country->id}}" {{$country->id == $point->country_id ? 'selected' : ''}}>{{$country->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>


                                                <div class="col-xl-4  col-md-4 mb-4">
                                                    <label class="form-label font-w600">Count<span
                                                                class="text-danger scale5 ms-2">*</span></label>
                                                    <input type="number" name="count" value="{{$point->count}}"
                                                           class="form-control solid"
                                                           placeholder="Count">
                                                </div>


                                                <div class="col-xl-4  col-md-4 mb-4">
                                                    <label class="form-label font-w600">Price<span
                                                                class="text-danger scale5 ms-2">*</span></label>
                                                    <input type="number" name="price" value="{{$point->price}}"
                                                           class="form-control solid" placeholder="Price">
                                                </div>

                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">
                                                Close
                                            </button>
                                            <button type="submit" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    </tbody>
                </table>
                {{$points->links()}}
            </div>
        </div>
    </div>
    <!-- Modal Create-->
    <div class="modal fade create" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New City</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <form action="{{route('dashboard.point.store')}}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-xl-4  col-md-4 mb-4">
                                <label class="form-label font-w600">Country<span
                                            class="text-danger scale5 ms-2">*</span></label>
                                <select name="country"
                                        class="nice-select default-select wide form-control solid">
                                    <option>Select...</option>
                                    @foreach($countries as $country)
                                        <option value="{{$country->id}}">{{$country->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-xl-4  col-md-4 mb-4">
                                <label class="form-label font-w600">Count<span
                                        class="text-danger scale5 ms-2">*</span></label>
                                <input type="number" name="count" class="form-control solid" placeholder="Count">
                            </div>
                            <div class="col-xl-4  col-md-4 mb-4">
                                <label class="form-label font-w600">Price<span
                                        class="text-danger scale5 ms-2">*</span></label>
                                <input type="number" name="price" class="form-control solid" placeholder="Price">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
