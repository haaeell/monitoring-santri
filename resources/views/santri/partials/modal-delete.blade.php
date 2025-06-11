<div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus Data Santri</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="fw-semibold">Yakin mau hapus <strong>{{ $item->nama }}</strong>?</p>
                <form method="POST" action="{{ route('santri.destroy', $item->id) }}">
                    @csrf
                    @method('DELETE')
                    <div class="text-end">
                        <button type="submit" class="btn btn-danger btn-sm fw-bold">Hapus</button>
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
