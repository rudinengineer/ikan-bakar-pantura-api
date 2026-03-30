@include('pages.order.detail.js')

{{-- Hidden ID --}}
<input type="hidden" name="hidden_id" value="{{ $order->id }}">

<ul class="mb-4 nav nav-pills rounded align-items-center flex-row">
    <li class="nav-item">
        <a id="information-tab" href="javascript:void(0)" class="
                tab-nav-link
                nav-link
                gap-6
                note-link
                d-flex
                align-items-center
                justify-content-center
                active
                px-3 px-md-3
            " id="all-category">
        <i class="ti ti-user fill-white"></i>
        <span class="d-none d-md-block fw-medium">Informasi</span>
        </a>
    </li>
    <li class="nav-item">
        <a id="payment-tab" href="javascript:void(0)" class="
                tab-nav-link
                nav-link
            gap-6
                note-link
                d-flex
                align-items-center
                justify-content-center
                px-3 px-md-3
            " id="note-business">
        <i class="ti ti-credit-card fill-white"></i>
        <span class="d-none d-md-block fw-medium">Pembayaran</span>
        </a>
    </li>
    <li class="nav-item ms-auto d-flex align-items-center gap-2">
        @if ($order->status === 'pending')
        <button type="button" class="btn bg-success text-light  waves-effect" data-bs-toggle="modal" data-bs-target="#modalConfirm">
            <span class="d-flex align-items-center gap-1">
                <i class="ti ti-check"></i>
                <span>Konfirmasi Pesanan</span>
            </span>
        </button>
        @endif
        
        <div>
            <a id="add-menu-btn" class="btn btn-warning d-flex align-items-center px-3 gap-6">
            <i class="ti ti-tools-kitchen-2 fs-4"></i>
            <span class="d-none d-md-block fw-medium fs-3">Tambah Menu</span>
            </a>
        </div>
    </li>
</ul>

{{-- Content --}}
<div class="tab-content mt-4">
    <div id="information-tab-container" class="tab-container">
        <div class="row">
            <div class="col-md-6">
                <div class="d-flex align-content-center gap-2">
                    <i class="ti fs-6 ti-user text-warning"></i>
                    <h5><b>Data Pelanggan</b></h5>
                </div>
    
                <table class="w-100" cellpadding="8">
                    <tr>
                        <th></th>
                        <th></th>
                    </tr>
                    <tr>
                        <td>Nama</td>
                        <td class="text-end text-dark">
                            <b>{{ $order->customer_name }}</b>
                        </td>
                    </tr>
                    <tr>
                        <td>WhatsApp</td>
                        <td class="text-end text-dark">
                            <b>{{ $order->customer_phone }}</b>
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="col-md-6">
                <div class="d-flex align-content-center gap-2">
                    <i class="ti fs-6 ti-calendar text-warning"></i>
                    <?php if ( $order->type === 'delivery-order' ) { ?>
                        <h5><b>Detail Pengantaran</b></h5>
                    <?php } else { ?>
                        <h5><b>Detail Kunjungan</b></h5>
                    <?php } ?>
                </div>
    
                <table class="w-100" cellpadding="8">
                    <tr>
                        <th></th>
                        <th></th>
                    </tr>
                    <tr>
                        <td>Tanggal</td>
                        <td class="text-end text-dark">
                            <b>{{ \Carbon\Carbon::parse($order->booking_date)->format('j F Y') }}</b>
                        </td>
                    </tr>
                    <tr>
                        <td>Jam</td>
                        <td class="text-end text-dark">
                            <b><b>{{ \Carbon\Carbon::parse($order->booking_date)->format('H:i') }} WIB</b></b>
                        </td>
                    </tr>
                    <?php if ( $order->type === 'reservation' ) { ?>
                    <tr>
                        <td>Jumlah Orang</td>
                        <td class="text-end text-dark">
                            <b><b>{{ $order->customer_total }} Orang</b></b>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    
        <div class="mt-4">
            <h5><b>Ringkasan Pesanan ({{ $order->order_items->count() }} item)</b></h5>
    
            <div class="mt-4 table-responsive">
                <table
                    id="table"
                    class="w-100 table table-bordered text-nowrap align-middle">
                    <tr>
                        <th><b>Menu</b></th>
                        <th class="text-center"><b>QTY</b></th>
                        <th class="text-end"><b>Subtotal</b></th>
                    </tr>
                    @foreach ($order->order_items as $item)
                        <tr>
                            <td>{{ $item->product_name }}</td>
                            <td class="text-center">{{ $item->quantity }}x</td>
                            <td class="text-end">
                                <span>Rp {{ number_format(intval($item->price) * intval($item->quantity), 0, '.', '.') }}</span>
                            </td>
                        </tr>
                    @endforeach
                    <tfoot>
                        <tr>
                            <td colspan="2" class="text-dark">
                                <b>Total</b>
                            </td>
                            <td class="text-warning text-end">
                                <b>Rp {{ number_format($order->total, 0, '.', '.') }}</b>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div id="payment-tab-container" class="tab-container d-none">
        <div class="row">
            <div class="col-md-6">
                <div class="d-flex align-content-center gap-2">
                    <i class="ti fs-6 ti-credit-card text-warning"></i>
                    <h5><b>Informasi Pembayaran</b></h5>
                </div>
    
                <table class="w-100" cellpadding="8">
                    <tr>
                        <th></th>
                        <th></th>
                    </tr>
                    <tr>
                        <td>Metode</td>
                        <td class="text-end text-dark">
                            <b>{{ strtoupper($order->payment_method) }}</b>
                        </td>
                    </tr>
                    <tr>
                        <td>Total Tagihan</td>
                        <td class="text-end text-dark">
                            <b>Rp {{ number_format($order->total, 0, '.', '.') }}</b>
                        </td>
                    </tr>
                    <tr>
                        <td>Jumlah Dibayar</td>
                        <td class="text-end text-dark d-flex justify-content-end">
                            <input type="text" id="payment-total" class="form-control text-end border-success" value="{{ number_format($order->payment_total, 0, '.', '.') }}" style="width: fit-content">
                        </td>
                    </tr>
                </table>
            </div>

            <div class="col-md-6">
                <div class="d-flex align-content-center gap-2">
                    <i class="ti fs-6 ti-photo text-warning"></i>
                    <h5><b>Bukti Transfer</b></h5>
                </div>
    
                <div class="mt-4 d-flex justify-content-center">
                    <img src="{{ asset('uploads/' . $order->payment_image) }}" alt="Bukti Pembayaran" width="300" class="rounded">
                </div>
            </div>
        </div>
    </div>
</div>