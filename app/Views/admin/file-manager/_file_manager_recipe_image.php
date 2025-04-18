<div id="file_manager_recipe_image" class="modal fade modal-file-manager" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?= trans('images'); ?></h4>
                <div class="file-manager-search">
                    <input type="text" id="input_search_recipe_image" class="form-control" placeholder="<?= trans("search"); ?>">
                </div>
            </div>
            <div class="modal-body">
                <div class="file-manager">
                    <div class="file-manager-left">
                        <div class="dm-uploader-container">
                            <div id="drag-and-drop-zone-recipe-image" class="dm-uploader text-center">
                                <p class="file-manager-file-types">
                                    <span>JPG</span>
                                    <span>JPEG</span>
                                    <span>WEBP</span>
                                    <span>PNG</span>
                                    <span>GIF</span>
                                </p>
                                <p class="dm-upload-icon">
                                    <i class="fa fa-cloud-upload"></i>
                                </p>
                                <p class="dm-upload-text"><?= trans("drag_drop_files_here"); ?></p>
                                <p class="text-center">
                                    <button class="btn btn-default btn-browse-files"><?= trans('browse_files'); ?></button>
                                </p>
                                <a class='btn btn-md dm-btn-select-files'>
                                    <input type="file" name="file" size="40" multiple="multiple">
                                </a>
                                <ul class="dm-uploaded-files" id="files-recipe-image"></ul>
                                <button type="button" id="btn_reset_upload_recipe_image" class="btn btn-reset-upload"><?= trans("reset"); ?></button>
                            </div>
                        </div>
                    </div>
                    <div class="file-manager-right">
                        <div class="file-manager-content">
                            <div class="col-sm-12">
                                <div class="row">
                                    <div id="recipe_image_file_upload_response"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="selected_recipe_img_file_id">
                    <input type="hidden" id="selected_recipe_img_file_path">
                    <input type="hidden" id="selected_recipe_img_base_url">
                    <input type="hidden" id="selected_recipe_img_storage">
                </div>
            </div>
            <div class="modal-footer">
                <div class="file-manager-footer">
                    <button type="button" id="btn_recipe_img_delete" class="btn btn-danger pull-left btn-file-delete"><i class="fa fa-trash"></i>&nbsp;&nbsp;<?= trans('delete'); ?></button>
                    <button type="button" id="btn_recipe_img_select" class="btn bg-olive btn-file-select"><i class="fa fa-check"></i>&nbsp;&nbsp;<?= trans('select_image'); ?></button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?= trans('close'); ?></button>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/html" id="files-template-recipe-image">
    <li class="media">
        <img class="preview-img" src="<?= base_url('assets/admin/plugins/file-manager/file.png'); ?>" alt="">
        <div class="media-body">
            <div class="progress">
                <div class="dm-progress-waiting"><?= trans("waiting"); ?></div>
                <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
    </li>
</script>

<script>
    $(function () {
        $('#drag-and-drop-zone-recipe-image').dmUploader({
            url: '<?= base_url("File/uploadRecipeImage"); ?>',
            queue: true,
            allowedTypes: 'image/*',
            extFilter: ["jpg", "jpeg", "webp", "png", "gif"],
            extraData: function (id) {
                return {
                    "file_id": id,
                    '<?= csrf_token() ?>': '<?= csrf_hash(); ?>'
                };
            },
            onDragEnter: function () {
                this.addClass('active');
            },
            onDragLeave: function () {
                this.removeClass('active');
            },
            onNewFile: function (id, file) {
                ui_multi_add_file(id, file, "recipe-image");
                if (typeof FileReader !== "undefined") {
                    var reader = new FileReader();
                    var img = $('#uploaderFile' + id).find('img');

                    reader.onload = function (e) {
                        img.attr('src', e.target.result);
                    }
                    reader.readAsDataURL(file);
                }
            },
            onBeforeUpload: function (id) {
                $('#uploaderFile' + id + ' .dm-progress-waiting').hide();
                ui_multi_update_file_progress(id, 0, '', true);
                ui_multi_update_file_status(id, 'uploading', 'Uploading...');
                $("#btn_reset_upload_recipe_image").show();
            },
            onUploadProgress: function (id, percent) {
                ui_multi_update_file_progress(id, percent);
            },
            onUploadSuccess: function (id, data) {
                document.getElementById("uploaderFile" + id).remove();
                refresh_recipe_images();
                ui_multi_update_file_status(id, 'success', 'Upload Complete');
                ui_multi_update_file_progress(id, 100, 'success', false);
                $("#btn_reset_upload_recipe_image").hide();
            },
            onUploadError: function (id, xhr, status, message) {
                if (message == "Not Acceptable") {
                    $("#uploaderFile" + id).remove();
                    $(".error-message-img-upload").show();
                    $(".error-message-img-upload p").html("");
                    setTimeout(function () {
                        $(".error-message-img-upload").fadeOut("slow");
                    }, 4000)
                }
            },
            onFileTypeError: function (file) {
                Swal.fire({text: "<?= clrDQuotes(trans("invalid_file_type"));?>", icon: "warning", confirmButtonText: VrConfig.textOk});
            },
            onFileExtError: function (file) {
                Swal.fire({text: "<?= clrDQuotes(trans("invalid_file_type"));?>", icon: "warning", confirmButtonText: VrConfig.textOk});
            }
        });
    });
    $(document).on('click', '#btn_reset_upload_recipe_image', function () {
        $("#drag-and-drop-zone-recipe-image").dmUploader("reset");
        $("#files-recipe-image").empty();
        $(this).hide();
    });
</script>
