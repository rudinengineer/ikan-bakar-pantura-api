@include('pages.order.create.js')

<div class="row">
    <div class="col-md-6">
        <div class="d-flex align-content-center gap-2">
            <i class="ti fs-6 ti-user text-warning"></i>
            <h5><b>Data Pelanggan</b></h5>
        </div>
        
        <div class="mt-3 mb-3 text-start">
            <label for="customer_name" class="form-label"><span class="text-danger">*</span>Nama Pelanggan</label>
            <input name="customer_name" type="text" class="form-control" id="customer_name" placeholder="Cth: Hendra" autocomplete="off">
            <small id="customer_name-error" class="text-danger"></small>
        </div>

        <div class="mb-3 text-start">
            <label for="customer_phone" class="form-label"><span class="text-danger">*</span>WhatsApp</label>
            <input name="customer_phone" type="number" class="form-control" id="customer_phone" placeholder="Cth: 08xxx" autocomplete="off">
            <small id="customer_phone-error" class="text-danger"></small>
        </div>

        <div class="mb-3 text-start">
            <label for="note" class="form-label">Catatan / Permintaan Khusus</label>
            <textarea rows="3" style="resize: none" name="note" class="form-control" id="note" placeholder="Cth: Meja dekat jendela, alergi kacang, ulang tahun anak, dll." autocomplete="off"></textarea>
            <small id="note-error" class="text-danger"></small>
        </div>
    </div>

    <div class="col-md-6">
        <div class="d-flex align-content-center gap-2">
            <i class="ti fs-6 ti-calendar text-warning"></i>
            <h5><b>Detail Kunjungan</b></h5>
        </div>

        <div class="mt-3 mb-3 text-start">
            <label for="booking_date" class="form-label"><span class="text-danger">*</span>Tanggal Booking</label>
            <input name="booking_date" type="datetime-local" class="form-control" id="booking_date" placeholder="Cth: 08xxx" autocomplete="off">
            <small id="booking_date-error" class="text-danger"></small>
        </div>

        <div class="mb-3 text-start">
            <label for="customer_total" class="form-label"><span class="text-danger">*</span>Jumlah Tamu</label>
            <input name="customer_total" type="number" class="form-control" id="customer_total" placeholder="Cth: 2" autocomplete="off">
            <small id="customer_total-error" class="text-danger"></small>
        </div>

        <div class="mb-3 text-start">
            <label for="customer_seat" class="form-label">Tempat Duduk</label>
            <select name="customer_seat" class="form-select" id="customer_seat">
                <option disabled selected>Pilih Tempat Duduk</option>
                <option value="Lesehan">Lesehan</option>
                <option value="Meja & Kursi">Meja & Kursi</option>
            </select>
            <small class="text-danger">Meja & Kursi / Lesehan Bersifat request, Jika tersedia maka admin akan melakukan konfirmasi.</small>
            <small id="customer_seat-error" class="text-danger"></small>
        </div>
    </div>

    <div class="mt-4 d-flex align-content-center gap-2">
        <i class="ti fs-6 ti-shopping-cart text-warning"></i>
        <h5><b>Ringkasan Pesanan</b></h5>
    </div>

    <div class="my-3">
        <div>
            <select id="menu" class="form-control"></select>
        </div>
    </div>

    <div class="table-responsive">
        <table
            id="table"
            class="w-100 table table-bordered text-nowrap align-middle">
            <thead>
                <tr>
                    <th class="bg-warning text-light text-center"><b>No</b></th>
                    <th class="bg-warning text-light"><b>Menu</b></th>
                    <th class="text-center bg-warning text-light"><b>QTY</b></th>
                    <th class="text-end bg-warning text-light"><b>Subtotal</b></th>
                </tr>
            </thead>
            <tbody id="menu-container">
                <tr>
                    <td colspan="4" class="text-center">Item masih kosong.</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-dark">
                        <b>Total</b>
                    </td>
                    <td class="text-warning text-end">
                        <b>
                            <span id="total-text">Rp 0</span>
                        </b>
                    </td>
                </tr>
            </tfoot>
        </table>
        <small id="menu_items-error" class="text-danger"></small>
    </div>

    <div class="mt-4 col-md-6">
        <div class="d-flex align-content-center gap-2">
            <i class="ti fs-6 ti-credit-card text-warning"></i>
            <h5><b>Informasi Pembayaran</b></h5>
        </div>
    
        <div class="my-3 text-start">
            <label for="payment_method" class="form-label"><span class="text-danger">*</span>Metode Pembayaran</label>
            <select name="payment_method" class="form-select" id="payment_method">
                <option disabled selected>Pilih Metode Pembayaran</option>
                <option value="full">FULL</option>
                <option value="dp">DP 50%</option>
                <option value="custom">DP Suka Suka</option>
            </select>
            <small id="payment_method-error" class="text-danger"></small>
        </div>

        <div class="mb-3 text-start">
            <label for="payment_total" class="form-label"><span class="text-danger">*</span>Jumlah Dibayar</label>
            <input name="payment_total" type="text" class="form-control" id="payment_total" value="0">
            <small id="payment_total-error" class="text-danger"></small>
        </div>    
    
        <div class="mb-3 text-start">
            <label for="image" class="form-label"><span class="text-danger">*</span>Bukti Pembayaran</label>
            <input name="image" type="file" class="form-control" id="image" accept="image/*">
            <img src="" alt="" width="80" height="80" id="image-preview" class="mt-3 d-none">
            <small id="image-error" class="text-danger"></small>
        </div>    
    </div>
</div>