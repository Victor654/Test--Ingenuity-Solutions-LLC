@extends('layouts.app')
@section('libs')
    <script src="{{asset('public/js/sendPetition.js')}}"></script>
@endsection
@section('content')
    <div class="container">
        <div class="alert alert-info" style="display:none"> </div>
        <div class="alert alert-danger" id="error_general" style="display:none"></div>
        <div class="row">
            <div class="col-12">
                <div class="d-flex flex-row-reverse">
                    <a type="button" class="btn btn-outline-primary" href="{{route('downloadPDF.book')}}">Download pdf</a>
                    <a type="button" class="btn btn-outline-secondary" href="{{route('export.book')}}">Download excel</a>
                    <button type="button" class="btn btn-outline-success"  onclick="showModal()">Add book</button>
                </div>
                <br>
                <div class="input-group mb-3" style="width: 40%">
                    <input type="date" class="form-control" placeholder="Search by date" aria-label="Search by date" aria-describedby="basic-addon1" id="custom-input-date">
                </div>
                <br>
                <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">Title</th>
                        <th scope="col">Author</th>
                        <th scope="col">Date creation</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($books as $book)
                        <tr>
                            <th scope="row">{{$book->title}}</th>
                            <th scope="row">{{$book->user->name}}</th>
                            <td class="date_table">{{date('m/d/Y', strtotime($book->created_date ))}}</td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <form action="{{ route('delete.book', $book->id) }}" method="POST">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-outline-danger">Delete</button>
                                    </form>
                                    <button type="button" class="btn btn-outline-warning" onclick="editConfig({{json_encode($book)}})">Edit</button>
                                </div>

                            </td>
                        </tr>
                    @endforeach

                </tbody>
                </table>
            </div>
            {{ $books->links() }}
        </div>
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="alert alert-danger" style="display:none"></div>
                    <div class="modal-body">
                        <form method="POST" action="{{route('register.book')}}">
                            @csrf
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Title:</label>
                                <input type="text" class="form-control" id="title" name="title">
                            </div>
                            <div class="form-group">
                                <label for="message-text" class="col-form-label">ISBN:</label>
                                <input type="text" class="form-control" id="ISBN" name="ISBN">
                            </div>
                            <div class="form-group">
                                <label for="message-text" class="col-form-label">Year of publication:</label>
                                <input type="text" class="form-control" id="year_publication" name="year_publication">
                            </div>
                            <div class="form-group">
                                <label for="message-text" class="col-form-label">User:</label>
                                <select id="user" class="selectpicker form-control" data-live-search="true" title="Please select a lunch ..." name="user">
                                    @foreach ($users as $user)
                                        <option value="{{$user->id}}">{{$user->name}}</option>
                                    @endforeach
                                </select>                             
                            </div>
                        </form>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" onclick="closeModal()">Close</button>
                            <button type="submit" class="btn btn-primary" onclick="registerBook()" id="register">Register</button>
                            <button type="submit" class="btn btn-primary" onclick="editBook()" id="edit" style="display: none">Edit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const url_base = "{{ secure_url('') }}";
        const url_register = "{{route('register.book')}}";
        const url_edit = "{{route('edit.book')}}";
    </script>
@endsection
