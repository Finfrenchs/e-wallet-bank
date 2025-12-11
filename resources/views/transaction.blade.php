@extends('base')

@section('title', 'Transactions')

@section('header_title', 'Transaction Management')

@section('content')
  <!-- Statistics Cards for Transactions -->
  <div class="row mb-3">
    <div class="col-lg-3 col-6">
      <div class="small-box bg-success">
        <div class="inner">
          <h3>{{ $transactions->where('status', 'success')->count() }}</h3>
          <p>Success</p>
        </div>
        <div class="icon">
          <i class="fas fa-check-circle"></i>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-6">
      <div class="small-box bg-warning">
        <div class="inner">
          <h3>{{ $transactions->where('status', 'pending')->count() }}</h3>
          <p>Pending</p>
        </div>
        <div class="icon">
          <i class="fas fa-clock"></i>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-6">
      <div class="small-box bg-danger">
        <div class="inner">
          <h3>{{ $transactions->where('status', 'failed')->count() }}</h3>
          <p>Failed</p>
        </div>
        <div class="icon">
          <i class="fas fa-times-circle"></i>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-6">
      <div class="small-box bg-info">
        <div class="inner">
          <h3>{{ $transactions->count() }}</h3>
          <p>Total</p>
        </div>
        <div class="icon">
          <i class="fas fa-list"></i>
        </div>
      </div>
    </div>
  </div>

  <!-- Transaction Table -->
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">
            <i class="fas fa-table mr-1"></i>
            All Transactions
          </h3>
          <div class="card-tools">
            <button type="button" class="btn btn-sm btn-success" id="exportExcel">
              <i class="fas fa-file-excel"></i> Export Excel
            </button>
            <button type="button" class="btn btn-sm btn-danger" id="exportPDF">
              <i class="fas fa-file-pdf"></i> Export PDF
            </button>
          </div>
        </div>
        <div class="card-body table-responsive">
          <table id="transactions" class="table table-bordered table-striped table-hover" style="width:100%">
            <thead>
              <tr>
                <th style="width: 5%">ID</th>
                <th style="width: 15%">User</th>
                <th style="width: 10%">Type</th>
                <th style="width: 12%">Amount</th>
                <th style="width: 15%">Payment Method</th>
                <th style="width: 10%">Status</th>
                <th style="width: 15%">Date</th>
                <th style="width: 8%">Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($transactions as $transaction)
                <tr>
                  <td><strong>#{{ $transaction->id }}</strong></td>
                  <td>
                    <i class="fas fa-user-circle mr-1 text-primary"></i>
                    {{ $transaction->user->name }}
                  </td>
                  <td>
                    <span class="badge badge-info">
                      {{ $transaction->transactionType->code }}
                    </span>
                  </td>
                  <td>
                    <strong class="text-success">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</strong>
                  </td>
                  <td>{{ $transaction->paymentMethod->name }}</td>
                  <td>
                    @if ($transaction->status == 'success')
                      <span class="badge badge-success">
                        <i class="fas fa-check-circle"></i> Success
                      </span>
                    @elseif ($transaction->status == 'pending')
                      <span class="badge badge-warning">
                        <i class="fas fa-clock"></i> Pending
                      </span>
                    @else
                      <span class="badge badge-danger">
                        <i class="fas fa-times-circle"></i> Failed
                      </span>
                    @endif
                  </td>
                  <td>
                    <small>
                      <i class="far fa-calendar-alt mr-1"></i>
                      {{ $transaction->created_at->format('d M Y, H:i') }}
                    </small>
                  </td>
                  <td>
                    <button type="button" class="btn btn-sm btn-info btn-view-detail"
                            data-id="{{ $transaction->id }}"
                            data-code="{{ $transaction->transaction_code ?? 'N/A' }}"
                            data-user="{{ $transaction->user->name }}"
                            data-email="{{ $transaction->user->email }}"
                            data-type="{{ $transaction->transactionType->code }}"
                            data-amount="{{ number_format($transaction->amount, 0, ',', '.') }}"
                            data-payment="{{ $transaction->paymentMethod->name }}"
                            data-status="{{ $transaction->status }}"
                            data-description="{{ $transaction->description ?? '-' }}"
                            data-created="{{ $transaction->created_at->format('d M Y, H:i:s') }}">
                      <i class="fas fa-eye"></i> View
                    </button>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Transaction Detail Modal -->
  <div class="modal fade" id="transactionDetailModal" tabindex="-1" role="dialog" aria-labelledby="transactionDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="transactionDetailModalLabel">
            <i class="fas fa-info-circle"></i>
            Transaction Details
          </h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <table class="table table-bordered">
            <tbody>
              <tr>
                <th width="35%" class="bg-light">Transaction ID:</th>
                <td id="modal-id"></td>
              </tr>
              <tr>
                <th class="bg-light">Transaction Code:</th>
                <td id="modal-code"></td>
              </tr>
              <tr>
                <th class="bg-light">User:</th>
                <td id="modal-user"></td>
              </tr>
              <tr>
                <th class="bg-light">Email:</th>
                <td id="modal-email"></td>
              </tr>
              <tr>
                <th class="bg-light">Type:</th>
                <td id="modal-type"></td>
              </tr>
              <tr>
                <th class="bg-light">Amount:</th>
                <td id="modal-amount"></td>
              </tr>
              <tr>
                <th class="bg-light">Payment Method:</th>
                <td id="modal-payment"></td>
              </tr>
              <tr>
                <th class="bg-light">Status:</th>
                <td id="modal-status"></td>
              </tr>
              <tr>
                <th class="bg-light">Description:</th>
                <td id="modal-description"></td>
              </tr>
              <tr>
                <th class="bg-light">Created At:</th>
                <td id="modal-created"></td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">
            <i class="fas fa-times"></i> Close
          </button>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('js')
