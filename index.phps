<?php
function fetchPocketMineAPI($version) {
    // URL ke API doc sesuai dengan versi yang dipilih
    $apiUrl = "https://apidoc.pmmp.io/$version/classes.html";

    // Mengambil konten halaman
    $htmlContent = file_get_contents($apiUrl);

    // Cek apakah data berhasil diambil
    if ($htmlContent === FALSE) {
        return [];
    }

    // Membuat DOMDocument untuk memproses HTML
    $dom = new DOMDocument;
    @$dom->loadHTML($htmlContent); // @ untuk menonaktifkan warning dari HTML yang tidak valid
    $xpath = new DOMXPath($dom);

    // Ambil semua elemen yang mengandung informasi kelas/method (berdasarkan struktur halaman)
    $classNodes = $xpath->query("//div[@class='class-list']//a");
    $classes = [];

    foreach ($classNodes as $classNode) {
        $className = $classNode->textContent;
        $classLink = "https://apidoc.pmmp.io/$version/" . $classNode->getAttribute('href');
        
        // Untuk setiap kelas, kita bisa mengambil metode-metodenya jika diperlukan
        $methods = fetchMethodsForClass($classLink);
        $classes[$className] = $methods;
    }

    return $classes;
}

function fetchMethodsForClass($classUrl) {
    $htmlContent = file_get_contents($classUrl);
    if ($htmlContent === FALSE) {
        return [];
    }

    $dom = new DOMDocument;
    @$dom->loadHTML($htmlContent);
    $xpath = new DOMXPath($dom);

    $methodNodes = $xpath->query("//section[@id='methods']//h4");
    $methods = [];

    foreach ($methodNodes as $methodNode) {
        $methods[] = $methodNode->textContent;
    }

    return $methods;
}

$apiVersion = $_POST['api_version'] ?? '5.0.0'; // Default ke versi 3.0.0 jika tidak ada pilihan
$apiData = fetchPocketMineAPI($apiVersion);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plugin Generator</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>PocketMine Plugin Generator</h1>
        <form id="plugin-form" method="post">
            <label for="api-version">Select API Version:</label>
            <select name="api_version" id="api-version">
                <option value="3.0.0" <?= $apiVersion === '3.0.0' ? 'selected' : '' ?>>API 3.0.0</option>
                <option value="4.0.0" <?= $apiVersion === '4.0.0' ? 'selected' : '' ?>>API 4.0.0</option>
                <option value="5.0.0" <?= $apiVersion === '5.0.0' ? 'selected' : '' ?>>API 5.0.0</option>
            </select>

            <label for="plugin-name">Plugin Name:</label>
            <input type="text" id="plugin-name" name="plugin_name" required>
            
            <label for="plugin-command">Plugin Command:</label>
            <input type="text" id="plugin-command" name="plugin_command" required>
            
            <button type="submit" id="generate-btn">Generate Plugin</button>
        </form>
        <div id="generated-code">
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $pluginName = htmlspecialchars($_POST['plugin_name']);
                $pluginCommand = htmlspecialchars($_POST['plugin_command']);
                $pluginFunction = htmlspecialchars($_POST['plugin_function']);
                
                echo "<h2>Generated PHP Code for PocketMine API $apiVersion</h2>";
                echo "<pre>
<?php

namespace $pluginName;

use pocketmine\\plugin\\PluginBase;
use pocketmine\\event\\Listener;
use pocketmine\\command\\Command;
use pocketmine\\command\\CommandSender;

class Main extends PluginBase implements Listener {

    public function onEnable(): void {
        \$this->getLogger()->info(\"$pluginName Plugin has been enabled!\");
    }

    public function onCommand(CommandSender \$sender, Command \$command, string \$label, array \$args): bool {
        if (strtolower(\$command->getName()) === \"$pluginCommand\") {
            \$this->$pluginFunction(\$sender);
            return true;
        }
        return false;
    }

    public function $pluginFunction(CommandSender \$sender): void {
        // Implement the function here
    }
}
                </pre>";
            }
            ?>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>
<style>
  body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    color: #333;
    margin: 0;
    padding: 20px;
}

.container {
    max-width: 800px;
    margin: auto;
    background: #fff;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h1 {
    text-align: center;
    color: #333;
}

form {
    margin-top: 20px;
}

label {
    display: block;
    margin-bottom: 10px;
}

input[type="text"], select, textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

button {
    display: block;
    width: 100%;
    padding: 10px;
    background: #5cb85c;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

button:hover {
    background: #4cae4c;
}

#generated-code pre {
    background: #333;
    color: #fff;
    padding: 20px;
    border-radius: 5px;
    overflow-x: auto;
    white-space: pre-wrap;
}
</style>
<script>
  document.getElementById('plugin-form').addEventListener('submit', function(event) {
    event.preventDefault();

    // Fetch the form data
    const formData = new FormData(this);

    // Optionally: Perform some client-side validation

    // Submit the form data via AJAX
    fetch('index.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(html => {
        document.getElementById('generated-code').innerHTML = html;
    })
    .catch(error => console.error('Error:', error));
});
</script>