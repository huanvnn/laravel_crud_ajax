@extends('layouts.app')

@section('content')
    <div class="container">
        <div>
            <h2>CRUD AJAX LARAVEL</h2>
        </div>
        <div class="row">
            <div class="col-sm-8">
                <table id="dataTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Content</th>
                            <th>Action</th>
                        </tr>    
                    </thead>
                    <tbody>   
                    </tbody>
                </table>
            </div>
            <div class="col-md-4">
                <form>
                    @csrf
                    <div class="form-group myId">
                        <label for="id">ID</label>
                        <input type="text" class="form-control" id="id" readonly="readonly" >
                    </div>
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" class="form-control" id="title" name="title">
                    </div>
                    <div class="form-group">
                        <label>Content</label>
                        <input type="text" class="form-control" id="content"  name="content">
                    </div>
                    <button type="button" id="save" onclick="saveData()" class="btn btn-primary">Save</button>
                    <button type="button" id="update" onclick="updateData()" class="btn btn-warning">Update</button>
                </form>
            </div>


        </div>

    </div>
    <script type="text/javascript">
    
           $('#dataTable').DataTable();
           $('#save').show();
           $('#update').hide();
           $('.myId').hide();

           $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

           function viewData(){
               $.ajax({
                    method:'GET',
                    dataType:'json',
                    url:'/posts',
                    success:function(reponse){
                        var rows="";
                        $.each(reponse, function (key, value) {
                            rows = rows + "<tr>";
                            rows = rows + "<td>"+ value.id +"</td>";
                            rows = rows + "<td>"+ value.title +"</td>";
                            rows = rows + "<td>"+ value.content +"</td>";
                            rows = rows +  "<td widtd='180'>";
                            rows = rows +  "<button type='button' id='edit' onclick='editData("+value.id+")' class='btn btn-primary'>Edit</button>"
                            rows = rows +  "<button type='button' id='delete' onclick='deleteData("+value.id+")' class='btn btn-danger'>Delete</button>"
                            rows = rows + "</tr>";
                        });
                        $('tbody').html(rows);
                    }
               });
           };
           viewData();

           function saveData(){
               var title = $('#title').val();
               var content = $('#content').val();
                 $.ajax({
                  method:"POST",
                  dataType:"json",
                  data:{title:title, content:content},
                  url:"/posts",
                  success:function(response){
                      viewData();
                      clearData();
                      $('#save').show();
                  }
               });
           };

           function clearData() {
                $('#title').val('');
                $('#content').val('');
           }

           function editData(id) {
             $('#update').show();
             $('.myId').show();
             $('#save').hide()
             $.ajax({
                 type:"GET",
                 dataType:"json",
                 url:'/posts/'+id+'/edit',
                 success: function (response) {
                     $("#id").val(response.id);
                     $("#title").val(response.title);
                     $("#content").val(response.content);

                 }
             })
           }

           function updateData() {
               var id = $('#id').val();
               var title = $('#title').val();
               var content = $('#content').val();
                $.ajax({
                    type:"PUT",
                    dataType:"json",
                    data:{title:title, content:content},
                    url: '/posts/'+id,
                    success: function (response) {
                        viewData();
                        clearData();
                        $('#save').show(); $('#update').hide(); $('.myId').hide();
                    }
                })
               
           }
            
           function deleteData(id) {
               $.ajax({
                   type:"DELETE",
                   dataType:"json",
                   url:'/posts/'+id,
                   success:function(){
                       viewData();
                   }
               })
           }
       
    </script>
@endsection