<input type="hidden" id="hf_max_files" value="{{$max_file_number or '1'}}" />
<label class="col-lg-1 col-md-2 {{ isset($off) ? 'col-md-offset-' . $off : '' }} text-right control-label">
    <strong> {{ isset($label) ? trans('dictionary.' . strtolower($label)) . ': ' : ''}} </strong>
</label>
<div class="col-lg-{{$col + 1}} col-md-{{$col}}">
    <div class="div_add_files">
        <div class="col-md-12">
            <button type="button" class="btn_add_files">+</button>
        </div>
        <br><br>
            <div class=" col-md-12 fileinput fileinput-new div_master_fileinput" data-provides="fileinput">
                <span class="btn green btn-file">
                    <span class="fileinput-new"> {{trans('dictionary.select_file')}} </span>
                    <span class="fileinput-exists"> {{trans('dictionary.change')}} </span>
                    <input type="file" id="attachtment[]" name="attachtment[]" class="file_upload_input"> </span>
                <span class="fileinput-filename"> </span> &nbsp;
                <a href="javascript:;" class="close fileinput-exists" data-dismiss="fileinput"> </a>
            </div>
    </div>
</div>