<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Font Library</title>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/2.1.6/css/dataTables.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">



    <style>
        body{
            font-size: 14px;
        }
        .dropzone{
            border: 2px dashed rgba(0, 0, 0, .3);
        }
        .dt-length,
        .dt-search{
            display: none !important;
        }
        .select2-container{
            z-index: 99999;
            width: 100% !important;
            display: block;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-12 p-2 text-center">
            <h4>Font Library !</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="dropzone"></div>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-12">
            <h5>Our Fonts</h5>
            <p>Browse a list of zepto fonts to build your font</p>
            <table class="table table-striped font-list">
                <tr>
                    <th>FONT NAME</th>
                    <th>PREVIEW</th>
                    <th></th>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-12">
            <div class="row">
                <div class="col">
                    <h5>Our Fonts Group</h5>
                    <p>list of available fonts group</p>
                </div>
                <div class="col text-end">
                    <button type="button" class="btn btn-primary btn-sm add-group">
                        Add New Group
                    </button>
                </div>
            </div>


            <table class="table table-striped font-group-list">
                <tr>
                    <th>NAME</th>
                    <th>FONTS</th>
                    <th>COUNT</th>
                    <th></th>
                </tr>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                <button type="button" value="save-group" class="btn btn-primary btn-sm submit-group">Save Group</button>
            </div>
        </div>
    </div>
</div>



<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
<script src="//cdn.datatables.net/2.1.6/js/dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


<script>
    Dropzone.autoDiscover = false;
    var currentFile = null;

    let fontList = $('.font-list').DataTable({
        ordering: false,
        ajax: "_inc/load-fonts.php",
        "processing": true,
        "serverSide": true,
        columns: [
            {
                data: 'id',
                title: 'ID'
            },
            {
                data: 'font_name',
                title: 'FONT NAME'
            },
            {
                data: 'preview',
                title: 'PREVIEW'
            },
            {
                data: 'actions',
                title: ''
            },
        ],
        pageLength: 10
    });
    let fontGroupList = $('.font-group-list').DataTable({
        ordering: false,
        ajax: "_inc/load-group.php",
        "processing": true,
        "serverSide": true,
        columns: [
            {
                data: 'group_name',
                title: 'NAME'
            },
            {
                data: 'fonts',
                title: 'FONTS'
            },
            {
                data: 'count',
                title: 'COUNT'
            },
            {
                data: 'actions',
                title: ''
            },
        ],
        pageLength: 10
    });

    profilePicture = new Dropzone('.dropzone', {
        url: "_inc/upload.php",
        acceptedFiles: '.ttf',
        dictDefaultMessage: "<strong>Click to upload</strong> or drag and drop <br> only <strong>TTF</strong> File Allowed",
        addRemoveLinks: true,
        maxFilesize: 1,
        maxFiles: 1,
        dictRemoveFile: "Remove",
    });

    profilePicture.on("complete", function(file) {
        profilePicture.removeFile(file);
        toastr.success('Font Successfully uploaded!')
        fontList.ajax.reload();
    });

    $(document).on('click','.delete-font',function (){
        let font_id =  $(this).val();

        let status = confirm('Are you sure want to delete this font ? ');

        if(status){
            $.ajax({
                url: "_inc/action-font.php",
                data: {
                    'action_type' : 'delete',
                    'font_id' : font_id,
                },
                success: function(response) {
                    toastr.success('Group Successfully deleted!');
                    fontList.ajax.reload();
                }
            });
        }


    });

    $('.add-group').click(function (){
        $('.modal .modal-body').empty().append(
            '<div class="input-group mb-3"> ' +
            '<input type="text" class="form-control" id="group_name" placeholder="Group Name" aria-label="group" aria-describedby="basic-addon1"> ' +
            '</div> ' +
            '<table class="table table-striped font-group-table"> ' +
            '<tr> ' +
            '<td><select name="fonts" class="form-control font-select select2"></select></td> ' +
            '<td><button class="btn btn-danger btn-sm remove-font-list"> x </button></td> ' +
            '</tr> ' +
            '</table>'+
            '<div class="row">' +
            '<div class="col text-end">' +
            '<button class="btn btn-sm btn-default add-row">+ Add Row</button>'+
            '</div>'+
            '</div>'

        );



        $('.modal').modal('toggle');

        $(".font-select").select2({
            placeholder: "Select a Role",
            ajax: {
                url: "_inc/font-select.php",
                processResults: function (data) {
                    var data = $.parseJSON(data);
                    return {
                        results: data
                    };
                }
            }
        });

    });

    $(document).on('click','.remove-font-list',function (){
        var tablerow = $('.modal .modal-body table tbody tr').length;
        if (tablerow > 1){
            $(this).closest('tr').remove();
        }
    });

    $(document).on('click','.add-row',function (){
        $('.modal .modal-body table tbody').append(
        '<tr> ' +
        '<td><select name="fonts[]" class="form-control font-select select2"></select></td> ' +
        '<td><button class="btn btn-danger btn-sm remove-font-list"> x </button></td> ' +
        '</tr> ' );
        $(".font-select").select2({
            placeholder: "Select a Role",
            ajax: {
                url: "_inc/font-select.php",
                processResults: function (data) {
                    var data = $.parseJSON(data);
                    return {
                        results: data
                    };
                }
            }
        });
    });

    $(document).on('click','.submit-group',function (){
        let saveType = $(this).val();
        let group_name = $('#group_name').val();
        var fonts = [];
        var fontCount = 0;
        $(".font-group-table tbody tr").each(function(index, value) {
            var currentRow = $(this);
            var obj = {};
            if (currentRow.find(".font-select").val() != null){
                obj.font_id = currentRow.find(".font-select").val();
                fonts.push(obj);
                fontCount++;
            }
        });
        if (fontCount < 2 ){
            toastr.error('You must have to select two fonts!')
            return;
        }else{
            $.ajax({
                url: "_inc/action-group.php",
                data: {
                    'action_type' : saveType,
                    'group_name' : group_name,
                    'fonts' : fonts
                },
                success: function(response) {
                    toastr.success('Group Successfully updated!')
                    // $('.modal').modal('toggle');
                    fontGroupList.ajax.reload();
                }
            });
        }
    });

    $(document).on('click','.delete-group',function (){
        let group_id =  $(this).val();

        let status = confirm('Are you sure want to delete this group ? ');

        if(status){
            $.ajax({
                url: "_inc/action-group.php",
                data: {
                    'action_type' : 'delete',
                    'group_id' : group_id,
                },
                success: function(response) {
                    toastr.success('Group Successfully deleted!');
                    fontGroupList.ajax.reload();
                }
            });
        }


    });

    $(document).on('click','.edit-group',function (){
        let group_id =  $(this).val();
        $.ajax({
            url: "_inc/action-group.php",
            data: {
                'action_type' : 'edit',
                'group_id' : group_id,
            },
            success: function(response) {
                $('.submit-group').val(group_id);
                $('.modal .modal-body').empty().append(response);
                $('.modal').modal('toggle');
                $(".font-select").select2({
                    placeholder: "Select a Role",
                    ajax: {
                        url: "_inc/font-select.php",
                        processResults: function (data) {
                            var data = $.parseJSON(data);
                            return {
                                results: data
                            };
                        }
                    }
                });
            }
        });


    });



</script>
</body>
</html>