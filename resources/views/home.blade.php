<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.3/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<style>
* {
  box-sizing: border-box;
}
.green-border{
     border: 5px solid green;
}
.red-border{
     border: 5px solid red;
}

.row {
  display: flex;
  margin-left:-5px;
  margin-right:-5px;
}

.column {
  flex: 50%;
  padding: 5px;
}

table {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;
  border: 1px solid #ddd;
}

th, td {
  text-align: left;
  padding: 16px;
}

/* tr:nth-child(even) {
  background-color: #f2f2f2;
} */
</style>
</head>
<body>
<div class="container mt-3">
    <h2>Upload Both File</h2>
    <div class="row mt-3">
        <form action="{{ route('store')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="input-group mb-3">
                <div class="custom-file mr-3">
                    <input type="file" accept=".csv,.xlsx,.xls" name="bank" class="custom-file-input" id="inputGroupFile01">
                    <label class="custom-file-label" for="inputGroupFile01">Bank CSV</label>
                </div>
                <div class="custom-file">
                    <input type="file" accept=".csv,.xlsx,.xls" name="order_payment" class="custom-file-input" id="inputGroupFile02">
                    <label class="custom-file-label" for="inputGroupFile02">Order Payment CSV</label>
                </div>
            </div>

            <div class="mt-3">
            <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
</div>
<div class="row">
  <div class="column">
    <h2>Bank CSV</h2>
    <h4>Match Record : {{ $match_record_count }}</h4>
    <h4>Unmatched Record : {{ count($bank) }}</h4>
    <table>
      <tr>
        <th>Transaction Reference</th>
        <th>Credit</th>
      </tr>
      @foreach ($bank as $v)
        <tr class="{{ isset($v['match']) ? 'green-border' : 'red-border'}}">
            <td>{{ trim($v[6],"'")  }}</td>
            <td>{{ $v[8] }}</td>
        </tr>
      @endforeach
    </table>
  </div>
  <div class="column">
    <h2>Order Payments</h2>
    <h4>&nbsp</h4>
    <h4>Unmatched Record : {{ count($payment) }}</h4>
    <table>
      <tr style="margin-top:20px;">
        <th>Order Id</th>
        <th>Transaction Reference</th>
        <th>Credit Amount</th>
      </tr>
        @foreach ($payment as $op)
            <tr class="{{ isset($op->match) ? 'green-border' : 'red-border'}}">
                <td>{{ $op[0] }}</td>
                <td>{{ $op[1] }}</td>
                <td>{{ $op[2] }}</td>
            </tr>
        @endforeach
    </table>
  </div>
</div>

<script>
// Add the following code if you want the name of the file appear on select
$(".custom-file-input").on("change", function() {
  var fileName = $(this).val().split("\\").pop();
  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});
</script>
</body>
</html>