<script src="{{ asset('AdminLTE/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('AdminLTE/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('AdminLTE/plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('AdminLTE/plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('AdminLTE/plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('AdminLTE/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('AdminLTE/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script>
    $(function () {
        // Initialize DataTable
        var table = $('#transactions').DataTable({
            "responsive": false,
            "lengthChange": true,
            "autoWidth": false,
            "pageLength": 10,
            "order": [[0, 'desc']],
            "scrollX": false,
            "buttons": [
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    className: 'btn btn-success btn-sm',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6]
                    }
                },
                {
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf"></i> PDF',
                    className: 'btn btn-danger btn-sm',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6]
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i> Print',
                    className: 'btn btn-info btn-sm',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6]
                    }
                }
            ]
        });

        // Custom export button handlers
        $('#exportExcel').on('click', function() {
            table.button('.buttons-excel').trigger();
        });

        $('#exportPDF').on('click', function() {
            table.button('.buttons-pdf').trigger();
        });

        // Handle View Detail Button Click
        $(document).on('click', '.btn-view-detail', function() {
            var id = $(this).data('id');
            var code = $(this).data('code');
            var user = $(this).data('user');
            var email = $(this).data('email');
            var type = $(this).data('type');
            var amount = $(this).data('amount');
            var payment = $(this).data('payment');
            var status = $(this).data('status');
            var description = $(this).data('description');
            var created = $(this).data('created');

            // Populate modal
            $('#modal-id').html('<strong>#' + id + '</strong>');
            $('#modal-code').text(code);
            $('#modal-user').html('<i class="fas fa-user-circle mr-1 text-primary"></i>' + user);
            $('#modal-email').text(email);
            $('#modal-type').html('<span class="badge badge-info">' + type + '</span>');
            $('#modal-amount').html('<strong class="text-success">Rp ' + amount + '</strong>');
            $('#modal-payment').text(payment);

            // Status badge
            var statusBadge = '';
            if (status === 'success') {
                statusBadge = '<span class="badge badge-success"><i class="fas fa-check-circle"></i> Success</span>';
            } else if (status === 'pending') {
                statusBadge = '<span class="badge badge-warning"><i class="fas fa-clock"></i> Pending</span>';
            } else {
                statusBadge = '<span class="badge badge-danger"><i class="fas fa-times-circle"></i> Failed</span>';
            }
            $('#modal-status').html(statusBadge);

            $('#modal-description').text(description);
            $('#modal-created').html('<i class="far fa-calendar-alt mr-1"></i>' + created);

            // Show modal
            $('#transactionDetailModal').modal('show');
        });
    });
</script>
@endsection
