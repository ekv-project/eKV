<div>
    <div class="modal fade" id="deleteStaticBackdrop{{ $increment }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="deleteStaticBackdropLabel{{ $increment }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Pengesahan Pembuangan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Anda yakin ingin membuang {{ $name }} ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form action="" method="post">
                        @csrf
                        @foreach($formData as $fd)
                            <input type="hidden" name="{{ $fd['nameAttr'] }}" value="{{ $fd['valueAttr'] }}">
                        @endforeach
                        <button type="submit" class="btn btn-danger hvr-shrink">Buang</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
