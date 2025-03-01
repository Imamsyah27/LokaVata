const express = require('express');
const path = require('path');
const app = express();
const PORT = 3000;

// Set folder 'gee-map-project' sebagai static
app.use(express.static(path.join(__dirname)));

// Jalankan server di port 3000
app.listen(PORT, () => {
    console.log(`Server berjalan di http://localhost:${PORT}`);
});
