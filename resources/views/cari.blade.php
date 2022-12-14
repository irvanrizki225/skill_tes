<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.1.2/css/fontawesome.min.css" integrity="sha384-X8QTME3FCg1DLb58++lPvsjbQoCT9bp3MsUU3grbIny/3ZwUJkRNO8NPW6zqzuW9" crossorigin="anonymous">

    <title>Tes Skill</title>
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Persentase Complaint Area
                    <br>
                    <form action="{{ route('cari') }}" method="get">
                        <label for="">Select Area</label>
                        <select name="area" id="">
                            <option value="">Pilih Area</option>
                            @foreach ($area as $item)
                                <option value="{{ $item->area_name }}">{{ $item->area_name }}</option>
                            @endforeach
                        </select>
                        &nbsp;
                        <label for="">Date From</label>
                        <input type="date" name="from" id="">
                        &nbsp;
                        <label for="">Date To</label>
                        <input type="date" name="to" id="">
                        &nbsp;
                        <button type="submit" class="btn btn-primary">View</button>
                    </form>
                </div>
                <div class="card-body">
                    <canvas id="myChart" width="300" height="100"></canvas>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-header">
                    Persentase Complaint Area And Brand
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Brand</th>
                                <th>DKI Jakarta</th>
                                <th>Jawa Barat</th>
                                <th>Kalimantan</th>
                                <th>Jawa Tengah</th>
                                <th>Bali</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            for ($i=0; $i < count($brand) ; $i++) { 
                                echo '<tr>';
                                echo '<td>'.$persentase_brand[0][$i]['brand']. '</td>';
                                echo '<td>'.$persentase_brand[0][$i]['DKI Jakarta']. '</td>';
                                echo '<td>'.$persentase_brand[0][$i]['Jawa Barat']. '</td>';
                                echo '<td>'.$persentase_brand[0][$i]['Kalimantan']. '</td>';
                                echo '<td>'.$persentase_brand[0][$i]['Jawa Tengah']. '</td>';
                                echo '<td>'.$persentase_brand[0][$i]['Bali']. '</td>';
                                echo '<tr>';
                            }
                                
                            @endphp
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
  </div>




<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>

<script>
    var ctx = document.getElementById('myChart');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [
                'DKI Jakarta', 'Jawa Barat', 'Kalimantan', 'Jawa Tengah', 'Bali'
            ],
            datasets: [{
                label: '# of Votes',
                data: [
                    @for ($i = 0; $i < count($persentase[0]); $i++)
                        @php
                            echo $persentase[0][$i];
                            echo ',';
                        @endphp
                        
                    @endfor
                ],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
</script>
</body>
</html>