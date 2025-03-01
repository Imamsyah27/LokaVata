const MAP_SERVICE_KEY = "67b6a6056fb80e64559cc07e"; // Ganti dengan API key MapID Anda

var map = new maplibregl.Map({
    style: `https://basemap.mapid.io/styles/basic/style.json?key=${MAP_SERVICE_KEY}`,
    center: [118.5, -2.5],
    zoom: 5,
    pitch: 0,
    bearing: 0,
    container: "map",
});

const carbonPrice = 15000; // Harga karbon per Mg/ha dalam Rupiah

// Buat panel kontrol layer
const layerControlContainer = document.createElement("div");
layerControlContainer.style.position = "absolute";
layerControlContainer.style.top = "10px";
layerControlContainer.style.right = "10px";
layerControlContainer.style.background = "white";
layerControlContainer.style.padding = "10px";
layerControlContainer.style.borderRadius = "5px";
layerControlContainer.style.boxShadow = "0 0 10px rgba(0,0,0,0.2)";
layerControlContainer.style.fontFamily = "Arial, sans-serif";
layerControlContainer.style.fontSize = "12px";
layerControlContainer.innerHTML = "<strong>Layer Control</strong><br>";

// Daftar layer yang bisa dikontrol
const layers = [
    { id: "AGCLayer", name: "AGC (Above Ground Carbon)" },
    { id: "RTRWLayer", name: "RTRW (Rencana Tata Ruang Wilayah)" },
    { id: "mangroveLayer", name: "Mangrove" }
];

// Tambahkan checkbox untuk setiap layer
layers.forEach(layer => {
    const layerDiv = document.createElement("div");
    layerDiv.style.marginBottom = "5px";

    const checkbox = document.createElement("input");
    checkbox.type = "checkbox";
    checkbox.checked = true; // Default: Layer ditampilkan
    checkbox.id = `checkbox-${layer.id}`;
    checkbox.dataset.layerId = layer.id;

    const label = document.createElement("label");
    label.htmlFor = `checkbox-${layer.id}`;
    label.textContent = ` ${layer.name}`;

    layerDiv.appendChild(checkbox);
    layerDiv.appendChild(label);
    layerControlContainer.appendChild(layerDiv);

    // Event listener untuk menampilkan / menyembunyikan layer
    checkbox.addEventListener("change", function () {
        const layerId = this.dataset.layerId;
        if (this.checked) {
            map.setLayoutProperty(layerId, "visibility", "visible");
        } else {
            map.setLayoutProperty(layerId, "visibility", "none");
        }
    });
});

document.body.appendChild(layerControlContainer);

// Tambahkan kontrol navigasi
// Tambahkan kontrol navigasi di sudut kiri atas terlebih dahulu
const nav = new maplibregl.NavigationControl();
map.addControl(nav, 'top-left');

// Gunakan CSS untuk memindahkannya ke tengah kiri
const navControl = document.querySelector('.maplibregl-ctrl-top-left');
if (navControl) {
    navControl.style.position = "absolute";
    navControl.style.top = "50%"; // Posisikan di tengah vertikal
    navControl.style.left = "10px"; // Jarak dari sisi kiri
    navControl.style.transform = "translateY(-50%)"; // Koreksi posisi agar benar-benar di tengah
}
        
// Tambahkan fitur drawing
const draw = new MapboxDraw({
    displayControlsDefault: true,
    controls: {
        polygon: true,
        trash: true
    }
});

map.addControl(draw, 'top-left');

// Fungsi pindah lokasi
function moveToLocation() {
    const locationSelect = document.getElementById("locationSelect");
    const selectedOption = locationSelect.value;
    if (selectedOption) {
        const coords = selectedOption.split(',');
        const lat = parseFloat(coords[0]);
        const lng = parseFloat(coords[1]);
        const zoom = parseInt(coords[2]);
        const regionKey = locationSelect.options[locationSelect.selectedIndex].getAttribute('data-region');

        map.flyTo({
            center: [lng, lat],
            zoom: zoom,
            essential: true
        });

        updateGeoJSONLayers(regionKey);
    }
}

