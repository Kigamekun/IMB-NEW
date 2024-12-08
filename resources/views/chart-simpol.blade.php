<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Chart with Filters</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
</head>
<body>
  <h1>Data IMB</h1>
 <center>
    <div style="width:90%;">
       <div class="row">
        <div class="col-md-6">
            <select class="form-control" id="jenis">
              <option value="all">Semua</option>
              <option value="imbinduk">IMB Induk</option>
              <option value="imbpecahan">IMB Pecahan</option>
              <option value="imbperluasan">IMB Perluasan</option>
              <option value="imbinduknonperum">IMB Induk Non Perum</option>
            </select>
        </div>
        <div class="col-md-4">

            <select  class="form-control" id="tahun"></select>
        </div>
        <div class="col-md-2">
        <button class="btn btn-success" id="applyFilters">Terapkan Filter</button>

        </div>
       </div>
       <br>
       <canvas id="imbChart" width="400" height="200"></canvas>
      </div>
 </center>
  <script>
    const ctx = document.getElementById('imbChart').getContext('2d');
    let chart;


    async function fetchData(jenis, tahun) {
      // Simulasi fetch data dari API
      const response = await axios.get(`/imb/chart-simpol-data?jenis=${jenis}&tahun=${tahun}`);
      return response.data;
    }

    function updateChart(data) {
      const labels = data.map(item => item.label);
      const values = data.map(item => item.value);

      if (chart) {
        chart.destroy(); // Hapus chart lama
      }

      chart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: labels,
          datasets: [{
            label: 'Jumlah IMB',
            data: values,
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              display: true
            },
          },
          scales: {
            y: {
              beginAtZero: true
            }
          }
        }
      });
    }

    function populateTahun() {
      const currentYear = new Date().getFullYear();
      const tahunSelect = document.getElementById('tahun');

      for (let year = currentYear; year >= 2000; year--) {
        const option = document.createElement('option');
        option.value = year;
        option.textContent = year;
        tahunSelect.appendChild(option);
      }
    }

    document.getElementById('applyFilters').addEventListener('click', async () => {
      const jenis = document.getElementById('jenis').value;
      const tahun = document.getElementById('tahun').value;

      const data = await fetchData(jenis, tahun);
      updateChart(data);
    });




    window.onload = () => {
      populateTahun();

      setTimeout(() => {
        document.getElementById('applyFilters').click();
      }, 500);
    };
  </script>
</body>
</html>
