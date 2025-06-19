<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit(); // Hentikan eksekusi script

}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>The Blog Nest - A Platform for Sharing Knowledge</title>
    <link rel="stylesheet" href="../css/admin.css" />
</head>

<body>
 <?php include 'sidebar.php'?>
    <div class="chart-container-wrapper">
        <section class="chart-container">
            <h2>Jumlah Artikel per Penulis per Kategori</h2>
            <div id="chart1" class="chart"></div>
            <div class="legend" id="legend1">
                <h4>Legenda Kategori</h4>
            </div>
        </section>

        <section class="chart-container">
            <h2>Jumlah Artikel Berdasarkan Tanggal Publikasi</h2>
            <div id="chart2" class="chart"></div>
            <div class="legend" id="legend2">
                <h4>Legenda Kategori</h4>
            </div>
        </section>
    </div>

    <script>
        fetch('../php/chartuser.php')
            .then(res => res.json())
            .then(response => {
                const data = response.data;
                const warna = response.warna;
                const chart = document.getElementById('chart1');
                const legend = document.getElementById('legend1');

                let max = 0;
                Object.values(data).forEach(item => {
                    Object.values(item).forEach(j => {
                        const val = parseInt(j) || 0;
                        if (val > max) max = val;
                    });
                });

                for (let penulis in data) {
                    const group = document.createElement('div');
                    group.className = 'bar-group';

                    for (let kategori in data[penulis]) {
                        const jumlah = parseInt(data[penulis][kategori]) || 0;
                        const tinggi = (jumlah / max) * 100;

                        const bar = document.createElement('div');
                        bar.className = 'bar';
                        bar.style.height = tinggi + '%';
                        bar.style.backgroundColor = warna[kategori] || '#ccc';
                        bar.title = `${kategori}: ${jumlah} artikel`;

                        group.appendChild(bar);
                    }

                    const label = document.createElement('div');
                    label.className = 'label';
                    label.innerText = penulis;
                    group.appendChild(label);

                    chart.appendChild(group);
                }

                for (let kategori in warna) {
                    const item = document.createElement('div');
                    item.className = 'legend-item';

                    const color = document.createElement('span');
                    color.className = 'color-box';
                    color.style.backgroundColor = warna[kategori];

                    item.appendChild(color);
                    item.appendChild(document.createTextNode(kategori));
                    legend.appendChild(item);
                }
            });

        fetch('../php/chartartikel.php')
            .then(res => res.json())
            .then(response => {
                const data = response.data;
                const warna = response.warna;
                const chart = document.getElementById('chart2');
                const legend = document.getElementById('legend2');

                let max = 0;
                Object.values(data).forEach(item => {
                    Object.values(item).forEach(j => {
                        const val = parseInt(j) || 0;
                        if (val > max) max = val;
                    });
                });

                for (let tanggal in data) {
                    const group = document.createElement('div');
                    group.className = 'bar-group';

                    for (let kategori in data[tanggal]) {
                        const jumlah = parseInt(data[tanggal][kategori]) || 0;
                        const tinggi = (jumlah / max) * 100;

                        const bar = document.createElement('div');
                        bar.className = 'bar';
                        bar.style.height = tinggi + '%';
                        bar.style.backgroundColor = warna[kategori] || '#ccc';
                        bar.title = `${kategori} - ${tanggal}: ${jumlah} artikel`;

                        group.appendChild(bar);
                    }

                    const label = document.createElement('div');
                    label.className = 'label';
                    label.innerText = tanggal;
                    group.appendChild(label);

                    chart.appendChild(group);
                }

                for (let kategori in warna) {
                    const item = document.createElement('div');
                    item.className = 'legend-item';

                    const color = document.createElement('span');
                    color.className = 'color-box';
                    color.style.backgroundColor = warna[kategori];

                    item.appendChild(color);
                    item.appendChild(document.createTextNode(kategori));
                    legend.appendChild(item);
                }
            });
    </script>
</body>

</html>