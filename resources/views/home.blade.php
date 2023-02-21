<!DOCTYPE html>
<html>
<head>
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

<div class="row">
  <div class="column">
    <h2>Bank CSV</h2>
    <table>
      <tr>
        <th>Transaction Reference</th>
        <th>Credit</th>
      </tr>
      @foreach ($csv_results as $v)
        <tr class="{{ isset($v['match']) ? 'green-border' : 'red-border'}}">
            <td>{{ trim($v[6],"'")  }}</td>
            <td>{{ $v[8] }}</td>
        </tr>
      @endforeach
    </table>
  </div>
  <div class="column">
    <h2>Order Payments</h2>
    <table>
      <tr>
        <th>Order Id</th>
        <th>Transaction Reference</th>
        <th>Credit Amount</th>
      </tr>
        @foreach ($order_payments as $op)
            <tr class="{{ isset($op->match) ? 'green-border' : 'red-border'}}">
                <td>{{ $op->order_id }}</td>
                <td>{{ $op->transaction_reference }}</td>
                <td>{{ $op->amount }}</td>
            </tr>
        @endforeach
    </table>
  </div>
</div>

</body>
</html>
