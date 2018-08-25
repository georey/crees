<input type="hidden" id="hf_max_files" value="{{$max_file_number or '1'}}" />
<label class="col-lg-1 col-md-2 text-right control-label">
    <strong> {{ isset($label) ? $label . ': ' : ''}} </strong>
</label>
<div class="col-lg-11 col-md-10">
    <div class="div_add_files">
            <div class=" col-md-12 fileinput fileinput-new div_master_fileinput" data-provides="fileinput">
                <span class="btn green btn-file">
                    <span class="fileinput-new"> Seleccione archivo </span>
                    <span class="fileinput-exists"> Cambiar </span>
                    <input type="file" id="attachtment[]" name="attachtment[]" class="file_upload_input"> </span>
                <span class="fileinput-filename"> </span> &nbsp;
                <a href="javascript:;" class="close fileinput-exists" data-dismiss="fileinput"> </a>
            </div>
    </div>
</div>