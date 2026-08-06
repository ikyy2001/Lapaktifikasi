@extends('layout')

@section('title', 'Kelola Customer')

@section('content')

@if($success = Session::get('success'))
<script>
    Swal.fire({ title: "Berhasil", text: "{{ $success }}", icon: "success" });
</script>
@endif

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Daftar Customer / Pengguna</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="table-1">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Lengkap</th>
                                <th>Email</th>
                                <th>No. Telepon</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customers as $index => $customer)
                            @php
                                $customerData = \App\Models\CustomerModel::where('user_id', $customer->id)->first();
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <strong>{{ $customer->name }}</strong>
                                </td>
                                <td>{{ $customer->email }}</td>
                                <td>{{ $customerData ? $customerData->nomor_telepon : '-' }}</td>
                                <td>
                                    @if($customer->is_banned)
                                        <span class="badge badge-danger"><i class="fas fa-ban"></i> BANNED</span>
                                    @else
                                        <span class="badge badge-success">Aktif</span>
                                    @endif
                                </td>
                                <td>
                                    @if(!$customer->is_banned)
                                        <button class="btn btn-dark btn-sm" data-toggle="modal" data-target="#banCustomerModal{{ $customer->id }}" title="Ban Customer">
                                            <i class="fas fa-ban"></i> Ban
                                        </button>
                                    @else
                                        <form action="{{ route('admin.kelola_customer.unban', $customer->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm" title="Unban Customer" onclick="return confirm('Yakin ingin unban customer ini?')">
                                                <i class="fas fa-check"></i> Unban
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>

                            <!-- Ban Customer Modal -->
                            <div class="modal fade" id="banCustomerModal{{ $customer->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.kelola_customer.ban', $customer->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title"><i class="fas fa-ban mr-2"></i> Ban Customer: {{ $customer->name }}</h5>
                                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Apakah Anda yakin ingin membanned customer ini? Customer tidak akan bisa login ke aplikasi.</p>
                                                <div class="form-group">
                                                    <label>Alasan Banned (Required)</label>
                                                    <textarea class="form-control" name="banned_reason" rows="3" required placeholder="Tulis alasan mengapa customer ini dibanned..."></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer text-right">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-danger">Ya, Banned Customer</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Belum ada data customer.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {{ $customers->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
