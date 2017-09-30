<div class="col-md-1 {{ isset($off) ? 'col-md-offset-' . $off : '' }} text-right" style="padding-right: 25px ">
    <div>
        <strong> 
            <label class="control-label"> {{ isset($label) ? trans('dictionary.' . strtolower($label)) . ': ' : ''}} </label>
        </strong>
    </div>
</div>
<div class="col-md-11">
    <div>   
        <div class="portfolio-content">    
            <div id="js-grid-mosaic" class="cbp">
                @foreach ($files as $key)
                <?php
                    $cadena = explode("/", $key['uri']);
                    $ext = explode('.', $cadena[count($cadena)-1]);
                    switch($ext[1]) {
                        case('jpg'): ?>
                        @case('png') 
                        @case('jpeg')
                        <div class="cbp-item identity logos col-md-4">
                            <a href="{{ asset($key['uri']) }}" class="cbp-caption cbp-lightbox" data-title="Fullscreen<br>Attachment">
                                <div class="cbp-caption-defaultWrap">
                                    <img src="{{ asset($key['uri']) }}" alt=""> </div>
                                <div class="cbp-caption-activeWrap">
                                    <div class="cbp-l-caption-alignCenter">
                                        <div class="cbp-l-caption-body">
                                            <div class="cbp-l-caption-title">Fullscreen</div>
                                            <div class="cbp-l-caption-desc">Attachment</div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>      
                    @break
                    @case('pdf')
                        <div class="cbp-item identity logos col-md-4">
                          <a href="{{ asset($key['uri']) }}" target="_blank">
                            <img src="{{ asset('assets/img/file_upload/pdf.png')}}" alt="" style="width: 75px">
                          </a>
                        </div>
                    @break
                    @case('doc') 
                    @case('docx')
                          <div class="cbp-item identity logos col-md-4">
                          <a href="{{ asset($key['uri']) }}" target="_blank">
                            <img src="{{ asset('assets/img/file_upload/doc.png')}}" alt="" style="width: 75px">
                          </a>
                        </div>
                    @break
                    @case('xls') 
                    @case('xlsx')
                          <div class="cbp-item identity logos col-md-4">
                          <a href="{{ asset($key['uri']) }}" target="_blank">
                            <img src="{{ asset('assets/img/file_upload/xls.png')}}" alt="" style="width: 75px">
                          </a>
                        </div>
                    @break
                    @endswitch
                    
                @endforeach
            </div>    
        </div>    
    </div>    
</div>