// Fungsi untuk menampilkan GeoJSON berdasarkan lokasi yang dipilih
function updateGeoJSONLayers(regionKey) {
    const mangroveUrl = `Data/AGC1_${capitalize(regionKey)}.geojson`;
    const RTRWUrl = `Data/Polaruang_${capitalize(regionKey)}.geojson`;

// Panggil fungsi untuk memuat GeoJSON RTRW dan AGC
    addAGCGeoJSON(mangroveUrl, 'AGCLayer');
    addRTRWGeoJSON(RTRWUrl, 'RTRWLayer');
    updateOverlayTable(mangroveUrl);
}

function addAGCGeoJSON(url, layerID) {
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (map.getSource(layerID)) {
                map.getSource(layerID).setData(data);
            } else {
                map.addSource(layerID, { type: 'geojson', data: data });
                map.addLayer({
                    id: layerID,
                    type: 'fill',
                    source: layerID,
                    paint: {
                        'fill-color': ["case", ["has", "AGC1"], ["interpolate", ["linear"], ["get", "AGC1"],
                            0, "#ffffbf",
                            10, "#fee08b",
                            30, "#d9ef8b",
                            60, "#a6d96a",
                            90, "#66bd63",
                            120, "#1a9850",
                            150, "#006837"
                        ], "#cccccc"],
                        'fill-outline-color': "white"
                    }
                });

                // Event klik untuk menampilkan data AGC dan valuasi ekonomi
                map.on('click', layerID, function (e) {
                    const agc = e.features[0].properties.AGC1 || 0;
                    const economyValue = agc * carbonPrice;
                    
                    new maplibregl.Popup()
                        .setLngLat(e.lngLat)
                        .setHTML(`<strong>AGC:</strong> ${agc} Mg/ha<br><strong>Valuasi Ekonomi:</strong> Rp ${economyValue.toLocaleString('id-ID')}`)
                        .addTo(map);
                });

                // Mengubah kursor saat mengarahkan ke layer
                map.on('mouseenter', layerID, function () {
                    map.getCanvas().style.cursor = 'pointer'; // Kursor normal
                });

                map.on('mouseleave', layerID, function () {
                    map.getCanvas().style.cursor = ''; // Kembali ke default
                });
            }
        })
        .catch(error => console.error("Gagal memuat data AGC:", error));
}

// Fungsi untuk menangani intersect antara drawing dan AGC Layer
function calculateIntersectionAGC(drawnFeature) {
    const agcSource = map.getSource("AGCLayer");
    if (!agcSource) {
        console.error("AGC Layer tidak ditemukan.");
        return;
    }

    const agcData = agcSource._data;
    if (!agcData || agcData.type !== "FeatureCollection") {
        console.error("Data AGC tidak valid.");
        return;
    }

    let totalAGC = 0;
    let totalArea = 0;
    let foundIntersection = false;

    agcData.features.forEach(agcFeature => {
        if (!drawnFeature.geometry || !agcFeature.geometry) {
            console.warn("Geometry tidak valid.");
            return;
        }

        if (
            (drawnFeature.geometry.type === "Polygon" || drawnFeature.geometry.type === "MultiPolygon") &&
            (agcFeature.geometry.type === "Polygon" || agcFeature.geometry.type === "MultiPolygon")
        ) {
            const intersection = turf.intersect(drawnFeature, agcFeature);
            if (intersection) {
                foundIntersection = true;
                const agcValue = agcFeature.properties?.AGC1 || 0;
                const intersectArea = turf.area(intersection) / 10000; // Konversi ke hektar

                totalAGC += agcValue * intersectArea;
                totalArea += intersectArea;
            }
        }
    });

    if (!foundIntersection) {
        alert("Tidak ada area yang berpotongan dengan AGC.");
        return;
    }

    if (totalArea > 0) {
        const avgAGC = totalAGC / totalArea;
        const totalEconomicValue = avgAGC * carbonPrice;

        new maplibregl.Popup()
            .setLngLat(drawnFeature.geometry.coordinates[0][0]) // Gunakan koordinat pertama
            .setHTML(`
                <strong>Hasil Intersect:</strong><br>
                <strong>Rata-rata AGC:</strong> ${avgAGC.toFixed(2)} Mg/ha<br>
                <strong>Valuasi Ekonomi:</strong> Rp ${totalEconomicValue.toLocaleString('id-ID')}<br>
                <strong>Total Luasan:</strong> ${totalArea.toFixed(2)} ha
            `)
            .addTo(map);
    }
}

// Tangani event setelah menggambar polygon
map.on('draw.create', function (e) {
    const drawnFeature = e.features[0];

    // Validasi jika hasil gambar bukan Polygon
    if (!drawnFeature || !drawnFeature.geometry || (drawnFeature.geometry.type !== "Polygon" && drawnFeature.geometry.type !== "MultiPolygon")) {
        alert("Harap gambar area dalam bentuk Polygon.");
        return;
    }

    calculateIntersectionAGC(drawnFeature);
});

function addRTRWGeoJSON(url, layerID) {
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (map.getSource(layerID)) {
                map.getSource(layerID).setData(data);
            } else {
                map.addSource(layerID, { type: 'geojson', data: data });
                map.addLayer({
                    id: layerID,
                    type: 'fill',
                    source: layerID,
                    paint: {
                        'fill-color': [
                            "match", ["get", "Kawasan"],
                            "Kawasan Perlindungan Terhadap Kawasan Bawahannya", "#ff0000",
                            "Kawasan Pencadangan Konservasi di Laut", "#ff6600",
                            "Kawasan Konservasi", "#008000",
                            "Kawasan Ekosistem Mangrove", "#006600",
                            "Kawasan Perlindungan Setempat", "#00ccff",
                            "Badan Air", "#0000ff",
                            "Kawasan Hutan Produksi", "#8B4513",
                            "Kawasan Peruntukan Industri", "#800080",
                            "Kawasan Transportasi", "#FFA500",
                            "Kawasan HGU dan HGB", "#FFD700",
                            "Garis Pantai", "#000000",
                            "#CCCCCC" // Warna default
                        ],
                        'fill-opacity': 0.5,
                        'fill-outline-color': "#000000"
                    }
                });

                map.on('click', layerID, function (e) {
                    const kawasan = e.features[0].properties.Kawasan || "Tidak Diketahui";
                    new maplibregl.Popup()
                        .setLngLat(e.lngLat)
                        .setHTML(`<strong>Jenis Kawasan:</strong> ${kawasan}`)
                        .addTo(map);
                    });

                    // Mengubah kursor saat mengarahkan ke layer
                    map.on('mouseenter', layerID, function () {
                        map.getCanvas().style.cursor = 'pointer'; // Kursor normal
                    });
    
                    map.on('mouseleave', layerID, function () {
                        map.getCanvas().style.cursor = ''; // Kembali ke default
                    });
                }
            })
        .catch(error => console.error("Gagal memuat data RTRW:", error));
}

function updateOverlayTable(geojsonUrl) {
    const tableBody = document.getElementById("overlayTableBody");
    tableBody.innerHTML = ""; // Kosongkan tabel sebelumnya

    let totalAGC = 0;
    let totalEconomicValue = 0;
    let totalArea = 0;
    const carbonPrice = 15000;

    fetch(geojsonUrl)
        .then(response => response.json())
        .then(data => {
            data.features.forEach(feature => {
                const agcValue = feature.properties?.AGC1 || 0;

                // Hitung luas menggunakan Turf.js
                const featureArea = turf.area(feature) / 10000; // Konversi ke hektar (ha)
                
                totalAGC += agcValue;
                totalArea += featureArea;
                totalEconomicValue += agcValue * carbonPrice;
            });

            const currentDate = new Date().toLocaleDateString('id-ID');

            // Tambahkan baris ringkasan ke tabel
            const row = document.createElement("tr");
            row.innerHTML = `
                <td>Total Data</td>
                <td>${totalAGC.toLocaleString('id-ID')} Mg/ha</td>
                <td>Rp ${totalEconomicValue.toLocaleString('id-ID')}</td>
                <td>${totalArea.toFixed(2).toLocaleString('id-ID')} ha</td>
                <td>${currentDate}</td>
                <td>
                    <div class="download-buttons">
                        <button class="download-btn csv">Download CSV</button>
                        <button class="download-btn geojson">Download GeoJSON</button>
                    </div>
                </td>
            `;
            tableBody.appendChild(row);

            // Tambahkan event listener untuk tombol download CSV
            row.querySelector(".download-btn.csv").addEventListener("click", downloadAGCDataAsCSV);

            // Tambahkan event listener untuk tombol download GeoJSON
            row.querySelector(".download-btn.geojson").addEventListener("click", function () {
                downloadAsGeoJSON(geojsonUrl);
            });
        })
        .catch(error => console.error(`Error loading GeoJSON data for table:`, error));
}

// Fungsi untuk mengunduh data dalam format CSV
function downloadAGCDataAsCSV() {
    let csvContent = "data:text/csv;charset=utf-8,";
    csvContent += "Keterangan,Total AGC (Mg/ha),Valuasi Ekonomi (Rp),Total Luasan (ha),Tanggal\n";

    const row = document.querySelector("#overlayTableBody tr");
    if (row) {
        let rowData = [];
        row.querySelectorAll("td:not(:last-child)").forEach(td => rowData.push(td.innerText)); // Hindari kolom tombol
        csvContent += rowData.join(",") + "\n";
    }

    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", "AGC_Data.csv");
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Fungsi untuk mengunduh GeoJSON secara langsung
function downloadAsGeoJSON(geojsonUrl) {
    fetch(geojsonUrl)
        .then(response => response.json())
        .then(geojson => {
            const geojsonString = JSON.stringify(geojson, null, 2); // Format JSON agar lebih rapi
            const blob = new Blob([geojsonString], { type: "application/json" });

            const link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = "AGC1_Data.geojson";
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        })
        .catch(error => console.error("Error downloading GeoJSON:", error));
}

function capitalize(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}

function addLegend() {
    const legendContainer = document.createElement("div");
    legendContainer.id = "map-legend";
    legendContainer.style.position = "absolute";
    legendContainer.style.top = "130px";
    legendContainer.style.right = "10px";
    legendContainer.style.background = "white";
    legendContainer.style.padding = "10px";
    legendContainer.style.borderRadius = "5px";
    legendContainer.style.boxShadow = "0 0 10px rgba(0,0,0,0.2)";
    legendContainer.style.fontFamily = "Arial, sans-serif";
    legendContainer.style.fontSize = "12px";

    legendContainer.innerHTML = "<strong>AGC (Mg/ha)</strong><br>";

    const grades = [0, 1, 2, 5, 10, 30, 60, 90, 120, 150];
    const colors = ["#ffffbf", "#fee08b", "#fdae61", "#f46d43", "#d73027", "#a6d96a", "#66bd63", "#1a9850", "#006837"];

    for (let i = 0; i < grades.length; i++) {
        const legendItem = document.createElement("div");
        legendItem.style.display = "flex";
        legendItem.style.alignItems = "center";
        legendItem.style.marginBottom = "4px";

        const colorBox = document.createElement("span");
        colorBox.style.width = "18px";
        colorBox.style.height = "18px";
        colorBox.style.display = "inline-block";
        colorBox.style.marginRight = "6px";
        colorBox.style.background = colors[i] || "#000";

        const label = document.createElement("span");
        label.innerHTML = `${grades[i]}${grades[i + 1] ? "–" + grades[i + 1] : "+"}`;

        legendItem.appendChild(colorBox);
        legendItem.appendChild(label);
        legendContainer.appendChild(legendItem);
    }

    document.body.appendChild(legendContainer);
}

// Panggil fungsi setelah peta dimuat
map.on("load", addLegend);
